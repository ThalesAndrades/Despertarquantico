<?php
$pageTitle = 'Mulher Espiral - Despertar Quantico';
$checkoutUrl = ($product ?? null) ? url('checkout/' . $product['slug']) : url('register');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - Sunyan Nunes</title>
    <meta name="description" content="Descubra o poder da sua essencia feminina com o metodo Mulher Espiral de Sunyan Nunes.">
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
        <a href="<?= url('') ?>" class="nav-logo">MULHER ESPIRAL</a>
        <div class="nav-links">
            <a href="#metodo">O Metodo</a>
            <a href="#sobre">Sobre</a>
            <a href="#depoimentos">Depoimentos</a>
            <a href="#faq">FAQ</a>
            <a href="<?= url('login') ?>" class="btn btn-sm btn-outline">Area de Membros</a>
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
        <span class="hero-badge">Metodo exclusivo de Sunyan Nunes</span>
        <h1 class="hero-title">
            Desperte a <em>Mulher Espiral</em><br>que existe em voce
        </h1>
        <p class="hero-subtitle">
            Um mergulho profundo no autoconhecimento feminino, na reconexao com sua essencia
            e no despertar quantico da sua energia mais poderosa.
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
<section class="section section-pain fade-in" id="problema">
    <div class="container container-sm text-center">
        <div class="gold-line"></div>
        <h2 class="section-title">Voce sente que algo esta faltando?</h2>
        <div class="pain-grid">
            <div class="pain-card">
                <p>Vive no automatico, desconectada de si mesma, repetindo padroes que nao fazem mais sentido</p>
            </div>
            <div class="pain-card">
                <p>Sente uma inquietacao interna, como se houvesse algo maior esperando por voce</p>
            </div>
            <div class="pain-card">
                <p>Busca transformacao, mas os metodos tradicionais nao tocam na raiz do que precisa mudar</p>
            </div>
            <div class="pain-card">
                <p>Quer se sentir plena, poderosa e conectada com a sua verdadeira essencia feminina</p>
            </div>
        </div>
        <p class="pain-conclusion">
            Se voce se identificou, saiba que <strong>existe um caminho</strong>. E ele comeca com o despertar da sua energia espiral.
        </p>
    </div>
</section>

<!-- ========== 3. SOLUCAO ========== -->
<section class="section section-solution fade-in" id="metodo">
    <div class="container">
        <div class="solution-grid">
            <div class="solution-text">
                <span class="badge badge-gold">O Metodo</span>
                <h2 class="section-title text-left">Mulher Espiral:<br>O Despertar Quantico</h2>
                <div class="gold-line-left"></div>
                <p>O Mulher Espiral e um programa de transformacao profunda que combina <strong>sabedoria ancestral feminina</strong> com tecnicas de <strong>reprogramacao quantica</strong>.</p>
                <p>Atraves de modulos cuidadosamente estruturados, voce vai:</p>
                <ul class="solution-list">
                    <li>Identificar e dissolver padroes limitantes que bloqueiam sua energia</li>
                    <li>Reconectar-se com o poder ciclico do seu corpo e da sua intuicao</li>
                    <li>Ativar sua energia espiral — a forca criativa e transformadora feminina</li>
                    <li>Criar uma nova realidade alinhada com quem voce realmente e</li>
                </ul>
                <a href="<?= e($checkoutUrl) ?>" class="btn btn-primary btn-lg mt-3">Quero Comecar Minha Transformacao</a>
            </div>
            <div class="solution-visual">
                <div class="spiral-visual">
                    <div class="spiral-ring ring-1"></div>
                    <div class="spiral-ring ring-2"></div>
                    <div class="spiral-ring ring-3"></div>
                    <div class="spiral-center">&#10022;</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== 4. SOBRE SUNYAN ========== -->
<section class="section section-about fade-in" id="sobre">
    <div class="container">
        <div class="about-grid">
            <div class="about-photo">
                <div class="about-photo-placeholder">
                    <span>Sunyan Nunes</span>
                </div>
            </div>
            <div class="about-text">
                <span class="badge badge-gold">Sua Mentora</span>
                <h2 class="section-title text-left">Sunyan Nunes</h2>
                <div class="gold-line-left"></div>
                <p>Ha mais de uma decada, Sunyan dedica sua vida ao estudo profundo da energia feminina, da espiritualidade e dos processos de transformacao pessoal.</p>
                <p>Ja ajudou <strong>milhares de mulheres</strong> a despertarem para uma vida mais plena, autentica e conectada com seu proposito. Seu metodo exclusivo, o Mulher Espiral, e resultado de anos de pesquisa, pratica e vivencia.</p>
                <p><em>"Eu acredito que toda mulher carrega em si uma forca espiral capaz de transformar nao so a propria vida, mas o mundo ao redor."</em></p>
                <p class="about-signature">— Sunyan Nunes</p>
            </div>
        </div>
    </div>
