<?php

$type = get_view();
$current_id = gett('id');
$current = tt(DB::select_one("SELECT * FROM news WHERE id={$current_id} AND active=1 AND view='{$type}'"));

global $page_vars;
$page_vars = (array) $current;
