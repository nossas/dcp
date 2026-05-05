# Deploy e CI-CD

## GitHub Actions (fluxo atual)
Localizado em `.github/workflows/`:

### 1. Build and Publish on DockerHub
- Gatilho: push para branches `release/**`, `hotfix/**`, `feature/**` ou tags.
- Build e push da imagem Docker `nossas/dcp-wp` para o Docker Hub.

### 2. CI (build de tema e release)
- Gatilho: push para `main` ou tags.
- Remove `dist/`, `node_modules` e `package-lock.json`.
- Executa `npm install && npm run production`.
- Faz upload do artefato `dcp`.
- Em tags: cria release no GitHub, gera `.zip` e dispara atualização via git-updater no site de produção.

## GitLab CI (legacy)
O arquivo `.gitlab-ci.yml` contém pipelines para deploy em Kubernetes em namespaces `base-theme-site-dev` e `base-theme-site-prod`. O fluxo:
1. `build_assets`: compila o tema com Node 20.
2. `create_pack_*`: gera zip do tema e publica como package genérico do GitLab.
3. `deploy_to_*`: usa `kubectl` para copiar o zip para o pod WordPress e instalar via `wp theme install --force`.

## Deploy em produção (Docker Compose)
O arquivo `docker-compose.deploy.yml`:
- Usa a imagem `nossas/dcp-wp` (tag configurável via `WORDPRESS_DOCKER_IMAGE`).
- Inclui Redis para cache de objetos.
- Espera a rede externa `web` (padrão Traefik).
- Variáveis sensíveis são injetadas via environment.
