# CC Homework 3 Checklist

This checklist tracks progress against the requirements in `week6.md` for a 3-member team.

## Team Split

- Data and Logic (Database, PHP API, Audit Logger): Bogdan
- Front-end: Andrei
- AI and Authentication: Denis

## Deployment Quick Start

This is a practical first deployment flow for App Engine Flexible.

1. Install and initialize gcloud locally.
2. Select your project and region.
3. Deploy services one by one from their folders.
4. Deploy dispatch rules from project root.

Commands:

1. Authenticate and select project

   gcloud auth login
   gcloud config set project YOUR_PROJECT_ID

2. Create App Engine app (once per project)

   gcloud app create --region=YOUR_REGION

3. Deploy default web UI service

   cd webui
   gcloud app deploy app.yaml
   cd ..

4. Deploy auth service

   cd AuthenticationService
   gcloud app deploy app.yaml
   cd ..

5. Deploy chatbot service

   cd ChatbotAPI
   gcloud app deploy app.yaml
   cd ..

6. Deploy dispatch rules

   gcloud app deploy dispatch.yml

7. Verify versions and service URLs

   gcloud app services list
   gcloud app versions list

Current starter files added for deployment:

- AuthenticationService/app.yaml
- AuthenticationService/Dockerfile
- ChatbotAPI/app.yaml
- webui/app.yaml
- dispatch.yml (updated route for chatbot)

Important notes before final submission:

- Replace placeholders in PhpApi/app.yaml for Cloud SQL instance names.
- ChatbotAPI app.yaml currently has a placeholder OLLAMA_HOST and should be replaced by your final AI backend endpoint.
- Authentication currently uses /tmp/auth.db as a starter setting for cloud deployment; replace with durable storage for final evaluation.

## Overall Submission Checklist

- [x] App is deployed on `appspot.com` domain (live URL included in docs)
- [ ] At least 7 Google Cloud services/APIs are used (for 3 members), respecting substitution limits
- [ ] At least one stateful Google Cloud service is used in production
- [x] Architecture and service choices are justified in documentation
- [x] Team member responsibilities are clearly documented
- [ ] End-to-end demo flow works on deployed environment

## Cloud Infrastructure Checklist

- [ ] Create/confirm GCP project with billing enabled
- [ ] Enable all required GCP APIs
- [ ] Create App Engine app (region chosen)
- [ ] Create Cloud SQL instance (stateful requirement)
- [ ] Create Cloud Storage bucket for media/static assets
- [ ] Configure service accounts and IAM permissions
- [ ] Move secrets from plain config to secure secret management/env strategy

## Deployment Config Checklist

- [ ] Keep/update existing PHP config: `PhpApi/app.yaml`
- [ ] Replace placeholder values in `PhpApi/app.yaml` for Cloud SQL instance/socket
- [x] Add App Engine config for Authentication service
- [x] Add App Engine config for Chatbot service
- [x] Add App Engine config for Web UI service (if deployed separately)
- [x] Verify dispatch rules in `dispatch.yml` match real endpoints
- [x] Deploy all services and verify routing behavior

## Backend Integration Checklist

- [ ] Production DB connection works against Cloud SQL
- [x] Authentication service works in cloud (register/login/token)
- [x] Chatbot service works in cloud (greet/chat)
- [ ] File/media handling uses Cloud Storage where required
- [ ] Error handling and validation return useful messages for frontend
- [ ] Basic health checks/manual test endpoints validated post-deploy

## Denis Checklist (AI + Authentication)

- [x] Finalize Authentication API deployment config and env vars
- [x] Finalize Chatbot API deployment config and env vars
- [ ] Ensure auth flow works end-to-end in deployed environment
- [ ] Ensure chatbot flow works end-to-end in deployed environment
- [x] Align chatbot routes with dispatch rules
- [ ] If substitutions are used (Identity Platform / Vertex AI), implement and prove usage
- [ ] Add short test evidence (screenshots/logs) for auth + AI cloud execution

## Bogdan Checklist (Data + PHP API + Audit Logger)

- [ ] Finalize PHP API Cloud SQL production wiring
- [ ] Ensure migrations/init scripts are production-safe
- [ ] Implement/deploy audit logging service and connect it to DB change events
- [ ] Validate audit records are persisted/queryable
- [ ] Add deployment and runbook notes for data path

## Andrei Checklist (Frontend)

- [x] Move auth and AI API base URLs to deployment-safe relative paths
- [x] Ensure frontend uses deployed endpoints for auth and AI, not localhost
- [ ] Validate login/register/AI/shop flows in deployed environment
- [ ] Provide UX-level error messaging for all critical API failures
- [ ] Build/deploy static frontend and verify route handling

## Implemented Features

- [x] App Engine deployment for the frontend, auth service, and chatbot service
- [x] Dispatch routing for `/api/auth/*` and `/ai/*`
- [x] Frontend auth and AI requests use deployed App Engine routes
- [x] Live hosted frontend responds successfully on App Engine
- [x] Authentication login/register flow is wired to the cloud deployment
- [x] Chatbot greet/chat flow is wired to the cloud deployment

## Evidence and Presentation Checklist

- [ ] Include architecture diagram (services + cloud services)
- [ ] Include table mapping each requirement to implementation proof
- [ ] Include deployed URLs and tested scenarios
- [ ] Include who built what (Denis/Bogdan/Andrei)
- [ ] Include known limitations and next steps
