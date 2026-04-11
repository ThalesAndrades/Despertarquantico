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
    <meta name="description" content="O programa de transformacao feminina mais completo do Brasil. Metodo exclusivo de Sunyan Nunes para despertar sua essencia e ativar seu poder interior.">
    <meta name="theme-color" content="#0A0A0A">
    <meta property="og:title" content="Mulher Espiral - Sunyan Nunes">
    <meta property="og:description" content="Desperte a mulher espiral que existe em voce. Programa completo de transformacao feminina.">
    <meta property="og:type" content="website">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/landing.css') ?>">
</head>
<body>

<!-- NAV -->
<nav class="landing-nav" id="nav">
    <div class="container flex-between">
        <a href="<?= url('') ?>" class="nav-logo">MULHER ESPIRAL</a>
        <div class="nav-links" id="navLinks">
            <a href="#metodo">Metodo</a>
            <a href="#sobre">Sunyan</a>
            <a href="#programa">Programa</a>
            <a href="#depoimentos">Resultados</a>
            <a href="<?= url('login') ?>" class="nav-cta-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Acessar
            </a>
        </div>
        <button class="nav-toggle" id="navToggle" aria-label="Menu" aria-expanded="false">
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
        <span class="hero-badge">
            <span class="hero-badge-dot"></span>
            Programa exclusivo
        </span>
        <h1 class="hero-title">
            Voce nao precisa de mais<br>
            informacao. Voce precisa<br>
            de <em>transformacao.</em>
        </h1>
        <p class="hero-subtitle">
            O Mulher Espiral e o metodo que ja guiou mais de 2.500 mulheres
            a reconectarem com sua essencia, dissolverem padroes limitantes
            e criarem uma vida alinhada com quem realmente sao.
        </p>
        <a href="<?= e($checkoutUrl) ?>" class="btn btn-gold btn-lg hero-cta-main">
            Comecar minha jornada
        </a>
        <a href="<?= url('aplicacao') ?>" class="btn btn-outline btn-lg" style="margin-top:12px;">
            Aplicar para mentoria premium
        </a>
        <div class="hero-proof">
            <div class="hero-proof-item">
                <strong>2.500+</strong>
                <span>mulheres</span>
            </div>
            <div class="hero-proof-sep"></div>
            <div class="hero-proof-item">
                <strong>10</strong>
                <span>modulos</span>
            </div>
            <div class="hero-proof-sep"></div>
            <div class="hero-proof-item">
                <strong>4.9</strong>
                <span>avaliacao</span>
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
            <p>Voce acorda todos os dias com aquela sensacao de que falta algo. Faz tudo certo &#8212; trabalha, cuida, se dedica &#8212; mas por dentro, existe um vazio que ninguem ve.</p>
            <p>Voce ja leu livros, assistiu videos, talvez ate fez terapia. E tudo ajudou um pouco. Mas a raiz continua la. Porque ninguem te ensinou a <strong>olhar para onde realmente importa.</strong></p>
            <p>O problema nunca foi falta de forca. O problema e que voce aprendeu a viver desconectada de quem realmente e.</p>
            <p class="prose-highlight">E se eu te dissesse que existe um caminho para reconectar com essa mulher que voce sente que perdeu?</p>
        </div>
    </div>
</section>

