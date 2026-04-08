<?php
$pageTitle = 'Mulher Espiral - Despertar Quântico';
$checkoutUrl = ($product ?? null) ? url('checkout/' . $product['slug']) : url('register');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - Sunyan Nunes</title>
    <meta name="description" content="Descubra o poder da sua essência feminina com o método Mulher Espiral de Sunyan Nunes.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/landing.css') ?>">
</head>
<body>

<!-- ========== NAV ========== -->
<nav class="landing-nav" id="nav">
    <div class="container flex-between">
        <a href="<?= url('') ?>" class="nav-logo">✦ Sunyan Nunes</a>
        <div class="nav-links">
            <a href="#metodo">O Método</a>
            <a href="#sobre">Sobre</a>
            <a href="#depoimentos">Depoimentos</a>
            <a href="#faq">FAQ</a>
            <a href="<?= url('login') ?>" class="btn btn-sm btn-outline">Área de Membros</a>
        </div>
        <button class="nav-toggle" id="navToggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

<!-- ========== 1. HERO ========== -->
<section class="hero" id="hero">
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <span class="hero-badge">Método exclusivo de Sunyan Nunes</span>
        <h1 class="hero-title">
            Desperte a <em>Mulher Espiral</em><br>que existe em você
        </h1>
        <p class="hero-subtitle">
            Um mergulho profundo no autoconhecimento feminino, na reconexão com sua essência
            e no despertar quântico da sua energia mais poderosa.
        </p>
        <div class="hero-cta">
            <a href="<?= e($checkoutUrl) ?>" class="btn btn-gold btn-lg">Quero Despertar Agora</a>
            <p class="hero-cta-note">Acesso imediato + Comunidade exclusiva</p>
        </div>
    </div>
    <div class="hero-scroll">
        <span>Descubra mais</span>
        <div class="scroll-arrow"></div>
    </div>
</section>

<!-- ========== 2. PROBLEMA / DOR ========== -->
<section class="section section-pain" id="problema">
    <div class="container container-sm text-center">
        <h2 class="section-title">Você sente que algo está faltando?</h2>
        <div class="pain-grid">
            <div class="pain-card">
                <div class="pain-icon">◇</div>
                <p>Vive no automático, desconectada de si mesma, repetindo padrões que não fazem mais sentido</p>
            </div>
            <div class="pain-card">
                <div class="pain-icon">◇</div>
                <p>Sente uma inquietação interna, como se houvesse algo maior esperando por você</p>
            </div>
            <div class="pain-card">
                <div class="pain-icon">◇</div>
                <p>Busca transformação, mas os métodos tradicionais não tocam na raiz do que precisa mudar</p>
            </div>
            <div class="pain-card">
                <div class="pain-icon">◇</div>
                <p>Quer se sentir plena, poderosa e conectada com a sua verdadeira essência feminina</p>
            </div>
        </div>
        <p class="pain-conclusion">
            Se você se identificou, saiba que <strong>existe um caminho</strong>. E ele começa com o despertar da sua energia espiral.
        </p>
    </div>
</section>

<!-- ========== 3. SOLUÇÃO ========== -->
<section class="section section-solution" id="metodo">
    <div class="container">
        <div class="solution-grid">
            <div class="solution-text">
                <span class="badge badge-gold">O Método</span>
                <h2 class="section-title text-left">Mulher Espiral:<br>O Despertar Quântico</h2>
                <p>O Mulher Espiral é um programa de transformação profunda que combina <strong>sabedoria ancestral feminina</strong> com técnicas de <strong>reprogramação quântica</strong>.</p>
                <p>Através de módulos cuidadosamente estruturados, você vai:</p>
                <ul class="solution-list">
                    <li>Identificar e dissolver padrões limitantes que bloqueiam sua energia</li>
                    <li>Reconectar-se com o poder cíclico do seu corpo e da sua intuição</li>
                    <li>Ativar sua energia espiral — a força criativa e transformadora feminina</li>
                    <li>Criar uma nova realidade alinhada com quem você realmente é</li>
                </ul>
                <a href="<?= e($checkoutUrl) ?>" class="btn btn-primary btn-lg mt-3">Quero Começar Minha Transformação</a>
            </div>
            <div class="solution-visual">
                <div class="spiral-visual">
                    <div class="spiral-ring ring-1"></div>
                    <div class="spiral-ring ring-2"></div>
                    <div class="spiral-ring ring-3"></div>
                    <div class="spiral-center">✦</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== 4. SOBRE SUNYAN ========== -->
