# AGENTS.md — Defesa Climática Popular (DCP)

> Arquivo de referência para agentes de código. Leia este documento antes de modificar qualquer arquivo. O projeto é mantido em **português (Brasil)**; comentários, nomes de variáveis e documentação seguem este idioma sempre que possível.

---

## Visão geral do projeto

O **Defesa Climática Popular (DCP)** é uma iniciativa de mapeamento participativo de riscos climáticos e fortalecimento de respostas comunitárias em territórios populares. Este repositório mantém a base tecnológica completa do projeto, construída sobre **WordPress** e containerizada com **Docker**.

A solução é composta por:
- Um **tema customizado** (`themes/dcp/`) que implementa o site público, um painel interno para agentes comunitários (`/dashboard/*`) e blocos customizados para o Gutenberg.
- Um **plugin proprietário** (`plugins/dcp-plugin/`) que expõe endpoints da API REST (`/wp-json/dcp/v1/*`), webhooks e integrações com o framework Pods.
- **Infraestrutura Docker** para desenvolvimento local e deploy em produção (compatível com Traefik e Kubernetes).
- Workflows de CI/CD via **GitHub Actions** (build de assets e releases) e **GitLab CI** (deploy legacy em Kubernetes).

---

## Tecnologias e arquitetura

| Camada | Tecnologia |
|--------|-----------|
| Backend | WordPress 6.5.3, PHP 8.3 |
| Banco de dados | MariaDB 10.4 |
| Cache (produção) | Redis 7 (AlmLRU, RDB snapshots) |
| Containerização | Docker, Docker Compose 2.x |
| Imagem base local | `hacklab/wp:6.5.3-php8.3` |
| Build de assets | Node.js 20, Laravel Mix 6, Webpack |
| Estilos | Sass/SCSS, ITCSS + BEM |
| Scripts front-end | Vanilla JS, Alpine.js 3, React (para blocos Gutenberg) |
| Campos customizados | Pods Framework |
| Mapas | OpenStreetMap / Leaflet (via plugin Jeo) |
| Automação externa | n8n (workflow de clima/situação atual) |

### Arquitetura de rede (Docker)
- **Local**: duas redes bridge (`interna` e `wordpress-bot`).
- **Produção**: rede externa nomeada `web` (padrão Traefik).

### Variáveis de ambiente importantes
- `WORDPRESS_DB_HOST`, `WORDPRESS_DB_USER`, `WORDPRESS_DB_PASSWORD`
- `WORDPRESS_DEBUG` (local: `0`; produção: `1` por padrão)
- `GOOGLE_MAPS_API_KEY` (opcional; se ausente, geocoding usa Nominatim/OpenStreetMap)
- `MYSQL_ROOT_PASSWORD`, `MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD`

---

## Estrutura de diretórios

```
.
├── wp-root/                     # Núcleo WordPress versionado
├── themes/dcp/                  # Tema principal
│   ├── assets/
│   │   ├── scss/               # Fontes SCSS (ITCSS)
│   │   ├── javascript/         # Scripts fonte (functionalities/ e shared/)
│   │   ├── images/             # Imagens e ícones do tema
│   │   └── fonts/              # Fontes tipográficas
│   ├── dist/                   # Build compilado (CSS, JS, blocos, mix-manifest.json)
│   ├── library/                # Lógica PHP modular
│   │   ├── blocks/             # Blocos Gutenberg customizados
│   │   ├── dashboard*.php      # Painel do agente (rotas, widgets, AJAX)
│   │   ├── api/                # Endpoints internos do tema
│   │   ├── template-tags/      # Funções auxiliares de templates
│   │   ├── sanitizers/         # Sanitizadores de entrada
│   │   ├── assets.php          # Classe singleton de enfileiramento de assets
│   │   └── ...
│   ├── template-parts/         # Componentes PHP reutilizáveis
│   ├── languages/              # Arquivos de tradução (.po/.mo)
│   ├── package.json            # Dependências Node do tema
│   ├── webpack.mix.js          # Configuração do Laravel Mix
│   └── theme.json              # Paleta de cores, tipografia e espaçamento do tema
├── plugins/dcp-plugin/
│   └── dcp-plugin.php          # Plugin de API REST, webhooks e colunas admin
├── plugins/hacklab-dev-utils/  # Submódulo Git de utilidades de dev (WP-CLI, PsySH)
├── mu-plugins/                 # Must-use plugins (vazio por padrão)
├── compose/
│   ├── local/
│   │   ├── wordpress/Dockerfile
│   │   ├── watcher/Dockerfile  # Container Node para watch de assets
│   │   └── mariadb/data/       # Dumps SQL para inicialização do banco
│   └── entrypoint-extra/
│       └── sync-dependencies.sh
├── dev-scripts/                # Atalhos de shell para rotina local
├── Pods/                       # Exportações JSON das configurações do Pods
├── n8n/                        # Workflows exportados do n8n
├── docker-compose.yml          # Stack local
├── docker-compose.deploy.yml   # Stack de produção
├── .gitlab-ci.yml              # CI/CD legacy (GitLab → Kubernetes)
└── .github/workflows/          # CI/CD atual (GitHub Actions)
```

