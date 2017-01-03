<?php

function flags() {
	$url = '/lang.php?lang='.t('en', 'vi');
	$img = t('/img/flag-en@2x.png', '/img/flag-vi@2x.png');

	return '<a href="'.$url.'"><img src="'.$img.'"/></a>';
}

function top_menu($depth = 0) {
	$view = get_view();

	$str = '<ul>';

	$items = DB::select("SELECT * FROM menu WHERE active=1 ORDER BY ind ASC, id DESC");
	foreach ($items as $row) {
		$class = $row->view;
		if ($view == internationalize_view($row->view))
			$class .= ' active';
		$title = t($row->title, $row->eTitle);
		$url = url($row->view);

		$str .= "<li class=\"$class\">";
		$str .= "<a href=\"$url\">$title</a>";

		if ($depth > 0) {

			if ($row->id == 2) {
				$table = 'about';
				$slug = 'gioi-thieu';
			} elseif ($row->id == 4) {
				$table = 'serv';
				$slug = 'quy-trinh';
			} else {
				$table = '';
			}

			if (!empty($table)) {
				$str .= "<ul>";

				$sub_items = DB::select("SELECT * FROM $table WHERE active=1 ORDER BY id DESC");
				foreach ($sub_items as $r) {
					$title = t($r->title, $r->eTitle);
					$url = url($slug, $r->id, $title);
					$str .= "<li><a href=\"$url\">$title</a></li>";
				}

				$str .= "</ul>";
			}

			if ($row->id == 3) {
				$str .= '<ul>';

				$subs = tt(DB::select("SELECT * FROM categories c WHERE active=1 AND (SELECT COUNT(*) FROM subcategories s WHERE s.cId=c.id AND s.active=1) > 0 ORDER BY ind"));
				foreach ($subs as $row) {
					extract((array)$row);
					$url = url('products/c', $id, $title);
					$active = (gett('c') && $id == gett('id')) ? ' class="h"' : '';
					$str .= "<li><a href=\"${url}\">${title}</a></li>";
				}

				$str .= '</ul>';
			}
		}

		$str .= "</li>";
	}

	$str .= "</ul>";

	return $str;
}

function bottom_menu() {
	extract($GLOBALS);
	$current_view = $view;

	$str = '<ul>';

	$items = DB::select("SELECT * FROM menu WHERE active=1 ORDER BY ind ASC, id DESC");
	foreach ($items as $row) {
		extract((array)$row);
		$title = t($title, $eTitle);
		$active = $view == $current_view ? ' class="active"' : '';
		$url = url($view);
		$str .= <<<HTML
        <li${active}>
            <a href="${url}">${title}</a>
        </li>
HTML;
	}

	$str .= '</ul>';

	return $str;
}

function product_menu() {
	$html = '<ul>';

	$items = DB::select("SELECT * FROM categories ORDER BY ind");
	foreach ($items as $item) {
		$url = url('products/c', $item->id, $item->title);
		$html .= '<li><a href="'.$url.'">'.$item->title.'</a></li>';
	}

	$html .= '</ul>';

	return $html;
}

function product_menu_full() {
	$html = '<ul>';

	$cats = tt(DB::select("SELECT * FROM categories WHERE active=1 ORDER BY ind"));
	foreach ($cats as $cat) {
		$url = url('products/c', $cat->id, $cat->title);
		$subs = tt(DB::select("SELECT * FROM subcategories WHERE active=1 AND cId={$cat->id} ORDER BY ind"));
		$liClass = '';
		if (count($subs)) $liClass .= 'has-child ';

		$html .= '<li class="'.$liClass.'"><a href="'.$url.'">'.$cat->title.'</a>';

		if (count($subs)) {
			$html .= '<ul>';

			foreach ($subs as $sub) {
				$url = url('products/s', $sub->id, $sub->title);
				$html .= '<li><a href="'.$url.'">'.$sub->title.'</a></li>';
			}

			$html .= '</ul>';
		}

		$html .= '</li>';
	}

	$html .= '</ul>';

	return $html;
}

function featured_products() {
	$str = '
    <div class="block featured-products">
        <h3>'.t('Sản phẩm bán chạy', 'Top products').'</h3>
        <ul>
    ';
	$products = DB::select("SELECT p.* FROM product p JOIN category c ON p.pId=c.id WHERE p.active=1 AND p.featured=1 ORDER BY p.id DESC LIMIT 6");
	foreach ($products as $row) {
		extract((array)$row);
		$title = t($title, $eTitle);
		$url = url('san-pham', $id, $title);
		$img_url = PATH_UPLOAD.$img;
		$str .= '
            <li>
                <a href="'.url('san-pham', $id, $title).'" >
                    <img src="'.PATH_UPLOAD.$img.'" />
                </a>
                <a href="'.url('san-pham', $id, $title).'" >'.$title.'</a>
                <div class="price">
                    <span>'.t('Giá', 'Price').':</span>
                    '.fmoney(t($price, $ePrice)).'
                </div>
                '.add_to_cart($id).'
            </li>
        ';
	}
	$str .= '
        </ul>
        <div class="read-more">
            <a href="'.url('san-pham').'">'.t('Xem thêm', 'View more').'</a>
        </div>
    </div>
    ';

	return $str;
}

function search_products() {

	if (!isset($_GET['q']))
		header('location:index.php');

	$q = $_GET['q'];
	$results = DB::select("SELECT p.* FROM product p JOIN category c ON p.pId=c.id WHERE p.title LIKE '%${q}%' OR p.content LIKE '%${q}%' OR p.eContent LIKE '%${q}%' AND p.active=1 ORDER BY p.id DESC LIMIT 30");
	$str = '
    <h3>'.t('Tìm sản phẩm', 'Search Products').'</h3>';

	if (empty($results)) {
		$str .= '
    <p class="not-found">'.t('Không tìm thấy sản phẩm', 'Product not found').'</p>';
	}

	$str .= '
    <ul class="product-list">
    ';
	foreach ($results as $row) {
		extract((array)$row);
		$title = t($title, $eTitle);
		$str .= '
        <li>
            <a href="'.url('san-pham', $id, $title).'" class="thumb">
                <img src="'.PATH_UPLOAD.$img.'" />
            </a>
            <a href="'.url('san-pham', $id, $title).'" class="title">
                '.$title.'
            </a>
            <div class="price">
                <span>'.t('Giá', 'Price').':</span>
                '.fmoney(t($price, $ePrice)).'
            </div>
            '.add_to_cart($id).'
        </li>
        ';
	}
	$str .= '
    </ul>
    ';

	return $str;
}

