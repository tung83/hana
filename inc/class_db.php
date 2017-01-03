<?php

/**
 * Created by PhpStorm.
 * User: dangh
 * Date: 9/5/15
 * Time: 2:37 PM
 */
class DB
{
    static $conn;

    static function connect( $hostname, $username, $password, $database ) {
        self::$conn = @mysql_connect( $hostname, $username, $password ) or die( "DB connect failed: " . mysql_error() );
        mysql_select_db( $database, self::$conn ) or die( "DB select failed: " . self::error() );
        self::execute( 'SET NAMES utf8' );
    }

    public static function error() {
        return mysql_error( self::$conn );
    }

    public static function execute( $sql ) {
        $result = mysql_query( $sql, self::$conn );

        $error = self::error();
        if ( !empty( $error ) ) {
            var_dump( $sql );
            die( $error );
        }

        return $result;
    }

    public static function select_one( $sql ) {
        $array = self::select( $sql );
        return $array[ 0 ];
    }

    public static function select( $sql ) {
        $result = self::execute( $sql ) or die( "DB query failed:" . mysql_error() );
        $array = array();
        while ( $row = mysql_fetch_object( $result ) ) {
            $props = (array) $row;
            foreach ($props as $key => $value) {
                if ( is_string( $value ) ) {
                    $row->{$key} = stripslashes( $value );
                }
            }
            $array[] = $row;
        }

        return $array;
    }

    public static function select_page( $sql, $page = 1, $pageSize = 10 ) {
        $result = self::select( $sql );
        $page = max( intval( $page ), 1 );
        $pageSize = max( intval( $pageSize ), 1 );
        $pageCount = (int) ceil( count( $result ) / $pageSize );
        $page = min( $page, $pageCount );
        return array_slice( $result, ( $page - 1 ) * $pageSize, $pageSize );
    }

    public static function insert( $sql ) {
        self::execute( $sql );
        return mysql_insert_id();
    }

    public static function delete( $table, $id ) {
        $id = intval( $id );
        $sql = "DELETE FROM {$table} WHERE id = {$id}";
        return self::execute( $sql );
    }

    public static function count( $table, $where = '' ) {
        if (!empty($where))
            $WHERE = "WHERE {$where}";
        $result = self::select_one("SELECT COUNT(*) as total FROM {$table} {$WHERE}");
        return $result->total;
    }

    public static function get($table, $id) {
        return self::select_one("SELECT * FROM {$table} WHERE id={$id}");
    }
}
