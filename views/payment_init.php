<?php

$type = 'payment';
$current_id = gett('id');
$current_id = gett('id');
if ($current_id) {
    $current = tt(DB::select_one("SELECT * FROM news WHERE id={$current_id} AND active=1 AND view='{$type}'"));
} else {
    $current = tt(DB::select_one("SELECT * FROM news WHERE active=1 AND view='{$type}'"));
    $current_id = $current->id;
}

global $page_vars;
$page_vars = (array) $current;
