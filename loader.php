<?php

@ob_start();
@session_start();
session_cache_expire( 0 );
error_reporting( E_ALL ^ E_NOTICE );

need('config.php');

need('inc/class_db.php');
DB::connect( DB_HOST, DB_USER, DB_PASS, DB_NAME );

need('inc/misc.php');
need('inc/class_cart.php');
need('inc/class_i18n.php');
need('inc/class_counter.php');
need('inc/class_session.php');
need('inc/class_table.php');
need('inc/class_pager.php');
need('inc/class_form.php');
need('functions.php');

need('vendor/phpmailer/phpmailer/PHPMailerAutoload.php');
need('vendor/eventviva/php-image-resize/src/ImageResize.php');

want('views.php');

function need( $path ) {
	require resolve($path);
}

function want( $path ) {
	include resolve($path);
}

function resolve( $path ) {
	return dirname(__FILE__) . '/' . ltrim($path, '/');
}
