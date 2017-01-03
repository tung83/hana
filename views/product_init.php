<?php

$prod_id = gett('id');
$prod = tt(DB::select_one("SELECT * FROM products WHERE active=1 AND id=${prod_id}"));

global $page_vars;
$page_vars = (array) $prod;
