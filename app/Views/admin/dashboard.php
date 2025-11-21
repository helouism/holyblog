<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>

<style>
    .metric-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .metric-card {
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        padding: 1rem;
        background: #fff;
    }
    .metric-card h3 {
        margin: 0 0 0.25rem;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #777;
    }
    .metric-card .value {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    .metric-card .hint {
        font-size: 0.8rem;
        color: #999;
    }

    .recent-table th,
    .recent-table td {
        font-size: 0.9rem;
    }

    .badge-status {
        padding: 0.15rem 0.5rem;
        border-radius: 999px;
        font-size: 0.75rem;
    }
    .badge-status.published { background:#def3e4; color:#207245; }
    .badge-status.draft { background:#fdf2d7; color:#946200; }
</style>

<div class="metric-grid">
    <div class="metric-card">
        <h3><i class="fa-solid fa-file-lines"></i> Total Posts</h3>
        <div class="value"><?= esc($totalPosts) ?></div>
        <div class="hint">All posts in the system</div>
    </div>

    <div class="metric-card">
        <h3><i class="fa-solid fa-circle-check"></i> Published</h3>
        <div class="value"><?= esc($publishedPosts) ?></div>
        <div class="hint">Visible on the public site</div>
    </div>

    <div class="metric-card">
        <h3><i class="fa-solid fa-circle-half-stroke"></i> Drafts</h3>
        <div class="value"><?= esc($draftPosts) ?></div>
        <div class="hint">Still being worked on</div>
    </div>
</div>

<h3>Recent Posts</h3>

<?php if (!empty($recentPosts)): ?>
    <table class="pure-table pure-table-horizontal recent-table" style="width:100%; margin-top:0.5rem;">
        <thead>
        <tr>
            <th>Title</th>
            <th>Status</th>
            <th>Tags</th>
            <th>Created At</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($recentPosts as $post): ?>
            <tr>
                <td><?= esc($post["title"]) ?></td>
                <td>
                    <span class="badge-status <?= esc($post["status"]) ?>">
                        <?= ucfirst($post["status"]) ?>
                    </span>
                </td>
                <td><?= esc($post["tag_names"] ?? "") ?></td>
                <td><?= esc($post["created_at"]) ?></td>
                <td style="text-align:right; white-space:nowrap;">
                    <a href="<?= route_to(
                        "admin.posts.edit",
                        $post["id"],
                    ) ?>" class="pure-button pure-button-primary">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No posts yet. <a href="<?= route_to(
        "admin.posts.create",
    ) ?>">Create your first post</a>.</p>
<?php endif; ?>

<?= $this->endSection() ?>
