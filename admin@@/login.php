<?php

include "../loader.php";
include "_components.php";

if ( isset( $_SESSION[ "ad_user" ] ) )
    redirect('./');

function checkLogin() {
    if ( postt('login') ) {
        $email = postt('email');
        $passwd = postt('password');

        if ( !$email || !$passwd ) return flash('danger', "Bạn phải nhập email và mật khẩu!");

        $user = DB::select_one("SELECT * FROM ad_user WHERE email='{$email}'");
        if ( !$user ) return flash('danger', "Tài khoản này không tồn tại!");
        if ( md5($passwd) != $user->pwd ) return flash('danger', "Mật khẩu không đúng!");

        sessionn('ad_user', $email);
        DB::execute("UPDATE ad_user SET lastOnl=NOW() WHERE email='{$email}'");
        redirect('./');
    }
}

checkLogin();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Administrator Panel</title>
    <link href="/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/bower_components/components-font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="/css/admin.css" rel="stylesheet">
</head>
<body class="page-login">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">ĐĂNG NHẬP</h3>
        </div>
        <? flash_out() ?>
        <div class="panel-body">
            <form role="form" method="post" action="">
                <fieldset>
                    <div class="form-group">
                        <input class="form-control" placeholder="Email" name="email" type="email" autofocus value="<?= submitted('email') ?>">
                    </div>
                    <div class="form-group">
                        <input class="form-control" placeholder="Password" name="password" type="password" value="">
                    </div>
                    <!--
                    <div class="checkbox">
                        <label>
                            <input name="remember" type="checkbox" value="Remember Me">Remember Me
                        </label>
                    </div>
                    -->
                    <input class="btn btn-primary btn-block" type="submit" name="login" value="Sign in"/>
                </fieldset>
            </form>
        </div>
    </div>

    <script src="/bower_components/trmix/dist/trmix.min.js"></script>
</body>
</html>
