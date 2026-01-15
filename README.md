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

## Pré-requisitos
- Git e acesso ao repositório.
- Docker e Docker Compose 2.x.
- Node 18+ e npm para builds locais do tema.
- Acesso ao repositório do submódulo `hacklab-dev-utils`.
- (Opcional) Chave `GOOGLE_MAPS_API_KEY` para habilitar geocoding via Google em vez do Nominatim.

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
2. Use `./dev-scripts/wp` para comandos WP-CLI no container e `./dev-scripts/mysql` para acessar o banco com o usuário `wordpress`.
3. Para depuração com PsySH, execute `./dev-scripts/dev.sh` e injete `eval(\psy\sh());` no ponto desejado.
4. Gere builds de produção com `npm run production` antes de publicar mudanças que afetem front-end ou APIs.

### Estrutura do tema
- `library/dashboard*.php`: rotas e widgets do painel (`/dashboard/inicio`, `/dashboard/riscos`, `/dashboard/indicadores`), criação da role `agente-dcp` e filtros de permissão.
- `template-parts/`: componentes PHP utilizados nas páginas públicas e no dashboard.
- `assets/js`, `assets/scss`: código fonte compilado pelo Laravel Mix definido em `webpack.mix.js`.

### API e integrações
O plugin `dcp-plugin` entrega endpoints públicos para uso em apps e integrações:
- `GET /wp-json/dcp/v1/riscos`: lista riscos com paginação, anexos e metadados.
- `GET /wp-json/dcp/v1/riscos-resumo`: resumo das ocorrências nas últimas 24h.
- `POST /wp-json/dcp/v1/webhook/situacao-atual`: webhook para atualizar temperatura, estágio e clima.
- `GET /wp-json/dcp/v1/situacao-atual-home`: conteúdo dinâmico da home.
Outros endpoints tratam de tarefas de ETL, dashboards e sincronização com Pods. Consulte o arquivo `plugins/dcp-plugin/dcp-plugin.php` para detalhes de parâmetros e cargas.

## Deploy
O arquivo `docker-compose.deploy.yml` referencia a imagem `nossas/dcp-wp` e espera os serviços na rede externa `web` (compatível com Traefik). Ajuste as variáveis `WORDPRESS_DB_*`, `GOOGLE_MAPS_API_KEY` e a tag da imagem conforme o ambiente.

## Atualizações do tema base
Este projeto se originou do boilerplate Hacklab. Para herdar melhorias, mantenha um remote apontando para o repositório base e sincronize quando necessário:
```bash
git remote add temaBase git@git.hacklab.com.br:open-source/base-wordpress-project.git
git pull temaBase develop
```
Revise os commits antes de aplicar para garantir que não revertam personalizações do DCP.

## Suporte e contato
Em caso de dúvidas sobre infraestrutura ou integrações, abra uma issue no repositório ou contate a equipe Hacklab em `contato@hacklab.com.br`. Para novos recursos no mapa ou no dashboard, descreva o fluxo esperado, dependências externas e anexos relevantes ao abrir a issue ou pull request.


## Contexto do Projeto e Objetivos

### Defesa Climática Popular (DCP)

A **Defesa Climática Popular** é uma iniciativa voltada ao fortalecimento das respostas comunitárias frente aos impactos da crise climática em territórios populares. O projeto parte do reconhecimento de que eventos como alagamentos, deslizamentos, ondas de calor e outros desastres climáticos afetam de forma desproporcional populações historicamente vulnerabilizadas, demandando soluções que integrem tecnologia, organização comunitária e produção de conhecimento local.

O projeto piloto foi realizado no **Jacarezinho (RJ)** e envolveu a formação de lideranças climáticas, ações de aprendizado coletivo e mobilização comunitária, além do desenvolvimento de tecnologias comunitárias e mapeamentos participativos de risco. Ao ampliar as capacidades locais de prevenção, resposta e articulação, a iniciativa busca fortalecer a **organização coletiva**, a **autonomia territorial** e a **resiliência climática**.

Este repositório mantém a base tecnológica do projeto, construída sobre WordPress, e sustenta mapas colaborativos, conteúdos informativos e ferramentas digitais que apoiam a tomada de decisão comunitária e a redução de danos em contextos de risco climático.

### Componentes da Solução

A solução digital da Defesa Climática Popular é composta pelos seguintes módulos:

- **Recomendações**  
  Módulo responsável por orientar a população sobre **o que fazer** em situações de risco climático, como alagamentos, deslizamentos e outros desastres ambientais, apoiando ações de prevenção, resposta imediata e redução de danos.

- **Quem Chamar**  
  Funcionalidade que auxilia na **identificação e no contato** com pessoas, serviços, órgãos públicos ou iniciativas comunitárias adequadas para cada tipo de situação de risco, facilitando o acionamento rápido de redes de apoio e resposta.

- **Rede de Apoio**  
  Módulo que orienta e apresenta **locais, serviços e iniciativas** onde é possível buscar abrigo, apoio ou assistência em momentos de emergência climática, fortalecendo a conexão entre a população e os recursos disponíveis no território.

- **Mapa de Riscos e Apoios**  
  Funcionalidade baseada no plugin **Jeo**, que exibe um mapa interativo com **legendas e camadas temáticas**, incluindo áreas de alagamento, pontos de lixo exposto, locais de risco e redes de apoio comunitárias. É uma ferramenta central para visualização territorial e planejamento de ações preventivas.

- **Conteúdos sobre Riscos Climáticos**  
  Conjunto de conteúdos informativos e educativos sobre temas como lixo, alagamentos e outros riscos ambientais, com foco em conscientização, prevenção e fortalecimento do conhecimento comunitário sobre a crise climática e seus impactos locais.
