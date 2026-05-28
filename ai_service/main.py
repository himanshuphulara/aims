import json
import os
import re
from hashlib import sha256
from pathlib import Path
from typing import Any

import chromadb
import requests
from fastapi import FastAPI, HTTPException
from pydantic import BaseModel, Field

try:
    from pypdf import PdfReader
except ImportError:  # pragma: no cover
    PdfReader = None


APP_NAME = "AIMS AI Service"
OLLAMA_URL = os.getenv("OLLAMA_URL", "http://127.0.0.1:11434")
CHAT_MODEL = os.getenv("OLLAMA_CHAT_MODEL", "qwen2.5:7b-instruct")
EMBED_MODEL = os.getenv("OLLAMA_EMBED_MODEL", "nomic-embed-text")
VECTOR_DB_PATH = os.getenv("VECTOR_DB_PATH", str(Path(__file__).resolve().parent / "data/chroma"))
COLLECTION_NAME = os.getenv("VECTOR_COLLECTION", "ai_documents")
MAX_CONTEXT_CHUNKS = int(os.getenv("MAX_CONTEXT_CHUNKS", "6"))

Path(VECTOR_DB_PATH).mkdir(parents=True, exist_ok=True)

client = chromadb.PersistentClient(path=VECTOR_DB_PATH)
collection = client.get_or_create_collection(name=COLLECTION_NAME, metadata={"hnsw:space": "cosine"})
app = FastAPI(title=APP_NAME, version="1.0.0")


class IngestPayload(BaseModel):
    document_id: int
    file_path: str
    title: str
    metadata: dict[str, Any] | None = None


class AskPayload(BaseModel):
    question: str = Field(min_length=2, max_length=4000)
    document_ids: list[int] = Field(default_factory=list)
    top_k: int = 5
    session_id: str | None = None


class GenerateSurveyPayload(BaseModel):
    document_id: int
    question_count: int = 8


def normalize_text(text: str) -> str:
    cleaned = re.sub(r"\s+", " ", text).strip()
    return cleaned


def split_text(text: str, chunk_size: int = 900, overlap: int = 150) -> list[str]:
    chunks: list[str] = []
    start = 0
    while start < len(text):
        end = start + chunk_size
        chunk = text[start:end].strip()
        if chunk:
            chunks.append(chunk)
        if end >= len(text):
            break
        start = max(0, end - overlap)
    return chunks


def embedding(text: str) -> list[float]:
    response = requests.post(
        f"{OLLAMA_URL}/api/embeddings",
        json={"model": EMBED_MODEL, "prompt": text},
        timeout=90,
    )
    response.raise_for_status()
    data = response.json()
    if "embedding" not in data:
        raise RuntimeError("Embedding response missing 'embedding'")
    return data["embedding"]


def ollama_chat(messages: list[dict[str, str]], temperature: float = 0.1) -> str:
    payload = {
        "model": CHAT_MODEL,
        "messages": messages,
        "stream": False,
        "options": {"temperature": temperature},
    }
    response = requests.post(f"{OLLAMA_URL}/api/chat", json=payload, timeout=180)
    response.raise_for_status()
    body = response.json()
    return body.get("message", {}).get("content", "").strip()


def read_pdf(file_path: str) -> tuple[list[dict[str, Any]], int]:
    if PdfReader is None:
        raise RuntimeError("Missing dependency: pypdf")

    try:
        reader = PdfReader(file_path, strict=False)
    except Exception as exc:
        raise RuntimeError(
            "Could not read PDF. Re-save it using Print to PDF or export from Word."
        ) from exc

    pages: list[dict[str, Any]] = []
    for i, page in enumerate(reader.pages, start=1):
        text = normalize_text(page.extract_text() or "")
        if text:
            pages.append({"page": i, "text": text})
    return pages, len(reader.pages)


@app.get("/health")
def health() -> dict[str, Any]:
    ollama_ok = False
    try:
        response = requests.get(f"{OLLAMA_URL}/api/tags", timeout=10)
        response.raise_for_status()
        ollama_ok = True
    except Exception:
        ollama_ok = False

    return {
        "status": "ok" if ollama_ok else "degraded",
        "success": ollama_ok,
        "ollama_reachable": ollama_ok,
        "chat_model": CHAT_MODEL,
        "embedding_model": EMBED_MODEL,
        "vector_path": VECTOR_DB_PATH,
    }