<section class="section section-about" id="sobre">
    <div class="container">
        <div class="about-grid">
            <div class="about-photo">
                <div class="about-photo-placeholder">
                    <span>Sunyan Nunes</span>
                </div>
            </div>
            <div class="about-text">
                <span class="badge badge-purple">Sua Mentora</span>
                <h2 class="section-title text-left">Sunyan Nunes</h2>
                <p>Há mais de uma década, Sunyan dedica sua vida ao estudo profundo da energia feminina, da espiritualidade e dos processos de transformação pessoal.</p>
                <p>Já ajudou <strong>milhares de mulheres</strong> a despertarem para uma vida mais plena, autêntica e conectada com seu propósito. Seu método exclusivo, o Mulher Espiral, é resultado de anos de pesquisa, prática e vivência.</p>
                <p><em>"Eu acredito que toda mulher carrega em si uma força espiral capaz de transformar não só a própria vida, mas o mundo ao redor."</em></p>
                <p class="about-signature">— Sunyan Nunes</p>
            </div>
        </div>
    </div>
</section>

<!-- ========== 5. O QUE VOCÊ VAI APRENDER ========== -->
<section class="section section-modules" id="modulos">
    <div class="container">
        <div class="text-center mb-4">
            <span class="badge badge-gold">Conteúdo do Programa</span>
            <h2 class="section-title">O que você vai vivenciar</h2>
        </div>
        <div class="modules-grid">
            <div class="module-card">
                <div class="module-number">01</div>
                <h3>Despertar da Consciência</h3>
                <p>Entenda os padrões inconscientes que regem sua vida e inicie o processo de libertação.</p>
            </div>
            <div class="module-card">
                <div class="module-number">02</div>
                <h3>Reconexão com a Essência</h3>
                <p>Práticas de reconexão com seu corpo, seus ciclos e sua sabedoria interior feminina.</p>
            </div>
            <div class="module-card">
                <div class="module-number">03</div>
                <h3>Ativação da Energia Espiral</h3>
                <p>Técnicas exclusivas para ativar e canalizar sua energia criativa e transformadora.</p>
            </div>
            <div class="module-card">
                <div class="module-number">04</div>
                <h3>Reprogramação Quântica</h3>
                <p>Reprograme crenças limitantes e alinhe sua frequência com a realidade que deseja criar.</p>
            </div>
            <div class="module-card">
                <div class="module-number">05</div>
                <h3>Manifestação Consciente</h3>
                <p>Aprenda a manifestar seus desejos a partir de um lugar de autenticidade e poder interior.</p>
            </div>
            <div class="module-card">
                <div class="module-number">06</div>
                <h3>Integração e Novo Ciclo</h3>
                <p>Integre toda a transformação vivida e trace seu novo caminho com clareza e propósito.</p>
            </div>
        </div>
    </div>
</section>

<!-- ========== 6. DEPOIMENTOS ========== -->
<section class="section section-testimonials" id="depoimentos">
    <div class="container">
        <div class="text-center mb-4">
            <span class="badge badge-purple">Transformações Reais</span>
            <h2 class="section-title">O que dizem as mulheres que já despertaram</h2>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-stars">★★★★★</div>
                <p>"Eu achava que já tinha tentado de tudo. O Mulher Espiral me mostrou que eu nunca tinha olhado para dentro de verdade. Minha vida mudou completamente."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">M</div>
                    <div>
                        <strong>Marina L.</strong>
                        <span>São Paulo, SP</span>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-stars">★★★★★</div>
                <p>"A comunidade é um acolhimento que nunca vivi em nenhum outro lugar. Poder ser eu mesma, sem julgamento, foi libertador."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">C</div>
                    <div>
                        <strong>Camila R.</strong>
                        <span>Belo Horizonte, MG</span>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-stars">★★★★★</div>
                <p>"Sunyan tem um dom. Cada módulo é uma jornada. Saí do programa me sentindo outra mulher — mais inteira, mais forte, mais eu."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">A</div>
                    <div>
                        <strong>Ana P.</strong>
                        <span>Curitiba, PR</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== 7. BÔNUS ========== -->
<section class="section section-bonus" id="bonus">
    <div class="container">
        <div class="text-center mb-4">
            <span class="badge badge-gold">Bônus Exclusivos</span>
            <h2 class="section-title">Você também vai receber</h2>
        </div>
        <div class="bonus-grid">
            <div class="bonus-card">
                <div class="bonus-icon">👥</div>
                <h3>Comunidade Mulher Espiral</h3>
                <p>Acesso à nossa comunidade privada e anônima de mulheres em transformação. Troque experiências, receba apoio e cresça junto.</p>
                <span class="bonus-value">Valor: Inestimável</span>
            </div>
            <div class="bonus-card">
                <div class="bonus-icon">📖</div>
                <h3>Diário de Jornada</h3>
                <p>Material em PDF para você registrar suas reflexões, insights e transformações ao longo de todo o programa.</p>
                <span class="bonus-value">Valor: R$ 97</span>
            </div>
            <div class="bonus-card">
                <div class="bonus-icon">🎧</div>
                <h3>Meditações Guiadas</h3>
                <p>Áudios exclusivos de meditações guiadas por Sunyan para cada fase da sua jornada de despertar.</p>
                <span class="bonus-value">Valor: R$ 147</span>
            </div>
        </div>
    </div>
</section>