function search_news() {

	if (!isset($_GET['q'])) {
		header('location:index.php');

		return;
	}

	$q = $_GET['q'];

	$str = '
    <h3>
        '.t('Tìm Tin Tức', 'Search News').'
    </h3>
    ';

	$results = DB::select("SELECT * FROM news WHERE title LIKE '%${q}%' OR eTitle LIKE '%${q}%' OR eTitle LIKE '%${q}%' OR sum LIKE '%${q}%' OR eSum LIKE '%${q}%' OR content LIKE '%${q}%' OR eContent LIKE '%${q}%' AND active=1 ORDER BY id DESC LIMIT 10");

	if (empty($results)) {
		$str .= '
            <p class="not-found">'.t('Không tìm thấy bản tin', 'Articles not found').'</p>';
	}

	$str .= '
            <ul class="news-list2">';
	foreach ($results as $row) {
		$title = t($row->title, $row->eTitle);
		$sum = t($row->sum, $row->eSum);
		$str .= '
                <li>
                    <a href="'.url('tin-tuc', $row->id, $title).'">
                        <h2>'.$title.'</h2>
                        <img src="'.PATH_UPLOAD.$row->img.'"/>
                        <span>'.$sum.'</span>
                    </a>
                </li>
        ';
	}
	$str .= '
            </ul>
    ';

	return $str;
}

function home_slider() {
	extract($GLOBALS);

	$str = <<<HTML
<div id="wowslider-container1">
    <div class="ws_images">
        <ul>
HTML;

	$slides = DB::select("SELECT * FROM home_slider WHERE active=1 ORDER BY ind DESC");
	foreach ($slides as $row) {
		extract((array)$row);

		if (!empty($lnk)) {
			$str .= <<<HTML
            <li><a href="${url}"><img src="${PATH_UPLOAD}${img}" alt="${title}" title="${title}"/></a></li>
HTML;
		} else {
			$str .= <<<HTML
            <li><img src="${PATH_UPLOAD}${img}" alt="${title}" title="${title}"/></li>
HTML;
		}

		$ws_bullets .= "<a></a>";
	}

	$str .= <<<HTML
        </ul>
    </div>
    <div class="ws_bullets">${ws_bullets}</div>
</div>
<link rel="stylesheet" type="text/css" href="/js/wow/style.css" />
<style type="text/css">
    * html #wowslider-container1 {
        width: ${SLIDER_WIDTH}px;
    }
    #wowslider-container1,
    #wowslider-container1 .ws_images,
    #wowslider-container1 .ws_images ul a,
    #wowslider-container1 .ws_images > div > img {
        max-height: ${SLIDER_HEIGHT}px;
    }
</style>
<script type="text/javascript" src="/js/wow/wowslider.js"></script>
<script type="text/javascript" src="/js/wow/script.js"></script>
<script type="text/javascript">
    jQuery(function ($) {
        $('#wowslider-container1').wowSlider({
            effect: 'glass_parallax,parallax,seven,kenburns,blur,domino,slices,blast,blinds,basic,fade,fly,stack,stack_vertical',
            prev: '',
            next: '',
            duration: 30 * 100,
            delay: 30 * 100,
            width: ${SLIDER_WIDTH},
            height: ${SLIDER_HEIGHT},
            autoPlay: false,
            autoPlayVideo: false,
            playPause: false,
            stopOnHover: true,
            loop: false,
            bullets: 1,
            caption: false,
            captionEffect: 'parallax',
            controls: true,
            controlsThumb: false,
            responsive: 2,
            fullScreen: false,
            gestures: 2,
            onBeforeStep: 0,
            images: 0
        });
    });
</script>
HTML;

	return $str;
}

function home_videos() {
	$tab = DB::select("SELECT * FROM video WHERE active=1 ORDER BY ind DESC LIMIT 4");

	$top = array_shift($tab);
	extract((array)$top);
	$title = t($title, $eTitle);
	$url = url('video', $id, $title);

	$str = <<<HTML
    <div class="home-videos">
        <h3>
            <a href="$url">$title</a>
        </h3>
        <div class="fluid-video">
            <iframe width="640" height="360" src="https://www.youtube.com/embed/${lnk}?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
        </div>
        <ul class="other-videos">
HTML;

	foreach ($tab as $row) {
		extract((array)$row);
		$title = t($title, $eTitle);
		$url = url('video', $id, $title);
		$str .= <<<HTML
            <li>
                <a href="${url}" class="title">${title}</a>
            </li>
HTML;
	}

	$str .= <<<HTML
        </ul>
    </div>
HTML;

	return $str;
}

function left_module() {
	$view = isset($_GET['view']) ? $_GET['view'] : 'trang-chu';
	// if($view!='trang-chu') $str.=feature_product);
	$str = featured_news();

	// $str.=partner);
	return $str;
}

function feature_product() {
	$row = DB::select_one("SELECT * FROM product WHERE active=1 ORDER BY id DESC");
	$str = '
    <div class="feature">
    <a href="'.url('san-pham', $row->id, $title).'">
        <img src="'.PATH_UPLOAD.$row->img.'"/>
        <h2>'.$row->title.'</h2>
        <span>'.$row->sum.'</span>
    </a>
    </div>
    ';

	return $str;
}

function partner() {
	$str = '
    <div class="box">
        <div class="box_top"></div>
        <div class="box_body">
            <ul class="box_list clearfix">
    ';
	$rows = DB::select("SELECT * FROM partner WHERE active=1 ORDER BY ind asc,id DESC LIMIT 10");
	foreach ($rows as $row) {
		$title = t($row->title, $row->eTitle);
		$href = $row->lnk != '' ? $row->lnk : '#';
		$str .= '
        <li class="partner_item">
            <a href="'.$href.'" target="_blank">
                <img src="'.PATH_UPLOAD.$row->img.'"/>
                <h2>'.$title.'</h2>
            </a>
        </li>
        ';
	}
	$str .= '
            </ul>
        </div>
        <div class="box_bottom"></div>
    </div>
    ';

	return $str;
}

