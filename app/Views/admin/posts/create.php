<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>

<form action="<?= route_to(
    "admin.posts.store",
) ?>" method="post" enctype="multipart/form-data" class="pure-form pure-form-stacked">
    <?= csrf_field() ?>

    <fieldset>
        <legend>Create Post</legend>

        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="<?= old(
            "title",
        ) ?>" class="pure-input-1" required>



        <label for="slug">Slug</label>
        <input type="text" name="slug" id="slug" value="<?= old(
            "slug",
        ) ?>" class="pure-input-1" required>

        <label for="meta_description">Meta Description</label>
        <input type="text" name="meta_description" id="meta_description" value="<?= old(
            "meta_description",
        ) ?>" class="pure-input-1" required>

        <label for="content">Content</label>
        <textarea name="content" id="content-editor"><?= old(
            "content",
        ) ?></textarea>

        <label for="tags">Tags (comma separated)</label>
        <input type="text" name="tags" id="tags" value="<?= old(
            "tags",
        ) ?>" class="pure-input-1" required>

        <label for="image_cover">Image Cover</label>
        <input type="file" name="image_cover" id="image_cover" class="filepond pure-input-1" />
        <small style="color:#777;">Maximum 3 MB. JPG, PNG, GIF, WEBP.</small>


        <label for="status">Status</label>
        <select name="status" id="status" class="pure-input-1">
            <option value="draft" <?= old("status") === "draft"
                ? "selected"
                : "" ?>>Draft</option>
            <option value="published" <?= old("status") === "published"
                ? "selected"
                : "" ?>>Published</option>
        </select>

        <label for="is_featured">
            <input type="checkbox" name="is_featured" id="is_featured" value="1" <?= old(
                "is_featured",
            )
                ? "checked"
                : "" ?>>
            Featured
        </label>

        <button type="submit" class="pure-button pure-button-primary">
            <i class="fa-solid fa-floppy-disk"></i> Save
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