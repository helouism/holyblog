<?php
/** @var array $post */
$img = !empty($post["image_cover"])
    ? base_url("uploads/covers/" . $post["image_cover"])
    : "https://via.placeholder.com/600x300?text=No+Image"; ?>
<div class="card">
    <a href="<?= site_url("posts/" . esc($post["slug"])) ?>">
        <img class="pure-img" src="<?= $img ?>" alt="<?= esc($post["title"]) ?>">
    </a>
    <div class="card-body">
        <h3 style="margin-top:0;">
            <a href="<?= site_url("posts/" . esc($post["slug"])) ?>"><?= esc(
                    $post["title"],
                ) ?></a>
        </h3>
        <p class="post-meta" style="margin-top:.25rem; color:var(--muted,#666);">
            <small>
                Published by <?= esc($post["username"]) ?> on
                <?= esc(date('F j, Y, g:i a', strtotime($post['created_at']))) ?>
            </small>
            <?php if (!empty($post['updated_at']) && $post['updated_at'] !== $post['created_at']): ?>
                <small> — Updated: <?= esc(date('F j, Y, g:i a', strtotime($post['updated_at']))) ?></small>
            <?php endif; ?>
        </p>
        <?php if (!empty($post["tag_names"])): ?>
            <div style="margin-bottom:0.5rem;">
                <?php foreach (explode(",", $post["tag_names"]) as $t): ?>
                    <span class="tag-badge"><?= esc(trim($t)) ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>