function featured_news() {
	$str = '
    <div class="block featured-news">
        <h3>'.t('Tin Tức', 'Hot News').'</h3>
        <ul>';
	$rows = DB::select("SELECT * FROM news WHERE active=1 ORDER BY id DESC LIMIT 6");
	foreach ($rows as $row) {
		$str .= '
                <li>
                    <a href="'.url('tin-tuc', $row->id, $title).'" class="thumb">
                        <img src="'.PATH_UPLOAD.$row->img.'"/>
                    </a>
                    <a href="'.url('tin-tuc', $row->id, $title).'" class="title">
                        '.t($row->title, $row->eTitle).'
                    </a>
                    <span class="date">'.fdate($row->dates, 'd/m/Y').'</span>
                    <p>'.t($row->sum, $row->eSum).'</p>
                </li>
            ';
	}
	$str .= '
        </ul>
        <div class="read-more">
            <a href="'.url('tin-tuc').'">'.t('Xem thêm', 'View more').'</a>
        </div>
    </div>
    ';

	return $str;
}

function home() {
	$str = '
    <div class="the_left">
        <div class="about-us">
            <h3>'.t('Giới thiệu', 'About us').'</h3>
            <p>'.qtext(2).'</p>
            <a href="'.url('gioi-thieu', 2, 'about-us').'" class="map-link">'.t('Xem chi tiết', 'Read more').'</a>
        </div>
        '.home_videos().'
        '.facebook_page().'
    </div>
    <div class="the_main">
        '.featured_products().'
        '.featured_news().'
    </div>
    <div class="clear"></div>
    ';

	return $str;
}

function search() {
	$str = '
    <h2 class="page-title">
        '.t('Tìm kiếm', 'Search').'
    </h2>
    <div class="page-content">
        '.search_products().'
    </div>
    ';

	return $str;
}

function about() {
	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
	$row = DB::select_one("SELECT * FROM about WHERE id=${id}");

	if (empty($row)) {
		$tmp = DB::select_one("SELECT id FROM about WHERE active=1 ORDER BY id ASC LIMIT 1");
		header('location:'.url('gioi-thieu', $tmp->id), TRUE);
	}

	$str = '
    <h2 class="page-title">
        '.t('Giới thiệu', 'About us').'
    </h2>
    <article class="page-content">
        <h3 class="title">'.t($row->title, $row->eTitle).'</h1>
        <p>'.t($row->content, $row->eContent).'</p>
    </article>
    ';

	return $str;
}

function news() {

	if (isset($_GET['id']))
		return one_news();

	return all_news();
}

function all_news() {
	$str = '
    <h2 class="page-title">
        '.t('Hạt gạo Tin Tức', 'News').'
    </h2>
    <div class="page-content">
        <ul class="news-list">
        ';
	$tab = DB::select("SELECT * FROM news WHERE active=1 ORDER BY id DESC");
	foreach ($tab as $row) {
		$title = t($row->title, $row->eTitle);
		$sum = t($row->sum, $row->eSum);
		$str .= '
            <li>
                <a href="'.url('tin-tuc', $row->id).'" class="thumb">
                    <img src="'.PATH_UPLOAD.$row->img.'" alt="" title=""/>
                </a>
                <h4>
                    <a href="'.url('tin-tuc', $row->id, $title).'">
                    '.$title.'
                    </a>
                </h4>
                <span class="date">'.fdate($row->dates).'</span>
                <p>'.strcut($sum, 120).'</p>
            </li>
        ';
	}
	$str .= '
        </ul>
        '.most_viewed_news().'
    </div>
    ';

	return $str;
}

function one_news() {
	$id = intval($_GET['id']);
	$row = DB::select_one("SELECT * FROM news WHERE id=$id");

	if (empty($row)) {
		$tmp = DB::select_one("SELECT id FROM news WHERE active=1 ORDER BY id ASC LIMIT 1");
		$title = t($tmp->title, $tmp->eTitle);
		header('location:'.url('tin-tuc', $tmp->id, $title), TRUE);
	}

	$str = '
    <h2 class="page-title">
        '.t('Tin Tức', 'News').'
    </h2>
    <div class="page-content">
        <article>
            <h3 class="title">'.t($row->title, $row->eTitle).'</h3>
            <p>'.t($row->content, $row->eContent).'</p>';

	$tb = DB::select("SELECT id,title FROM news WHERE active=1 AND id<>{$row->id} ORDER BY id DESC");
	if (!empty($tb)) {
		$str .= '
            <div class="related-news">
                <h4>'.t('Bài viết khác', 'Other article').'</h4>
                <ul>';
		foreach ($tb as $r) {
			$title = t($r->title, $r->eTitle);
			$str .= '
                    <li>
                        <a href="'.url('tin-tuc', $r->id, $title).'">'.$title.'</a>
                    </li>';
		}
		$str .= '
               </ul>
           </div>
        ';
	}

	$str .= '
        </article>
        '.most_viewed_news().'
    </div>';

	return $str;
}

function most_viewed_news() {
	$str = '
        <div class="most-viewed">
            <h3>'.t('Tin đọc nhiều nhất', 'Most viewed').'</h3>
            <ul>
    ';

	$tab = DB::select("SELECT * FROM news WHERE active=1 ORDER BY id DESC LIMIT 8");
	foreach ($tab as $row) {
		$title = t($row->title, $row->eTitle);
		$sum = t($row->sum, $row->eSum);
		$str .= '
                <li>
                    <a href="'.url('tin-tuc', $row->id).'" class="thumb">
                        <img src="'.PATH_UPLOAD.$row->img.'" alt="" title=""/>
                    </a>
                    <a href="'.url('tin-tuc', $row->id, $title).'">
                    '.$title.'
                    </a>
                </li>
        ';
	}

	$str .= '
            </ul>
        </div>
    ';

	return $str;
}

