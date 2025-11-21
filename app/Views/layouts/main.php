<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <?php
    $siteName = "My Blog";
    $pageTitle = $meta_title ?? ($title ?? $siteName);
    $metaDescription =
        $meta_description ?? "A simple blog built with CodeIgniter 4.";
    $ogTitle = $og_title ?? $pageTitle;
    $ogDescription = $og_description ?? $metaDescription;
    $ogType = $og_type ?? "website";
    $ogImage = $og_image ?? base_url("uploads/og-default.jpg");
    $currentUrl = current_url();
    ?>

    <title><?= esc($pageTitle) ?></title>
    <meta name="description" content="<?= esc($metaDescription) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- OpenGraph -->
    <meta property="og:title" content="<?= esc($ogTitle) ?>">
    <meta property="og:description" content="<?= esc($ogDescription) ?>">
    <meta property="og:type" content="<?= esc($ogType) ?>">
    <meta property="og:url" content="<?= esc($currentUrl) ?>">
    <meta property="og:image" content="<?= esc($ogImage) ?>">
    <meta property="og:site_name" content="<?= esc($siteName) ?>">

    <!-- Twitter -->
    <meta name="twitter:card" content="<?= esc(
        $twitter_card ?? "summary_large_image",
    ) ?>">
    <meta name="twitter:title" content="<?= esc($ogTitle) ?>">
    <meta name="twitter:description" content="<?= esc($ogDescription) ?>">
    <meta name="twitter:image" content="<?= esc($ogImage) ?>">

    <!-- Canonical -->
    <link rel="canonical" href="<?= esc($currentUrl) ?>">

    <!-- Pure CSS -->
    <link rel="stylesheet" href="https://unpkg.com/purecss@3.0.0/build/pure-min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <style>
        body { margin:0; font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        .header { padding:1rem 2rem; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center; }
        .content { padding:2rem; max-width:1024px; margin:0 auto; }
        .post-grid { display:grid; grid-template-columns: repeat(auto-fit,minmax(260px,1fr)); gap:1.5rem; }
        .card { border:1px solid #e5e5e5; border-radius:8px; overflow:hidden; background:#fff; }
        .card img { width:100%; height:180px; object-fit:cover; }
        .card-body { padding:1rem; }
        .tag-badge { display:inline-block; padding:0.2rem 0.6rem; border-radius:999px; background:#f5f5f5; font-size:0.75rem; margin-right:0.25rem; }
        footer { border-top:1px solid #eee; padding:1rem 2rem; margin-top:2rem; text-align:center; font-size:0.85rem; color:#777; }
        a { text-decoration:none; color:#0078e7; }
        a:hover { text-decoration:underline; }
    </style>
</head>

<body>
<header class="header">
    <div>
        <a href="<?= site_url(
            "/",
        ) ?>" class="pure-menu-heading"><i class="fa-solid fa-pen-nib"></i> My Blog</a>
    </div>
    <nav>
        <a href="<?= site_url("/") ?>" class="pure-menu-link">Home</a>
    </nav>
</header>

<main class="content">
    <?= $this->renderSection("content") ?>
</main>

<footer>
    &copy; <?= date("Y") ?> My Blog
</footer>

<script src="https://unpkg.com/htmx.org@2.0.3"></script>
</body>
</html>
