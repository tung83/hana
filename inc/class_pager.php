<?php

class Pager
{
    private $page;
    private $pageSize;
    private $pageCount;
    private $records = array();
    private $first;
    private $last;
    private $prev;
    private $next;
    private $forceDisplay = false;

    function __construct( $records, $page = 1, $pageSize = 10, $firstLabel = '«', $prevLabel = '<', $nextLabel = '>', $lastLabel = '»' ) {
        $this->pageSize = max( intval( $pageSize ), 1 );
        $this->pageCount = (int)ceil( count( $records ) / $this->pageSize );
        $this->page = min( max( intval( $page ), 1 ), $this->pageCount );
        $this->records = array_slice( $records, ( $this->page - 1 ) * $this->pageSize, $this->pageSize );
        $this->first = $firstLabel;
        $this->last = $lastLabel;
        $this->prev = $prevLabel;
        $this->next = $nextLabel;
    }

    public function get() {
        return $this->records;
    }

    public function render( $queryParam = "page" ) {

        if ( !$this->forceDisplay && $this->pageCount < 2 )
            return '';

        $str = "<nav><ul class=\"pagination pagination-sm\">";

        if ( !empty( $this->first ) )
            $str .= $this->render_button( 1, $this->first, 1 < $this->page );

        if ( !empty( $this->prev ) )
            $str .= $this->render_button( $this->page - 1, $this->prev, 1 < $this->page );

        for ( $page = 1; $page <= $this->pageCount; $page++ )
            $str .= $this->render_button( $page, $page, 1, 1 );

        if ( !empty( $this->next ) )
            $str .= $this->render_button( $this->page + 1, $this->next, $this->page < $this->pageCount );

        if ( !empty( $this->last ) )
            $str .= $this->render_button( $this->pageCount, $this->last, $this->page < $this->pageCount );

        $str .= "</ul></nav>";

        echo $str;
    }

    private function render_button( $page, $label, $clickable = 1, $activable = 0 ) {
        $queryParam = 'page';

        if ( $clickable ) {
            $active = $activable && ( $page == $this->page );
            if ( $active )
                return '<li class="active"><span>' . $label . '</span></li>';
            else
                return '<li><a href="' . self::build_url( $queryParam, $page ) . '">' . $label . '</a></li>';
        } else {
            return '<li class="disabled"><span>' . $label . '</span></li>';
        }
    }

    private static function build_url( $name, $value ) {
        $params = $_GET;
        $params[ $name ] = $value;

        // blacklist fields
        unset($params['id']);
        unset($params['view']);

        $url = strtok( $_SERVER[ "REQUEST_URI" ], '?' ) . '?' . http_build_query( $params );
        return $url;
    }

    public function count() {
        return $this->pageCount;
    }
}
