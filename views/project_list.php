<div class="container">
    <main>
        <div class="big-caption"><?= t( "Dự án tiêu biểu", "Projects" ) ?></div>
        <ul id="project_list">
            <?
            $rows = tt(DB::select("SELECT * FROM project WHERE active=1"));
            foreach ( $rows as $row ) {
                extract( (array) $row );
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
    </main>
    <aside>
        <?= render('list_projects_widget') ?>
        <?= render('top_projects_widget') ?>
    </aside>
</div>