</section>

<!-- ========== 5. O QUE VOCE VAI APRENDER ========== -->
<section class="section section-modules fade-in" id="modulos">
    <div class="container">
        <div class="text-center mb-4">
            <div class="gold-line"></div>
            <span class="badge badge-gold">Conteudo do Programa</span>
            <h2 class="section-title" style="margin-top:16px;">O que voce vai vivenciar</h2>
        </div>
        <div class="modules-grid">
            <div class="module-card">
                <div class="module-number">01</div>
                <h3>Despertar da Consciencia</h3>
                <p>Entenda os padroes inconscientes que regem sua vida e inicie o processo de libertacao.</p>
            </div>
            <div class="module-card">
                <div class="module-number">02</div>
                <h3>Reconexao com a Essencia</h3>
                <p>Praticas de reconexao com seu corpo, seus ciclos e sua sabedoria interior feminina.</p>
            </div>
            <div class="module-card">
                <div class="module-number">03</div>
                <h3>Ativacao da Energia Espiral</h3>
                <p>Tecnicas exclusivas para ativar e canalizar sua energia criativa e transformadora.</p>
            </div>
            <div class="module-card">
                <div class="module-number">04</div>
                <h3>Reprogramacao Quantica</h3>
                <p>Reprograme crencas limitantes e alinhe sua frequencia com a realidade que deseja criar.</p>
            </div>
            <div class="module-card">
                <div class="module-number">05</div>
                <h3>Manifestacao Consciente</h3>
                <p>Aprenda a manifestar seus desejos a partir de um lugar de autenticidade e poder interior.</p>
            </div>
            <div class="module-card">
                <div class="module-number">06</div>
                <h3>Integracao e Novo Ciclo</h3>
                <p>Integre toda a transformacao vivida e trace seu novo caminho com clareza e proposito.</p>
            </div>
        </div>
    </div>
</section>

<!-- ========== 6. DEPOIMENTOS ========== -->
<section class="section section-testimonials fade-in" id="depoimentos">
    <div class="container">
        <div class="text-center mb-4">
            <div class="gold-line"></div>
            <span class="badge badge-gold">Transformacoes Reais</span>
            <h2 class="section-title" style="margin-top:16px;">O que dizem as mulheres que ja despertaram</h2>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                <p>"Eu achava que ja tinha tentado de tudo. O Mulher Espiral me mostrou que eu nunca tinha olhado para dentro de verdade. Minha vida mudou completamente."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">M</div>
                    <div>
                        <strong>Marina L.</strong>
                        <span>Sao Paulo, SP</span>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                <p>"A comunidade e um acolhimento que nunca vivi em nenhum outro lugar. Poder ser eu mesma, sem julgamento, foi libertador."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">C</div>
                    <div>
                        <strong>Camila R.</strong>
                        <span>Belo Horizonte, MG</span>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                <p>"Sunyan tem um dom. Cada modulo e uma jornada. Sai do programa me sentindo outra mulher — mais inteira, mais forte, mais eu."</p>
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

<!-- ========== 7. BONUS ========== -->
<section class="section section-bonus fade-in" id="bonus">
    <div class="container">
        <div class="text-center mb-4">
            <div class="gold-line"></div>
            <span class="badge badge-gold">Bonus Exclusivos</span>
            <h2 class="section-title" style="margin-top:16px;">Voce tambem vai receber</h2>
        </div>
        <div class="bonus-grid">
            <div class="bonus-card">
                <div class="bonus-icon">&#10022;</div>
                <h3>Comunidade Mulher Espiral</h3>
                <p>Acesso a nossa comunidade privada e anonima de mulheres em transformacao. Troque experiencias, receba apoio e cresca junto.</p>
                <span class="bonus-value">Valor: Inestimavel</span>
            </div>
            <div class="bonus-card">
                <div class="bonus-icon">&#10023;</div>
                <h3>Diario de Jornada</h3>
                <p>Material em PDF para voce registrar suas reflexoes, insights e transformacoes ao longo de todo o programa.</p>
                <span class="bonus-value">Valor: R$ 97</span>
            </div>
            <div class="bonus-card">
                <div class="bonus-icon">&#10025;</div>
                <h3>Meditacoes Guiadas</h3>
                <p>Audios exclusivos de meditacoes guiadas por Sunyan para cada fase da sua jornada de despertar.</p>
                <span class="bonus-value">Valor: R$ 147</span>
            </div>
        </div>
    </div>
</section>

<!-- ========== 8. GARANTIA ========== -->
<section class="section section-guarantee fade-in" id="garantia">
    <div class="container container-sm text-center">
        <div class="guarantee-badge">&#9878;</div>
        <h2 class="section-title">Garantia Incondicional de 7 Dias</h2>
        <p class="guarantee-text">
            Sua satisfacao e a nossa prioridade. Se dentro de <strong>7 dias</strong> voce sentir que o programa nao e para voce,
            devolvemos <strong>100% do seu investimento</strong>. Sem perguntas, sem burocracia.
            Voce nao tem absolutamente nada a perder.
        </p>
    </div>
