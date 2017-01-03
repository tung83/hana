<?php

$current_id = gett('id');
$current = tt(DB::select_one("SELECT * FROM service WHERE id=${current_id} AND active=1"));
extract( (array) $current );

if (empty($current))
    return redirect(url('services'));

$others = tt(DB::select("SELECT * FROM service WHERE active=1 AND id<>${current_id} ORDER BY dates DESC LIMIT 4"));

?>
<div class="container">
    <main>
        <div class="big-caption sm"><?= t( "Dịch vụ", "Services" ) ?></div>
                <article>
            <h1 class="page-title"><?= $title ?></h1>
            <div class="page-content"><?= $content ?></div>
        </article>
        <div id="other-projects">
            <h3><?= t("Dịch vụ khác", "Other Services") ?></h3>
            <ul id="service_list">
                <? foreach ($others as $row) {
                    extract((array)$row);
                    $url = url('services', $id, $title);
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
    </main>
    <aside>
        <?= render('list_projects_widget') ?>
        <?= render('top_projects_widget') ?>
    </aside>
</div>
