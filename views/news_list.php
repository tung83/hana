<?php

$type = get_view();
$rows = tt(DB::select("SELECT * FROM news WHERE active=1 AND view='{$type}'"));

$h1 = t( "Tin tức", "News" );

if ($type == 'recruitment') {
    $h1 = t("Tuyển dụng", "Recruitment");
}

if ($type == 'world') {
    $h1 = t("Nhìn ra thế giới");
}
?>
<div class="container"><div class="row">
    <main class="col-sm-9">
        <div class="section__head"><span><?= $h1 ?></span></div>
        <ul class="news-list card">
            <?php
            foreach ( $rows as $row ) {
                extract( (array) $row );
                $url = url($type, $id, $title);
            ?>
                <li>
                    <a href="<?= $url ?>" class="thumb">
                        <? if (empty($img)) { ?>
                            <img data-src="holder.js/200x150" alt="">
                        <? } else { ?>
                            <img src="<?= PATH_UPLOAD.$img ?>" alt="">
                        <? } ?>
                    </a>
                    <a href="<?= $url ?>" class="title"><?= $title ?></a>
                    <span class="date"><?= fdate($dates) ?></span>
                    <div class="sum"><?= $sum ?></div>
                </li>
            <?php } ?>
        </ul>
    </main>
    <aside class="col-sm-3">
        <?= render('hot_news_widget') ?>
    </aside>
</div></div>
