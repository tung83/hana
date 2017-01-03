<?php
extract((array)$data);
$title = $data->title;
$id = $data->id;
$url = url('products', $id, $title);
if (!empty($img)) {
	$path = dirname(__FILE__).'/..'.PATH_UPLOAD.$img;
	if (!file_exists($path))
		$img = '';
	else
		$img = PATH_UPLOAD.$img;
}
$price = priceVi($data->price);
$cart = "/gio-hang/add?id={$id}";
?>
<div class="product-card card">
	<a class="thumb" href="<?= $url ?>">
		<? if (empty($img)) { ?>
			<img data-src="holder.js/350x350"/>
		<? } else { ?>
			<img src="<?= $img ?>" alt="">
		<? } ?>
	</a>
	<div class="info">
		<a class="title" href="<?= $url ?>"><?= $title ?></a>
		<div class="price">
			<span><?= $price ?></span>
			<a href="<?= $cart ?>" class="btn-order"></a>
		</div>
	</div>
</div>
