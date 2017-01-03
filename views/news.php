<?php

$type = get_view();

$current_id = gett('id');
$current = tt(DB::select_one("SELECT * FROM news WHERE id={$current_id} AND active=1 AND view='{$type}'"));
extract( (array) $current );

if (empty($current))
    return redirect(url('news'));

$others = tt(DB::select("SELECT * FROM news WHERE active=1 AND view='{$type}' AND id<>{$current_id} ORDER BY dates DESC LIMIT 4"));

$h1 = t( "Tin tức", "News" );
$h3 = t("Tin tức khác", "Other News");

if ($type == 'recruitment') {
    $h1 = t("Tuyển dụng", "Recruitment");
    $h3 = t("Tuyển dụng khác", "Other Recruitments");
}

if ($type == 'world') {
    $h1 = t("Nhìn ra thế giới");
}

?>
<div class="container"><div class="row">
    <main class="col-sm-9">
        <div class="section__head"><span><?= $h1 ?></span></div>
        <article class="card">
            <h1><?= $title ?></h1>
            <?= close_html_tags( $current->content ) ?>
            <? if (count($others)) { ?>
                <div id="other-news">
                    <hr>
                    <h3><?= $h3 ?></h3>
                    <ul id="service_list">
                        <? foreach ($others as $row) {
                            extract((array)$row);
                            $url = url($type, $id, $title);
                            ?>
                            <li>
                                <? /*
                                <a href="<?= $url ?>" class="thumb">
                                    <? if (empty($img)) { ?>
                                        <img data-src="holder.js/300x230" alt="">
                                    <? } else { ?>
                                        <img src="<?= PATH_UPLOAD.$img ?>" alt="">
                                    <? } ?>
                                </a>
                                */ ?>
                                <a href="<?= $url ?>" class="title"><?= $title ?></a>
                            </li>
                        <? } ?>
                    </ul>
                </div>
            <? } ?>
        </article>
    </main>
    <aside class="col-sm-3">
        <?= render('hot_news_widget') ?>
    </aside>
</div></div>
