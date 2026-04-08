<?php
$pageTitle = 'Mulher Espiral - Despertar Quantico';
$checkoutUrl = ($product ?? null) ? url('checkout/' . $product['slug']) : url('register');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | Sunyan Nunes</title>
    <meta name="description" content="Descubra o poder da sua essencia feminina com o metodo Mulher Espiral de Sunyan Nunes. Programa completo de transformacao e despertar quantico.">
    <meta name="theme-color" content="#0A0A0A">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/landing.css') ?>">
</head>
<body>

<!-- ========== NAV ========== -->
<nav class="landing-nav" id="nav">
    <div class="container flex-between">
        <a href="<?= url('') ?>" class="nav-logo">MULHER ESPIRAL</a>
        <div class="nav-links" id="navLinks">
            <a href="#metodo">O Metodo</a>
            <a href="#modulos">Conteudo</a>
            <a href="#sobre">Mentora</a>
            <a href="#depoimentos">Depoimentos</a>
            <a href="#faq">FAQ</a>
            <a href="<?= url('login') ?>" class="nav-cta-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Area de Membros
            </a>
        </div>
        <button class="nav-toggle" id="navToggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

<!-- ========== HERO ========== -->
<section class="hero" id="hero">
    <div class="hero-bg-effects">
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>
        <div class="hero-orb hero-orb-3"></div>
    </div>
    <div class="container hero-content">
        <div class="hero-badge-wrap">
            <span class="hero-badge">
                <span class="hero-badge-dot"></span>
                Vagas limitadas - Turma 2025
            </span>
        </div>
        <h1 class="hero-title">
            Desperte a <em>Mulher Espiral</em><br>que existe em voce
        </h1>
        <p class="hero-subtitle">
            O programa de transformacao feminina mais completo do Brasil.
            Reconecte-se com sua essencia, ative seu poder interior e
            crie a realidade que voce merece viver.
        </p>
        <div class="hero-cta-group">
            <a href="<?= e($checkoutUrl) ?>" class="btn btn-gold btn-lg hero-cta-main">
                Quero Comecar Minha Transformacao
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
            <span class="hero-cta-sub">Acesso imediato &#183; Garantia de 7 dias &#183; Pagamento seguro</span>
        </div>
        <!-- Social proof strip -->
        <div class="hero-proof">
            <div class="hero-proof-item">
                <span class="hero-proof-number">2.500+</span>
                <span class="hero-proof-label">Alunas transformadas</span>
            </div>
            <div class="hero-proof-divider"></div>
            <div class="hero-proof-item">
                <span class="hero-proof-number">10+</span>
                <span class="hero-proof-label">Modulos completos</span>
            </div>
            <div class="hero-proof-divider"></div>
            <div class="hero-proof-item">
                <span class="hero-proof-number">4.9</span>
                <span class="hero-proof-label">Avaliacao media</span>
            </div>
        </div>
    </div>
    <div class="hero-scroll">
        <span>Descubra mais</span>
        <div class="scroll-arrow"></div>
    </div>
</section>

<!-- ========== TRUST BAR ========== -->
<div class="trust-bar">
    <div class="container">
        <div class="trust-items">
            <div class="trust-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <span>Pagamento 100% Seguro</span>
            </div>
            <div class="trust-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>Garantia de 7 Dias</span>
            </div>
            <div class="trust-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>Acesso Imediato</span>
            </div>
            <div class="trust-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>Acesso Vitalicio</span>
            </div>
        </div>
    </div>
</div>

