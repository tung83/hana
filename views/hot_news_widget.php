<div id="hot-news-widget" class="card">
    <div class="section__head">TIN HOT</div>
    <ul>
        <?
        $rows = tt(DB::select("SELECT * FROM news LIMIT 8"));
        foreach ($rows as $row) {
            extract((array)$row);
            $id = $row->id;
            $title = $row->title;
            $img = empty($row->img) ? '' : PATH_UPLOAD.$row->img;
            $url = url('news', $id, $title);
        ?>
            <li>
                <a href="<?= $url ?>" class="thumb">
                    <? if (empty($img)) { ?>
                        <img data-src="holder.js/95x76" alt="">
                    <? } else { ?>
                        <img src="<?= $img ?>" alt="">
                    <? } ?>
                </a>
                <a href="<?= $url ?>" class="title"><?= $title ?></a>
            </li>
        <? } ?>
    </ul>
</div>
