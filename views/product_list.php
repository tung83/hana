<?php
$pageSize = 16;
$rows = tt(DB::select("SELECT * FROM products"));
$pager = new Pager($rows, gett('page'), $pageSize);
?>
<? render('home_promo') ?>
<div class="container">
    <div class="section__head"><span>SẢN PHẨM</span></div>
    <? render('product_card_list', $pager->get()) ?>
    <? if ($pager->count() > 1) { ?>
        <a href="#" class="btn-more" data-url="products?" data-pages="<?= $pager->count() ?>"><?= t("Xem thêm", "Read more") ?>...</a>
    <? } ?>
</div>
