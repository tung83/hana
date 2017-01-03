<?php

class Counter
{
    private static $values = null;

    public static function get( $name ) {

        if ( $values == null ) {
            self::$values = array();

            $rows = DB::select("SELECT * FROM counter");
            foreach ( $rows as $row ) {
                self::$values[$row->name] = $row->value;
            }
        }

        if ( !array_key_exists( $name, self::$values ) ) {
            return 0;
        }

        return self::$values[$name];
    }

    public static function inc( $name ) {
        $value = self::get( $name );

        $value++;

        self::$values[ $name ] = $value;

        DB::execute("INSERT INTO counter(name,value) VALUES('${name}',${value}) ON DUPLICATE KEY UPDATE value=${value}");

        return $value;
    }
}

class VisitCounter
{
    public static function get() {
        return Counter::get('visit');
    }

    public static function inc() {

        if ( empty( $_SESSION['visit_counted'] ) ) {
            $_SESSION['visit_counted'] = true;
            Counter::inc('visit');
        }

        return self::get();
    }
}
