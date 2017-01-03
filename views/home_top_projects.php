<div class="container">
<div id="top-projects">
    <div class="block-caption"><?= t("Dự Án Tiêu Biểu", "Top Projects") ?></div>
    <div id="carousel-container-1">
        <?php
        $rows = tt(DB::select("SELECT * FROM project WHERE active=1 ORDER BY dates DESC LIMIT 8"));
        foreach ( $rows as $row ) {
            extract( (array) $row );
            $url = url('projects', $id, $title);
        ?>
            <div>
                <a href="<?= $url ?>" class="thumb">
                    <? if (empty($img)) { ?>
                        <img data-src="holder.js/215x159"/>
                    <? } else { ?>
                        <img src="<?= PATH_UPLOAD.$img ?>"/>
                    <? } ?>
                </a>
            </div>
        <?php } ?>
    </div>
</div>
</div>
<link rel="stylesheet" href="/bower_components/slick-carousel/slick/slick.css">
<link rel="stylesheet" href="/bower_components/slick-carousel/slick/slick-theme.css">
<script type="text/javascript" src="/bower_components/slick-carousel/slick/slick.min.js"></script>
<script type="text/javascript">
    $(function() {
        $('#carousel-container-1').slick({
            slidesToShow: 4,
            slidesToScroll: 4
        })
    })
</script>
