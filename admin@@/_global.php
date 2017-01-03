<?php
include_once '../loader.php';

// authenticate
if ( !sessionn("ad_user") )
    redirect('login.php');

// render appropriate action
if (gett('act'))
    require 'actions/'.gett('act').'.php';
else
    require 'actions/dashboard.php';
