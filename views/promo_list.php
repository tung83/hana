<?php

$type = get_view();
$rows = tt(DB::select("SELECT * FROM news WHERE active=1 AND view='promo'"));

$h1 = t( "Khuyến mãi", "News" );

?>
<div class="container"><div class="row">
    <main class="col-sm-9">
        <div class="section__head"><span><?= $h1 ?></span></div>
        <ul class="news-list card promo-list">
            <?php
            foreach ( $rows as $row ) {
                extract( (array) $row );
                $url = url('promo', $id, $title);
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
        <?= render('top_products_widget') ?>
    </aside>
</div></div>
