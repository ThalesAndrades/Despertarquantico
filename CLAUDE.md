# CLAUDE.md - Contexto do Projeto

## Visao Geral

**Projeto:** Mulher Espiral - Plataforma de cursos online e comunidade
**Autora/Cliente:** Sunyan Nunes
**Dominio:** mulherespiral.shop
**Hospedagem:** Hostinger (shared hosting)
**Linguagem:** PHP 7.4+ (sem frameworks externos, sem Composer)

Plataforma premium de cursos online para transformacao feminina baseada no metodo "Mulher Espiral". Inclui area de membros, comunidade anonima, checkout via Stripe e painel administrativo completo.

---

## Stack Tecnologico

- **Backend:** PHP 7.4+ puro, MVC customizado, PDO/MySQL
- **Frontend:** HTML5, CSS3 puro (variaveis CSS), Vanilla JS
- **Banco:** MySQL 5.7+ / MariaDB
- **Servidor:** Apache (mod_rewrite)
- **Pagamentos:** Stripe (via cURL, sem SDK)
- **Email:** PHP mail() / SMTP Hostinger
- **Fontes:** Playfair Display (display) + Inter (body)
- **Tema:** Dark luxo (#0A0A0A preto + #C9A84C dourado)

---

## Estrutura de Diretorios

```
/
├── index.php              # Front controller (entry point)
├── install.php            # Instalador automatico Hostinger
├── .htaccess              # HTTPS + rewrite para index.php
├── config/                # Configuracoes (app, database, stripe, mail)
├── src/                   # Classes core do framework
│   ├── Router.php         # Roteamento URL com regex
│   ├── Database.php       # PDO singleton + auto-migracao
│   ├── Auth.php           # Autenticacao (session-based)
│   ├── CSRF.php           # Protecao CSRF
│   ├── Helpers.php        # Funcoes globais (e(), redirect(), view()...)
│   ├── Mailer.php         # Envio de emails HTML
│   └── Stripe.php         # Integracao Stripe via cURL
├── controllers/           # 7 controllers MVC
│   ├── HomeController.php
│   ├── AuthController.php
│   ├── DashboardController.php
│   ├── ProductController.php
│   ├── CommunityController.php
│   ├── CheckoutController.php
│   └── AdminController.php
├── views/                 # 25 templates PHP
│   ├── layouts/           # app.php, admin.php, landing.php
│   ├── landing/           # Homepage publica
│   ├── auth/              # Login, registro, reset senha
│   ├── dashboard/         # Area do membro
│   ├── products/          # Listagem e visualizacao de cursos
│   ├── community/         # Forum anonimo
│   ├── checkout/          # Fluxo de pagamento
│   ├── admin/             # Painel administrativo
│   └── errors/            # 404
├── database/
│   └── schema.sql         # Schema MySQL (11 tabelas)
├── public/
│   ├── css/               # style.css, dashboard.css, landing.css
│   ├── js/                # app.js, community.js, landing.js
│   └── images/
└── uploads/               # Imagens de produtos e conteudo
```

---

## Banco de Dados (11 tabelas)

| Tabela | Funcao |
|--------|--------|
| `users` | Contas (email unico, anonymous_name unico, role member/admin) |
| `products` | Cursos (slug unico, preco, stripe_price_id) |
| `modules` | Secoes do curso (FK product_id, sort_order) |
| `lessons` | Aulas (FK module_id, tipo: video/text/pdf/audio) |
| `user_products` | Acesso usuario-produto (many-to-many) |
| `lesson_progress` | Progresso por aula (completed boolean) |
| `orders` | Pedidos Stripe (status: pending/paid/failed/refunded) |
| `community_posts` | Posts do forum (5 categorias, is_pinned, is_visible) |
| `community_comments` | Comentarios em posts |
| `community_likes` | Likes em posts e comentarios |

**Categorias da comunidade:** geral, desabafo, duvidas, conquistas, dicas

**Auto-migracao:** Database.php executa schema.sql automaticamente na primeira conexao e cria admin padrao (sunyan@mulherespiral.shop).

---

## Rotas Principais

**Publicas:**
- `GET /` - Landing page
- `GET|POST /login, /register, /logout` - Autenticacao
- `GET|POST /forgot-password, /reset-password` - Recuperacao de senha
- `GET|POST /checkout/{slug}` - Checkout Stripe
- `POST /webhook/stripe` - Webhook de pagamento

**Autenticadas (requireAuth):**
- `GET /dashboard` - Dashboard do membro
- `GET /products` - Meus produtos
- `GET /products/{slug}` - Visualizar curso
- `GET /products/{slug}/lesson/{id}` - Aula individual
- `POST /products/progress` - Marcar progresso
- `GET|POST /community` - Forum
- `GET /community/topic/{id}` - Topico + comentarios

**Admin (requireAdmin):**
- `GET /admin` - Dashboard admin (metricas)
- `GET|POST /admin/users` - Gerenciar usuarios
- `GET|POST /admin/products` - CRUD de produtos
- `GET|POST /admin/products/{id}/content` - Editor de modulos/aulas
- `GET /admin/orders` - Historico de pedidos
- `GET /admin/community` - Moderacao

---

## Convencoes de Codigo

### PHP
- Classes: PascalCase (`ProductController`, `StripeClient`)
- Metodos: camelCase (`markProgress()`, `createCheckoutSession()`)
- Constantes: UPPER_SNAKE (`APP_NAME`, `SESSION_LIFETIME`)
- Colunas DB: snake_case (`user_id`, `stripe_session_id`)
- Helpers globais: snake_case (`e()`, `redirect()`, `view()`, `flash()`)

### CSS
- Classes: kebab-case (`.sidebar-link`, `.product-card`)
- Variaveis CSS no `:root` (40+ variaveis de tema)
- Sem preprocessador, sem Tailwind, sem CSS modules

### HTML/Views
- Escape obrigatorio: `<?= e($variavel) ?>`
- CSRF em todo form: `<?= CSRF::field() ?>`
- Flash messages via `flash('success')` / `flash('error')`
- Dados passados para views via `view('path', compact(...))`

---

## Seguranca

- **SQL:** Prepared statements (PDO) em todas as queries
- **XSS:** `e()` wrapper de `htmlspecialchars()` em toda saida
- **CSRF:** Token obrigatorio em todos os POSTs
- **Senhas:** `password_hash()` / `password_verify()` (bcrypt)
- **Sessao:** httpOnly, SameSite=Lax, Secure, gc_maxlifetime=7200
- **Headers:** X-Content-Type-Options, X-Frame-Options, X-XSS-Protection
- **HTTPS:** Forcado via .htaccess
- **Stripe:** Verificacao HMAC-SHA256 no webhook (tolerancia 5min)
- **Uploads:** Validacao MIME type (jpeg, png, webp), limite 10MB

---

## Configuracoes

| Arquivo | Conteudo |
|---------|----------|
| `config/app.php` | APP_NAME, APP_URL, APP_ENV, SESSION_LIFETIME, limites upload |
| `config/database.php` | Host, dbname, user, password (Hostinger) |
| `config/stripe.php` | public_key, secret_key, webhook_secret, currency (brl) |
| `config/mail.php` | SMTP host/port/user/pass, from_email |

Arquivos `.example` disponiveis para database, stripe e mail.

---

## Padroes Importantes

1. **Zero dependencias externas** - Sem Composer, sem npm, sem frameworks
2. **Front Controller** - Tudo passa por index.php via .htaccess
3. **Auto-instalacao** - install.php + Database auto-migration
4. **Comunidade anonima** - anonymous_name separado do nome real
5. **Checkout flexivel** - Funciona para logados e visitantes (captura email)
6. **Idioma unico** - Tudo em portugues (pt-BR), sem i18n
7. **Tema dark luxo** - Preto + dourado, fontes premium
8. **Controllers diretos** - Sem service layer, queries diretas no controller
9. **Moeda BRL** - Precos em Real brasileiro, formato `number_format($p, 2, ',', '.')`
10. **Deploy Hostinger** - Upload ZIP + install.php, sem CI/CD

---

## Comandos Uteis

```bash
# Nao ha build step - PHP puro servido diretamente
# Nao ha testes automatizados
# Nao ha migrations CLI - schema.sql roda automaticamente

# Deploy: upload via Hostinger File Manager → install.php → deletar install.php
```

---

## Hierarquia de Conteudo

```
Produto (Course)
  └── Modulo (Section)
       └── Aula (Lesson)
            ├── video (iframe embed)
            ├── text (HTML body)
            ├── pdf (link download)
            └── audio (suportado no schema)
```

Progresso calculado: `aulas_completadas / total_aulas * 100`
