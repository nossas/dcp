# Arquitetura

## Tecnologias e stack

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

## Arquitetura de rede (Docker)
- **Local**: duas redes bridge (`interna` e `wordpress-bot`).
- **Produção**: rede externa nomeada `web` (padrão Traefik).

## Variáveis de ambiente importantes
- `WORDPRESS_DB_HOST`, `WORDPRESS_DB_USER`, `WORDPRESS_DB_PASSWORD`
- `WORDPRESS_DEBUG` (local: `0`; produção: `1` por padrão)
- `GOOGLE_MAPS_API_KEY` (opcional; se ausente, geocoding usa Nominatim/OpenStreetMap)
- `MYSQL_ROOT_PASSWORD`, `MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD`

## Estrutura de diretórios

```
.
├── wp-root/                     # Núcleo WordPress versionado
├── themes/dcp/                  # Tema principal
│   ├── assets/
│   │   ├── scss/               # Fontes SCSS (ITCSS)
│   │   ├── javascript/         # Scripts fonte
│   │   ├── images/             # Imagens e ícones
│   │   └── fonts/              # Fontes tipográficas
│   ├── dist/                   # Build compilado
│   ├── library/                # Lógica PHP modular
│   │   ├── blocks/             # Blocos Gutenberg
│   │   ├── dashboard*.php      # Painel do agente
│   │   ├── api/                # Endpoints internos
│   │   ├── template-tags/      # Funções auxiliares
│   │   ├── sanitizers/         # Sanitizadores
│   │   └── assets.php          # Classe singleton de assets
│   ├── template-parts/         # Componentes PHP reutilizáveis
│   ├── languages/              # Traduções
│   ├── package.json
│   ├── webpack.mix.js
│   └── theme.json              # Cores, tipografia, espaçamento
├── plugins/dcp-plugin/         # Plugin de API REST e webhooks
├── plugins/hacklab-dev-utils/  # Submódulo de utilidades
├── mu-plugins/                 # Must-use plugins
├── compose/                    # Docker local e deploy
├── dev-scripts/                # Atalhos de shell
├── Pods/                       # Exportações Pods
├── n8n/                        # Workflows n8n
├── docker-compose.yml
├── docker-compose.deploy.yml
└── .github/workflows/          # CI/CD
```

### Sobre o `style.css` na raiz
O arquivo `style.css` na raiz é um **link simbólico** para `themes/dcp/style.css`. Ele existe para compatibilidade com o plugin **git-updater**.
