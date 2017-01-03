<?php

$msg = '';
if ( $_POST[ "Del" ] == 1 ) {
    if ( DB::delete( 'contact', $_POST[ 'idLoad' ] ) ) {
        header( "location:" . $_SERVER[ 'REQUEST_URI' ], true );
    } else {
        $msg = DB::error();
    }
}

?>

<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-dashboard"></i> Liên hệ
            </li>
        </ol>
    </div>
</div>

<?php if ( ! empty($msg) ) : ?>
    <div class="alert alert-danger" role="alert" style="margin-top:10px"><?= $msg ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>Họ Tên</th>
                    <th>Địa chỉ</th>
                    <th>Điện thoại</th>
                    <th>Fax</th>
                    <th>Email</th>
                    <th>Nội dung</th>
                    <th>Ngày gửi</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT * FROM contact ORDER BY id DESC";
                $contacts = DB::select( $sql );
                $pager = new Pager( $contacts, $_GET['page'] );
                foreach ( $pager->get() as $row ) : extract( (array) $row ); ?>
                    <tr>
                        <td><?= $name ?></td>
                        <td><?= $adds ?></td>
                        <td><?= $phone ?></td>
                        <td><?= $fax ?></td>
                        <td><a href="mailto:<?= $email ?>"><?= $email ?></a></td>
                        <td><?= $content ?></td>
                        <td><?= fdate( $dates ); ?></td>
                        <td align="center">
                            <a href="javascript:operationFrm(<?= $id; ?>,'D')" class="glyphicon glyphicon-trash" aria-hidden="true"></a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
        <?= $pager->render() ?>
    </div>
</div>
<form role="form" name="actionForm" enctype="multipart/form-data" action="" method="post">
    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" name="idLoad" value="<?= $_POST[ "idLoad" ] ?>"/>
            <input type="hidden" name="Edit"/>
            <input type="hidden" name="Del"/>
        </div>
    </div>
</form>
