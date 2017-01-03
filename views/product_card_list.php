<div class="product-cards row">
	<? foreach ($data as $row) { ?>
		<div class="col-sm-3">
			<? render('product_card_item', $row) ?>
		</div>
	<? } ?>
</div>
