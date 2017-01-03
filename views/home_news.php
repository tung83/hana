<div id="home-news">
    <div class="block-caption">
        <?= t('Tin tức mới', 'News') ?>
    </div>
    <ul>
        <?
        $rows = tt(DB::select("SELECT * FROM news WHERE active=1"));
        foreach ( $rows as $row ) {
            extract( (array) $row );
            $url = url('news', $id, $title);
        ?>
            <li>
                <a href="<?= $url ?>" class="thumb">
                    <? if (empty($img)) { ?>
                        <img data-src="holder.js/130x97">
                    <? } else { ?>
                        <img src="<?= PATH_UPLOAD.$img ?>"/>
                    <? } ?>
                </a>
                <a href="<?= $url ?>" class="title"><?= $title ?></a>
                <div class="sum"><?= $sum ?></div>
            </li>
        <? } ?>
    </ul>
    <div class="readmore">
        <a href="<?= url('news') ?>"><?= t("Xem thêm", "Read more") ?>...</a>
    </div>
</div>