function service() {
	/*if(!isset($_GET['id']))
	{
		return all_serv);
	}
	else */
	echo one_service();
}

function all_service() {
	$str = '
    <div class="ground">
        <div class="board">
            '.t('Quy Trình', 'Services').'
        </div>
        <div class="all_here">
            <div class="the_left">
                <ul class="news_list">
    ';
	$k = 1;
	$tab = DB::select("SELECT * FROM serv WHERE active=1 ORDER BY id DESC");
	foreach ($tab as $row) {
		if ($k == 1) {
			$str .= '
                    <li class="big_news">
            ';
		} else {
			$str .= '
                    <li class="small_news">
            ';
		}
		$title = t($row->title, $row->eTitle);
		$sum = t($row->sum, $row->eSum);
		$str .= '
                        <a href="'.url('quy-trinh', $row->id, $title).'">
                            <img src="'.PATH_UPLOAD.$row->img.'" alt="" title=""/>
                            <div>
                                <h2>'.$title.'</h2>
                                <em>'.date("d-m-y", strtotime($row->dates)).'</em>
                                <span>'.strcut($sum, 120).'</span>
                            </div>
                            <nav class="clear"></nav>
                        </a>
                    </li>
        ';
		$k++;
	}
	$str .= '
                </ul>
            </div>
            <div class="the_right">
                '.left_module().'
            </div>
            <div class="clear"></div>
        </div>
    </div>
    ';

	return $str;
}

function one_service() {
	$id = intval($_GET['id']);
	$row = DB::select_one("SELECT * FROM serv WHERE id={$id}");

	if (empty($row)) {
		$tmp = DB::select_one("SELECT id FROM serv WHERE active=1 ORDER BY id ASC LIMIT 1");
		$title = t($tmp->title, $tmp->eTitle);
		header('location:'.url('quy-trinh', $tmp->id, $title), TRUE);
	}

	$str = '
    <div class="ground ground1">
        <div class="board">
            '.t('Quy trình', 'Services').'
        </div>
        <div class="all_here">
            <div class="the_left">
                <article class="about">
                    <h1 class="about">'.t($row->title, $row->eTitle).'</h1>
                    <p>'.t($row->content, $row->eContent).'</p>';

	$tb = DB::select("SELECT id,title,eTitle FROM serv WHERE active=1 AND id<>{$row->id} ORDER BY id DESC");
	if (!empty($tb)) {
		$str .= '
                    <span class="about">'.t('Bài viết khác', 'Other aticle').'</span>
                    <nav class="about">';
		foreach ($tb as $r) {
			$title = t($r->title, $t->eTitle);
			$str .= '
                        <a href="'.url('quy-trinh', $r->id, $title).'">
                            '.$title.'
                        </a><br/>';
		}
		$str .= '
                    </nav>
        ';
	}

	$str .= '
                </article>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="shadow"></div>
    </div>
    ';

	return $str;
}

function product() {
	if (isset($_GET['id'])) return one_product();
	else return all_products();
}

function one_product() {
	$id = intval($_GET['id']);
	$row = DB::select_one("SELECT * FROM product WHERE id={$id}");
	extract((array)$row);

	$str = '
    <h2 class="page-title">
        '.t('Sản phẩm', 'Products').'
    </h2>
    <article class="page-content">
        <img src="'.PATH_UPLOAD.$img.'" alt="" class="product-thumb">
        <div class="product-detail">
            <h3>'.t($title, $eTitle).'</h3>
            <p>'.t($sum, $eSum).'</p>
            <div class="price">
                <span>'.t('Giá', 'Price').':</span>
                '.fmoney(t($price, $ePrice)).'
            </div>
            '.add_to_cart($id).'
        </div>
        <p>
            '.t($content, $eContent).'
        </p>
        '.related_products($pId).'
    </article>
    ';

	return $str;
}

function all_products() {
	$tab = DB::select("SELECT p.* FROM product p JOIN category c ON p.pId=c.id WHERE p.active=1 ORDER BY c.ind ASC, id DESC");
	$str = '
    <h2 class="page-title">
        '.t('Sản phẩm', 'Products').'
    </h2>
    <div class="page-content">
        <ul class="product-list">';

	foreach ($tab as $row) {
		extract((array)$row);

		$title = t($title, $eTitle);
		$str .= '
            <li>
                <a href="'.url('san-pham', $id, $title).'">
                    <img src="'.PATH_UPLOAD.$img.'" />
                </a>
                <span class="title">
                    <a href="'.url('san-pham', $id, $title).'">
                        '.$title.'
                    </a>
                </span>
                <div class="price">
                    <span>'.t('Giá', 'Price').':</span>
                    '.fmoney(t($price, $ePrice)).'
                </div>
                '.add_to_cart($id).'
            </li>';
	}

	$str .= '
        </ul>
    </div>';

	return $str;
}

function product_kind() {
	$id = intval($_GET['id']);
	$row = DB::select_one("SELECT * FROM category WHERE id={$id}");
	$str = '
    <h2 class="page-title">
        '.t($row->title, $row->eTitle).'
    </h2>
    <div class="page-content">
        '.products_in_category($row->id).'
    </div>
    ';

	return $str;
}

function products_in_category($pId) {
	$tab = DB::select("SELECT * FROM product WHERE active=1 AND pId={$pId} ORDER BY id DESC LIMIT 20");
	$str = '
    <ul class="product-list">
    ';
	foreach ($tab as $row) {
		extract((array)$row);

		$title = t($title, $eTitle);
		$str .= '
        <li>
            <a href="'.url('san-pham', $id, $title).'">
                <img src="'.PATH_UPLOAD.$img.'" />
            </a>
            <span class="title">
                <a href="'.url('san-pham', $id, $title).'">
                    '.$title.'
                </a>
            </span>
            <div class="price">
                <span>'.t('Giá', 'Price').':</span>
                '.fmoney(t($price, $ePrice)).'
            </div>
            '.add_to_cart($id).'
        </li>
        ';
	}
	$str .= '
    </ul>
    ';

	return $str;
}