### Sobre o `style.css` na raiz
O arquivo `style.css` na raiz do projeto é um **link simbólico** para `themes/dcp/style.css`. Ele existe para compatibilidade com o plugin **git-updater**, que atualiza o tema diretamente via release do GitHub.

---

## Comandos de build e desenvolvimento

### Setup inicial
```bash
git clone <ssh-do-repositorio> dcp
cd dcp
git submodule update --init --recursive
docker-compose up --build
```

### Scripts utilitários (`dev-scripts/`)

| Script | Descrição |
|--------|-----------|
| `./dev-scripts/wp <comando>` | Executa WP-CLI dentro do container `wordpress` |
| `./dev-scripts/mysql` | Acessa o MariaDB como usuário `wordpress` |
| `./dev-scripts/mysql-root` | Acessa o MariaDB como `root` |
| `./dev-scripts/dump` | Gera dump do banco de dados |
| `./dev-scripts/dev.sh` | Roda o PHP built-in server localmente (sem Docker) |
| `./dev-scripts/compilar.sh` | Build de produção do tema via container Node efêmero |
| `./dev-scripts/zip.sh` | Gera `zips/dcp.zip` do tema (exclui `node_modules`) |

### Build de assets do tema
```bash
cd themes/dcp

# Desenvolvimento (com watch)
npm install
npm run watch

# Produção (minificado)
npm run production
```

O serviço `watcher` do `docker-compose.yml` executa `npm install && npm run watch` automaticamente ao subir os containers.

### Estrutura do build (`webpack.mix.js`)
- **SCSS**: `app.scss`, `dashboard.scss`, `editor.scss`
- **JS**: cada arquivo em `assets/javascript/functionalities/*.js` vira um chunk separado em `dist/js/functionalities/`
- **Blocos Gutenberg**: cada pasta em `library/blocks/*/` com `.js` e `.scss` gera assets em `dist/blocks/<nome-do-bloco>/`
- **Source maps**: `eval-source-map` em dev; `source-map` em produção
- **Extração de dependências**: `@wordpress/dependency-extraction-webpack-plugin` com `combineAssets: true`

---

## Convenções de código

### PHP
- **Namespace base**: `hacklabr` para o tema; funções do plugin usam prefixo `dcp_`.
- O tema ainda usa `require` em arquivos procedurais (veja `library/README.md`: *"The idea is migrate later to classes"*).
- A classe `Assets` (`library/assets.php`) é um **singleton** que gerencia enfileiramento de scripts e estilos.
- **Indentação**: 4 espaços (`.editorconfig`).
- **Charset**: UTF-8, fim de linha LF.

### SCSS: ITCSS + BEM
A organização de estilos segue rigorosamente **ITCSS** (Inverted Triangle CSS) com nomenclatura **BEM**:

```
assets/scss/
├── 1-settings/          # Variáveis globais (cores, breakpoints)
├── 2-tools/             # Mixins e funções Sass
├── 3-generic/           # Reset, fontes, estilos html/body
├── 4-elements/          # Estilos de elementos HTML puros
├── 5-objects/           # Objetos reutilizáveis (botões, containers, forms)
├── 6-components/        # Componentes específicos do projeto
├── 7-trumps/            # Utilitários e helpers (!important)
├── 9-overrides/         # Sobrescritas de plugins/componentes de terceiros
└── app.scss             # Ponto de entrada principal
```

