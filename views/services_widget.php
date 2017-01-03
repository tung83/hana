<div id="hotnews">
    <div id="hotnews_top">
    <div id="hotnews_bottom">
        <div id="hotnews_display">
            <div id="hotnews_title">
                <span class="title"><?= t("Dịch vụ", "Services") ?></span>
            </div>
            <div id="hotnews_list">
                <div id="list">
                    <ul>
                        <?php
                        $rows = tt(DB::select("SELECT * FROM service ORDER BY dates DESC LIMIT 4"));
                        foreach ( $rows as $row ) {
                            extract( (array) $row );
                            $url = url('services', $id, $title);
                        ?>
                            <li><a href="<?= $url ?>"><?= $title ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