function related_products($pId) {
	$id = $_GET['id'];
	$tab = DB::select("SELECT * FROM product WHERE active=1 AND pId=${pId} AND id<>${id} ORDER BY id DESC LIMIT 20");

	if (empty($tab))
		return '';

	$str = '
    <div class="related-products">
        <h3>'.t('Sản phẩm khác', 'Other products').'</h3>
        <ul class="product-list">
    ';
	foreach ($tab as $row) {
		extract((array)$row);

		$title = t($title, $eTitle);
		$str .= '
            <li>
                <a href="'.url('san-pham', $id, $title).'">
                    <img src="'.PATH_UPLOAD.$img.'" />
                </a>
                <span class="title">
                    <a href="'.url('san-pham', $id, $title).'">
                        '.$title.'
                    </a>
                </span>
                <div class="price">
                    <span>'.t('Giá', 'Price').':</span>
                    '.fmoney(t($price, $ePrice)).'
                </div>
                '.add_to_cart($id).'
            </li>
        ';
	}
	$str .= '
        </ul>
    </div>
    ';

	return $str;
}

function contact() {
	$str = '
    <h2 class="page-title">
        '.t('Liên hệ', 'Contact us').'
    </h2>
    <div class="page-content">
        <div class="message">'.t('Cảm ơn Quý khách đã truy cập vào website. Mọi thông tin chi tiết xin vui lòng liên hệ:', 'Thanks for visiting our website. For more information please contact us').'</div>
        <div class="contact_makeup">
            <img src="/img/contact-cover.jpg" class="cover"/>
            <div>
                '.qtext(1).'
            </div>
        </div>
        <div class="contact-form-full">
            '.contact_form().'
        </div>
        <div class="maps">
            <h5>'.t('Bản đồ đường đi').'</h5>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.6945467890773!2d106.6203175!3d10.834671300000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752bd2fdeb1aa1%3A0x756b06730361b689!2zMjg4IFRyxrDhu51uZyBDaGluaCwgVMOibiBUaOG7m2kgTmjhuqV0LCBRdeG6rW4gMTIsIEjhu5MgQ2jDrSBNaW5oLCBWaWV0bmFt!5e0!3m2!1sen!2s!4v1442421850208" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
    </div>
    ';

	return $str;
}

function contact_form() {
	$alert = t('Thông tin của bạn đã được gửi đi.Chúng tôi sẽ phản hồi sớm nhất có thể. Xin cám ơn!', 'Your contact have been sent. We will reply as soon as possible, Thanks!');
	$or = t('Form liên hệ', 'Contact form');
	$utf = t('Chú ý: Dấu (*) các trường bắt buộc phải nhập vào. Quý vị có thể gõ chữ tiếng Việt không dấu hoặc chữ tiếng Việt có dấu theo font UNICODE (UTF-8).', 'Notice: The asterisk (*) Mandatory fields must be entered. You can type unsigned Vietnamese or Vietnamese letters in the font accented UNICODE (UTF-8).');
	$frm_name = t('Họ và tên', 'Full name');
	$frm_comp = t('Công ty', 'Company');
	$frm_add = t('Địa chỉ', 'Address');
	$frm_phone = t('Điện thoại', 'Phone number');
	$frm_fax = t('Fax', 'Fax');
	$frm_email = t('Email', 'Email');
	$frm_content = t('Nội dung', 'Description');
	$frm_send = t('Gửi', 'Send');
	$frm_reset = t('Xoá bỏ', 'Reset');

	if (isset($_POST['btn_submit'])) {
		$name = str_replace("'", "&rsquo;", $_POST["name"]);
		$comp_name = str_replace("'", "&rsquo;", $_POST["comp_name"]);
		$adds = str_replace("'", "&rsquo;", $_POST["adds"]);
		$phone = str_replace("'", "&rsquo;", $_POST["phone"]);
		$fax = str_replace("'", "&rsquo;", $_POST["fax"]);
		$email = str_replace("'", "&rsquo;", $_POST["email"]);
		$content = str_replace("'", "&rsquo;", $_POST["content"]);
		$sql = "insert into contact(name,comp_name,adds,phone,fax,email,content,dates) ";
		$sql .= "values('$name','$comp_name','$adds','$phone','$fax','$email','$content',now())";
		mysql_query($sql);
		send_mail($name, $comp_name, $adds, $phone, $fax, $email, $content);
		echo "
        <script>alert('".$alert."');</script>";
	}

	$str = '
	<form action="javascript:void(0)" method="post" name="frmContact">
		<h3>'.$or.'</h3>
		<small>'.$utf.'</small>
		<div class="frmHead">'.$frm_name.' <em>*</em> :</div>
		<input type="text" name="name" autocomplete="off" placeholder="'.$frm_name.' *"/>
		<div class="frmHead">'.$frm_comp.' :</div>
		<input type="text" name="comp_name" autocomplete="off" placeholder="'.$frm_comp.'"/>
		<div class="frmHead">'.$frm_add.' <em>*</em> :</div>
		<input type="text" name="adds" autocomplete="off" placeholder="'.$frm_add.' *"/>
		<div class="frmHead">'.$frm_phone.' <em>*</em> :</div>
		<input type="text" name="phone" autocomplete="off" placeholder="'.$frm_phone.' *"/>
		<div class="frmHead">'.$frm_fax.' :</div>
		<input type="text" name="fax" autocomplete="off" placeholder="'.$frm_fax.'"/>
		<div class="frmHead">'.$frm_email.' <em>*</em> :</div>
		<input type="text" name="email" autocomplete="off" placeholder="'.$frm_email.' *"/>
		<div class="frmHead">'.$frm_content.' <em>*</em> :</div>
		<textarea name="content" placeholder="'.$frm_content.' *" maxlength="1000"></textarea>
		<input type="submit" value="'.$frm_send.'" name="btn_submit" id="btn_submit" onclick="return chkContact()"/>
					&ensp;
		<input type="reset" value="'.$frm_reset.'"/>
	</form>
	';

	return $str;
}

