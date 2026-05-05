# Setup e Desenvolvimento

## Pré-requisitos
- Git e acesso ao repositório.
- Docker e Docker Compose 2.x.
- Node 18+ e npm para builds locais do tema.
- Acesso ao repositório do submódulo `hacklab-dev-utils`.
- (Opcional) Chave `GOOGLE_MAPS_API_KEY` para geocoding via Google.

## Setup rápido
```bash
git clone <ssh-do-repositorio> dcp
cd dcp
git submodule update --init --recursive
docker-compose up --build
```
O serviço `watcher` instala dependências do tema e roda `npm run watch` automaticamente. Caso prefira rodar localmente, entre em `themes/dcp/` e execute `npm install && npm run watch`.

Importe um dump para `compose/local/mariadb/data/` e reinicie os containers (`docker-compose down -v && docker-compose up`) quando precisar carregar dados reais.

## Fluxo de desenvolvimento
1. Trabalhe em branches a partir de `develop`; `main` acompanha o ambiente de produção.
2. Use `./dev-scripts/wp` para comandos WP-CLI no container e `./dev-scripts/mysql` para acessar o banco.
3. Para depuração com PsySH, execute `./dev-scripts/dev.sh` e injete `eval(\psy\sh());` no ponto desejado.
4. Gere builds de produção com `npm run production` antes de publicar mudanças que afetem front-end ou APIs.

## Scripts utilitários (`dev-scripts/`)

| Script | Descrição |
|--------|-----------|
| `./dev-scripts/wp <comando>` | Executa WP-CLI dentro do container `wordpress` |
| `./dev-scripts/mysql` | Acessa o MariaDB como usuário `wordpress` |
| `./dev-scripts/mysql-root` | Acessa o MariaDB como `root` |
| `./dev-scripts/dump` | Gera dump do banco de dados |
| `./dev-scripts/dev.sh` | Roda o PHP built-in server localmente (sem Docker) |
| `./dev-scripts/compilar.sh` | Build de produção do tema via container Node efêmero |
| `./dev-scripts/zip.sh` | Gera `zips/dcp.zip` do tema (exclui `node_modules`) |

## Build de assets do tema
```bash
cd themes/dcp

# Desenvolvimento (com watch)
npm install
npm run watch

# Produção (minificado)
npm run production
```

O serviço `watcher` do `docker-compose.yml` executa `npm install && npm run watch` automaticamente ao subir os containers.

## Estrutura do build (`webpack.mix.js`)
- **SCSS**: `app.scss`, `dashboard.scss`, `editor.scss`
- **JS**: cada arquivo em `assets/javascript/functionalities/*.js` vira um chunk separado em `dist/js/functionalities/`
- **Blocos Gutenberg**: cada pasta em `library/blocks/*/` com `.js` e `.scss` gera assets em `dist/blocks/<nome-do-bloco>/`
- **Source maps**: `eval-source-map` em dev; `source-map` em produção
- **Extração de dependências**: `@wordpress/dependency-extraction-webpack-plugin` com `combineAssets: true`
