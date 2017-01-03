<?php
$pageSize = 16;
$cId = gett('id');
$rows = tt(DB::select("SELECT p.* FROM products p JOIN subcategories s ON s.id=p.sId AND s.active=1 JOIN categories c ON c.id=s.cId AND c.id={$cId} WHERE p.active=1"));
$pager = new Pager($rows, gett('page'), $pageSize);
?>
<? render('home_promo') ?>
<div class="container">
    <div class="section__head"><span>SẢN PHẨM</span></div>
    <? render('product_card_list', $pager->get()) ?>
    <? if ($pager->count() > 1) { ?>
        <a href="#" class="btn-more" data-url="products?c=1&amp;id=<?= $cId ?>" data-pages="<?= $pager->count() ?>"><?= t("Xem thêm", "Read more") ?>...</a>
    <? } ?>
</div>