<!-- ========== PAIN SECTION ========== -->
<section class="section section-pain" id="problema">
    <div class="container container-sm">
        <div class="section-label text-center">IDENTIFICACAO</div>
        <h2 class="section-title text-center">Voce se identifica com alguma dessas situacoes?</h2>
        <p class="section-desc text-center">Milhares de mulheres passam por isso todos os dias. A boa noticia e que existe um caminho de saida.</p>
        <div class="pain-grid">
            <div class="pain-card">
                <div class="pain-icon-wrap">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M8 15h8"/><path d="M9 9h.01"/><path d="M15 9h.01"/></svg>
                </div>
                <p>Vive no automatico, desconectada de si mesma, repetindo padroes que nao fazem mais sentido</p>
            </div>
            <div class="pain-card">
                <div class="pain-icon-wrap">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                </div>
                <p>Sente uma inquietacao interna, como se houvesse algo maior esperando por voce</p>
            </div>
            <div class="pain-card">
                <div class="pain-icon-wrap">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <p>Busca transformacao, mas os metodos tradicionais nao tocam na raiz do que precisa mudar</p>
            </div>
            <div class="pain-card">
                <div class="pain-icon-wrap">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                <p>Quer se sentir plena, poderosa e conectada com a sua verdadeira essencia feminina</p>
            </div>
        </div>
        <div class="pain-conclusion text-center">
            <p>Se voce se identificou com pelo menos uma dessas situacoes, o <strong>Mulher Espiral</strong> foi criado para voce.</p>
        </div>
    </div>
</section>

<!-- ========== METHOD ========== -->
<section class="section section-method" id="metodo">
    <div class="container">
        <div class="method-grid">
            <div class="method-text">
                <div class="section-label">O METODO</div>
                <h2 class="section-title text-left">Mulher Espiral: O Despertar Quantico</h2>
                <div class="gold-line-left"></div>
                <p class="method-desc">O Mulher Espiral e um programa de transformacao profunda que combina <strong>sabedoria ancestral feminina</strong> com tecnicas de <strong>reprogramacao quantica</strong>, desenvolvido ao longo de mais de 10 anos de pesquisa e pratica.</p>
                <div class="method-features">
                    <div class="method-feature">
                        <div class="method-feature-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <span>Identificar e dissolver padroes limitantes que bloqueiam sua energia</span>
                    </div>
                    <div class="method-feature">
                        <div class="method-feature-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <span>Reconectar-se com o poder ciclico do seu corpo e da sua intuicao</span>
                    </div>
                    <div class="method-feature">
                        <div class="method-feature-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <span>Ativar sua energia espiral &#8212; a forca criativa e transformadora</span>
                    </div>
                    <div class="method-feature">
                        <div class="method-feature-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <span>Criar uma nova realidade alinhada com quem voce realmente e</span>
                    </div>
                </div>
                <a href="<?= e($checkoutUrl) ?>" class="btn btn-gold btn-lg mt-3">Quero Minha Transformacao</a>
            </div>
            <div class="method-visual">
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

<!-- ========== ABOUT SUNYAN ========== -->
<section class="section section-about" id="sobre">
    <div class="container">
        <div class="about-grid">
            <div class="about-photo">
                <div class="about-photo-placeholder">
                    <span>SN</span>
                </div>
                <div class="about-credentials">
                    <div class="about-credential">+10 anos de experiencia</div>
                    <div class="about-credential">+2.500 alunas</div>
                    <div class="about-credential">Especialista em energia feminina</div>
                </div>
            </div>
            <div class="about-text">
                <div class="section-label">SUA MENTORA</div>
                <h2 class="section-title text-left">Sunyan Nunes</h2>
                <div class="gold-line-left"></div>
                <p>Ha mais de uma decada, Sunyan dedica sua vida ao estudo profundo da energia feminina, da espiritualidade e dos processos de transformacao pessoal.</p>
                <p>Ja ajudou <strong>milhares de mulheres</strong> a despertarem para uma vida mais plena, autentica e conectada com seu proposito. Seu metodo exclusivo, o Mulher Espiral, e resultado de anos de pesquisa, pratica e vivencia real com mulheres de todo o Brasil.</p>
                <blockquote class="about-quote">
                    "Eu acredito que toda mulher carrega em si uma forca espiral capaz de transformar nao so a propria vida, mas o mundo ao redor."
                </blockquote>
                <p class="about-signature">&#8212; Sunyan Nunes</p>
            </div>
        </div>
    </div>
