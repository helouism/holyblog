<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>

<form action="<?= route_to(
    "admin.posts.update",
    $post["id"],
) ?>" method="post" enctype="multipart/form-data" class="pure-form pure-form-stacked">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="PUT">

    <fieldset>
        <legend>Edit Post</legend>

        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="<?= old(
            "title",
            $post["title"],
        ) ?>" class="pure-input-1">

        <label for="slug">Slug</label>
        <input type="text" name="slug" id="slug" value="<?= old(
            "slug",
            $post["slug"],
        ) ?>" class="pure-input-1">

        <label for="meta_description">Meta Description</label>
        <input type="text" name="meta_description" id="meta_description" value="<?= old(
            "meta_description",
            $post["meta_description"],
        ) ?>" class="pure-input-1">

        <label for="content">Content</label>
        <textarea name="content" id="content-editor"><?= old(
            "content",
            $post["content"],
        ) ?></textarea>

        <label for="tags">Tags (comma separated)</label>
        <input type="text" name="tags" id="tags" value="<?= old(
            "tags",
            $post["tags_string"],
        ) ?>" class="pure-input-1">

        <label for="image_cover">Image Cover</label>

        <?php if (!empty($post['image_cover'])): ?>
            <div style="margin-bottom:0.5rem;">
                <img src="<?= base_url('uploads/covers/' . $post['image_cover']) ?>" alt=""
                    style="max-width:150px; border-radius:4px;">
            </div>
        <?php endif; ?>

        <input type="file" name="image_cover" id="image_cover" class="filepond pure-input-1" />
        <small style="color:#777;">Upload a new image to replace the existing cover (max 3 MB).</small>

        <label for="status">Status</label>
        <select name="status" id="status" class="pure-input-1">
            <option value="draft" <?= old("status", $post["status"]) === "draft"
                ? "selected"
                : "" ?>>Draft</option>
            <option value="published" <?= old("status", $post["status"]) ===
                "published"
                ? "selected"
                : "" ?>>Published
            </option>
        </select>

        <label for="is_featured">
            <input type="checkbox" name="is_featured" id="is_featured" value="1" <?= old(
                "is_featured",
                $post["is_featured"],
            )
                ? "checked"
                : "" ?>>
            Featured
        </label>

        <button type="submit" class="pure-button pure-button-primary">
            <i class="fa-solid fa-floppy-disk"></i> Update
        </button>
    </fieldset>
</form>

<?= $this->endSection() ?>

<?= $this->section("scripts") ?>
<script>
    $(function () {
        $('#content-editor').summernote({
            height: 300
        });
    });
</script>
<?= $this->endSection() ?>