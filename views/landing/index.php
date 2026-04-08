<?php
$pageTitle = 'Mulher Espiral - Sunyan Nunes';
$checkoutUrl = ($product ?? null) ? url('checkout/' . $product['slug']) : url('register');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="Metodo Mulher Espiral: uma jornada simplificada e transformadora para mulheres que desejam mapear o caminho da cura e viver com mais leveza, seguranca e liberdade.">
    <meta name="theme-color" content="#0A0A0A" id="themeColorMeta">
    <?= themeInitScript() ?>
    <meta property="og:title" content="Mulher Espiral - Sunyan Nunes">
    <meta property="og:description" content="Desperte a mulher espiral que existe em voce. Programa completo de transformacao feminina.">
    <meta property="og:type" content="website">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/landing.css') ?>">
</head>
<body class="landing-page">

<!-- NAV -->
<nav class="landing-nav" id="nav">
    <div class="container flex-between">
        <a href="<?= url('') ?>" class="nav-logo">MULHER ESPIRAL</a>
        <div class="nav-links" id="navLinks">
            <a href="#metodo">Metodo</a>
            <a href="#sobre">Sunyan</a>
            <a href="#programa">Programa</a>
            <a href="<?= url('marketplace') ?>">Marketplace</a>
            <a href="#depoimentos">Resultados</a>
            <?= themeToggleButton('theme-toggle theme-toggle-nav', 'Modo claro') ?>
            <a href="<?= url('login') ?>" class="nav-cta-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Ja sou aluna
            </a>
        </div>
        <button class="nav-toggle" id="navToggle" aria-label="Abrir menu" aria-controls="navLinks" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

