<section id="home-products">
    <div class="container">
        <div class="section__head"><span>SẢN PHẩM BÁN CHẠY</span></div>
        <? render('product_card_list', tt(DB::select("SELECT * FROM products WHERE active=1 LIMIT 8"))) ?>
        <a href="<?= url('products') ?>" class="btn-more"><?= t("Xem thêm", "Read more") ?>...</a>
    </div>
</section>
