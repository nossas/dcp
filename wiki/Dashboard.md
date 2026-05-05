# Dashboard

O dashboard é uma área administrativa frontend customizada do tema, acessível em `/dashboard/{rota}`. Ele não utiliza o wp-admin padrão; toda a interface é renderizada via template `page-dashboard.php` com rotas amigáveis definidas pelo rewrite rule `^dashboard/([^/]+)/?`.

## Autenticação e permissões
- **Role `agente-dcp`** (label "Community agent") é criada automaticamente em `library/dashboard.php` com capacidades de CRUD sobre o CPT `risco` e `upload_files`.
- O acesso ao dashboard exige `current_user_can('edit_riscos')`; usuários sem permissão são redirecionados para a tela de login.
- Após login, usuários com role `agente-dcp` são redirecionados automaticamente para o dashboard.
- ⚠️ **Inconsistência detectada:** `library/frontend_auth.php` possui uma restrição paralela que redireciona não-administradores em `is_page('dashboard')`, o que pode conflitar com a lógica de `dashboard.php`. O login AJAX também restringe acesso a administradores, impedindo que agentes comunitários autentiquem-se pelo formulário frontend.

## Rotas e funcionalidades

| Rota | Descrição |
|------|-----------|
| `/dashboard/inicio` | Página inicial com saudação, card da Situação Atual, contador de novos relatos e lista de riscos aguardando avaliação. |
| `/dashboard/riscos` | Lista de riscos com tabs: Aguardando Aprovação (`draft`), Publicados (`publish`) e Arquivados (`pending`). Cards com categoria, data, endereço, descrição e galeria de mídias (Swiper.js). |
| `/dashboard/adicionar-risco` | Formulário de cadastro de novo risco. |
| `/dashboard/editar-risco` | Edição de risco existente. |
| `/dashboard/acoes` | Lista de ações com tabs: Sugestões, Agendadas, Realizadas, Arquivadas e Ações Relatadas. Renderiza via `loop-post-card-{tipo_acao}`. |
| `/dashboard/adicionar-acao` / `/dashboard/editar-acao` | CRUD de ações. |
| `/dashboard/adicionar-relato` / `/dashboard/editar-relato` | CRUD de relatos de ações realizadas. |
| `/dashboard/apoio` | Lista de pontos de apoio filtrados por taxonomy `tipo_apoio` (Locais Seguros, Caçambas, Iniciativas Locais, Quem Acionar). Suporta arquivamento via meta `apoio_arquivado`. |
| `/dashboard/adicionar-apoio` / `/dashboard/editar-apoio-novo` | CRUD de pontos de apoio. |
| `/dashboard/situacao_atual` | Exibe o alerta climático ativo (CPT `situacao_atual`) e recomendações ativas (CPT `recomendacao` com `is_active = true`). |
| `/dashboard/alterar_risco` | Alterar situação de risco atual. |
| `/dashboard/editar_recomendacao` | Editar recomendações de segurança. |
| `/dashboard/editar_cacambas` | Editar caçambas. |
| `/dashboard/editar_quem_acionar` | Editar "quem acionar". |
| `/dashboard/indicadores` | Painel de estatísticas com filtros por data, cards de contadores e gráficos Chart.js (riscos por categoria, ações agendadas vs sugestões, realizadas vs arquivadas). |

## AJAX e operações CRUD (`dashboard-ajax.php`)
Todas as operações de criação, edição e exclusão são feitas via AJAX:
- `form_single_risco_new` / `edit` — CRUD de riscos (pode ser anônimo)
- `form_single_acao_new` / `edit` — CRUD de ações (pode ser anônimo)
- `form_single_relato_new` / `edit` — CRUD de relatos
- `form_single_apoio_new` / `edit` — CRUD de apoios
- `form_participar_acao` — Inscrição em ação
- `download_participantes_acao` — Exporta CSV de inscritos
- `form_single_delete_attachment` — Remove mídia anexada

## Indicadores (`dashboard-indicadores.php`)
Funções para contagem de posts com filtros por intervalo de datas:
- `dashboard_get_post_type_by_status_between_date()` — Conta posts por status e data
- `dashboard_get_riscos_count_by_taxonomy()` / `by_term()` — Conta riscos por taxonomia/termo

## Utilitários (`dashboard-utils.php`)
- `formatarTelefoneBR()` / `limparTelefone()` — Formatação de telefones
- `risco_badge_category()` — Badges com ícones Iconify por categoria de risco
- `upload_file_to_attachment_by_ID()` — Upload de mídias (jpg, jpeg, png, mp4)
- `dashboard_excerpt()` — Texto truncado com "Ver mais"
- `wpcf7_form_sugestao_acao()` — Hook Contact Form 7 que cria post `acao` a partir de formulário público

## Estrutura visual
- Layout: sidebar fixa à esquerda + conteúdo principal
- Ícones: Iconify + Bootstrap Icons
- Sliders: Swiper.js (galerias de mídia)
- Gráficos: Chart.js + plugin datalabels
- Responsivo: tratamento diferenciado para mobile (`wp_is_mobile()`)
