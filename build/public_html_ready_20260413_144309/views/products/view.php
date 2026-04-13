<div class="product-view">
    <!-- Video / Content Area -->
    <?php if ($currentLesson ?? null): ?>
        <div class="lesson-content-area">
            <?php if ($currentLesson['content_type'] === 'video' && $currentLesson['content_url']): ?>
                <div class="video-container">
                    <iframe src="<?= e($currentLesson['content_url']) ?>" allowfullscreen allow="autoplay; encrypted-media" loading="lazy"></iframe>
                </div>
            <?php elseif ($currentLesson['content_type'] === 'text' && $currentLesson['content_body']): ?>
                <div class="text-content-area">
                    <?= $currentLesson['content_body'] ?>
                </div>
            <?php elseif ($currentLesson['content_type'] === 'pdf' && $currentLesson['content_url']): ?>
                <div class="pdf-content-area">
                    <p>Material em PDF disponivel para download:</p>
                    <a href="<?= e($currentLesson['content_url']) ?>" target="_blank" class="btn btn-primary">Baixar PDF</a>
                </div>
            <?php endif; ?>

            <!-- Lesson header -->
            <div class="lesson-header">
                <div>
                    <span class="text-muted text-sm"><?= e($currentLesson['module_title'] ?? '') ?></span>
                    <h2 class="lesson-current-title"><?= e($currentLesson['title']) ?></h2>
                    <?php if ($currentLesson['duration_minutes'] > 0): ?>
                        <span class="text-muted text-sm">&#9202; <?= $currentLesson['duration_minutes'] ?> min</span>
                    <?php endif; ?>
                </div>
                <form method="POST" action="<?= url('products/progress') ?>">
                    <?= CSRF::field() ?>
                    <input type="hidden" name="lesson_id" value="<?= $currentLesson['id'] ?>">
                    <input type="hidden" name="product_slug" value="<?= e($product['slug']) ?>">
                    <button type="submit" class="btn <?= ($currentLesson['is_completed'] ?? false) ? 'btn-outline' : 'btn-primary' ?> btn-sm">
                        <?= ($currentLesson['is_completed'] ?? false) ? '&#10003; Concluida' : 'Marcar como concluida' ?>
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Module/Lesson List -->
    <div class="course-content-section">
        <h3 class="course-content-title">Conteudo do curso</h3>
        <?php foreach ($modules as $module): ?>
            <div class="module-section">
                <div class="module-header">
                    <span class="module-title"><?= e($module['title']) ?></span>
                    <span class="text-muted text-xs"><?= count($module['lessons']) ?> aulas</span>
                </div>
                <ul class="lesson-list">
                    <?php foreach ($module['lessons'] as $i => $les): ?>
                        <li>
                            <a href="<?= url('products/' . e($product['slug']) . '/lesson/' . $les['id']) ?>"
                               class="lesson-item <?= ($currentLesson && $les['id'] == $currentLesson['id']) ? 'active' : '' ?> <?= $les['is_completed'] ? 'completed' : '' ?>">
                                <div class="lesson-number"><?= $i + 1 ?></div>
                                <div class="lesson-info">
                                    <div class="lesson-title"><?= e($les['title']) ?></div>
                                    <div class="lesson-meta">
                                        <?= ucfirst($les['content_type']) ?>
                                        <?php if ($les['duration_minutes'] > 0): ?>
                                            &#183; <?= $les['duration_minutes'] ?> min
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="lesson-check">
                                    <?= $les['is_completed'] ? '&#10003;' : '&#9675;' ?>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
</div>
