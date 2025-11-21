<?php
/** @var CodeIgniter\Pager\Pager $pager */
/** @var string|null $q */

$details = $pager->getDetails();
if (!$details) {
    return;
}

$current = (int) $details["currentPage"];
$pages = (int) $details["pageCount"];

if ($pages <= 1) {
    return;
}
?>
<nav style="margin-top:1rem; display:flex; gap:0.25rem; flex-wrap:wrap;">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <?php
        $isActive = $i === $current;
        $url = site_url(
            "admin/posts?page=" . $i . ($q ? "&q=" . urlencode($q) : ""),
        );
        ?>
        <a href="<?= $url ?>"
           class="pure-button <?= $isActive ? "pure-button-primary" : "" ?>"
           hx-get="<?= $url ?>"
           hx-target="#posts-table"
           hx-push-url="true">
            <?= $i ?>
        </a>
    <?php endfor; ?>
</nav>
