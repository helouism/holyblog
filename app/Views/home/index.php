<?= $this->extend("layouts/main") ?>

<?= $this->section("content") ?>

<?php if (!empty($activeTag)): ?>
    <h2>Posts tagged: <?= esc($activeTag["name"]) ?></h2>
<?php else: ?>
    <h2>Featured Posts</h2>
<?php endif; ?>

<?php if (!empty($featured) && empty($activeTag)): ?>
    <div class="post-grid" style="margin-bottom:2rem;">
        <?php foreach ($featured as $post): ?>
            <?= view("components/post_card", ["post" => $post]) ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<h2>Latest Posts</h2>
<?php if (!empty($latest)): ?>
    <div class="post-grid">
        <?php foreach ($latest as $post): ?>
            <?= view("components/post_card", ["post" => $post]) ?>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No posts yet.</p>
<?php endif; ?>

<?= $this->endSection() ?>