**Regras importantes:**
- SEMPRE usar variáveis Sass para cores, tamanhos e espaçamentos.
- EVITE estilizar elementos HTML diretamente; prefira classes. Se necessário, seja específico (`.myblock>.content>p`, nunca `.myblock p`).
- Objetos e componentes **NÃO devem** conter estilização externa (margin, position, width, max-width). Quem define é o elemento pai ou modificador.
- Overrides de terceiros ficam em `9-overrides/`, mas **sempre** usando variáveis do projeto.

### JavaScript
- Cada funcionalidade isolada vive em seu próprio arquivo dentro de `assets/javascript/functionalities/`.
- Arquivos em `assets/javascript/shared/` são módulos reutilizáveis (ex: `wait.js`, `pins.js`, `legends.js`).
- Blocos Gutenberg usam React e se beneficiam da extração automática de dependências do WordPress.

### WordPress
- **Text domain do tema**: `hacklabr`
- **Role customizada**: `agente-dcp` (`DASHBOARD_AGENT_ROLE`), criada em `library/dashboard.php`.
- **Rewrite rules**: `^dashboard/([^/]+)/?` mapeia para `pagename=dashboard&ver=$matches[1]`.
- **Custom post types gerenciados via Pods**: `risco`, `acao`, `relato`, `apoio`, `recomendacao`, `situacao_atual`, entre outros.

---

## Testes

**Não há suíte de testes automatizados configurada neste projeto** (não há PHPUnit, Jest, Pest, Cypress, etc.).

A validação é feita manualmente e pelos pipelines de CI/CD:
1. O workflow do GitHub Actions compila os assets (`npm run production`) e falha se o build quebrar.
2. O artefato do tema é inspecionado (`ls -lhtra`) antes do upload.
3. Em produção, o deploy via git-updater aciona um curl de teste no endpoint de atualização.

Para testar localmente:
- Use `./dev-scripts/wp` para rodar comandos WP-CLI e verificar estado do WordPress.
- Para depuração interativa, insira `eval(\psy\sh());` no código PHP e execute `./dev-scripts/dev.sh`.

---

## Segurança

- O plugin `dcp-plugin` expõe endpoints REST **públicos** (`permission_callback => '__return_true'`). Isso é intencional para consumo por apps parceiras, mas qualquer modificação nesses endpoints deve avaliar impacto de exposição de dados.
- O webhook `/wp-json/dcp/v1/webhook/situacao-atual` aceita POST sem autenticação (ou com autenticação básica via WordPress API, conforme configuração do n8n). Verifique se a URL está protegida por firewall ou chave secreta em produção.
- **Nunca commite** dumps de produção com dados reais para o repositório.
- `WORDPRESS_DEBUG` deve permanecer `0` em produção.
- O arquivo `.htaccess` local está em `compose/local/wordpress/htaccess`; em produção a configuração é gerenciada pela imagem Docker ou pelo reverse proxy (Traefik).

---

## Deploy e CI/CD

### GitHub Actions (fluxo atual)
Localizado em `.github/workflows/`:

1. **Build and Publish on DockerHub**
   - Gatilho: push para branches `release/**`, `hotfix/**`, `feature/**` ou tags.
   - Build e push da imagem Docker `nossas/dcp-wp` para o Docker Hub.

2. **CI (build de tema e release)**
   - Gatilho: push para `main` ou tags.
   - Remove `dist/`, `node_modules` e `package-lock.json`.
   - Executa `npm install && npm run production`.
   - Faz upload do artefato `dcp`.
   - Em tags: cria release no GitHub, gera `.zip` e dispara atualização via git-updater no site de produção.

### GitLab CI (legacy)
O arquivo `.gitlab-ci.yml` contém pipelines para deploy em Kubernetes em namespaces `base-theme-site-dev` e `base-theme-site-prod`. O fluxo:
1. `build_assets`: compila o tema com Node 20.
2. `create_pack_*`: gera zip do tema e publica como package genérico do GitLab.
3. `deploy_to_*`: usa `kubectl` para copiar o zip para o pod WordPress e instalar via `wp theme install --force`.

### Deploy em produção (Docker Compose)
O arquivo `docker-compose.deploy.yml`:
- Usa a imagem `nossas/dcp-wp` (tag configurável via `WORDPRESS_DOCKER_IMAGE`).
- Inclui Redis para cache de objetos.
- Espera a rede externa `web` (padrão Traefik).
- Variáveis sensíveis são injetadas via environment.

---

## Dados e integrações externas

