<?php
$q = "'%".gett('q')."%'";
$all = tt(DB::select("SELECT * FROM products WHERE title LIKE $q OR eTitle LIKE $q OR content LIKE $q OR eContent LIKE $q"));
$pager = new Pager($all, gett('page'), 12, '«', '', '', '»');
?>
<div class="container">
        <div class="section__head"><span><?= t( "Tìm kiếm", "Search" ) ?></span></div>
            <center style="margin: 0 0 20px;">
                <?= t("<b>Kết quả</b>: Tìm thấy", "<b>Result</b>: Found") ?>
                <?= count($all) ?>
                <?= t("kết quả cho từ khóa", "result(s) with") ?>
                <font color="#f00">"<?= gett('q') ?>"</font>
            </center>
            <? render('product_card_list', $pager->get()) ?>
            <div class="pages">
                <?= $pager->render() ?>
            </div>
        </article>
</div>
