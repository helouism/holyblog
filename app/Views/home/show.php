<?= $this->extend("layouts/main") ?>

<?= $this->section("content") ?>

<article>
    <h1><?= esc($post["title"]) ?></h1>
    <p class="post-meta" style="margin-top:.25rem; color:var(--muted,#666);">
        <small>
            Published: <?= esc(date('l jS \of F Y h:i:s A', strtotime($post['created_at']))) ?>
        </small>
        <?php if (!empty($post['updated_at']) && $post['updated_at'] !== $post['created_at']): ?>
            <small> <br>Updated: <?= esc(date('l jS \of F Y h:i:s A', strtotime($post['updated_at']))) ?></small>
        <?php endif; ?>
    </p>
    <?php if (!empty($tags)): ?>
        <p>
            <?php foreach ($tags as $tag): ?>
                <a class="tag-badge" href="<?= site_url(
                    "tag/" . esc($tag["slug"]),
                ) ?>">
                    <?= esc($tag["name"]) ?>
                </a>
            <?php endforeach; ?>
        </p>
    <?php endif; ?>



    <?php if (!empty($post["image_cover"])): ?>
        <figure style="margin:0 0 1rem;">
            <img src="<?= base_url("uploads/covers/" . $post["image_cover"]) ?>" alt="<?= esc($post["title"]) ?>"
                style="max-width:100%; border-radius:8px;">
        </figure>
    <?php endif; ?>

    <div>
        <?= $post["content"] ?> <!-- Summernote already produces HTML -->
    </div>
</article>

<?= $this->endSection() ?>