<!-- HERO -->
<section class="hero" id="hero">
    <div class="hero-bg-effects">
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>
    </div>
    <div class="container hero-content">
        <div class="hero-layout">
            <div class="hero-copy">
                <span class="hero-badge">
                    <span class="hero-badge-dot"></span>
                    Jornada guiada com acolhimento real
                </span>
                <h1 class="hero-title">
                    Um metodo para<br>
                    mapear o caminho da sua<br>
                    <em>cura.</em>
                </h1>
                <p class="hero-subtitle">
                    O Mulher Espiral nasceu de uma jornada real de cura e hoje
                    conduz mulheres a uma vida mais leve, livre e alinhada,
                    sem viverem refens das suas dores fisicas e emocionais.
                </p>
                <div class="hero-cta-group">
                    <a href="<?= e($checkoutUrl) ?>" class="btn btn-gold btn-lg hero-cta-main">
                        Quero entrar para o Mulher Espiral
                    </a>
                    <a href="#programa" class="btn btn-outline btn-lg hero-cta-secondary">
                        Ver como funciona
                    </a>
                </div>
                <div class="hero-cta-trust">
                    <span>Acesso imediato</span>
                    <span class="trust-dot"></span>
                    <span>Garantia de 7 dias</span>
                    <span class="trust-dot"></span>
                    <span>Comunidade privada</span>
                </div>
                <div class="hero-proof">
                    <div class="hero-proof-item">
                        <strong>21+</strong>
                        <span>anos com mulheres</span>
                    </div>
                    <div class="hero-proof-sep"></div>
                    <div class="hero-proof-item">
                        <strong>15+</strong>
                        <span>anos de estudos</span>
                    </div>
                    <div class="hero-proof-sep"></div>
                    <div class="hero-proof-item">
                        <strong>leveza</strong>
                        <span>cura e reconexao</span>
                    </div>
                </div>
            </div>
            <div class="hero-visual-wrap">
                <div class="hero-visual-frame">
                    <div class="tablet-frame tablet-frame-hero">
                        <div class="tablet-notch" aria-hidden="true"></div>
                        <div class="tablet-screen">
                            <img
                                src="<?= asset('images/landing/hero-essencia.svg') ?>"
                                alt="Visual da plataforma em formato tablet"
                                class="tablet-screen-image"
                                fetchpriority="high"
                                decoding="async"
                            >
                            <div class="tablet-tool" data-frequency-tool>
                                <div class="tablet-tool-top">
                                    <div class="tablet-tool-title">Scanner de Frequencia</div>
                                    <div class="tablet-tool-hz" data-ft-value>432 Hz</div>
                                </div>
                                <div class="tablet-tool-dots" aria-hidden="true">
                                    <span class="tablet-dot is-active" data-ft-dot></span>
                                    <span class="tablet-dot" data-ft-dot></span>
                                    <span class="tablet-dot" data-ft-dot></span>
                                </div>
                                <div class="tablet-tool-step" data-ft-step>
                                    <div class="tablet-tool-step-label">Passo 1/3 • Pessoal</div>
                                    <div class="tablet-tool-step-help">Escreva 1 a 3 palavras que vem a cabeca.</div>
                                    <input class="tablet-tool-input" type="text" placeholder="ex: cansaco, coragem, paz" data-ft-input="personal">
                                </div>
                                <div class="tablet-tool-step" data-ft-step style="display:none">
                                    <div class="tablet-tool-step-label">Passo 2/3 • Familiar</div>
                                    <div class="tablet-tool-step-help">Primeiras palavras sobre seu contexto familiar.</div>
                                    <input class="tablet-tool-input" type="text" placeholder="ex: apoio, pressao, distancia" data-ft-input="family">
                                </div>
                                <div class="tablet-tool-step" data-ft-step style="display:none">
                                    <div class="tablet-tool-step-label">Passo 3/3 • Profissional</div>
                                    <div class="tablet-tool-step-help">Primeiras palavras sobre trabalho e rotina.</div>
                                    <input class="tablet-tool-input" type="text" placeholder="ex: foco, sobrecarga, direcao" data-ft-input="professional">
                                    <div class="tablet-tool-meter">
                                        <div class="tablet-tool-meter-fill" data-ft-meter style="width:29%"></div>
                                    </div>
                                    <div class="tablet-tool-band">
                                        <strong data-ft-band-title>Clareza</strong>
                                        <span data-ft-band-desc>Sua energia esta organizando o caminho.</span>
                                    </div>
                                    <div class="tablet-tool-fine">
                                        <button type="button" class="tablet-tool-btn" data-ft-minus aria-label="Diminuir 1 Hz">−</button>
                                        <input type="range" min="200" max="1000" step="1" value="432" class="tablet-tool-range" data-ft-range aria-label="Ajustar Hz">
                                        <button type="button" class="tablet-tool-btn" data-ft-plus aria-label="Aumentar 1 Hz">+</button>
                                    </div>
                                </div>
                                <div class="tablet-tool-actions">
                                    <button type="button" class="tablet-tool-ghost" data-ft-back>Voltar</button>
                                    <button type="button" class="tablet-tool-primary" data-ft-next>Continuar</button>
                                    <button type="button" class="tablet-tool-primary" data-ft-apply style="display:none">Aplicar ao fundo</button>
                                </div>
                            </div>
                        </div>
                        <div class="tablet-shadow" aria-hidden="true"></div>
                    </div>
                    <div class="hero-visual-card hero-visual-card-primary">
                        <strong>Metodo em camadas</strong>
                        <span>Voce entende, integra e aplica no seu tempo.</span>
                    </div>
                    <div class="hero-visual-card hero-visual-card-secondary">
                        <strong>Experiencia acolhedora</strong>
                        <span>Clareza, seguranca emocional e proximo passo evidente.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TRUST -->
<div class="trust-bar">
    <div class="container">
        <div class="trust-items">
            <span>Pagamento seguro</span>
            <span class="trust-dot"></span>
            <span>Garantia 7 dias</span>
            <span class="trust-dot"></span>
            <span>Acesso imediato</span>
            <span class="trust-dot"></span>
            <span>Acesso vitalicio</span>
        </div>
    </div>
</div>

