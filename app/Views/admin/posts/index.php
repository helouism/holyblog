<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>

<div class="pure-g">
    <div class="pure-u-1 pure-u-md-1-2">
        <a href="<?= route_to(
            "admin.posts.create",
        ) ?>" class="pure-button pure-button-primary">
            <i class="fa-solid fa-plus"></i> New Post
        </a>
    </div>

    <div class="pure-u-1 pure-u-md-1-2" style="text-align:right;">
        <!-- SEARCH FORM -->
        <form class="pure-form"
              style="display:inline-block;"
              hx-get="<?= route_to("admin.posts.index") ?>"
              hx-target="#posts-table"
              hx-push-url="true">
            <input type="search"
                   name="q"
                   value="<?= esc($q ?? "") ?>"
                   placeholder="Search posts...">
            <button type="submit" class="pure-button">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>
</div>

<div class="pure-g" style="margin-top:1rem;">
    <div class="pure-u-1" id="posts-table">
        <?= view("admin/posts/_table", [
            "posts" => $posts,
            "pager" => $pager,
            "q" => $q ?? null,
        ]) ?>
    </div>
</div>

<?= $this->endSection() ?>
