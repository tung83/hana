<?php

$current_id = gett('id');
$current = tt(DB::select_one("SELECT * FROM project WHERE id=${current_id} AND active=1"));

global $page_vars;
$page_vars = (array) $current;