<!-- CONTEXT: A VERDADE -->
<section class="section" id="verdade">
    <div class="container container-sm">
        <div class="prose-block">
            <p class="prose-lead">Eu sei o que voce esta sentindo.</p>
            <p>Talvez voce esteja cansada de sustentar tudo, de parecer forte o tempo inteiro e ainda assim sentir que sua vida nao flui como deveria.</p>
            <p>Muitas mulheres chegaram ate aqui depois de anos tentando resolver sintomas, culpas, medos e repeticoes sem conseguir enxergar a raiz do que as aprisiona.</p>
            <p>O Metodo Mulher Espiral nasce exatamente desse ponto: quando voce entende que nao precisa continuar refem das suas dores e pode <strong>mapear conscientemente o caminho da cura.</strong></p>
            <p class="prose-highlight">Existe um jeito mais leve, mais profundo e mais verdadeiro de voltar para si mesma.</p>
        </div>
    </div>
</section>

<!-- EXPERIENCIA GUIADA -->
<section class="section section-story" id="experiencia">
    <div class="container">
        <div class="text-center">
            <span class="section-label">COMO A EXPERIENCIA FLUI</span>
            <h2 class="section-title">Uma jornada que revela o proximo passo enquanto voce avanca.</h2>
            <p class="section-desc">A plataforma foi pensada para guiar, acolher e aprofundar sua experiencia sem ruido visual, sem excesso e sem confusao.</p>
        </div>
        <div class="story-scroll">
            <div class="story-visual" data-story-visual>
                <div class="story-visual-stack">
                    <img src="<?= asset('images/landing/story-acolhimento.svg') ?>" alt="Painel visual de acolhimento e seguranca emocional" class="story-image is-active" data-story-image="0" loading="lazy" decoding="async">
                    <img src="<?= asset('images/landing/story-jornada.svg') ?>" alt="Painel visual de jornada estruturada e progresso claro" class="story-image" data-story-image="1" loading="lazy" decoding="async">
                    <img src="<?= asset('images/landing/story-comunidade.svg') ?>" alt="Painel visual de comunidade privada e integracao da experiencia" class="story-image" data-story-image="2" loading="lazy" decoding="async">
                </div>
                <div class="story-visual-chip">Experiencia premium guiada</div>
            </div>
            <div class="story-steps">
                <article class="story-step is-active" data-story-step="0">
                    <span class="story-step-index">01</span>
                    <h3>Acolhe antes de acelerar</h3>
                    <p>Desde o primeiro scroll, a experiencia explica o que voce vai receber, reduz friccao e transmite seguranca para que a decisao seja leve e consciente.</p>
                    <ul class="story-step-list">
                        <li>Mensagem central simples e elegante</li>
                        <li>Contraste premium para leitura sem esforco</li>
                        <li>CTA principal sempre visivel e compreensivel</li>
                    </ul>
                </article>
                <article class="story-step" data-story-step="1">
                    <span class="story-step-index">02</span>
                    <h3>Mostra a jornada com clareza</h3>
                    <p>Cada parte da pagina conduz a usuaria com uma narrativa visual que mostra transformacao, metodo e beneficios reais sem parecer agressiva.</p>
                    <ul class="story-step-list">
                        <li>Blocos visuais com leitura cadenciada</li>
                        <li>Provas suaves e beneficios bem posicionados</li>
                        <li>Sensacao de plataforma de alto nivel</li>
                    </ul>
                </article>
                <article class="story-step" data-story-step="2">
                    <span class="story-step-index">03</span>
                    <h3>Fecha a decisao com confianca</h3>
                    <p>Ao chegar no CTA, a usuaria sabe exatamente o que acontece depois do clique: checkout seguro, acesso imediato e continuidade na area de membros.</p>
                    <ul class="story-step-list">
                        <li>Compra com expectativa alinhada</li>
                        <li>Proximo passo sempre evidente</li>
                        <li>Suporte visual e emocional ate a conversao</li>
                    </ul>
                </article>
            </div>
        </div>
    </div>
</section>

