## Deploy (Hostinger via Git)

### O que fica no repositório (branch main)
- O repositório já está no formato de `public_html/` (raiz publicada).
- Segredos não entram no Git. Use apenas [.env.example](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/.env.example) como referência.

### O que você edita direto no repositório (quando precisar)
- Textos/estrutura da landing: [landing/index.php](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/views/landing/index.php)
- Layouts: [landing.php](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/views/layouts/landing.php), [app.php](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/views/layouts/app.php), [admin.php](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/views/layouts/admin.php)
- CSS: [style.css](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/public/css/style.css), [landing.css](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/public/css/landing.css), [dashboard.css](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/public/css/dashboard.css)
- JS: [landing.js](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/public/js/landing.js), [landing-hero3d-loader.js](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/public/js/landing-hero3d-loader.js)
- Configs que não carregam segredos: [app.php](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/config/app.php), [database.php](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/config/database.php), [asaas.php](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/config/asaas.php), [mail.php](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/config/mail.php), [sequenzy.php](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/config/sequenzy.php)

### O que você edita no servidor (obrigatório)
- Criar/atualizar `.env` fora do docroot:
  - recomendado: `/home/<USER>/domains/despertarespiral.com/.env`
  - o [index.php](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/index.php) busca automaticamente nesse caminho se não existir `.env` no mesmo diretório do app.
- Permissões de escrita:
  - `storage/logs/`
  - `uploads/`

### Deploy via Git no hPanel
1) hPanel → Websites → `despertarespiral.com` → Git
2) Repositório: `ThalesAndrades/Despertarquantico`
3) Branch: `main`
4) Deploy directory: `public_html`
5) Deploy

### Verificação pós-deploy
- Página inicial: `https://despertarespiral.com/`
- Healthcheck (precisa do token do `.env`): `https://despertarespiral.com/_health?token=SEU_TOKEN`

