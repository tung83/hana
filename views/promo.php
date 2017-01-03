<?php

$type = get_view();

$current_id = gett('id');
$current = tt(DB::select_one("SELECT * FROM news WHERE id={$current_id} AND active=1 AND view='promo'"));
extract( (array) $current );

if (empty($current))
    return redirect(url('promo'));

$others = tt(DB::select("SELECT * FROM news WHERE active=1 AND id<>{$current_id} AND view='promo' ORDER BY dates DESC LIMIT 4"));

$h1 = t( "Khuyến mãi", "Promo" );
$h3 = t("Khuyến mãi khác", "Other Promo");

?>
<div class="container"><div class="row">
    <main class="col-sm-9">
        <div class="section__head"><span><?= $h1 ?></span></div>
        <article class="card">
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
        <?= render('top_products_widget') ?>
    </aside>
</div></div>