<!-- METODO -->
<section class="section section-dark" id="metodo">
    <div class="container">
        <div class="method-layout">
            <div class="method-text">
                <span class="section-label">O METODO</span>
                <h2 class="section-title text-left">Nao e apenas um curso.<br>E um metodo vivo de reconexao e cura.</h2>
                <div class="gold-line-left"></div>
                <p>Depois de buscar em varias partes do mundo respostas para dores fisicas e emocionais, Sunyan transformou sua propria experiencia em um processo estruturado de cura, reconexao e materializacao de uma vida mais leve.</p>
                <p>O metodo simplifica aquilo que antes parecia confuso: voce aprende a reconhecer seus padroes, equilibrar seu feminino e deixar de viver no piloto automatico do sofrimento.</p>
                <div class="method-pillars">
                    <div class="method-pillar">
                        <span class="method-pillar-num">01</span>
                        <span>Consciencia</span>
                        <small>Enxergar os padroes</small>
                    </div>
                    <div class="method-pillar">
                        <span class="method-pillar-num">02</span>
                        <span>Reconexao</span>
                        <small>Voltar pra si mesma</small>
                    </div>
                    <div class="method-pillar">
                        <span class="method-pillar-num">03</span>
                        <span>Ativacao</span>
                        <small>Despertar sua energia</small>
                    </div>
                    <div class="method-pillar">
                        <span class="method-pillar-num">04</span>
                        <span>Integracao</span>
                        <small>Viver o novo</small>
                    </div>
                </div>
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

<!-- SOBRE SUNYAN -->
<section class="section" id="sobre">
    <div class="container">
        <div class="about-layout">
            <div class="about-image">
                <div class="about-photo-frame">
                    <img
                        src="<?= asset('images/landing/about-sunyan.svg') ?>"
                        alt="Ilustracao editorial representando a guia da jornada Mulher Espiral"
                        class="about-photo"
                        loading="lazy"
                        decoding="async"
                    >
                    <div class="about-photo-badge">
                        <strong>21+ anos</strong>
                        <span>acompanhando mulheres em autoestima e seguranca</span>
                    </div>
                </div>
            </div>
            <div class="about-text">
                <span class="section-label">QUEM TE GUIA</span>
                <h2 class="section-title text-left">Sunyan Nunes</h2>
                <div class="gold-line-left"></div>
                <p>Apaixonada por desenvolvimento pessoal e espiritual, Sunyan buscou em diferentes lugares do mundo caminhos para curar as proprias dores fisicas e emocionais.</p>
                <p>Formada inicialmente em <strong>Odontologia</strong>, profissao que ainda exerce, dedicou mais de <strong>15 anos</strong> ao estudo de cursos e processos de desenvolvimento pessoal e espiritual.</p>
                <p>Depois de mais de <strong>21 anos</strong> atendendo mulheres que buscavam autoestima e seguranca, decidiu compartilhar seu metodo para ajuda-las a se libertarem dos processos de sofrimento e viverem com mais leveza.</p>
                <blockquote class="about-quote">
                    "Hoje eu compartilho com o universo feminino um caminho simplificado e transformador para fazer a vida fluir de forma leve e fluida."
                </blockquote>
            </div>
        </div>
    </div>
</section>