<!-- METODO -->
<section class="section section-dark" id="metodo">
    <div class="container">
        <div class="method-layout">
            <div class="method-text">
                <span class="section-label">O METODO</span>
                <h2 class="section-title text-left">Mulher Espiral nao e um curso.<br>E uma jornada de volta pra voce.</h2>
                <div class="gold-line-left"></div>
                <p>Desenvolvido ao longo de 10 anos por Sunyan Nunes, o Metodo Espiral combina <strong>sabedoria ancestral feminina</strong> com <strong>reprogramacao quantica</strong> para criar transformacoes que duram.</p>
                <p>Nao e teoria. Nao e motivacao vazia. E um processo estruturado, modulo a modulo, que te leva da desconexao ao despertar real.</p>
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
                <div class="about-photo-placeholder">SN</div>
            </div>
            <div class="about-text">
                <span class="section-label">QUEM TE GUIA</span>
                <h2 class="section-title text-left">Sunyan Nunes</h2>
                <div class="gold-line-left"></div>
                <p>Nao sou uma influenciadora. Nao sou coach. Sou uma mulher que passou pela propria espiral &#8212; e decidiu dedicar a vida a ajudar outras mulheres a fazerem o mesmo.</p>
                <p>Ha mais de <strong>10 anos</strong> estudo energia feminina, espiritualidade e transformacao pessoal. Ja acompanhei mais de <strong>2.500 mulheres</strong> nessa jornada.</p>
                <p>Criei o Mulher Espiral porque sei que toda mulher carrega dentro de si uma forca que nao precisa ser construida &#8212; <strong>precisa ser lembrada.</strong></p>
                <blockquote class="about-quote">
                    "Eu nao te ensino nada novo. Eu te ajudo a lembrar quem voce sempre foi."
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
                <button class="faq-question" aria-expanded="false">Por quanto tempo terei acesso?<span class="faq-arrow">+</span></button>
                <div class="faq-answer"><p>Acesso vitalicio. Sem mensalidade, sem renovacao. Uma vez dentro, sempre dentro.</p></div>
            </div>
            <div class="faq-item">
                <button class="faq-question" aria-expanded="false">Preciso de experiencia previa?<span class="faq-arrow">+</span></button>
                <div class="faq-answer"><p>Nenhuma. O programa foi feito para mulheres de todos os niveis &#8212; desde quem nunca estudou autoconhecimento ate quem ja tem anos de caminhada.</p></div>
            </div>
            <div class="faq-item">
                <button class="faq-question" aria-expanded="false">Como funciona a comunidade?<span class="faq-arrow">+</span></button>
                <div class="faq-answer"><p>Voce escolhe um pseudonimo e participa de forma anonima. E um espaco seguro para compartilhar, perguntar e trocar sem julgamento.</p></div>
            </div>
            <div class="faq-item">
                <button class="faq-question" aria-expanded="false">Posso acessar pelo celular?<span class="faq-arrow">+</span></button>
                <div class="faq-answer"><p>Sim. A plataforma e 100% otimizada para celular, tablet e computador.</p></div>
            </div>
            <div class="faq-item">
                <button class="faq-question" aria-expanded="false">E se eu nao gostar?<span class="faq-arrow">+</span></button>
                <div class="faq-answer"><p>Voce tem 7 dias para decidir. Se nao for para voce, devolvemos 100%. Simples assim.</p></div>
            </div>
        </div>
    </div>
</section>

<!-- CTA FINAL -->
<section class="section section-cta" id="comprar">
    <div class="container container-sm text-center">
        <h2 class="section-title">Voce ja sabe que precisa disso.</h2>
        <p class="section-desc section-desc-cta">A unica pergunta e: vai ser agora ou vai continuar esperando?</p>
        <div class="price-card">
            <div class="price-label">Programa completo + todos os bonus</div>
            <div class="price-old">De <s>R$ 997</s></div>
            <div class="price-main">
                <span class="price-currency">R$</span>
                <span class="price-value"><?= ($product ?? null) ? number_format($product['price'], 0, ',', '.') : '497' ?></span>
            </div>
            <div class="price-info">Pagamento unico &#183; Acesso vitalicio</div>
            <a href="<?= e($checkoutUrl) ?>" class="btn btn-gold btn-lg btn-block">
                Quero comecar agora
            </a>
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
                <a href="mailto:contato@despertarespiral.com">Contato</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Sunyan Nunes. Todos os direitos reservados.</p>
            <p class="footer-disclaimer">Este produto nao garante a obtencao de resultados. Resultados variam de pessoa para pessoa.</p>
        </div>
    </div>
</footer>

<!-- SCROLL TO TOP -->
<button class="scroll-top" id="scrollTop" aria-label="Voltar ao topo">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"/></svg>
</button>

<!-- FLOATING CTA MOBILE -->
<div class="floating-cta" id="floatingCta">
    <a href="<?= e($checkoutUrl) ?>" class="btn btn-gold btn-block">Comecar minha jornada</a>
</div>

<script src="<?= asset('js/landing.js') ?>"></script>
</body>
</html>
