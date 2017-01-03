<?php

$table = 'ad_user';
$list_url = "index.php?act=".gett('act');
$list = DB::select("SELECT * FROM {$table}");
$delete_id = postt('did');
$edit_id = gett('eid');
if ($edit_id)
   $form = new Form(safe_html(DB::select_one("SELECT * FROM {$table} WHERE id={$edit_id}")));
else
   $form = new Form();

if (postt('do') == 'delete') {
   if ($delete_id) {
      DB::delete($table, $delete_id);
      flash('success', "Tài khoản đã được xóa.");
      redirect($list_url);
   }
}

if (postt('do') == 'update') {

   escape_post('email');

   $fields = 'email,power';
   if (gett('pwd')) {
      $fields .= ',pwd';
      $_POST['pwd'] = md5( postt('pwd') );
   }

   if ($edit_id) {
      DB::execute(post_to_update($table, $fields, $edit_id));
      flash('success', "Tài khoản đã được cập nhật.");
   } else {
      DB::execute(post_to_insert($table, $fields));
      flash('success', "Tài khoản đã được tạo.");
   }

   redirect($list_url);
}

function get_power( $power ) {
   switch ( $power ) {
      case 1: return "Administrator";
      case 2: return "Moderator";
      case 3: return "Writer";
      case 4: return "Transporter";
   }
}

?>

<? breadcrumbs("Quản lý người dùng") ?>
<? flash_out() ?>

<div class="table-responsive">
   <table class="table table-bordered table-striped">
      <thead>
         <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Quyền hạn</th>
            <th>Đăng nhập lần cuối</th>
            <th align="center" style="width:12% !important">Options</th>
         </tr>
      </thead>
      <tbody>
         <?
         foreach ($list as $row) {
            extract((array)$row);
            $edit_url = "{$list_url}&eid={$id}";
         ?>
            <tr class="<?= $edit_id==$id ? 'warning' : '' ?>">
               <td>
                  <small><?= $id ?></small>
               </td>
               <td>
                  <?= $email ?>
               </td>
               <td>
                  <?= get_power($power) ?>
               </td>
               <td>
                  <?= date("Y-m-d H:i:s", strtotime($lastOnl)) ?>
               </td>
               <td align="center">
                  <? if ( $edit_id == $id ) { ?>
                     <a href="<?= $list_url ?>" class="btn btn-link"><i class="fa fa-refresh"></i></a>
                  <? } else { ?>
                     <a href="<?= $edit_url ?>" class="btn btn-link"><i class="fa fa-pencil"></i></a>
                  <? } ?>
                  <? delete_button('did', $id); ?>
               </td>
            </tr>
         <? } ?>
      </tbody>
   </table>
</div>

<form enctype="multipart/form-data" method="post">
   <div class="panel panel-default">
      <div class="panel-heading">
         Cập nhật - Thêm mới thông tin
      </div>
      <div class="panel-body">
         <div class="row">

            <div class="col-lg-4">
               <? $form->email('email', "Email:", TRUE) ?>
            </div>
            <div class="col-lg-4">
               <? $form->pwd() ?>
            </div>

            <div class="col-lg-4">
               <?
               $form->select('power', "Phân quyền:", array(
                  1 => get_power(1),
                  2 => get_power(2),
                  3 => get_power(3),
                  4 => get_power(4)
               ));
               ?>
            </div>

            <div class="col-lg-12">
               <input type="hidden" name="do" value="update">
               <input type="hidden" name="eid" value="<?= $edit_id ?>"/>
               <? if ( $edit_id ) { ?>
                  <button type="submit" class="btn btn-primary">Update</button>
               <? } else { ?>
                  <button type="submit" class="btn btn-primary">Submit</button>
               <? } ?>
               <button type="reset" class="btn btn-default" id="reset">Reset</button>
            </div>

         </div>
      </div>
   </div>
</form>
