<?php
if (gett('id')) {
    $current = tt(DB::select_one("SELECT * FROM about WHERE id=".gett('id')." AND active=1"));
    $rows = tt(DB::select("SELECT * FROM about WHERE id<>".gett('id')." AND active=1"));
} else {
    $rows = tt(DB::select("SELECT * FROM about WHERE active=1"));
    $current = array_shift($rows);
}
?>
<div class="container">
    <div class="row">
        <main class="col-sm-9">
            <div class="section__head"><span><?= t("Giới thiệu", "About") ?></span></div>
            <article class="card"><?= close_html_tags( $current->content ) ?></article>
        </main>
        <aside class="col-sm-3">
            <?= render('top_products_widget') ?>
        </aside>
    </div>
</div>