</section>

<!-- ========== MODULES ========== -->
<section class="section section-modules" id="modulos">
    <div class="container">
        <div class="text-center">
            <div class="section-label">CONTEUDO COMPLETO</div>
            <h2 class="section-title">Tudo que voce vai vivenciar</h2>
            <p class="section-desc">Um programa cuidadosamente estruturado em modulos progressivos para sua jornada de transformacao.</p>
        </div>
        <div class="modules-grid">
            <div class="module-card">
                <div class="module-number">01</div>
                <h3>Despertar da Consciencia</h3>
                <p>Entenda os padroes inconscientes que regem sua vida e inicie o processo de libertacao interior.</p>
            </div>
            <div class="module-card">
                <div class="module-number">02</div>
                <h3>Reconexao com a Essencia</h3>
                <p>Praticas de reconexao com seu corpo, seus ciclos naturais e sua sabedoria interior feminina.</p>
            </div>
            <div class="module-card">
                <div class="module-number">03</div>
                <h3>Masculino e Feminino</h3>
                <p>Compreenda e equilibre as energias masculina e feminina que coexistem dentro de voce.</p>
            </div>
            <div class="module-card">
                <div class="module-number">04</div>
                <h3>Natureza dos Resultados</h3>
                <p>Descubra como sua energia interna se manifesta em resultados concretos na sua vida exterior.</p>
            </div>
            <div class="module-card">
                <div class="module-number">05</div>
                <h3>Padrao de Personalidade</h3>
                <p>Identifique padroes de personalidade e aprenda a transmuta-los para seu crescimento.</p>
            </div>
            <div class="module-card">
                <div class="module-number">06</div>
                <h3>Doencas Fisicas e Emocionais</h3>
                <p>Entenda a conexao mente-corpo e como emocoes nao resolvidas se manifestam fisicamente.</p>
            </div>
            <div class="module-card">
                <div class="module-number">07</div>
                <h3>Sentimentos que Aprisionam</h3>
                <p>Liberte-se de sentimentos como culpa, medo e raiva que aprisionam seu potencial.</p>
            </div>
            <div class="module-card">
                <div class="module-number">08</div>
                <h3>Neutralizacao das Emocoes</h3>
                <p>Tecnicas poderosas para neutralizar emocoes toxicas e restaurar seu equilibrio interno.</p>
            </div>
            <div class="module-card">
                <div class="module-number">09</div>
                <h3>Mapeando a Cura</h3>
                <p>Trace seu mapa pessoal de cura e transformacao com ferramentas praticas e profundas.</p>
            </div>
            <div class="module-card">
                <div class="module-number">10</div>
                <h3>Funcionando com o Metodo</h3>
                <p>Integre todo o aprendizado e funcione no dia a dia com o Metodo Espiral ativado.</p>
            </div>
        </div>
        <div class="modules-bonus-tag text-center">
            <span class="badge badge-gold">+ BONUS: SunyClass Exclusiva</span>
        </div>
    </div>
</section>

<!-- ========== RESULTS BAR ========== -->
<div class="results-bar">
    <div class="container">
        <div class="results-grid">
            <div class="result-item">
                <span class="result-number" data-count="2500">0</span>
                <span class="result-label">Alunas transformadas</span>
            </div>
            <div class="result-item">
                <span class="result-number" data-count="10">0</span>
                <span class="result-label">Modulos completos</span>
            </div>
            <div class="result-item">
                <span class="result-number" data-count="50">0</span>
                <span class="result-label">Horas de conteudo</span>
            </div>
            <div class="result-item">
                <span class="result-number" data-count="98">0</span>
                <span class="result-label">% de satisfacao</span>
            </div>
        </div>
    </div>
</div>

