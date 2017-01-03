<?php
if ( isset( $_POST[ "submit" ] ) ) {
    Cart::persist();
    $msg = t('Giỏ hàng của bạn đã được gởi đi! Vui lòng chờ phản hồi của chúng tôi!', 'Your order has been placed');
    $homeUrl = url('trang-chu');
    echo "<script>alert('$msg');location.href='$homeUrl';</script>";
} else {
    if ( !count( Cart::items() ))
        return redirect(url('gio-hang'));
}

$pageM = t('Đặt hàng', 'Checkout');
$customerInfoM = t('Thông tin khách hàng', 'Customer info');
$requireM = '<i class="required"></i> ' . t('Thông tin yêu cầu.', 'Compulsory fields');
$nameM = t('Họ và tên', 'Full name') . ' <i class="required"></i>';
$addressM = t('Địa chỉ', 'Address') . ' <i class="required"></i>';
$cityM = t('Thành phố', 'City') . ' <i class="required"></i>';
$phoneM = t('Điện thoại', 'Phone number') . ' <i class="required"></i>';
$emailM = 'Email <i class="required"></i>';
$noteM = t('Ghi chú', 'Note');
$submitM = t('Gởi', 'Submit');
$resetM = t('Làm lại', 'Reset');

$chkOut = count( Cart::items() );
$checkoutUrl = url('dat-hang');
?>

<div class="container"><div class="row">
    <main class="col-sm-12">
        <div class="section__head"><span><?= $pageM ?></span></div>
        <article class="card">

            <div class="msg">
                <h3><?= $customerInfoM ?></h3>
                <small class="require-msg"><?= $requireM ?></small>
            </div>

            <form method="POST" name="frmCart" class="form-horizontal">
                <div class="form-group">
                    <label for="name" class="control-label col-sm-3"><?= $nameM ?></label>
                    <div class="col-sm-7">
                        <input type="text" name="name" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="adds" class="control-label col-sm-3"><?= $addressM ?></label>
                    <div class="col-sm-7">
                        <input type="text" name="adds" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="city" class="control-label col-sm-3"><?= $cityM ?></label>
                    <div class="col-sm-7">
                        <input type="text" name="city" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone" class="control-label col-sm-3"><?= $phoneM ?></label>
                    <div class="col-sm-7">
                        <input type="text" name="phone" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="control-label col-sm-3"><?= $emailM ?></label>
                    <div class="col-sm-7">
                        <input type="text" name="email" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="content" class="control-label col-sm-3"><?= $noteM ?></label>
                    <div class="col-sm-7">
                        <textarea name="content" class="form-control" rows="5"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-7 col-sm-offset-3">
                        <input type="submit" class="btn btn-primary" value="<?= $submitM ?>">
                        <input type="reset" class="btn" value="<?= $resetM ?>">
                    </div>
                </div>
            </form>
        </article>
    </main>
</div>
