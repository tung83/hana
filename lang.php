<?php

require_once 'loader.php';

if ( gett('lang') ) {
    Lang::set(gett('lang'));
}

redirect(choose( $_SERVER['HTTP_REFERER'], '/' ));
