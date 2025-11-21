<?php
/** @var array $post */
$img = !empty($post["image_cover"])
    ? base_url("uploads/covers/" . $post["image_cover"])
    : "https://via.placeholder.com/600x300?text=No+Image"; ?>
<div class="card">
    <a href="<?= site_url("posts/" . esc($post["slug"])) ?>">
        <img src="<?= $img ?>" alt="<?= esc($post["title"]) ?>">
    </a>
    <div class="card-body">
        <h3 style="margin-top:0;">
            <a href="<?= site_url("posts/" . esc($post["slug"])) ?>"><?= esc(
    $post["title"],
) ?></a>
        </h3>
        <?php if (!empty($post["tag_names"])): ?>
            <div style="margin-bottom:0.5rem;">
                <?php foreach (explode(",", $post["tag_names"]) as $t): ?>
                    <span class="tag-badge"><?= esc(trim($t)) ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <p><?= esc(word_limiter(strip_tags($post["content"]), 25)) ?></p>
    </div>
</div>
