<?php
$prod_id = gett('id');
$prod = tt(DB::select_one("SELECT * FROM products WHERE active=1 AND id=${prod_id}"));
extract( (array) $prod );

$sum = nl2br($sum);
$img = !empty($img) ? PATH_UPLOAD . $img : '';
$cart = "/cart/add?id={$id}";

$sub = tt(DB::select_one("SELECT * FROM subcategories WHERE id=" . $prod->sId));
$cat = tt(DB::select_one("SELECT * FROM categories WHERE id=" . $sub->cId));
$cat_url = url('products/c', $cat->id, $cat->title);
$sub_url = url('products/s', $sub->id, $sub->title);

$photos = DB::select("SELECT * FROM product_photos WHERE pId={$prod_id} ORDER BY ind");

$others = tt(DB::select("SELECT * FROM products WHERE id<>${prod_id} AND sId=${sId} LIMIT 8"));
?>
<? render('home_promo') ?>
<div class="container">
    <div class="section__head"><span>SẢN PHẨM</span></div>
    <div class="row"><div class="col-sm-12">
        <main class="card">
            <div class="row">
                <div class="col-sm-6">
                    <div class="product-l">
                        <div id="photo">
                            <? foreach ($photos as $photo) { ?>
                                <div>
                                    <div class="thumb" href="#"><img src="<?= PATH_UPLOAD.$photo->img ?>" alt=""></div>
                                </div>
                            <? } ?>
                        </div>
                        <div id="photos">
                            <? foreach ($photos as $photo) { ?>
                                <div class="thumb" href="#"><img src="<?= PATH_UPLOAD.$photo->img ?>" alt=""></div>
                            <? } ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="product-r">
                        <h1 class="title"><?= $title ?></h1>
                        <hr>
                        <dl class="info dl-horizontal">
                            <dt>Mã SP:</dt>
                            <dd><?= $sku ?></dd>
                            <dt>Đơn giá:</dt>
                            <dd><?= price($price) ?></dd>
                            <dt>Mô tả:</dt>
                            <dd><?= $sum ?></dd>
                        </dl>
                        <hr>
                        <div class="color">
                            Màu sắc:
                            <label>
                                <input type="radio" name="color" value="">
                                <span style="background: #ba8b67;"></span>
                            </label>
                            <label>
                                <input type="radio" name="color" value="">
                                <span style="background: #ff444f"></span>
                            </label>
                            <label>
                                <input type="radio" name="color" value="">
                                <span style="background: #715c5d"></span>
                            </label>
                        </div>
                        <a href="<?= $cart ?>" class="btn-order"></a>
                        <hr>
                        <h2>CHI TIẾT SẢN PHẨM</h2>
                        <div class="details"><?= $content ?></div>
                    </div>
                </div>
            </div>
        </main>
    </div></div>
    <div id="other-products">
        <h3>SẢN PHẨM KHÁC</h3>
        <? render('product_card_list', $others) ?>
    </div>
</div>
<link rel="stylesheet" href="/bower_components/slick-carousel/slick/slick.css">
<link rel="stylesheet" href="/bower_components/slick-carousel/slick/slick-theme.css">
<script type="text/javascript" src="/bower_components/slick-carousel/slick/slick.min.js"></script>
<script type="text/javascript">
    $(function() {
        $('#photo').slick({
            infinite: true,
            speed: 500,
            fade: true,
            cssEase: 'linear',
            asNavFor: '#photos',
            autoplay: true,
            autoplaySpeed: 2000,
        })
        $('#photos').slick({
            infinite: true,
            speed: 300,
            focusOnSelect: true,
            arrows: true,
            centerMode: true,
            variableWidth: true,
            asNavFor: '#photo'
        })
    })
</script>