<!-- PROGRAMA COMPLETO -->
<section class="section section-dark" id="programa">
    <div class="container">
        <div class="text-center">
            <span class="section-label">O QUE VOCE RECEBE</span>
            <h2 class="section-title">10 modulos + bonus exclusivo</h2>
            <p class="section-desc">Cada modulo foi desenhado como uma fase da sua jornada. Nao existe pular etapas &#8212; cada passo prepara o proximo.</p>
        </div>
        <div class="program-grid">
            <div class="program-item"><span>01</span><div><h4>Despertar da Consciencia</h4><p>Os padroes inconscientes que regem sua vida</p></div></div>
            <div class="program-item"><span>02</span><div><h4>Despertar</h4><p>O momento de abrir os olhos para sua verdade</p></div></div>
            <div class="program-item"><span>03</span><div><h4>Masculino e Feminino</h4><p>Equilibre as duas energias que coexistem em voce</p></div></div>
            <div class="program-item"><span>04</span><div><h4>Natureza dos Resultados</h4><p>Como sua energia interna cria sua realidade externa</p></div></div>
            <div class="program-item"><span>05</span><div><h4>Padrao de Personalidade</h4><p>Identifique e transmute seus padroes de repeticao</p></div></div>
            <div class="program-item"><span>06</span><div><h4>Doencas Fisicas e Emocionais</h4><p>A conexao corpo-mente que ninguem te explicou</p></div></div>
            <div class="program-item"><span>07</span><div><h4>Sentimentos que Aprisionam</h4><p>Liberte-se de culpa, medo e raiva acumulados</p></div></div>
            <div class="program-item"><span>08</span><div><h4>Neutralizacao das Emocoes</h4><p>Tecnicas para restaurar seu equilibrio interno</p></div></div>
            <div class="program-item"><span>09</span><div><h4>Mapeando a Cura</h4><p>Seu mapa pessoal de transformacao e cura profunda</p></div></div>
            <div class="program-item"><span>10</span><div><h4>Funcionando com o Metodo</h4><p>Integre o Metodo Espiral no seu dia a dia</p></div></div>
        </div>
        <div class="program-bonus">
            <span class="section-label">BONUS INCLUSOS</span>
            <div class="bonus-list">
                <div class="bonus-item">
                    <h4>Comunidade Privada</h4>
                    <p>Espaco anonimo e seguro para trocar com outras mulheres em transformacao</p>
                </div>
                <div class="bonus-item">
                    <h4>Diario de Jornada</h4>
                    <p>PDF para registrar reflexoes e insights ao longo do programa</p>
                </div>
                <div class="bonus-item">
                    <h4>Meditacoes Guiadas</h4>
                    <p>Audios exclusivos de Sunyan para cada fase da sua jornada</p>
                </div>
                <div class="bonus-item">
                    <h4>SunyClass Exclusiva</h4>
                    <p>Aulas extras ao vivo com aprofundamento no metodo</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- DEPOIMENTOS -->
<section class="section" id="depoimentos">
    <div class="container container-sm">
        <div class="text-center">
            <span class="section-label">RESULTADOS REAIS</span>
            <h2 class="section-title">Palavras de quem ja viveu a transformacao</h2>
        </div>
        <div class="testimonial-stack">
            <div class="testimonial-item">
                <p>"Eu achava que ja tinha tentado de tudo. O Mulher Espiral me mostrou que eu nunca tinha olhado para dentro de verdade. Minha vida mudou completamente. Meu relacionamento, minha autoestima, tudo."</p>
                <div class="testimonial-meta">
                    <div class="testimonial-avatar">M</div>
                    <div><strong>Marina L.</strong><span>Sao Paulo, SP</span></div>
                </div>
            </div>
            <div class="testimonial-item">
                <p>"A comunidade e um acolhimento que nunca vivi em nenhum outro lugar. Poder ser eu mesma, sem julgamento, foi libertador. Pela primeira vez me senti vista de verdade."</p>
                <div class="testimonial-meta">
                    <div class="testimonial-avatar">C</div>
                    <div><strong>Camila R.</strong><span>Belo Horizonte, MG</span></div>
                </div>
            </div>
            <div class="testimonial-item">
                <p>"Sunyan tem um dom. Cada modulo e uma jornada. Sai do programa me sentindo outra mulher &#8212; mais inteira, mais forte, mais eu. O investimento se pagou mil vezes."</p>
                <div class="testimonial-meta">
                    <div class="testimonial-avatar">A</div>
                    <div><strong>Ana P.</strong><span>Curitiba, PR</span></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- GARANTIA -->