### Pods (Custom Post Types e Campos)
As definições de Pods estão exportadas em `Pods/`. O arquivo `autores.json` é um exemplo de exportação de taxonomy com campos customizados (avatar). Alterações estruturais no Pods devem ser acompanhadas de exportação atualizada para versionamento.

### n8n
O workflow `n8n/NOSSAS_DCP___SITUACAO_ATUAL.json` é executado automaticamente a cada hora (minuto 1) e realiza a coleta de dados meteorológicos e de estágio de risco, enviando-os ao WordPress via webhook.

**Fluxo de execução:**
1. **Schedule Trigger** (`scheduleTrigger`) — dispara a cada hora, no minuto 1.
2. **HG_GET_RJ_LAT_LON** (`httpRequest`) — consulta a API HG Brasil (`https://api.hgbrasil.com/weather`) para obter a condição climática do Rio de Janeiro (coordenadas `-22.8873378, -43.2559982`). Parâmetros da consulta:
   - `key`: `9aef5a6d`
   - `fields`: `only_results,temp,date,time,condition_code,description,currently,rain,condition_slug`
3. **HTTP GET ESTAGIO** (`httpRequest`) — consulta a API de estágio da COCR (`https://aplicativo.cocr.com.br/estagio_api`) com um timestamp no query-string para evitar cache.
4. **Merge** (`merge`) — combina os dois resultados (clima + estágio) em um único objeto.
5. **SAVE DCP SITUACAO ATUAL ESTAGIO** (`httpRequest`) — envia os dados combinados via `POST` para o endpoint WordPress `/wp-json/dcp/v1/webhook/situacao-atual/`.

**Payload enviado ao webhook (`multipart/form-data`):**
| Campo | Origem |
|-------|--------|
| `temp` | `$json.clima.temp` |
| `condition_code` | `$json.clima.condition_code` |
| `description` | `$json.clima.description` |
| `is_rain` | `$json.clima.rain` |
| `estagio` | `$json.estagio.estagio` |
| `date` | `$json.clima.date` |
| `time` | `$json.clima.time` |
| `currently` | `$json.clima.currently` |
| `condition_slug` | `$json.clima.condition_slug` |

**Destinos configurados:**
- `https://<staging-url>/wp-json/dcp/v1/webhook/situacao-atual/` (ambiente staging)
- `https://defesaclimaticapopular.org/wp-json/dcp/v1/webhook/situacao-atual/` (produção)
- `https://<ngrok-local>/wp-json/dcp/v1/webhook/situacao-atual/` (desabilitado; ambiente local via ngrok)

A autenticação nos dois primeiros nós usa credenciais do tipo `wordpressApi` (WP DCP PROD); o nó local usa `WP_DCP_LOCAL`. Para reimportar o workflow no n8n, utilize a interface web do n8n.

### API REST do plugin (`dcp-plugin`)
Endpoints documentados (não é uma lista exaustiva; consulte o código fonte):
- `GET /wp-json/dcp/v1/riscos` — lista riscos com paginação.
- `GET /wp-json/dcp/v1/riscos-resumo` — resumo das ocorrências nas últimas 24h.
- `GET /wp-json/dcp/v1/abrigos` — lista locais seguros (tipo `locais-seguros`).
- `GET /wp-json/dcp/v1/dicas` — recomendações ativas (filtro por `tipo` e `active`).
- `GET /wp-json/dcp/v1/contatos` — contatos de emergência (Bombeiros, Defesa Civil, SAMU).
- `GET /wp-json/dcp/v1/risco-regiao` — situação atual ativa (alerta, estágio, temperatura).
- `GET /wp-json/dcp/v1/situacao-atual-home` — conteúdo dinâmico da home.
- `POST /wp-json/dcp/v1/webhook/situacao-atual` — webhook para atualização de clima/estágio.

---

## Atualizações do tema base (boilerplate Hacklab)

Este projeto nasceu do boilerplate `base-wordpress-project` da Hacklab. Para herdar melhorias:
```bash
git remote add temaBase git@git.hacklab.com.br:open-source/base-wordpress-project.git
git pull temaBase develop
```
Revise os commits antes de aplicar para evitar reverter personalizações do DCP.

---

## Contatos e suporte

- Infraestrutura/integrações: equipe Hacklab — `contato@hacklab.com.br`
- Issues e pull requests: abra no repositório com descrição do fluxo esperado, dependências externas e anexos relevantes.
