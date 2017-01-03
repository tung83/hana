<?php

global $page_vars;

if (gett('id')) {

    $current = tt(DB::select_one("SELECT * FROM about WHERE id=".gett('id')." AND active=1"));
    $page_vars = (array) $current;

} else {

    $page_vars = array(
        );
}
