# Frontend Next.js
 
Next.js frontend for the ecommerce app. Runs on Node 20 Alpine with
`pnpm` (see `Dockerfile`).

## Local Deployment
#### Preparation

1. Clone Repo
```
git clone https://github.com/raflydwipriyono/challenge-adinusa.git
cd frontend-nextjs
```

2. Install dependencies
```
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install nodejs
curl -fsSL https://get.pnpm.io/install.sh | sh -
```
```
npm --version
10.8.2
node --version
v20.19.5
```


3. Setup .env
```
cp .env.example .env

NEXT_PUBLIC_BACKEND_URL=http://192.168.88.10:8080
FRONTEND_URL=http://192.168.88.10:3000
NEXT_PUBLIC_API_URL=http://192.168.88.10:8080/api
```

5. Install Depedencies
```
pnpm install
```

6. Run Nextjs
```
npm run dev -- -H 0.0.0.0 -p 3000
```
App is now available at `http://192.168.88.10:3000`.

---
 
## Docker Image
 
The `Dockerfile` in this folder builds an image running the Next.js dev
server on port `3000`.
 
What happens at build time (already done inside the image, Kubernetes just runs the finished image, it doesn't redo these steps):
 
- `pnpm install` (dependency manifests copied and installed before the rest
  of the source, so this layer is cached across builds unless
  `package.json`/`pnpm-lock.yaml` change)
- Full source code copied in
- Image `CMD` is `npm run dev -- -H 0.0.0.0 -p 3000`, meaning the dev server
  starts automatically on container start — nothing to run by hand
What is **not** baked into the image, and must be provided at container
runtime instead:
 
- `NEXT_PUBLIC_BACKEND_URL`
- `FRONTEND_URL`
- `NEXT_PUBLIC_API_URL`
---
 
## Environment Variables Reference
 
| Variable | Required | Notes |
|---|---|---|
| `NEXT_PUBLIC_BACKEND_URL` | Yes | Backend URL used by the app, `/api` suffix included where the app calls the API |
| `API_URL` | Yes | Backend URL used by the app, `/api` suffix included where the app calls the API |
| `FRONTEND_URL` | Yes | Public URL of this frontend |
| `NEXT_PUBLIC_API_URL` | Yes | Backend API base URL |
 
`NEXT_PUBLIC_*` variables are exposed to the browser at build/runtime by
Next.js convention — do not put secrets behind that prefix.
 
---
 
## Notes
 
- Env values are meant to be injected as environment variables at runtime
  (e.g. Kubernetes Secrets)
