<?php

$view = isset( $_GET[ 'view' ] ) ? $_GET[ 'view' ] : 'trang-chu';
$lang = Lang::get();

/**
 * Extract constants to global varibles so we can use it in HEREDOC
 */
extract( get_defined_constants() );

function url( $view, $id = null, $text = '' ) {
    global $lang;

    $slug = slug( $text );
    $slug_segment = !empty( $slug ) ? "${slug}." : '';
    $view = internationalize_view( $view );

    // Patch
    if ( $view == 'products/c' ) return "/{$slug_segment}c{$id}.html";
    if ( $view == 'products/s' ) return "/{$slug_segment}s{$id}.html";
    if ( $view == 'products' && isset($id) ) return "/{$slug_segment}p{$id}.html";
    if ( $view == 'news' && isset($id) ) return "/{$slug_segment}n{$id}.html";
    if ( $view == 'services' && isset($id) ) return "/{$slug_segment}s{$id}.html";
    if ( $view == 'projects' && isset($id) ) return "/{$slug_segment}pr{$id}.html";
    if ( $view == 'about-us' && isset($id) ) return "/{$slug_segment}a{$id}.html";
    if ( $view == 'cart' ) return '/gio-hang';
    if ( $view == 'checkout' ) return '/dat-hang';

    $view = localize_view( $view );

    if ( DEBUG ) {
        $url = "/?view=${view}";
        if ( isset( $id ) )
            $url .= "&id=${id}";
        return $url;
    }

    $view_segment = "/${view}";
    $id_segment = !isset( $id ) ? '' : "/${slug_segment}${id}";

    $path = $view_segment . $id_segment;
    if ( $path != '/' )
        $path .= '.html';

    return $path;
}

function qtext( $key ) {
    $row = DB::select_one( "SELECT content, eContent FROM qtext WHERE name='{$key}'" );
    return t( $row->content, $row->eContent );
}

function send_mail( $from, $to, $subject, $body ) {

    // extract name and address from @from
    if ( preg_match( '/(.*)<(.*)>/', $from, $arr ) ) {
        $fromName = $arr[1];
        $fromAddress = trim( $arr[2] );
    } else {
        $fromName = $from;
        $fromAddress = $from;
    }

    $mail = new PHPMailer;

    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;

    $mail->From = $fromAddress;
    $mail->FromName = $fromName;
    $mail->addAddress( $to );

    $mail->isHTML();
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $subject;
    $mail->Body    = $body;

    $mail->send();
}

function send_contact_email( $name, $company, $address, $phone, $fax, $email, $message ) {
    $body = <<<HTML
<html>
<head>
    <title>{CONTACT_SUBJECT}</title>
</head>
<body>
    <p>Họ tên: ${name}</p>
    <p>Công ty: ${company}</p>
    <p>Địa chỉ: ${address}</p>
    <p>Điện thoại: ${phone}</p>
    <p>Fax: ${fax}</p>
    <p>Email: ${email}</p>
    <p>Nội dung: ${message}</p>
</body>
</html>
HTML;

    send_mail( "$name <$email>", CONTACT_EMAIL, CONTACT_SUBJECT, $body );
}

function render( $name, $data = '' ) {
    if (!empty($data)) {
        @ob_start();
        include( dirname(__FILE__) . '/views/' . $name . '.php' );
        $html = ob_get_contents();
        ob_end_clean();
        echo $html;
    } else {
        include( dirname(__FILE__) . '/views/' . $name . '.php' );
    }
}

function init_page() {
    switch (get_view()) {
        case 'about-us':
            return @render('about_init');
        case 'products':
            if (gett('c')) return @render('category_init');
            if (gett('s')) return @render('category_sub_init');
            if (gett('id')) return @render('product_init');
            return @render('product_list_init');
        case 'projects':
            if (gett('id')) return @render('project_init');
            return @render('project_list_init');
        case 'news':
        case 'world':
        case 'recruitment':
            if (gett('id')) return @render('news_init');
            return @render('news_list_init');
        case 'payment':
            return @render('payment_init');
        case 'promo':
            if (gett('id')) return @render('promo_init');
            return @render('promo_list_init');
        case 'contact-us':
            return @render('contact_init');
        case 'cart':
            return @render('cart_init');
        case 'checkout':
            return @render('checkout_init');
        case 'search':
            return @render('search_init');
        default:
            return @render('home_init');
    }
}

