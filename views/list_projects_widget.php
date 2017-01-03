<div id="list-projects-a">
    <div class="caption"><?= t("Danh Mục Dự Án", "List Projects") ?></div>
    <ul>
        <?php
        $rows = tt(DB::select("SELECT * FROM project WHERE active=1 ORDER BY dates DESC LIMIT 6"));
        foreach ( $rows as $row ) {
            extract( (array) $row );
            $url = url('projects', $id, $title);
            $active = $id == gett('id') ? ' class="h"' : '';
        ?>
            <li<?= $active ?>><a href="<?= $url ?>"><?= $title ?></a></li>
        <?php } ?>
    </ul>
</div>
