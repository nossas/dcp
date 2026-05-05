# Segurança

- O plugin `dcp-plugin` expõe endpoints REST **públicos** (`permission_callback => '__return_true'`). Isso é intencional para consumo por apps parceiras, mas qualquer modificação nesses endpoints deve avaliar impacto de exposição de dados.
- O webhook `/wp-json/dcp/v1/webhook/situacao-atual` aceita POST sem autenticação (ou com autenticação básica via WordPress API, conforme configuração do n8n). Verifique se a URL está protegida por firewall ou chave secreta em produção.
- **Nunca commite** dumps de produção com dados reais para o repositório.
- `WORDPRESS_DEBUG` deve permanecer `0` em produção.
- O arquivo `.htaccess` local está em `compose/local/wordpress/htaccess`; em produção a configuração é gerenciada pela imagem Docker ou pelo reverse proxy (Traefik).
