<div id="wowslider-container1">
    <div class="ws_images">
        <ul>
<?
    $slides = DB::select( "SELECT * FROM home_slider WHERE active=1 ORDER BY ind DESC" );
    foreach ( $slides as $row ) {
        extract( (array)$row );

        if ( !empty( $lnk ) ) {
            ?>
            <li><a href="<?= $url ?>"><img src="<?= PATH_UPLOAD.$img ?>" alt="<?= $title ?>" title="<?= $title ?>"/></a></li>
            <?
        } else {
            ?>
            <li><img src="<?= PATH_UPLOAD.$img ?>" alt="<?= $title ?>" title="<?= $title ?>"/></li>
            <?
        }
    }
?>
        </ul>
    </div>
    <div class="ws_bullets">
        <? for ( $i=1; $i <= count($slides); $i++ ) { ?>
            <a><?= $i ?></a>
        <? } ?>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="/js/wow/style.css" />
<style type="text/css">
    * html #wowslider-container1 {
        width: <?= SLIDER_WIDTH ?>px;
    }
    #wowslider-container1,
    #wowslider-container1 .ws_images,
    #wowslider-container1 .ws_images ul a,
    #wowslider-container1 .ws_images > div > img {
        max-height: <?= SLIDER_HEIGHT ?>px;
    }
</style>
<script type="text/javascript" src="/js/wow/wowslider.js"></script>
<script type="text/javascript" src="/js/wow/script.js"></script>
<script type="text/javascript">
    jQuery(function ($) {
        $('#wowslider-container1').wowSlider({
            effect: 'glass_parallax,parallax,seven,kenburns,blur,domino,slices,blast,blinds,basic,fade,fly,stack,stack_vertical',
            prev: '',
            next: '',
            duration: 2000,
            delay: 4500,
            width: <?= SLIDER_WIDTH ?>,
            height: <?= SLIDER_HEIGHT ?>,
            autoPlay: true,
            autoPlayVideo: false,
            playPause: false,
            stopOnHover: false,
            loop: false,
            bullets: 1,
            caption: false,
            captionEffect: 'parallax',
            controls: false,
            controlsThumb: false,
            responsive: 2,
            fullScreen: false,
            gestures: 2,
            onBeforeStep: 0,
            images: 0
        });
    });
</script>