<section class="section section-dark">
    <div class="container container-sm text-center">
        <div class="guarantee-block">
            <svg class="guarantee-icon" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 12 15 16 10" stroke-width="2"/></svg>
            <h3>Garantia de 7 dias. Risco zero.</h3>
            <p>Se por qualquer motivo voce sentir que o programa nao e para voce, devolvemos 100% do valor. Sem perguntas, sem burocracia. O risco e todo nosso.</p>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="section" id="faq">
    <div class="container container-sm">
        <div class="text-center">
            <h2 class="section-title">Duvidas frequentes</h2>
        </div>
        <div class="faq-list">
            <div class="faq-item">
                <button class="faq-question">Por quanto tempo terei acesso?<span class="faq-arrow">+</span></button>
                <div class="faq-answer"><p>Acesso vitalicio. Sem mensalidade, sem renovacao. Uma vez dentro, sempre dentro.</p></div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Preciso de experiencia previa?<span class="faq-arrow">+</span></button>
                <div class="faq-answer"><p>Nenhuma. O programa foi feito para mulheres de todos os niveis &#8212; desde quem nunca estudou autoconhecimento ate quem ja tem anos de caminhada.</p></div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Como funciona a comunidade?<span class="faq-arrow">+</span></button>
                <div class="faq-answer"><p>Voce escolhe um pseudonimo e participa de forma anonima. E um espaco seguro para compartilhar, perguntar e trocar sem julgamento.</p></div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Posso acessar pelo celular?<span class="faq-arrow">+</span></button>
                <div class="faq-answer"><p>Sim. A plataforma e 100% otimizada para celular, tablet e computador.</p></div>
            </div>
            <div class="faq-item">
                <button class="faq-question">E se eu nao gostar?<span class="faq-arrow">+</span></button>
                <div class="faq-answer"><p>Voce tem 7 dias para decidir. Se nao for para voce, devolvemos 100%. Simples assim.</p></div>
            </div>
        </div>
    </div>
</section>

<!-- CTA FINAL -->
<section class="section section-cta" id="comprar">
    <div class="container container-sm text-center">
        <h2 class="section-title">Entre hoje e comece sua transformacao com clareza.</h2>
        <p class="section-desc final-cta-desc">Ao clicar no botao, voce segue para um checkout seguro e recebe as instrucoes de acesso no seu e-mail.</p>
        <div class="price-card">
            <div class="price-label">Programa completo + todos os bonus</div>
            <div class="price-old">De <s>R$ 997</s></div>
            <div class="price-main">
                <span class="price-currency">R$</span>
                <span class="price-value"><?= ($product ?? null) ? number_format($product['price'], 0, ',', '.') : '497' ?></span>
            </div>
            <div class="price-info">Pagamento unico &#183; Acesso vitalicio</div>
            <a href="<?= e($checkoutUrl) ?>" class="btn btn-gold btn-lg btn-block">
                Entrar agora no Mulher Espiral
            </a>
            <p class="cta-helper-note">Voce conclui a compra em poucos minutos, sem mensalidade e sem etapas confusas.</p>
            <div class="price-secure">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Compra segura &#183; Dados protegidos
            </div>
            <ul class="price-checklist">
                <li>10 modulos completos do Metodo Espiral</li>
                <li>Comunidade privada e anonima</li>
                <li>Bonus: SunyClass + Diario + Meditacoes</li>
                <li>Acesso vitalicio + atualizacoes</li>
                <li>Garantia incondicional de 7 dias</li>
            </ul>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="landing-footer">
    <div class="container">
        <div class="footer-inner">
            <div>
                <p class="footer-brand">MULHER ESPIRAL</p>
                <p class="footer-sub">por Sunyan Nunes</p>
            </div>
            <div class="footer-links">
                <a href="<?= url('login') ?>">Area de Membros</a>
                <a href="mailto:contato@mulherespiral.shop">Contato</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Sunyan Nunes. Todos os direitos reservados.</p>
            <p class="footer-disclaimer">Este produto nao garante a obtencao de resultados. Resultados variam de pessoa para pessoa.</p>
        </div>
    </div>
</footer>

<!-- FLOATING CTA MOBILE -->
<div class="floating-cta" id="floatingCta">
    <a href="<?= e($checkoutUrl) ?>" class="btn btn-gold btn-block">Entrar agora</a>
</div>

<?= themeScriptTag() ?>
<script src="<?= asset('js/frequency-tool.js') ?>" defer></script>
<script src="<?= asset('js/landing.js') ?>" defer></script>
</body>
</html>
