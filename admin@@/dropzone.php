<?php

include '../loader.php';

$product_id = gett('id');
if (!$product_id) exit;

if (gett('act') == 'del') {
	$photo = DB::select_one("SELECT * FROM product_photos WHERE pId={$product_id} AND img='".gett('img')."'");
	if (!$photo) exit;
	$photo_id = $photo->id;
	delete_attachment('product_photos.img', $photo_id);
	DB::delete('product_photos', $photo_id);
	json(array('success' => TRUE));
}

if (gett('act') == 'sort') {

}

if (gett('act') == 'upload') {
	$fileName = handleUploaded('file');
	$ind = DB::count('product_photos', "pId={$product_id}");
	$inserted_id = DB::insert("INSERT INTO product_photos (img, pId, ind) VALUES ('{$fileName}', {$product_id}, $ind)");
	json(array(
		'success' => TRUE,
		'photo_id' => $inserted_id,
		'ind' => $ind,
	));
}