function cont_compact() {
	$frm_name = t('Họ và tên', 'Full name');
	$frm_email = t('Email', 'Email');
	$frm_content = t('Nội dung', 'Description');
	$frm_send = t('Gửi', 'Send');
	if (isset($_POST['btn_submit_compact'])) {
		$name = str_replace("'", "&rsquo;", $_POST["name"]);
		$comp_name = str_replace("'", "&rsquo;", $_POST["comp_name"]);
		$adds = str_replace("'", "&rsquo;", $_POST["adds"]);
		$phone = str_replace("'", "&rsquo;", $_POST["phone"]);
		$fax = str_replace("'", "&rsquo;", $_POST["fax"]);
		$email = str_replace("'", "&rsquo;", $_POST["email"]);
		$content = str_replace("'", "&rsquo;", $_POST["content"]);
		$sql = "insert into contact(name,comp_name,adds,phone,fax,email,content,dates) ";
		$sql .= "values('$name','$comp_name','$adds','$phone','$fax','$email','$content',now())";
		mysql_query($sql);
		send_mail($name, $comp_name, $adds, $phone, $fax, $email, $content);
		echo "
        <script>alert('".t('Thông tin của bạn đã được gửi đi.Chúng tôi sẽ phản hồi sớm nhất có thể. Xin cám ơn!', 'Your contact have been sent. We will reply as soon as possible, Thanks!')."');</script>";
	}
	$str = '
    <form action="javascript:void(0)" method="post" name="frmContactCompact">
        <input type="text" name="name" autocomplete="off" placeholder="'.$frm_name.' *"/>
        <input type="text" name="email" autocomplete="off" placeholder="'.$frm_email.' *"/>
        <textarea name="content" placeholder="'.$frm_content.' *"></textarea>
        <input type="submit" value="'.$frm_send.'" name="btn_submit_compact" id="btn_submit" onclick="return chkContactCompact()"/>
    </form>
    ';

	return $str;
}

function add_to_cart($id) {
	$str = '
        <a href="'.url('gio-hang').'/add/?id='.$id.'" class="add-to-cart">
            '.t('Đặt hàng', 'Add to cart').'
        </a>';

	return $str;
}

function news_($table, $vi, $en) {
	global $view;

	$slug = $view;

	if (isset($_GET['id']))
		return one_news_($table, $vi, $en, $slug);

	return all_news_($table, $vi, $en, $slug);
}

function all_news_($table, $vi, $en, $slug) {
	$str = '
    <h2 class="page-title">
        '.t($vi, $en).'
    </h2>
    <div class="page-content">
        <ul class="news-list2">
        ';
	$tab = DB::select("SELECT * FROM news${table} WHERE active=1 ORDER BY id DESC");
	foreach ($tab as $row) {
		extract((array)$row);
		$title = t($title, $eTitle);
		$sum = t($sum, $eSum);
		$str .= '
            <li>
                <a href="'.url($slug, $id).'" class="thumb">
                    <img src="'.PATH_UPLOAD.$img.'" alt="" title=""/>
                </a>
                <h4>
                    <a href="'.url($slug, $id, $title).'">
                    '.$title.'
                    </a>
                </h4>
                <span class="date">'.fdate($dates).'</span>
                <p>'.strcut($sum, 120).'</p>
            </li>
        ';
	}
	$str .= '
        </ul>
    </div>
    ';

	return $str;
}

function one_news_($table, $vi, $en, $slug) {
	$id = intval($_GET['id']);
	$row = DB::select_one("SELECT * FROM news${table} WHERE id=$id");

	if (empty($row)) {
		$tmp = DB::select_one("SELECT id FROM news${table} WHERE active=1 ORDER BY id ASC LIMIT 1");
		$title = t($tmp->title, $tmp->eTitle);
		header('location:'.url($slug, $tmp->id, $title), TRUE);
	}

	extract((array)$row);

	$str = '
    <h2 class="page-title">
        '.t($vi, $en).'
    </h2>
    <div class="page-content">
        <article>
            <h3 class="title">'.t($title, $eTitle).'</h3>
            <p>'.t($content, $eContent).'</p>';

	$tb = DB::select("SELECT id,title FROM news${table} WHERE active=1 AND id<>{$id} ORDER BY id DESC");
	if (!empty($tb)) {
		$str .= '
            <div class="related-news">
                <h4>'.t('Bài viết khác', 'Other article').'</h4>
                <ul>';
		foreach ($tb as $r) {
			$title = t($r->title, $r->eTitle);
			$str .= '
                    <li>
                        <a href="'.url($slug, $r->id, $title).'">'.$title.'</a>
                    </li>';
		}
		$str .= '
               </ul>
           </div>
        ';
	}

	$str .= '
        </article>
    </div>';

	return $str;
}

function video() {
	if (isset($_GET['id']))
		return one_video();

	return all_videos();
}

function all_videos() {
	$str = '
    <h2 class="page-title">
        Video Clips
    </h2>
    <div class="page-content">
        <ul class="video-list">
        ';
	$tab = DB::select("SELECT * FROM video WHERE active=1 ORDER BY id DESC");
	foreach ($tab as $row) {
		extract((array)$row);
		$title = t($title, $eTitle);
		$sum = t($sum, $eSum);
		$str .= '
            <li>
                <a href="'.url('video', $id).'" class="thumb">
                    <img src="'.PATH_UPLOAD.$img.'" alt="" title=""/>
                </a>
                <a href="'.url('video', $id, $title).'" class="title">
                    '.$title.'
                </a>
            </li>
        ';
	}
	$str .= '
        </ul>
    </div>
    ';

	return $str;
}

function one_video() {
	$id = intval($_GET['id']);
	$row = DB::select_one("SELECT * FROM video WHERE id=${id}");

	if (empty($row)) {
		$tmp = DB::select_one("SELECT id FROM video WHERE active=1 ORDER BY id ASC LIMIT 1");
		$title = t($tmp->title, $tmp->eTitle);
		header('location:'.url('video', $tmp->id, $title), TRUE);
	}

	extract((array)$row);

	$str = '
    <h2 class="page-title">
        Video Clips
    </h2>
    <div class="page-content">
        <article>
            <h3 class="title">'.t($title, $eTitle).'</h3>
            <div class="player">
                <iframe width="640" height="360" src="https://www.youtube.com/embed/'.$lnk.'?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
            </div>
            <p>'.t($content, $eContent).'</p>';

	$tb = DB::select("SELECT * FROM video WHERE active=1 AND id<>${id} ORDER BY ind DESC");
	if (!empty($tb)) {
		$str .= '
            <div class="related-videos">
                <h3>'.t('Clips khác', 'Other videos').'</h3>
                <ul class="video-list">';

		foreach ($tb as $r) {
			$title = t($r->title, $r->eTitle);
			$str .= '
                    <li>
                        <a href="'.url('video', $r->id, $title).'" class="thumb">
                            <img src="'.PATH_UPLOAD.$r->img.'" />
                        </a>
                        <a href="'.url('video', $r->id, $title).'" class="title">'.$title.'</a>
                    </li>';
		}

		$str .= '
               </ul>
           </div>
        ';
	}

	$str .= '
        </article>
    </div>';

	return $str;
}

