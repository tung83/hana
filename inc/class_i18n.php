<?php

class Lang {

    public static function set( $lang ) {
        $lang = choose( $lang, self::fallback() );
        $_SESSION['lang'] = $lang;
        setcookie( 'lang', $lang, time() + 60 * 60 * 24 * 30 );
    }

    public static function get() {
        return choose( $_COOKIE['lang'], $_SESSION['lang'], self::fallback() );
    }

    private static function fallback() {
        return LANG_DEFAULT;
    }
}
