<?php

global $page_vars;
$page_vars = array(
    'title' => t( "Tin tức", "News" )
    );

$type = get_view();
if ($type == 'recruitment') {
    $page_vars['title'] = t("Tuyển dụng", "Recruitment");
}

if ($type == 'world') {
    $page_vars['title'] = t("Nhìn ra thế giới");
}