@app.post("/ingest")
def ingest(payload: IngestPayload) -> dict[str, Any]:
    file_path = payload.file_path
    if not os.path.exists(file_path):
        raise HTTPException(status_code=404, detail="Document file not found")

    try:
        pages, page_count = read_pdf(file_path)
        if not pages:
            return {"success": False, "error": "No extractable text found in PDF"}

        collection.delete(where={"document_id": payload.document_id})

        ids: list[str] = []
        docs: list[str] = []
        embeddings: list[list[float]] = []
        metadatas: list[dict[str, Any]] = []
        chunk_count = 0

        for page_info in pages:
            page_number = page_info["page"]
            page_text = page_info["text"]
            chunks = split_text(page_text)
            for idx, chunk in enumerate(chunks):
                chunk_id = f"{payload.document_id}-{page_number}-{idx}-{sha256(chunk.encode()).hexdigest()[:8]}"
                ids.append(chunk_id)
                docs.append(chunk)
                embeddings.append(embedding(chunk))
                metadatas.append(
                    {
                        "document_id": payload.document_id,
                        "document_title": payload.title,
                        "page": page_number,
                        "chunk_index": idx,
                    }
                )
                chunk_count += 1

        if ids:
            collection.add(ids=ids, documents=docs, embeddings=embeddings, metadatas=metadatas)

        return {
            "success": True,
            "chunks_indexed": chunk_count,
            "pages_count": page_count,
            "message": "Indexed successfully",
        }
    except Exception as exc:
        return {"success": False, "error": str(exc)}


@app.post("/ask")
def ask(payload: AskPayload) -> dict[str, Any]:
    try:
        query_vector = embedding(payload.question)
        query_args: dict[str, Any] = {
            "query_embeddings": [query_vector],
            "n_results": max(1, min(payload.top_k, MAX_CONTEXT_CHUNKS)),
            "include": ["documents", "metadatas", "distances"],
        }

        if payload.document_ids:
            query_args["where"] = {"document_id": {"$in": payload.document_ids}}

        results = collection.query(**query_args)
        docs = results.get("documents", [[]])[0]
        metas = results.get("metadatas", [[]])[0]
        distances = results.get("distances", [[]])[0]

        if not docs:
            return {
                "success": True,
                "answer": "I could not find this information in the uploaded documents.",
                "confidence": 0.0,
                "citations": [],
            }

        context_blocks = []
        citations = []
        for doc, meta, dist in zip(docs, metas, distances):
            title = meta.get("document_title", "Document")
            page = meta.get("page")
            context_blocks.append(f"[{title} | page {page}] {doc}")
            citations.append(
                {
                    "document_id": meta.get("document_id"),
                    "document_title": title,
                    "page": page,
                    "snippet": doc[:350],
                    "distance": dist,
                }
            )

        prompt = (
            "You are a strict retrieval assistant for a military accounting and operations application.\n"
            "Rules:\n"
            "1) Answer ONLY from the provided context.\n"
            "2) If the answer is not in context, say you do not know based on uploaded documents.\n"
            "3) Keep the answer concise and operational.\n\n"
            f"Question: {payload.question}\n\n"
            "Context:\n"
            + "\n\n".join(context_blocks)
        )

        answer = ollama_chat(
            [
                {"role": "system", "content": "You must ground every answer in provided context only."},
                {"role": "user", "content": prompt},
            ],
            temperature=0.0,
        )

        confidence = max(0.0, min(1.0, 1 - (distances[0] if distances else 1.0)))
        return {
            "success": True,
            "answer": answer,
            "confidence": round(confidence, 4),
            "citations": citations,
        }
    except Exception as exc:
        return {"success": False, "error": str(exc)}


@app.post("/generate-survey")
def generate_survey(payload: GenerateSurveyPayload) -> dict[str, Any]:
    try:
        results = collection.get(
            where={"document_id": payload.document_id},
            include=["documents", "metadatas"],
            limit=30,
        )
        docs = results.get("documents", [])
        metas = results.get("metadatas", [])

        if not docs:
            return {"success": False, "error": "Document is not indexed or has no extractable chunks."}

        title = metas[0].get("document_title", f"Document {payload.document_id}") if metas else f"Document {payload.document_id}"
        context = "\n".join([d[:500] for d in docs[:12]])

        json_schema_prompt = (
            "Generate a stakeholder feedback survey in JSON only.\n"
            "Return object with keys: title, questions.\n"
            "questions is an array of objects: question_text, question_type, required, options.\n"
            "Allowed question_type: rating, yes_no, multiple_choice, short_text.\n"
            f"Create exactly {payload.question_count} questions.\n"
            "Use operationally useful language.\n"
            "If using rating, options should be [1,2,3,4,5].\n"
            "For yes_no, options should be [\"yes\",\"no\"].\n\n"
            f"Document title: {title}\n"
            f"Context:\n{context}"
        )

        raw = ollama_chat(
            [
                {"role": "system", "content": "Return strict JSON only, no markdown."},
                {"role": "user", "content": json_schema_prompt},
            ],
            temperature=0.2,
        )

        parsed = json.loads(raw)
        questions = parsed.get("questions", [])

        normalized = []
        for q in questions:
            normalized.append(
                {
                    "question_text": q.get("question_text", "Please provide your feedback."),
                    "question_type": q.get("question_type", "short_text"),
                    "required": bool(q.get("required", True)),
                    "options": q.get("options"),
                }
            )

        return {
            "success": True,
            "title": parsed.get("title", f"Stakeholder Feedback - {title}"),
            "questions": normalized[: payload.question_count],
        }
    except Exception as exc:
        return {"success": False, "error": str(exc)}

