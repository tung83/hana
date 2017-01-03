<?php
    $alert = t( "Thông tin của bạn đã được gửi đi.Chúng tôi sẽ phản hồi sớm nhất có thể. Xin cám ơn!", "Your contact have been sent. We will reply as soon as possible, Thanks!" );

    if ( postt('name') ) {

        escape_post('name,comp_name,adds,phone,fax,email,content');

        DB::insert(post_to_insert('contact', 'name,comp_name,adds,phone,fax,email,content,dates'));
        // send_mail( $name, $comp_name, $adds, $phone, $fax, $email, $content );

        ?><script>alert('<?= $alert ?>');</script><?
    }
?>
<div class="container"><div class="row">
    <main class="col-sm-9">
        <div class="section__head"><span><?= t("Liên hệ", "Contact") ?></span></div>
        <article class="card">
            <img src="/img/contact_cover.png" alt="" class="cover">
            <div class="msg"><?= t("Cám ơn quý khách đã ghé thăm website của chúng tôi. Để biết thêm chi tiết xin vui lòng liên hệ:", "Thanks for visiting our website. For more information, please contact:") ?></div>
            <div class="info"><?= qtext('contact') ?></div>
            <hr style="margin-top:30px;">
            <div class="msg">
                Hoặc vui lòng gởi thông tin liên hệ cho chúng tôi theo form dưới đây:<br/>
                <small>(Chú ý: Thông tin đánh dấu * là bắt buộc)</small>
            </div>
            <form name="fcontactusadd" id="fcontactusadd" action="" method="post" onsubmit="return ew_ValidateForm(this);" class="form-horizontal">
                <div class="form-group">
                    <label for="name" class="control-label col-sm-3"><?= t("Họ tên", "Name")?> *</label>
                    <div class="col-sm-8">
                        <input type="text" name="name" maxlength="125" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="comp_name" class="control-label col-sm-3"><?= t("Công ty", "Company") ?></label>
                    <div class="col-sm-8">
                        <input type="text" name="comp_name" maxlength="255" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="adds" class="control-label col-sm-3"><?= t("Địa chỉ", "Address") ?> *</label>
                    <div class="col-sm-8">
                        <input type="text" name="adds" maxlength="255" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone" class="control-label col-sm-3"><?= t("Điện thoại", "Phone") ?> *</label>
                    <div class="col-sm-8">
                        <input type="text" name="phone" maxlength="25" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="fax" class="control-label col-sm-3">Fax</label>
                    <div class="col-sm-8">
                        <input type="text" name="fax" maxlength="25" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="control-label col-sm-3">Email *</label>
                    <div class="col-sm-8">
                        <input type="text" name="email" maxlength="255" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="content" class="control-label col-sm-3"><?= t("Nội dung", "Message") ?> *</label>
                    <div class="col-sm-8">
                        <textarea name="content" rows="8" class="form-control"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-8 col-sm-offset-3">
                        <input type="submit" value="<?= t("Gửi", "Send") ?>" class="btn btn-primary">
                        <input type="reset" value="<?= t("Xóa", "Reset") ?>" class="btn btn-default">
                    </div>
                </div>
            </form>
        </article>
    </main>
    <aside class="col-sm-3">
        <?= render('top_products_widget') ?>
    </aside>
</div></div>
<script type="text/javascript">
    function ew_ValidateForm (form) {

        function empty (field) {
            if (!field.value) {
                field.focus();
                return true;
            }
            return false;
        }

        function email (field) {
            var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            if (!re.test(email)) {
                field.focus();
                return false;
            }
            return true;
        }

        function fail (msg) {
            alert(msg);
            return false;
        }

        if (empty(form.name)) return fail('<?= t("Bạn chưa nhập họ tên", "Please enter required field – Name") ?>');
        if (empty(form.adds)) return fail('<?= t("Bạn chưa nhập địa chỉ", "Please enter required field - Address") ?>');
        if (empty(form.phone)) return fail('<?= t("Bạn chưa nhập số điện thoại", "Please enter required field - Phone") ?>');
        if (empty(form.email)) return fail('<?= t("Bạn chưa nhập email", "Please enter required field - Email") ?>');
        if (!email(form.email)) return fail('<?= t("Địa chỉ email không đúng", "Incorrect email address") ?>');
        if (empty(form.content)) return fail('<?= t("Bạn chưa nhập nội dung", "Please enter required field - Content") ?>');

        return true;
    }
</script>
