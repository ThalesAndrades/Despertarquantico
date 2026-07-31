## Deploy (Hostinger via Git)

### O que fica no repositório (branch main)
- O repositório já está no formato de `public_html/` (raiz publicada).
- Segredos não entram no Git. Use apenas [.env.example](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/.env.example) como referência.

### O que você edita direto no repositório (quando precisar)
- Textos/estrutura da landing: [landing/index.php](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/views/landing/index.php)
- Layouts: [landing.php](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/views/layouts/landing.php), [app.php](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/views/layouts/app.php), [admin.php](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/views/layouts/admin.php)
- CSS: [style.css](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/public/css/style.css), [landing.css](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/public/css/landing.css), [dashboard.css](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/public/css/dashboard.css)
- JS: [landing.js](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/public/js/landing.js), [landing-hero3d-loader.js](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/public/js/landing-hero3d-loader.js)
- Configs que não carregam segredos: [app.php](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/config/app.php), [database.php](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/config/database.php), [woovi.php](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/config/woovi.php), [mail.php](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/config/mail.php), [sequenzy.php](file:///c:/Users/Thales/Desktop/Sunyan/Despertarquantico/config/sequenzy.php)

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
  (deve mostrar `WOOVI_APP_ID: true`)

---

## Cutover Asaas → Woovi (fazer UMA vez, nesta ordem)

> A ordem importa. Fora dela, dá para receber um PIX sem liberar o acesso da aluna.

**Antes do deploy**

1. **Feche o que está em aberto no Asaas.** Liste as cobranças `pending` ainda válidas
   (`SELECT id, customer_email, amount FROM orders WHERE status='pending' AND provider='asaas'`).
   Quem pagar um link antigo **depois** do cutover cai em `/webhook/asaas`, que apenas
   registra no inbox como `needs_manual_review` — o acesso terá de ser liberado à mão no `/admin`.
2. No painel da Woovi (app.woovi.com), com **2FA habilitado**, gere um **AppID**.
3. No `.env` do servidor: adicione `WOOVI_APP_ID=<appid>` e **remova** `ASAAS_API_KEY`,
   `ASAAS_ENV`, `ASAAS_WEBHOOK_TOKEN`, `ASAAS_WALLET_ID`.

**Deploy**

4. Deploy normal pelo hPanel. Na primeira conexão, o `Database.php` renomeia sozinho
   `asaas_payment_id → provider_charge_id`, `asaas_invoice_url → provider_payment_url`,
   `asaas_event → provider_event`, `users.asaas_customer_id → provider_customer_id`,
   e cria `provider` (pedidos antigos ficam `'asaas'`), `provider_correlation_id` e `brcode`.
   **É `ALTER ... CHANGE COLUMN`, ou seja, renomeia preservando os dados — nenhuma venda é perdida.**
   Ainda assim: **faça backup do banco antes**, porque isso roda automático e não tem rollback.

**Depois do deploy**

5. No painel da Woovi, crie o **webhook** apontando para `https://despertarespiral.com/webhook/woovi`,
   evento de **cobrança paga**. Não há token para colar — a autenticação é por assinatura.
6. Dispare o **webhook de teste** pelo painel e confirme HTTP 200.
7. Faça **uma compra real de valor baixo** e confirme, no `/admin`, que o pedido virou `paid`
   e que o acesso ao produto foi liberado. Este é o único teste que prova o caminho completo.
8. Desative o webhook antigo do Asaas no painel deles.

**Rollback:** as colunas são neutras de gateway; voltar para o Asaas exigiria restaurar
`src/Asaas.php` (está no histórico do git, commit anterior à branch `feat/woovi-pix`).

### Seed rápido (criar admin + produtos de teste)
> ⚠️ **Nunca use senha fraca aqui.** Este endpoint cria um usuário **admin**. Uma senha
> previsível somada a um `BOOTSTRAP_ENABLED` esquecido em `true` entrega o painel inteiro.

1) Gere segredos fortes e aleatórios (não invente à mão):
   ```bash
   # token do bootstrap
   openssl rand -hex 32
   # senha inicial do admin
   openssl rand -base64 24
   ```
2) No servidor, no `.env`, adicione temporariamente:
   - `BOOTSTRAP_ENABLED=true`
   - `BOOTSTRAP_TOKEN=<saída do primeiro comando>`
   - `ADMIN_BOOTSTRAP_EMAIL=sunyan@despertarespiral.com`
   - `ADMIN_BOOTSTRAP_PASSWORD=<saída do segundo comando>`
3) Acesse:
   - `https://despertarespiral.com/_bootstrap?token=SEU_BOOTSTRAP_TOKEN`
4) **Imediatamente depois (não deixe para depois):**
   - `BOOTSTRAP_ENABLED=false` — ou, melhor, **remova** as quatro variáveis do `.env`
   - troque a senha do admin no painel
   - confirme que `https://despertarespiral.com/_bootstrap?token=...` agora responde 404/403
