<?php
include '../loader.php';
include "_components.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrator Panel</title>
    <link href="/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/bower_components/jasny-bootstrap/dist/css/jasny-bootstrap.min.css" rel="stylesheet">
    <link href="/bower_components/components-font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/css/admin.css" rel="stylesheet">
    <script src="/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="/bower_components/ckeditor/ckeditor.js"></script>
</head>
<body>

    <?php require "navigation.php"; ?>

    <div class="container-fluid">
        <?php
        include "_global.php";

        if ( function_exists( 'mainProcess' ) )
            echo mainProcess();
        ?>
    </div>

    <script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/bower_components/jasny-bootstrap/dist/js/jasny-bootstrap.min.js"></script>
    <script src="/bower_components/holderjs/holder.min.js"></script>
    <script src="/bower_components/trmix/dist/trmix.min.js"></script>
    <script>
        function confirmDelete(form) {
            var $tr = $(form).closest('tr');
            $tr.addClass('danger');
            var ok = confirm("Bạn có chắc là muốn xóa?");
            $tr.removeClass('danger');
            return ok;
        }
    </script>
</body>
</html>
