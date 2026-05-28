# AI Knowledge UAT Traceability Checklist

Use this checklist to validate implemented features against BA expectations.

| Requirement Area | Scenario | Expected Result | Status |
|---|---|---|---|
| Documents | Upload valid PDF | Record created in `ai_documents`, status progresses to `ready` |  |
| Documents | Upload invalid file type | Validation error shown; no record created |  |
| Documents | Retry failed document | Status resets to pending/processing then updates |  |
| Ask AI | Ask with selected docs | Answer returned with citations only from selected docs |  |
| Ask AI | Ask with missing context | Answer states not found in uploaded documents |  |
| Ask AI | Service unavailable | User sees friendly error toast/message |  |
| Surveys | Generate from ready document | Draft survey created with generated questions |  |
| Surveys | Edit survey | Question text/type/options update persists |  |
| Surveys | Publish survey | Status becomes published; token URL is active |  |
| Surveys | Close survey | Status becomes closed; new submissions blocked |  |
| Public Link | Open token URL (LAN) | Survey page renders without login |  |
| Public Link | Submit response | Response saved; survey response_count increments |  |
| Public Link | Closed survey URL | Closed message displayed, no submission allowed |  |
| Feedback | Dashboard lists surveys | Response counts shown correctly |  |
| Feedback | Export CSV | CSV includes metadata + answer columns |  |
| Permissions | Non-authorized user access | Access denied or menu hidden by permission |  |
| Diagnostics | Open diagnostics page | Health JSON and module stats visible |  |

## Pilot sign-off

- Pilot users:
- Pilot date range:
- Key observations:
- Required fixes:
- BA approval:
