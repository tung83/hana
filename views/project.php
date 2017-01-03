<?php

$current_id = gett('id');
$current = tt(DB::select_one("SELECT * FROM project WHERE id=${current_id} AND active=1"));
extract( (array) $current );

if (empty($current))
    return redirect(url('services'));

$others = tt(DB::select("SELECT * FROM project WHERE active=1 AND id<>${current_id} ORDER BY dates DESC LIMIT 4"));

$photos = tt(DB::select("SELECT * FROM project_photos WHERE pId=${current_id} AND active=1 ORDER BY ind"));

?>

<div class="container">
    <main>
        <div class="big-caption sm"><?= t( "Dự án", "Projects" ) ?></div>
        <article>
            <h1 class="page-title"><?= $title ?></h1>

            <div id="carousel-container-2">
                <? foreach ( $photos as $photo ) { ?>
                    <div>
                        <div class="photo">
                            <img src="<?= PATH_UPLOAD.$photo->img ?>" alt="">
                        </div>
                    </div>
                <? } ?>
            </div>
            <div id="carousel-container-3">
                <? foreach ( $photos as $photo ) { ?>
                    <div>
                        <div class="thumb">
                            <img src="<?= PATH_UPLOAD.$photo->img ?>" alt="">
                        </div>
                    </div>
                <? } ?>
            </div>

            <h3 class="mid-caption"><?= t("Thông tin về dự án", "Information") ?></h3>
            <div class="page-content"><?= $content ?></div>
        </article>
        <div id="other-projects">
            <h3><?= t("Dự án khác", "Other Projects") ?></h3>
            <ul id="project_list">
                <? foreach ($others as $row) {
                    extract((array)$row);
                    $url = url('projects', $id, $title);
                    ?>
                    <li>
                        <a href="<?= $url ?>" class="thumb">
                            <? if (empty($img)) { ?>
                                <img data-src="holder.js/300x230" alt="">
                            <? } else { ?>
                                <img src="<?= PATH_UPLOAD.$img ?>" alt="">
                            <? } ?>
                        </a>
                        <a href="<?= $url ?>" class="title"><?= $title ?></a>
                    </li>
                <? } ?>
            </ul>
        </div>
    </main>
    <aside>
        <?= render('list_projects_widget') ?>
        <?= render('top_projects_widget') ?>
    </aside>
</div>
<link rel="stylesheet" href="/bower_components/slick-carousel/slick/slick.css">
<link rel="stylesheet" href="/bower_components/slick-carousel/slick/slick-theme.css">
<script type="text/javascript" src="/bower_components/slick-carousel/slick/slick.min.js"></script>
<script type="text/javascript">
    $(function() {
        $('#carousel-container-2').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '#carousel-container-3',
            autoplay: true,
            autoplaySpeed: 2000,
        })
        $('#carousel-container-3').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            asNavFor: '#carousel-container-2',
            focusOnSelect: true,
            arrows: true,
            centerMode: true,
        })
    })
</script>
