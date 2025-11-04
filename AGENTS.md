# Repository Guidelines

## Project Structure & Module Organization
- `themes/dcp` — main theme; source assets in `assets/`, compiled bundles in `dist/`.
- `plugins/` holds project-specific plugins; `mu-plugins/` loads always-on integrations.
- Docker recipes live in `compose/`, including PHP overrides in `compose/local/wordpress` and database state in `compose/local/mariadb`.
- Reuse the helpers inside `dev-scripts/` for dumps, WP-CLI, and MySQL shells.
- Cache directories (`node_modules/`, `mariadb_data/`, `Pods/`) stay at the repo root but remain ignored by git.

## Build, Test, and Development Commands
- `docker-compose up` starts the local stack; add `-d` to detach and `docker-compose down -v` for a clean DB reset.
- Inside `themes/dcp`, run `npm install` once, then `npm run watch` for live recompiles or `npm run production` for optimized bundles.
- Use `dev-scripts/wp <subcommand>` for container-aware WP-CLI actions such as `dev-scripts/wp plugin list`.
- `dev-scripts/mysql` and `dev-scripts/mysql-root` open shells with the correct credentials, avoiding manual password management.

## Coding Style & Naming Conventions
- `.editorconfig` enforces LF endings, UTF-8, and 4-space indentation (YAML stays at 2 spaces).
- Follow WordPress Coding Standards; prefix PHP functions, hooks, and taxonomies with `dcp_`.
- Favor descriptive kebab-case for folders and keep Sass partials organized by component under `themes/dcp/assets`.
- Commit front-end updates only after running `npm run production` so `dist/` mirrors the source changes.

## Testing Guidelines
- Automated tests are not in place, so smoke-test critical flows: homepage hero, relatos archive filters, relato submission, and login/reset screens.
- After template edits, run `docker-compose restart wordpress` and confirm there are no PHP notices in the container logs.
- Before risky database work, create a snapshot with `dev-scripts/dump > backup.sql`.

## Commit & Pull Request Guidelines
- Write short, imperative commit summaries and reference issues with `Ref.: #123` or `#123` when relevant.
- Keep the release tagging pattern (`Version: x.y.z`) for theme bumps and update `style.css` headers together with the version commit.
- Ensure assets are rebuilt, containers pass smoke-tests, and temporary dumps/logs are excluded before opening a PR.
- PR descriptions should outline the context, solution, verification steps, and UI captures when the change touches visible components.

## Environment & Configuration Tips
- Tune PHP by editing `compose/local/wordpress/php/extra.ini`, then restart the WordPress container.
- Toggle per-environment flags (e.g., `WORDPRESS_DEBUG=0`) through `docker-compose.yml` overrides or `.env` files.
- For interactive debugging, enable `hacklab-dev-utils` and drop `eval(\psy\sh());` where execution should pause; exit the Psy shell to resume.
