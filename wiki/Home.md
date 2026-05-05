# Defesa Climática Popular (DCP)

Repositório que mantém o stack WordPress do projeto **Defesa Climática Popular**. Aqui vivem o tema `dcp`, o plugin de integração com serviços externos e a infraestrutura Docker usada por designers, desenvolvedores e equipe de dados. O objetivo desta base é sustentar o mapa colaborativo de riscos, o painel interno para agentes comunitários e as APIs consumidas por aplicações parceiras.

## Arquitetura em alto nível
- `wp-root/`: núcleo WordPress versionado para garantir reprodutibilidade do ambiente.
- `themes/dcp/`: tema principal, com assets em `assets/`, builds em `dist/` e lógica modular em `library/` (dashboard, auth, integrações e importações).
- `plugins/dcp-plugin/`: plugin proprietário que expõe a API REST (`/wp-json/dcp/v1/*`), webhooks e rotinas de sincronização com Pods.
- `plugins/hacklab-dev-utils`: submódulo com utilidades de desenvolvimento (WP-CLI, PsySH etc.).
- `compose/`: imagens, entrypoints e configs extras de PHP, MariaDB e watcher de assets.
- `dev-scripts/`: atalhos para rotina local (`./wp`, `./mysql`, `./dev.sh`, `./dump`, entre outros).
- `style.css`: link simbólico que aponta para `themes/dcp/style.css`, exigência do fluxo com o plugin git-updater.

## Navegação da Wiki
- [[Arquitetura]] — Stack tecnológico, Docker, redes e variáveis de ambiente.
- [[Setup e Desenvolvimento]] — Como clonar, buildar e rodar o projeto localmente.
- [[Dashboard]] — Painel interno para agentes comunitários (`/dashboard/*`).
- [[API e Integrações]] — Endpoints REST, webhook n8n e integrações externas.
- [[Deploy e CI-CD]] — Pipelines de deploy e release.
- [[Convenções de Código]] — PHP, SCSS (ITCSS + BEM), JS e WordPress.
- [[Segurança]] — Endpoints públicos, autenticação e cuidados com dados.

---

## Contexto do Projeto

A **Defesa Climática Popular** é uma iniciativa voltada ao fortalecimento das respostas comunitárias frente aos impactos da crise climática em territórios populares. O projeto parte do reconhecimento de que eventos como alagamentos, deslizamentos, ondas de calor e outros desastres climáticos afetam de forma desproporcional populações historicamente vulnerabilizadas, demandando soluções que integrem tecnologia, organização comunitária e produção de conhecimento local.

O projeto piloto foi realizado no **Jacarezinho (RJ)** e envolveu a formação de lideranças climáticas, ações de aprendizado coletivo e mobilização comunitária, além do desenvolvimento de tecnologias comunitárias e mapeamentos participativos de risco.

Este repositório mantém a base tecnológica do projeto, construída sobre WordPress, e sustenta mapas colaborativos, conteúdos informativos e ferramentas digitais que apoiam a tomada de decisão comunitária e a redução de danos em contextos de risco climático.

### Componentes da Solução

- **Recomendações** — orienta a população sobre **o que fazer** em situações de risco climático.
- **Quem Chamar** — auxilia na **identificação e no contato** com pessoas, serviços e órgãos públicos.
- **Rede de Apoio** — apresenta **locais, serviços e iniciativas** onde é possível buscar abrigo ou assistência.
- **Mapa de Riscos e Apoios** — mapa interativo com legendas e camadas temáticas (plugin **Jeo**).
- **Conteúdos sobre Riscos Climáticos** — conteúdos educativos sobre lixo, alagamentos e outros riscos ambientais.
