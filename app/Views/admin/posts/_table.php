<table class="pure-table pure-table-horizontal" style="width:100%;">
    <thead>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Tags</th>
        <th>Status</th>
        <th>Featured</th>
        <th>Created</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <tr id="post-row-<?= $post["id"] ?>">
                <td><?= $post["id"] ?></td>
                <td><?= esc($post["title"]) ?></td>
                <td><?= esc($post["tag_names"] ?? "") ?></td>
                <td>
                    <!-- INLINE STATUS TOGGLE -->
                    <button
                        class="badge-status <?= esc(
                            $post["status"],
                        ) ?> pure-button"
                        style="border:none; cursor:pointer; padding:0.25rem 0.7rem;"
                        hx-post="<?= route_to(
                            "admin.posts.toggleStatus",
                            $post["id"],
                        ) ?>"
                        hx-target="#posts-table"
                        hx-swap="outerHTML"
                    >
                        <?= ucfirst($post["status"]) ?>
                    </button>
                </td>
                <td><?= $post["is_featured"] ? "Yes" : "No" ?></td>
                <td><?= esc($post["created_at"]) ?></td>
                <td style="text-align:right; white-space:nowrap;">
                    <a href="<?= route_to(
                        "admin.posts.edit",
                        $post["id"],
                    ) ?>" class="pure-button pure-button-primary">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <button
                        class="pure-button button-error"
                        hx-delete="<?= route_to(
                            "admin.posts.delete",
                            $post["id"],
                        ) ?>"
                        hx-target="#posts-table"
                        hx-swap="outerHTML"
                        hx-confirm="Delete this post?"
                    >
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="7" style="text-align:center;">No posts found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<!-- HTMX-aware pagination -->
<?= view("components/pagination_links", ["pager" => $pager, "q" => $q ?? null]) ?>