<!-- ========== TESTIMONIALS ========== -->
<section class="section section-testimonials" id="depoimentos">
    <div class="container">
        <div class="text-center">
            <div class="section-label">DEPOIMENTOS REAIS</div>
            <h2 class="section-title">O que dizem as mulheres que ja despertaram</h2>
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
                <p>"A comunidade e um acolhimento que nunca vivi em nenhum outro lugar. Poder ser eu mesma, sem julgamento, foi libertador. Recomendo de olhos fechados."</p>
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
                <p>"Sunyan tem um dom. Cada modulo e uma jornada. Sai do programa me sentindo outra mulher &#8212; mais inteira, mais forte, mais eu. Valeu cada centavo."</p>
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

<!-- ========== BONUSES ========== -->
<section class="section section-bonus" id="bonus">
    <div class="container">
        <div class="text-center">
            <div class="section-label">BONUS EXCLUSIVOS</div>
            <h2 class="section-title">Voce tambem vai receber</h2>
            <p class="section-desc">Alem de todo o conteudo do programa, preparamos bonus especiais para acelerar sua transformacao.</p>
        </div>
        <div class="bonus-grid">
            <div class="bonus-card">
                <div class="bonus-tag">BONUS 1</div>
                <div class="bonus-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <h3>Comunidade Mulher Espiral</h3>
                <p>Comunidade privada e anonima de mulheres em transformacao. Troque experiencias e cresca junto.</p>
                <span class="bonus-value">Valor: Inestimavel</span>
            </div>
            <div class="bonus-card">
                <div class="bonus-tag">BONUS 2</div>
                <div class="bonus-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                </div>
                <h3>Diario de Jornada</h3>
                <p>Material em PDF para registrar reflexoes, insights e transformacoes ao longo do programa.</p>
                <span class="bonus-value">Valor: R$ 97</span>
            </div>
            <div class="bonus-card">
                <div class="bonus-tag">BONUS 3</div>
                <div class="bonus-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                </div>
                <h3>Meditacoes Guiadas</h3>
                <p>Audios exclusivos de meditacoes guiadas por Sunyan para cada fase da jornada de despertar.</p>
                <span class="bonus-value">Valor: R$ 147</span>
            </div>
        </div>
    </div>
</section>

<!-- ========== GUARANTEE ========== -->
<section class="section section-guarantee" id="garantia">
    <div class="container container-sm text-center">
        <div class="guarantee-shield">
            <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 12 15 16 10" stroke-width="2"/></svg>
        </div>
        <h2 class="section-title">Garantia Incondicional de 7 Dias</h2>
        <p class="guarantee-text">
            Sua satisfacao e nossa prioridade absoluta. Se dentro de <strong>7 dias</strong> voce sentir que o programa nao e para voce,
            devolvemos <strong>100% do seu investimento</strong>. Sem perguntas, sem burocracia, sem letras miudas.
            O risco e todo nosso. Voce nao tem absolutamente nada a perder.
        </p>
    </div>
</section>

<!-- ========== FAQ ========== -->
<section class="section section-faq" id="faq">
    <div class="container container-sm">
        <div class="text-center">
            <div class="section-label">DUVIDAS</div>
            <h2 class="section-title">Perguntas Frequentes</h2>
        </div>
        <div class="faq-list">
            <div class="faq-item">
                <button class="faq-question">Por quanto tempo terei acesso ao conteudo?<span class="faq-arrow">&#9660;</span></button>
                <div class="faq-answer"><p>Voce tera acesso vitalicio a todo o conteudo do programa, incluindo atualizacoes futuras. Sem mensalidades, sem taxas adicionais.</p></div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Preciso de algum conhecimento previo?<span class="faq-arrow">&#9660;</span></button>
                <div class="faq-answer"><p>Nao! O programa foi desenhado para mulheres de todos os niveis, desde iniciantes ate aquelas que ja trilham um caminho de autoconhecimento.</p></div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Como funciona a comunidade?<span class="faq-arrow">&#9660;</span></button>
                <div class="faq-answer"><p>A comunidade e um espaco seguro e anonimo dentro da plataforma. Voce escolhe um pseudonimo e pode compartilhar experiencias e trocar com outras mulheres sem expor sua identidade.</p></div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Quais as formas de pagamento?<span class="faq-arrow">&#9660;</span></button>
                <div class="faq-answer"><p>Aceitamos cartao de credito (todas as bandeiras) atraves do Stripe, com total seguranca e criptografia. Pagamento processado instantaneamente.</p></div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Posso acessar pelo celular?<span class="faq-arrow">&#9660;</span></button>
                <div class="faq-answer"><p>Sim! A plataforma e 100% responsiva e otimizada para qualquer dispositivo &#8212; celular, tablet ou computador.</p></div>
            </div>
            <div class="faq-item">
                <button class="faq-question">E se eu nao gostar?<span class="faq-arrow">&#9660;</span></button>
                <div class="faq-answer"><p>Voce tem 7 dias de garantia incondicional. Se por qualquer motivo sentir que o programa nao e para voce, devolvemos 100% do valor investido. Sem perguntas.</p></div>
            </div>
        </div>
    </div>
