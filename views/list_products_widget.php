<div id="listproducts">
    <div id="listproducts_top">
        <div id="listproducts_bottom">
            <div id="listproducts_display">
                <div id="listproducts_title"><span class="title"><?= t("Sản Phẩm", "List Products") ?></span></div>
                <div id="listproducts_list">
                    <div id="list">
                        <ul>
                            <?php
                            $rows = tt(DB::select("SELECT * FROM categories c WHERE active=1 AND (SELECT COUNT(*) FROM subcategories s WHERE s.cId=c.id) > 0 ORDER BY ind"));
                            foreach ( $rows as $row ) {
                                extract( (array) $row );
                                $url = url('products/c', $id, $title);
                                $active = ( gett('c') && $id == gett('id') ) ? ' class="h"' : '';
                            ?>
                                <li<?= $active ?>><a href="<?= $url ?>"><?= $title ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