function cart() {
	extract($GLOBALS);

	if (gett('act') == 'add') {
		Cart::add(gett('id'));

		return redirect(url('cart'));
	}

	if (gett('act') == 'remove') {
		Cart::remove(gett('id'));

		return redirect(url('cart'));
	}

	if (gett('act') == 'empty') {
		Cart::destroy();

		return redirect(url('cart'));
	}

	if (gett('act') == 'update') {
		foreach (Cart::items() as $id => $item) {
			$qty = postt('qty-'.$id);
			Cart::add($id, $qty, 1);
		}

		return redirect(url('cart'));
	}

	$str = <<<HTML
    <h2 class="page-title">
        Giỏ Hàng Của Bạn
    </h3>
    <div class="page-content">
HTML;

	$stt = '#';
	$nameM = t("Sản phẩm", "Product");
	$qtyM = t("Số lượng", "Qty");
	$priceM = t("Giá <sup>VNĐ</sup>", "Price <sup>USD</sup>");
	$subtotalM = t("Tổng <sup>VNĐ</sup>", "Subtotal <sup>USD</sup>");
	$totalM = t("Tổng cộng:", "Total");
	$continueM = t("Tiếp tục mua hàng", "Continue shopping");
	$checkoutM = t("Đặt hàng", "Checkout");
	$updateM = t("Cập nhật giỏ hàng", "Update cart");
	$removeM = t("Hủy bỏ", "Remove");
	$destroyM = t("Hủy toàn bộ", "Empty cart");
	$destroyConfirmM = t("Bạn có chắc hủy toàn bộ đơn hàng?", "Are you sure?");
	$emptyCartM = t("Giỏ hàng của bạn không có sản phẩm nào", "Cart is empty");
	$shopUrl = url('products');
	$checkoutUrl = url('checkout');
	$destroyUrl = url('cart').'/empty/';
	$updateUrl = url('cart').'/update/';

	$str .= <<<HTML
        <form action="${updateUrl}" method="POST">
        <table class="table table-bordered cart">
            <tr>
                <th>${stt}</th>
                <th colspan="2">${nameM}</th>
                <th>${qtyM}</th>
                <th>${priceM}</th>
                <th>${subtotalM}</th>
                <th>${removeM}</th>
            </tr>
HTML;

	if (!count(Cart::items())) {

		$str .= <<<HTML
            <tr class="empty-cart">
                <td colspan="7">$emptyCartM</td>
            </tr>
HTML;

	} else {

		$total = 0;
		$i = 0;

		foreach (Cart::items() as $id => $item) {
			$i++;

			$qty = $item['qty'];

			$row = DB::select_one("SELECT * FROM product WHERE id=${id}");
			extract((array)$row);

			$price = t($price, $ePrice);
			$subtotal = $qty * $price;
			$total += $subtotal;

			$title = t($title, $eTitle);
			$img = empty($img) ? '' : PATH_UPLOAD.$img;
			$priceF = fmoney($price, 0);
			$subtotalF = fmoney($subtotal, 0);

			$removeUrl = url('cart').'/remove/?id='.$id;

			$str .= <<<HTML
                <tr>
                    <td>${i}</td>
                    <td>
                        <img src="${img}" style="border:2px solid #fff;border-radius:3px;width:60px"/>
                    </td>
                    <td style="color:#060;font-weight:bold">${title}</td>
                    <td align="center">
                        <input type="text" onkeypress="return keypress(event);" name="qty-${id}" value="${qty}"/>
                    </td>
                    <td align="right">${priceF}</td>
                    <td align="right">${subtotalF}</td>
                    <td align="center"><a href="${removeUrl}">$removeM</a></td>
                </tr>
HTML;
		}

		$totalF = fmoney($total);

		$str .= <<<HTML
            <tfoot>
                <tr>
                    <td align="right" colspan="5">${totalM}</td>
                    <td align="right" colspan="2">${totalF}</td>
                </tr>
            </tfoot>
HTML;

	}

	$str .= <<<HTML
        </table>
        <div style="text-align:right;">
            <a href="${shopUrl}" class="btnB">${continueM}</a>
            <button class="btnB">${updateM}</button>
            <a href="${destroyUrl}" onclick="return confirm('${destroyConfirmM}')" class="btnB">${destroyM}</a>
            <a href="${checkoutUrl}" class="btnB">${checkoutM}</a>
        </div>
        </form>
    </div>
HTML;

	return $str;
}

