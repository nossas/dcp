# API e Integrações

## API REST do plugin (`dcp-plugin`)

Endpoints documentados (não é uma lista exaustiva; consulte o código fonte):
- `GET /wp-json/dcp/v1/riscos` — lista riscos com paginação.
- `GET /wp-json/dcp/v1/riscos-resumo` — resumo das ocorrências nas últimas 24h.
- `GET /wp-json/dcp/v1/abrigos` — lista locais seguros (tipo `locais-seguros`).
- `GET /wp-json/dcp/v1/dicas` — recomendações ativas (filtro por `tipo` e `active`).
- `GET /wp-json/dcp/v1/contatos` — contatos de emergência (Bombeiros, Defesa Civil, SAMU).
- `GET /wp-json/dcp/v1/risco-regiao` — situação atual ativa (alerta, estágio, temperatura).
- `GET /wp-json/dcp/v1/situacao-atual-home` — conteúdo dinâmico da home.
- `POST /wp-json/dcp/v1/webhook/situacao-atual` — webhook para atualização de clima/estágio.

## Webhook `situacao-atual` (n8n)

O workflow n8n (`n8n/NOSSAS_DCP___SITUACAO_ATUAL.json`) é executado a cada hora para coletar dados meteorológicos e de estágio de risco e enviá-los ao WordPress.

### Fontes de dados
- **Clima:** API HG Brasil (`https://api.hgbrasil.com/weather`), consultando as coordenadas do Rio de Janeiro (`-22.8873378, -43.2559982`). Retorna `temp`, `date`, `time`, `condition_code`, `description`, `currently`, `rain`, `condition_slug`.
- **Estágio:** API da COCR (`https://aplicativo.cocr.com.br/estagio_api`), que retorna o estágio operacional de risco.

### Payload enviado ao WordPress (`multipart/form-data`)

| Campo | Descrição |
|-------|-----------|
| `temp` | Temperatura atual (°C) |
| `condition_code` | Código numérico da condição climática |
| `description` | Descrição textual do clima |
| `is_rain` | Indicador booleano de chuva |
| `estagio` | Estágio operacional de risco da COCR |
| `date` | Data da leitura |
| `time` | Hora da leitura |
| `currently` | Período do dia (`dia` / `noite`) |
| `condition_slug` | Slug da condição climática |

### Destinos configurados no workflow
1. `https://<staging-url>/wp-json/dcp/v1/webhook/situacao-atual/` — ambiente staging
2. `https://defesaclimaticapopular.org/wp-json/dcp/v1/webhook/situacao-atual/` — ambiente de produção
3. `https://<ngrok-local>/wp-json/dcp/v1/webhook/situacao-atual/` — ambiente local (desabilitado)

A autenticação nos ambientes staging e produção utiliza credenciais do tipo `wordpressApi` (WP DCP PROD); o nó local usa `WP_DCP_LOCAL`.

## Pods (Custom Post Types e Campos)

As definições de Pods estão exportadas em `Pods/`. O arquivo `autores.json` é um exemplo de exportação de taxonomy com campos customizados (avatar). Alterações estruturais no Pods devem ser acompanhadas de exportação atualizada para versionamento.

CPTs gerenciados via Pods:
- `risco` — Riscos mapeados pela comunidade
- `acao` — Ações comunitárias
- `relato` — Relatos de ações realizadas
- `apoio` — Pontos de apoio
- `recomendacao` — Recomendações de segurança
- `situacao_atual` — Situação climática atual

Taxonomias:
- `situacao_de_risco` — Categorias de risco
- `tipo_acao` — Tipos de ação
- `tipo_apoio` — Tipos de apoio
