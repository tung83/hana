<?php
require_once 'loader.php';
init_page();
$page_title = !empty($page_vars['title']) ? $page_vars['title'].' :: Túi xách hàng hiệu Mi Lan' : 'Túi xách hàng hiệu Mi Lan';
$page_desc = choose(one_line_text($page_vars['desc']), one_line_text(qtext('description')), '');
$page_keywords = choose(one_line_text($page_vars['keyword']), one_line_text(qtext('keywords')), '');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?= $page_title ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?= $page_desc ?>">
	<meta name="keywords" content="<?= $page_keywords ?>">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="/bower_components/components-font-awesome/css/font-awesome.min.css" />
	<link rel="stylesheet" href="/bower_components/pushy-reloaded/css/pushy.min.css" />
	<link rel="stylesheet" href="/css/style.css" />
	<script type="text/javascript" src="/bower_components/jquery/dist/jquery.min.js"></script>
</head>
<body class="page-<?= get_view() ?> lang-<?= $lang ?>">
	<? //fbscript() ?>
	<? //fbchatbox('https://www.facebook.com/tuixachmilan') ?>
	<? pushy_begin() ?>

	<header>
		<div class="container">
			<div class="col1">

				<a href="/" id="logo"></a>

			</div>
			<div class="col2">

				<div id="sitename">TÚI XÁCH HÀNG HIỆU CAO CẤP</div>
				<div id="fanpage">
					<span>Fanpage:</span>
					<a href="https://facebook.com/tuixachmilan" target="_blank">facebook.com/tuixachmilan</a>
				</div>
				<div id="email">
					<span>Email:</span>
					<a href="mailto:hanghieumilan@yahoo.com.vn">hanghieumilan@yahoo.com.vn</a>
				</div>
				<div id="hotline">
					<span>Hotline:</span>
					<a href="tel:0908199399">0908.199.399</a>
					<br class="visible-xs-block">
					<a href="#" id="viber"></a>
					<a href="#" id="zalo"></a>
				</div>

			</div>
			<div class="col3">

				<form action="#" id="login">
					<label>Thành viên</label>
					<input type="text" name="username" placeholder="Tên đăng nhập">
					<input type="password" name="password" placeholder="Mật khẩu">
					<input type="submit" value="Đăng nhập">
				</form>

				<form action="<?= url('search') ?>" id="search">
					<input type="text" name="q" value="<?= gett('q') ?>" placeholder="Tìm kiếm…" />
					<input type="submit" value="">
				</form>

				<a id="cart" href="<?= url('cart') ?>">Giỏ hàng</a>

			</div>
		</div>
	</header>
	<nav id="menu">
		<div class="container">
			<div class="hidden-xs">
				<?= top_menu() ?>
			</div>
			<div class="visible-xs-block">
				<? pushy_button() ?>
			</div>
			<div id="social"></div>
		</div>
	</nav>
	<? if (strpos('products,products/c,products/s,products/gc', get_view()) !== FALSE) { ?>
		<div id="top" class="hidden-xs">
			<div class="container product_nav">
				<?= product_menu_full() ?>
			</div>
		</div>
	<? } elseif (get_view() != 'home') { ?>
		<div id="top">
		    <div class="container">
		        Chào mừng bạn đến với website của chúng tôi
		    </div>
		</div>
	<? } ?>
	<div id="body">
		<? body() ?>
	</div>
	<?= render('home_subscribe') ?>
	<footer>
		<div class="container"><div class="row">
			<div class="col-sm-3">

				<div class="foot-block">
					<div class="foot-block__header">MENU</div>
					<div class="foot-block__content footer_menu">
						<?= bottom_menu() ?>
					</div>

				</div>
			</div>
			<div class="col-sm-3">

				<div class="foot-block">
					<div class="foot-block__header">SẢN PHẨM</div>
					<div class="foot-block__content footer_menu">
						<?= product_menu() ?>
					</div>
				</div>

			</div>
			<div class="col-sm-6">

				<div class="foot-block">
					<div class="foot-block__header">THÔNG TIN LIÊN HỆ</div>
					<div class="foot-block__content"><?= qtext('contact') ?></div>
				</div>

			</div>
		</div></div>
	</footer>
	<div id="bottom">
		<div class="container">
			<span class="copyright">
				Copyright © 2012. All Rights Reserved - Designed by <a href="http://pspmedia.vn" target="_blank">PSPMedia</a>
			</span>
			<div id="counters">
				<span>Đang online: <?= Session::total() ?></span> |
				<span>Lượt truy cập: <?= VisitCounter::inc() ?></span>
			</div>
		</div>
	</div>

	<? pushy_end() ?>

	<script type="text/javascript" src="/bower_components/jquery-migrate/jquery-migrate.min.js"></script>
	<script type="text/javascript" src="/bower_components/holderjs/holder.min.js"></script>
	<script type="text/javascript" src="/bower_components/trmix/dist/trmix.min.js"></script>
	<script type="text/javascript" src="/bower_components/pushy-reloaded/js/pushy.min.js"></script>
	<script src="js/main.js"></script>
	<? tawk() ?>

</body>
</html>
