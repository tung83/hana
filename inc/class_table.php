<?php

class Table
{
    public static function all( $table ) {
        return tt(DB::select("SELECT * FROM ${table}"));
    }

    public static function id( $table, $id ) {
        return self::one( $table, "id=${id}");
    }

    public static function one( $table, $where ) {
        return tt(DB::select_one("SELECT * FROM ${table} WHERE ${where}"));
    }
}
