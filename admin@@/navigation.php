<?php

$items = array(
	array("Home slider", "home-slider", "photo"),
	array("Giới thiệu", "about", "bookmark"),
	array("Sản phẩm", "category", "shopping-cart" ),
	// array("Dự án", "project", 'star'),
	// array("Dịch vụ", "service", "map-signs"),
	array("Tin tức", "news", "newspaper-o"),
	array("Khuyến mãi", "news_promo", "newspaper-o"),
	array("Thanh toán", "payment", "money"),
	array("Liên hệ", "contact", "envelope"),
	array("Quản lý Text", "qtext", "font"),
	array("Quản lý người dùng", "ad_user", "user")
);

// convert to associative array
$assoc_items = array();
foreach ($items as $item) {
	$assoc_items[] = (object) array(
		'text' => $item[0],
		'act' => $item[1],
		'icon' => $item[2],
		);
}

?>
<nav class="navbar navbar-inverse navbar-fixed-top">
<div class="container-fluid">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="index.php">Administrator Panel</a>
	</div>
	<div class="collapse navbar-collapse">
		<ul class="nav navbar-nav navbar-sidebar">
			<? foreach ($assoc_items as $item) { ?>
				<li<?= $item->act==gett('act') ? ' class="active"' : '' ?>>
					<a href="index.php?act=<?= $item->act ?>">
						<i class="fa fa-fw fa-<?= $item->icon ?>"></i>
						<?= $item->text ?>
					</a>
				</li>
			<? } ?>
		</ul>
		<ul class="nav navbar-nav navbar-right">
			<li>
				<a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
			</li>
		</ul>
	</div>
</div>
</nav>