</section>

<!-- ========== 9. FAQ ========== -->
<section class="section section-faq fade-in" id="faq">
    <div class="container container-sm">
        <div class="text-center mb-4">
            <div class="gold-line"></div>
            <h2 class="section-title">Perguntas Frequentes</h2>
        </div>
        <div class="faq-list">
            <div class="faq-item">
                <button class="faq-question">
                    Por quanto tempo terei acesso ao conteudo?
                    <span class="faq-arrow">&#9660;</span>
                </button>
                <div class="faq-answer">
                    <p>Voce tera acesso vitalicio a todo o conteudo do programa, incluindo atualizacoes futuras.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">
                    Preciso de algum conhecimento previo?
                    <span class="faq-arrow">&#9660;</span>
                </button>
                <div class="faq-answer">
                    <p>Nao! O programa foi desenhado para mulheres de todos os niveis, desde iniciantes ate aquelas que ja trilham um caminho de autoconhecimento.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">
                    Como funciona a comunidade?
                    <span class="faq-arrow">&#9660;</span>
                </button>
                <div class="faq-answer">
                    <p>A comunidade e um espaco seguro e anonimo dentro da plataforma. Voce escolhe um pseudonimo e pode compartilhar experiencias, fazer perguntas e trocar com outras mulheres sem expor sua identidade.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">
                    Quais as formas de pagamento?
                    <span class="faq-arrow">&#9660;</span>
                </button>
                <div class="faq-answer">
                    <p>Aceitamos cartao de credito (todas as bandeiras) atraves do Stripe, com total seguranca. Pagamento processado instantaneamente.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">
                    Posso acessar pelo celular?
                    <span class="faq-arrow">&#9660;</span>
                </button>
                <div class="faq-answer">
                    <p>Sim! A plataforma e 100% responsiva. Voce pode acessar de qualquer dispositivo — celular, tablet ou computador.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">
                    E se eu nao gostar?
                    <span class="faq-arrow">&#9660;</span>
                </button>
                <div class="faq-answer">
                    <p>Voce tem 7 dias de garantia incondicional. Se por qualquer motivo sentir que o programa nao e para voce, devolvemos 100% do valor investido.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== 10. CTA FINAL ========== -->
<section class="section section-final-cta fade-in" id="comprar">
    <div class="container container-sm text-center">
        <h2 class="cta-title">Sua transformacao comeca com uma decisao</h2>
        <p class="cta-subtitle">
            Nao espere o "momento certo". O momento e agora. Cada dia que passa e um dia a menos vivendo no seu potencial maximo.
        </p>
        <div class="cta-price-box">
            <span class="cta-price-from">De <s>R$ 997</s></span>
            <div class="cta-price">
                <span class="cta-price-currency">R$</span>
                <span class="cta-price-value"><?= ($product ?? null) ? number_format($product['price'], 0, ',', '.') : '497' ?></span>
            </div>
            <span class="cta-price-note">Pagamento unico &#183; Acesso vitalicio</span>
            <a href="<?= e($checkoutUrl) ?>" class="btn btn-gold btn-lg btn-block mt-3">
                Quero Despertar Agora
            </a>
            <p class="cta-guarantee-note">&#9878; 7 dias de garantia incondicional</p>
        </div>
        <div class="cta-includes">
            <p><strong>Tudo que voce recebe:</strong></p>
            <ul>
                <li>&#10022; Programa completo Mulher Espiral (6 modulos)</li>
                <li>&#10022; Acesso a Comunidade exclusiva e anonima</li>
                <li>&#10022; Bonus: Diario de Jornada + Meditacoes Guiadas</li>
                <li>&#10022; Acesso vitalicio + Atualizacoes futuras</li>
                <li>&#10022; Garantia incondicional de 7 dias</li>
            </ul>
        </div>
    </div>
</section>

<!-- ========== FOOTER ========== -->
<footer class="landing-footer">
    <div class="container text-center">
        <p class="footer-brand">MULHER ESPIRAL</p>
        <p class="footer-links">
            <a href="<?= url('login') ?>">Area de Membros</a>
            <span>&#183;</span>
            <a href="mailto:contato@mulherespiral.shop">Contato</a>
        </p>
        <p class="footer-copy">&copy; <?= date('Y') ?> Sunyan Nunes. Todos os direitos reservados.</p>
        <p class="footer-disclaimer">
            Este produto nao garante a obtencao de resultados. Qualquer referencia ao desempenho de uma estrategia nao deve ser interpretada como uma garantia de resultados.
        </p>
    </div>
</footer>

<script src="<?= asset('js/landing.js') ?>"></script>
</body>
</html>