function body() {
    switch (get_view()) {
        case 'about-us':
            return render('about');
        case 'products':
            if (gett('c')) return render('category');
            if (gett('s')) return render('category_sub');
            if (gett('id')) return render('product');
            return render('product_list');
        case 'projects':
            if (gett('id')) return render('project');
            return render('project_list');
        case 'news':
        case 'world':
        case 'recruitment':
            if (gett('id')) return render('news');
            return render('news_list');
        case 'payment':
            return render('payment');
        case 'promo':
            if (gett('id')) return render('promo');
            return render('promo_list');
        case 'services':
            if (gett('id')) return render('service');
            return render('service_list');
        case 'contact-us':
            return render('contact');
        case 'search':
            return render('search');
        case 'cart':
            return render('cart');
        case 'checkout':
            return render('checkout');
        default:
            return render('home');
    }
}

/**
 * translate view into current locale
 */
function localize_view( $view ) {
    $lang = Lang::get();

    if ( $lang == 'vi' ) {
        // en => vi
        $map = array_flip(get_views_map());

        // from en
        if (array_key_exists($view, $map))
            return $map[$view];

        // already vi
        if (in_array($view, $map))
            return $view;

        return 'trang-chu';
    }

    return $view;
}

/**
 * translate view into English
 */
function internationalize_view( $view ) {
    if (Lang::get() == 'en')
        return $view;

    // vi => en
    $map = get_views_map();

    // from vi
    if (array_key_exists($view, $map))
        return $map[$view];

    // already en
    if (in_array($view, $map))
        return $view;

    return 'home';
}

function get_view() {
    return internationalize_view($_GET['view']);
}

function get_views_map() {
    return array(
        'trang-chu' => 'home',
        'gioi-thieu' => 'about-us',
        'san-pham' => 'products',
        'san-pham/c' => 'products/c',
        'san-pham/s' => 'products/s',
        'du-an' => 'projects',
        'dich-vu' => 'services',
        'tin-tuc' => 'news',
        'lien-he' => 'contact-us',
        'tim-kiem' => 'search',
        'khuyen-mai' => 'promo',
        'thanh-toan' => 'payment',
        'gio-hang' => 'cart',
        'dat-hang' => 'checkout',
    );
}

function fbscript() {
    $script_url = '//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5';
    if (defined(FACEBOOK_APP_API)) {
        $script_url .= '&appId='.FACEBOOK_APP_API;
    }
    ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "<?= $script_url ?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
    <?
}

function fbchatbox($page_url, $width = 300, $height = 300) {
    ?>
    <div class="fbchatbox hidden-xs" style="width: <?= $width ?>px; bottom: -<?= $height ?>px;" data-width="<?= $width ?>" data-height="<?= $height ?>">
        <div class="fbchatbox__heading">Online Support</div>
        <div class="fbchatbox__body" style="height: <?= $height ?>px;">
            <div class="fb-page" data-href="<?= $page_url ?>" data-small-header="true" data-adapt-container-width="false" data-hide-cover="true" data-show-facepile="false" data-show-posts="false" data-tabs="messages" data-width="<?= $width+2 ?>" data-height="<?= $height+2 ?>"><div class="fb-xfbml-parse-ignore"><blockquote cite="<?= $page_url ?>"><a href="<?= $page_url ?>"></a></blockquote></div></div>
        </div>
    </div>
    <script>
        (function() {
            var $fbchatbox = $('.fbchatbox');
            var $fbchatbox__heading = $('.fbchatbox__heading');

            if (localStorage.getItem('fbchaton') == 'on') {
                $fbchatbox.addClass('on');
            }

            $fbchatbox__heading.on('click', function() {
                $fbchatbox.toggleClass('on');
                localStorage.setItem('fbchaton', $fbchatbox.hasClass('on') ? 'on' : 'off');
            })
        })();
    </script>
    <?
}