</section>

<!-- ========== FINAL CTA ========== -->
<section class="section section-final-cta" id="comprar">
    <div class="container container-sm text-center">
        <div class="section-label">INVESTIMENTO</div>
        <h2 class="cta-title">Sua transformacao comeca com uma decisao</h2>
        <p class="cta-subtitle">
            Nao espere o "momento certo". O momento e agora.
        </p>
        <div class="cta-price-box">
            <div class="cta-price-header">
                <span>Programa Completo</span>
                <span class="cta-price-from">De <s>R$ 997</s></span>
            </div>
            <div class="cta-price">
                <span class="cta-price-currency">R$</span>
                <span class="cta-price-value"><?= ($product ?? null) ? number_format($product['price'], 0, ',', '.') : '497' ?></span>
            </div>
            <span class="cta-price-note">Pagamento unico &#183; Acesso vitalicio</span>
            <a href="<?= e($checkoutUrl) ?>" class="btn btn-gold btn-lg btn-block mt-3">
                Quero Despertar Agora
            </a>
            <div class="cta-secure">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <span>Compra segura &#183; Garantia de 7 dias</span>
            </div>
        </div>
        <div class="cta-includes">
            <p><strong>Tudo que voce recebe:</strong></p>
            <ul>
                <li>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Programa completo Mulher Espiral (10 modulos + bonus)
                </li>
                <li>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Acesso a Comunidade exclusiva e anonima
                </li>
                <li>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Bonus: Diario de Jornada + Meditacoes Guiadas
                </li>
                <li>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Acesso vitalicio + Atualizacoes futuras
                </li>
                <li>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Garantia incondicional de 7 dias
                </li>
            </ul>
        </div>
    </div>
</section>

<!-- ========== FOOTER ========== -->
<footer class="landing-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand-col">
                <p class="footer-brand">MULHER ESPIRAL</p>
                <p class="footer-brand-desc">Programa de transformacao feminina por Sunyan Nunes. Desperte a mulher espiral que existe em voce.</p>
            </div>
            <div class="footer-links-col">
                <h4>Links</h4>
                <a href="<?= url('login') ?>">Area de Membros</a>
                <a href="#metodo">O Metodo</a>
                <a href="#modulos">Conteudo</a>
                <a href="#faq">FAQ</a>
            </div>
            <div class="footer-links-col">
                <h4>Contato</h4>
                <a href="mailto:contato@mulherespiral.shop">contato@mulherespiral.shop</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Sunyan Nunes. Todos os direitos reservados.</p>
            <p class="footer-disclaimer">
                Este produto nao garante a obtencao de resultados. Qualquer referencia ao desempenho de uma estrategia nao deve ser interpretada como uma garantia de resultados.
            </p>
        </div>
    </div>
</footer>

<!-- Floating CTA (mobile) -->
<div class="floating-cta" id="floatingCta">
    <a href="<?= e($checkoutUrl) ?>" class="btn btn-gold btn-block">Quero Despertar Agora</a>
</div>

<script src="<?= asset('js/landing.js') ?>"></script>
</body>
</html>
