<?php

$list_url = "index.php?act=".gett('act');
$table = 'promo';
$list = DB::select("SELECT * FROM {$table} ORDER BY ind DESC");
$delete_id = postt('did');
$edit_id = gett('eid');
if ($edit_id)
	$form = new Form(safe_html(DB::select_one("SELECT * FROM {$table} WHERE id={$edit_id}")));
else
	$form = new Form();

if (postt('do') == 'delete') {
	if ($delete_id) {
		delete_attachment("{$table}.img", $delete_id);
		DB::delete($table, $delete_id);
		flash('success', "Hình ảnh đã được xóa.");
		redirect($list_url);
	}
}

if (postt('do') == 'update') {

	escape_post('lnk,title,content');

	$fieldsToProcess = 'active,ind,lnk,title,content';
	$fileName = handleUploaded('file', PROMO_BANNER_WIDTH, PROMO_BANNER_HEIGHT);
	if ( !empty( $fileName ) ) {
		$fieldsToProcess .= ',img';
		$_POST['img'] = $fileName;
		if ($edit_id)
			$oldFileName = get_attachment("{$table}.img", $edit_id);
	}

	if ($edit_id) {
		DB::execute(post_to_update($table, $fieldsToProcess, $edit_id));
		@unlink( $oldFileName );
		flash('success', "Slide đã được cập nhật.");
	} else {
		DB::execute(post_to_insert($table, $fieldsToProcess));
		flash('success', "Slide đã được tạo.");
	}

	redirect($list_url);
}
?>

<? breadcrumbs('Khuyến mãi') ?>
<? flash_out() ?>
<div class="table-responsive">
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>Hình ảnh</th>
				<th>Liên kết</th>
				<th class="text-center">Thứ tự</th>
				<th class="text-center">Hiển thị</th>
				<th style="width:12% !important" class="text-center">Options</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($list as $row) {
				extract((array)$row);
				$edit_url = "{$list_url}&eid=${id}";
			?>
				<tr class="<?= $edit_id == $id ? 'warning' : '' ?>">
					<td>
						<img src="<?= PATH_UPLOAD . $img ?>" style="height:80px"/>
					</td>
					<td><?= $lnk ?></td>
					<td class="text-center">
						<small><?= $ind ?></small>
					</td>
					<td class="text-center">
						<? if ($active) { ?>
							<i class="fa fa-check-square-o"></i>
						<? } else { ?>
							<i class="fa fa-square-o"></i>
						<? } ?>
					</td>
					<td class="text-center">
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
		<div class="panel-heading">Cập nhật - Thêm mới thông tin</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-lg-6">
					<?
					$form->lnk();
					$form->ind();
					$form->active();
					$form->img(PROMO_BANNER_WIDTH, PROMO_BANNER_HEIGHT);
					?>
				</div>
				<div class="col-lg-6">
					<?
					// $form->title();
					// $form->textarea('content', "Nội dung:");
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
