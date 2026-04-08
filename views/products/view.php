<div class="product-view">
    <!-- Video / Content Area -->
    <?php if ($currentLesson ?? null): ?>
        <div class="lesson-content-area">
            <?php if ($currentLesson['content_type'] === 'video' && $currentLesson['content_url']): ?>
                <div class="video-container">
                    <iframe src="<?= e($currentLesson['content_url']) ?>" allowfullscreen allow="autoplay; encrypted-media" loading="lazy"></iframe>
                </div>
            <?php elseif ($currentLesson['content_type'] === 'text' && $currentLesson['content_body']): ?>
                <div class="text-content-area" style="background:#fff;border-radius:16px;padding:40px;box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                    <?= $currentLesson['content_body'] ?>
                </div>
            <?php elseif ($currentLesson['content_type'] === 'pdf' && $currentLesson['content_url']): ?>
                <div style="background:#fff;border-radius:16px;padding:40px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                    <p style="margin-bottom:16px;">📄 Material em PDF disponível para download:</p>
                    <a href="<?= e($currentLesson['content_url']) ?>" target="_blank" class="btn btn-primary">Baixar PDF</a>
                </div>
            <?php endif; ?>

            <!-- Lesson header -->
            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:20px;flex-wrap:wrap;gap:12px;">
                <div>
                    <span class="text-muted text-sm"><?= e($currentLesson['module_title'] ?? '') ?></span>
                    <h2 style="font-family:'Inter',sans-serif;font-size:22px;font-weight:700;color:#1D1D1F;margin-top:4px;">
                        <?= e($currentLesson['title']) ?>
                    </h2>
                    <?php if ($currentLesson['duration_minutes'] > 0): ?>
                        <span class="text-muted text-sm">⏱ <?= $currentLesson['duration_minutes'] ?> min</span>
                    <?php endif; ?>
                </div>
                <form method="POST" action="<?= url('products/progress') ?>">
                    <?= CSRF::field() ?>
                    <input type="hidden" name="lesson_id" value="<?= $currentLesson['id'] ?>">
                    <input type="hidden" name="product_slug" value="<?= e($product['slug']) ?>">
                    <button type="submit" class="btn <?= ($currentLesson['is_completed'] ?? false) ? 'btn-outline' : 'btn-primary' ?> btn-sm">
                        <?= ($currentLesson['is_completed'] ?? false) ? '✓ Concluída' : 'Marcar como concluída' ?>
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Module/Lesson List -->
    <div style="margin-top:32px;">
        <h3 style="font-family:'Inter',sans-serif;font-size:16px;font-weight:700;color:#1D1D1F;margin-bottom:16px;">
            Conteúdo do curso
        </h3>
        <?php foreach ($modules as $module): ?>
            <div class="module-section">
                <div class="module-header">
                    <span class="module-title"><?= e($module['title']) ?></span>
                    <span class="text-muted text-xs"><?= count($module['lessons']) ?> aulas</span>
                </div>
                <ul class="lesson-list">
                    <?php foreach ($module['lessons'] as $i => $les): ?>
                        <a href="<?= url('products/' . e($product['slug']) . '/lesson/' . $les['id']) ?>"
                           class="lesson-item <?= ($currentLesson && $les['id'] == $currentLesson['id']) ? 'active' : '' ?> <?= $les['is_completed'] ? 'completed' : '' ?>">
                            <div class="lesson-number"><?= $i + 1 ?></div>
                            <div class="lesson-info">
                                <div class="lesson-title"><?= e($les['title']) ?></div>
                                <div class="lesson-meta">
                                    <?= ucfirst($les['content_type']) ?>
                                    <?php if ($les['duration_minutes'] > 0): ?>
                                        · <?= $les['duration_minutes'] ?> min
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="lesson-check">
                                <?= $les['is_completed'] ? '✓' : '○' ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
</div>
