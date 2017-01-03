<div class="container">
    <main>
        <div class="big-caption sm"><?= t( "Dịch vụ", "Services" ) ?></div>
        <article>
            <ul class="news-list">
                <?php
                $rows = tt(DB::select("SELECT * FROM service WHERE active=1"));
                foreach ( $rows as $row ) {
                    extract( (array) $row );
                    $url = url('services', $id, $title);
                ?>
                    <li>
                        <a href="<?= $url ?>" class="thumb">
                            <? if (empty($img)) { ?>
                                <img data-src="holder.js/200x150" alt="">
                            <? } else { ?>
                                <img src="<?= PATH_UPLOAD.$img ?>" alt="">
                            <? } ?>
                        </a>
                        <a href="<?= $url ?>" class="title"><?= $title ?></a>
                        <div class="sum"><?= $sum ?></div>
                    </li>
                <?php } ?>
            </ul>
        </article>
    </main>
    <aside>
        <?= render('list_projects_widget') ?>
        <?= render('top_projects_widget') ?>
    </aside>
</div>
