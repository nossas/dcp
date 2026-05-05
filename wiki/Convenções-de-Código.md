# Convenções de Código

## PHP
- **Namespace base**: `hacklabr` para o tema; funções do plugin usam prefixo `dcp_`.
- O tema ainda usa `require` em arquivos procedurais (veja `library/README.md`: *"The idea is migrate later to classes"*).
- A classe `Assets` (`library/assets.php`) é um **singleton** que gerencia enfileiramento de scripts e estilos.
- **Indentação**: 4 espaços (`.editorconfig`).
- **Charset**: UTF-8, fim de linha LF.

## SCSS: ITCSS + BEM
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

## JavaScript
- Cada funcionalidade isolada vive em seu próprio arquivo dentro de `assets/javascript/functionalities/`.
- Arquivos em `assets/javascript/shared/` são módulos reutilizáveis (ex: `wait.js`, `pins.js`, `legends.js`).
- Blocos Gutenberg usam React e se beneficiam da extração automática de dependências do WordPress.

## WordPress
- **Text domain do tema**: `hacklabr`
- **Role customizada**: `agente-dcp` (`DASHBOARD_AGENT_ROLE`), criada em `library/dashboard.php`.
- **Rewrite rules**: `^dashboard/([^/]+)/?` mapeia para `pagename=dashboard&ver=$matches[1]`.
- **Custom post types gerenciados via Pods**: `risco`, `acao`, `relato`, `apoio`, `recomendacao`, `situacao_atual`, entre outros.
