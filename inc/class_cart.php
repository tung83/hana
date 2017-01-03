<?php

class Cart
{
    public static function &items() {
        if ( empty( $_SESSION['cart'] ) )
            $_SESSION['cart'] = array();

        return $_SESSION['cart'];
    }

    public static function add( $id, $qty = 1, $fix = 0 ) {
        $cart = &self::items();

        if ( $qty == 0 ) return;

        if ( array_key_exists( $id, $cart ) ) {
            $item = &$cart[ $id ];
            if ( $fix == 1 )
                $item['qty'] = $qty;
            else
                $item['qty'] += $qty;
        } else {
            $cart[ $id ] = array( 'qty' => $qty );
        }

        if ( $cart[ $id ]['qty'] == 0 )
            unset( $cart[ $id ] );
    }

    public static function remove( $id ) {
        $cart = &self::items();
        unset( $cart[ $id ] );
    }

    public static function destroy() {
        unset( $_SESSION['cart'] );
    }

    public static function persist() {
        $cart = &self::items();

        if (empty($cart)) return;

        $name = str_replace( "'", "&rsquo;", gett('name') );
        $adds = str_replace( "'", "&rsquo;", gett('adds') );
        $city = str_replace( "'", "&rsquo;", gett('city') );
        $phone = str_replace( "'", "&rsquo;", gett('phone') );
        $email = str_replace( "'", "&rsquo;", gett('email') );
        $content = str_replace( "'", "&rsquo;", gett('content') );
        $dates = date( "Y-m-d H:i:s" );

        $cartId = DB::insert("INSERT INTO cart(name, adds, phone, email, descript, dates, city) VALUES ('$name', '$adds', '$phone', '$email', '$content', '$dates', '$city')");

        foreach ( $cart as $id => $item ) {
            $qty = $item['qty'];
            DB::insert("INSERT INTO cartdetail(cartId, productId, qty) VALUES ($cartId, $id, $qty)");
        }

        self::destroy();
    }
}
