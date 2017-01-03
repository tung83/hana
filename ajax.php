<?php
require_once 'loader.php';

if (gett('do') == 'subscribe') {
	escape_post('email');
	DB::insert(post_to_insert('email', 'email,dates'));
}

if (gett('do') == 'products') {
	$pageSize = 16;
	$id = gett('id');

	if (gett('c')) {
		if (!$id) exit();
		$rows = tt(DB::select("SELECT p.* FROM products p JOIN subcategories s ON s.id=p.sId AND s.active=1 JOIN categories c ON c.id=s.cId AND c.id={$id} WHERE p.active=1"));
	} elseif (gett('s')) {
		if (!$id) exit();
		$rows = tt(DB::select("SELECT p.* FROM products p WHERE p.active=1 AND p.sId={$id}"));
	} else {
		$rows = tt(DB::select("SELECT * FROM products"));
	}

	$pager = new Pager($rows, gett('page'), $pageSize);
	return json(array(
		'pages' => $pager->count(),
		'products' => filter_sensitive($pager->get()),
	));
}

function filter_sensitive($rows) {
	$filtered = array();
	foreach ($rows as $row) {
		extract((array)$row);

		$prod = new stdClass();
		$prod->id = $id;
		$prod->title = $title;
		$prod->price = price($price);
		$prod->url = url('products', $id, $title);

		if (!empty($img)) {
			$path = dirname(__FILE__).'/..'.PATH_UPLOAD.$img;
			if (!file_exists($path))
				$img = '';
			else
				$img = PATH_UPLOAD.$img;
		}

		$prod->img = $img;

		$filtered[] = $prod;
	}
	return $filtered;
}