<!-- ========== 8. GARANTIA ========== -->
<section class="section section-guarantee" id="garantia">
    <div class="container container-sm text-center">
        <div class="guarantee-badge">🛡️</div>
        <h2 class="section-title">Garantia Incondicional de 7 Dias</h2>
        <p class="guarantee-text">
            Sua satisfação é a nossa prioridade. Se dentro de <strong>7 dias</strong> você sentir que o programa não é para você,
            devolvemos <strong>100% do seu investimento</strong>. Sem perguntas, sem burocracia.
            Você não tem absolutamente nada a perder.
        </p>
    </div>
</section>

<!-- ========== 9. FAQ ========== -->
<section class="section section-faq" id="faq">
    <div class="container container-sm">
        <div class="text-center mb-4">
            <h2 class="section-title">Perguntas Frequentes</h2>
        </div>
        <div class="faq-list">
            <div class="faq-item">
                <button class="faq-question">
                    Por quanto tempo terei acesso ao conteúdo?
                    <span class="faq-arrow">▼</span>
                </button>
                <div class="faq-answer">
                    <p>Você terá acesso vitalício a todo o conteúdo do programa, incluindo atualizações futuras.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">
                    Preciso de algum conhecimento prévio?
                    <span class="faq-arrow">▼</span>
                </button>
                <div class="faq-answer">
                    <p>Não! O programa foi desenhado para mulheres de todos os níveis, desde iniciantes até aquelas que já trilham um caminho de autoconhecimento.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">
                    Como funciona a comunidade?
                    <span class="faq-arrow">▼</span>
                </button>
                <div class="faq-answer">
                    <p>A comunidade é um espaço seguro e anônimo dentro da plataforma. Você escolhe um pseudônimo e pode compartilhar experiências, fazer perguntas e trocar com outras mulheres sem expor sua identidade.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">
                    Quais as formas de pagamento?
                    <span class="faq-arrow">▼</span>
                </button>
                <div class="faq-answer">
                    <p>Aceitamos cartão de crédito (todas as bandeiras) através do Stripe, com total segurança. Pagamento processado instantaneamente.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">
                    Posso acessar pelo celular?
                    <span class="faq-arrow">▼</span>
                </button>
                <div class="faq-answer">
                    <p>Sim! A plataforma é 100% responsiva. Você pode acessar de qualquer dispositivo — celular, tablet ou computador.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">
                    E se eu não gostar?
                    <span class="faq-arrow">▼</span>
                </button>
                <div class="faq-answer">
                    <p>Você tem 7 dias de garantia incondicional. Se por qualquer motivo sentir que o programa não é para você, devolvemos 100% do valor investido.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== 10. CTA FINAL ========== -->
<section class="section section-final-cta" id="comprar">
    <div class="container container-sm text-center">
        <h2 class="cta-title">Sua transformação começa com uma decisão</h2>
        <p class="cta-subtitle">
            Não espere o "momento certo". O momento é agora. Cada dia que passa é um dia a menos vivendo no seu potencial máximo.
        </p>
        <div class="cta-price-box">
            <span class="cta-price-from">De <s>R$ 997</s></span>
            <div class="cta-price">
                <span class="cta-price-currency">R$</span>
                <span class="cta-price-value"><?= ($product ?? null) ? number_format($product['price'], 0, ',', '.') : '497' ?></span>
            </div>
            <span class="cta-price-note">Pagamento único • Acesso vitalício</span>
            <a href="<?= e($checkoutUrl) ?>" class="btn btn-gold btn-lg btn-block mt-3">
                Quero Despertar Agora
            </a>
            <p class="cta-guarantee-note">🛡️ 7 dias de garantia incondicional</p>
        </div>
        <div class="cta-includes">
            <p><strong>Tudo que você recebe:</strong></p>
            <ul>
                <li>✦ Programa completo Mulher Espiral (6 módulos)</li>
                <li>✦ Acesso à Comunidade exclusiva e anônima</li>
                <li>✦ Bônus: Diário de Jornada + Meditações Guiadas</li>
                <li>✦ Acesso vitalício + Atualizações futuras</li>
                <li>✦ Garantia incondicional de 7 dias</li>
            </ul>
        </div>
    </div>
</section>

<!-- ========== FOOTER ========== -->
<footer class="landing-footer">
    <div class="container text-center">
        <p class="footer-brand">✦ Sunyan Nunes</p>
        <p class="footer-links">
            <a href="<?= url('login') ?>">Área de Membros</a>
            <span>•</span>
            <a href="mailto:contato@mulherespiral.shop">Contato</a>
        </p>
        <p class="footer-copy">&copy; <?= date('Y') ?> Sunyan Nunes. Todos os direitos reservados.</p>
        <p class="footer-disclaimer">
            Este produto não garante a obtenção de resultados. Qualquer referência ao desempenho de uma estratégia não deve ser interpretada como uma garantia de resultados.
        </p>
    </div>
</footer>

<script src="<?= asset('js/landing.js') ?>"></script>
</body>
</html>
