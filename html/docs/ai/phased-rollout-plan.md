# AI Knowledge Module - Phased Rollout Plan

## Stage 1: Infrastructure readiness

- Deploy Python AI service and Ollama on the single LAN server.
- Pull required models:
  - chat: `qwen2.5:7b-instruct`
  - embedding: `nomic-embed-text`
- Configure Laravel `.env` AI keys and run migrations.
- Seed AI permissions and assign pilot roles.
- Validate diagnostics page and `/health` endpoint.

**Exit criteria**
- Health check reports `status=ok`.
- AI routes accessible by admin users.

## Stage 2: Document ingestion pilot

- Enable only `ai.documents.manage` for pilot operators.
- Upload 5-10 representative SOP/policy PDFs.
- Validate indexing success rate, retries, and failure messages.
- Confirm storage and vector DB growth is acceptable.

**Exit criteria**
- >=90% documents indexed without manual intervention.
- Retry flow tested and documented.

## Stage 3: Ask AI pilot

- Enable `ai.ask` for a small pilot group.
- Run scripted question sets with known answers.
- Validate citation quality and "not found" behavior.
- Tune model/runtime if latency is too high.

**Exit criteria**
- Responses grounded to uploaded docs.
- Median response time acceptable for unit operations.

## Stage 4: Survey workflow pilot

- Generate survey drafts from pilot documents.
- Review/edit/publish at least 3 surveys.
- Share token links on LAN and collect feedback submissions.
- Validate dashboard counts and CSV export.

**Exit criteria**
- End-to-end survey loop works (generate -> publish -> submit -> export).
- Public token URLs function on target LAN clients.

## Stage 5: Controlled production enablement

- Expand permissions to all intended roles.
- Publish user SOP for:
  - document upload standards
  - ask AI usage guidelines
  - survey publication checklist
- Turn on regular backup for:
  - MySQL
  - `storage/app/ai/documents`
  - vector DB directory

**Exit criteria**
- BA/UAT checklist signed.
- Operations and backup owners assigned.

## Stage 6: Post-go-live monitoring (first 2-4 weeks)

- Weekly review:
  - failed ingests
  - survey response rates
  - top user issues
- Model/runtime tuning based on feedback.
- Decide on split-server migration if usage grows.