function checkout() {

	if (isset($_POST["submit"])) {
		Cart::persist();
		$msg = t('Giỏ hàng của bạn đã được gởi đi! Vui lòng chờ phản hồi của chúng tôi!', 'Your order has been placed');
		$homeUrl = url('home');
		echo "<script>alert('$msg');location.href='$homeUrl';</script>";
	} else {
		if (!count(Cart::items()))
			return redirect(url('cart'));
	}

	$pageM = t('Đặt hàng', 'Checkout');
	$customerInfoM = t('Thông tin khách hàng', 'Customer info');
	$requireM = '<i class="required"></i> '.t('Thông tin yêu cầu.', 'Compulsory fields');
	$nameM = t('Họ và tên', 'Full name').' <i class="required"></i>';
	$addressM = t('Địa chỉ', 'Address').' <i class="required"></i>';
	$cityM = t('Thành phố', 'City').' <i class="required"></i>';
	$phoneM = t('Điện thoại', 'Phone number').' <i class="required"></i>';
	$emailM = 'Email <i class="required"></i>';
	$noteM = t('Ghi chú', 'Note');
	$submitM = t('Gởi', 'Submit');
	$resetM = t('Làm lại', 'Reset');

	$chkOut = count(Cart::items());
	$checkoutUrl = url('checkout');

	$str = <<<HTML
    <h2 class="page-title">$pageM</h2>
    <div class="page-content">
        <div style="width:600px;margin:10px auto">
            <h3>$customerInfoM</h3>
            <p class="require-msg">$requireM</p>
            <form method="POST" name="frmCart">
            <table class="frmContact">
                <tr>
                    <td align="right">
                        $nameM
                    </td>
                    <td>
                        <input type="text" name="name" style="width:100%"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        $addressM
                    </td>
                    <td>
                        <input type="text" name="adds" style="width:100%"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        $cityM
                    </td>
                    <td>
                        <input type="text" name="city" style="width:100%"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        $phoneM
                    </td>
                    <td>
                        <input type="text" name="phone" style="width:100%"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        $emailM
                    </td>
                    <td>
                        <input type="text" name="email" style="width:100%"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        $noteM
                    </td>
                    <td>
                        <textarea name="content" style="width:100%;height:100px"></textarea>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                    </td>
                    <td>
                        <input type="submit" onclick="javascript:addToCart($chkOut)" class="btnB" value="$submitM" style="margin-left:0px" name="submit">
                        <input type="button" onclick="location.href='$checkoutUrl';" class="btnB" value="$resetM">
                    </td>
                </tr>
            </table>
        </form>
    </div>
HTML;

	return $str;
}

function facebook_page() {
	return <<<HTML
    <div style="width:310px;height:590px;">
        <div class="fb-page" data-href="https://www.facebook.com/gaosachkimsang" data-width="310" data-height="590" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/gaosachkimsang"><a href="https://www.facebook.com/gaosachkimsang">Gạo Sạch Kim Sáng</a></blockquote></div></div>
    </div>
HTML;
}

function facebook_script() {
	return <<<HTML
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=260740137383168";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
HTML;
}

function tawk() {
	?>
	<!--Start of Tawk.to Script-->
	<script type="text/javascript">
		var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
		(function () {
			var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
			s1.async = true;
			s1.src = 'https://embed.tawk.to/56452d59ea57d0634fa97c05/default';
			s1.charset = 'UTF-8';
			s1.setAttribute('crossorigin', '*');
			s0.parentNode.insertBefore(s1, s0);
		})();
	</script>
	<!--End of Tawk.to Script-->
	<?
}

function pushy_begin() {
	?>
	<!-- Pushy Menu -->
	<nav class="pushy pushy-static">
		<?= pushy_menu() ?>
	</nav>

    <!-- Your Content -->
    <div class="pushy-container">
    <?
}

function pushy_end() {
    ?>
    	<!-- Site Overlay -->
    	<div class="pushy-site-overlay"></div>

	</div>
	<?
}

function pushy_button() {
	?>
	<!-- Menu Button -->
	<div class="pushy-menu-btn">&#9776; Menu</div>
	<?
}

function pushy_menu($depth = 1) {
    $view = get_view();

    $str = '<ul class="pushy-main-submenu">';

    $items = DB::select("SELECT * FROM menu WHERE active=1 ORDER BY ind ASC, id DESC");
    foreach ($items as $row) {
        $class = $row->view;
        if ($view == internationalize_view($row->view))
            $class .= ' active';
        $title = t($row->title, $row->eTitle);
        $url = url($row->view);

        $str .= "<li class=\"$class\">";
        $str .= "<a href=\"$url\">$title";

        if ($depth > 0) {

            if ($row->id == 2 || $row->id == 4) {
                if ($row->id == 2) {
                    $table = 'about';
                    $slug = 'gioi-thieu';
                } elseif ($row->id == 4) {
                    $table = 'serv';
                    $slug = 'quy-trinh';
                } else {
                    $table = '';
                }

                if (!empty($table)) {
                    $sub_items = DB::select("SELECT * FROM $table WHERE active=1 ORDER BY id DESC");
                    if (count($sub_items)) {
                        $str .= '<i class="fa fa-chevron-right"></i></a>';
                        $str .= '<ul class="pushy-submenu">';

                        foreach ($sub_items as $r) {
                            $title = t($r->title, $r->eTitle);
                            $url = url($slug, $r->id, $title);
                            $str .= "<li><a href=\"$url\">$title</a></li>";
                        }

                        $str .= '
                            </ul>
                            <div class="pushy-close-submenu"></div>';
                    } else {
                        $str .= '</a>';
                    }
                } else {
                    $str .= '</a>';
                }
            }

            if ($row->id == 3) {
                $str .= '<i class="fa fa-chevron-right"></i></a>';
                $str .= '<ul class="pushy-submenu">';

                $cats = tt(DB::select("SELECT * FROM categories c WHERE active=1 ORDER BY ind"));
                foreach ($cats as $row) {
                    extract((array)$row);
                    $url = url('products/c', $id, $title);
                    $active = (gett('c') && $id == gett('id')) ? ' class="active"' : '';
                    $str .= "<li><a href=\"${url}\">${title}";

                    $subs = tt(DB::select("SELECT * FROM subcategories WHERE cId={$id} AND active=1 ORDER BY ind"));
                    if (count($subs)) {
                        $str .= '<i class="fa fa-chevron-right"></i></a>';
                        $str .= '<ul class="pushy-submenu">';

                        foreach ($subs as $row) {
                            extract((array)$row);
                            $url = url('products/s', $id, $title);
                            $active = (gett('s') && $id == gett('id')) ? ' class="active"' : '';
                            $str .= "<li><a href=\"${url}\">${title}</a></li>";
                        }

                        $str .= '
                            </ul>
                            <div class="pushy-close-submenu"></div>';
                    } else {
                        $str .= '</a>';
                    }

                    $str .= '</li>';
                }

                $str .= '
                    </ul>
                    <div class="pushy-close-submenu"></div>';
            }
        }

        $str .= "</li>";
    }

    $str .= "</ul>";

    echo $str;
}
