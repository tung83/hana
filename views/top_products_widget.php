<div id="top-products-widget">
    <ul>
        <?
        $rows = tt(DB::select("SELECT * FROM products WHERE active=1 LIMIT 4"));
        foreach ( $rows as $row ) {
        ?>
            <li>
                <? render('product_card_item', $row) ?>
            </li>
        <? } ?>
    </ul>
</div>
