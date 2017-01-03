<?php

define('NEWS_TYPE', 'news');

$table = 'news';
$list_url = "index.php?act=".gett('act');
$list = DB::select("SELECT * FROM {$table} WHERE view='".NEWS_TYPE."' ORDER BY id DESC");
$delete_id = postt('did');
$edit_id = gett('eid');
if ($edit_id)
	$form = new Form(safe_html(DB::select_one("SELECT * FROM {$table} WHERE id={$edit_id}")));
else
	$form = new Form();

if (postt('do') == 'delete') {
	if ($delete_id) {
		delete_attachment("$table.img", $delete_id);
		DB::delete($table, $delete_id);
		flash('success', "Bài viết đã được xóa.");
		redirect($list_url);
	}
}

if (postt('do') == 'update') {

	escape_post('title,eTitle,content,eContent,desc,eDesc,keyword,eKeyword,view');

	$fields = 'active,title,eTitle,content,eContent,desc,eDesc,keyword,eKeyword';
	$fileName = handleUploaded('file', NEWS_THUMB_WIDTH, NEWS_THUMB_HEIGHT);
	if ( !empty( $fileName ) ) {
		$fields .= ',img';
		$_POST['img'] = $fileName;
		if ($edit_id)
			$oldFileName = get_attachment("{$table}.img", $edit_id);
	}

	if ($edit_id) {
		DB::execute(post_to_update($table, $fields, $edit_id));
		@unlink($oldFileName);
		flash('success', "Bài viết đã được cập nhật.");
	} else {
		DB::execute(post_to_insert($table, $fields));
		flash('success', "Bài viết đã được tạo.");
	}

	redirect($list_url);
}
?>

<? breadcrumbs("Tin tức") ?>
<? flash_out() ?>

<div class="table-responsive">
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>ID</th>
				<th>Tiêu đề</th>
				<th>Hình ảnh</th>
				<th align="center">Hiển thị</th>
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
						<?= $title ?>
					</td>
					<td>
						<? if (!empty($img)) { ?>
							<img src="<?= PATH_UPLOAD.$img ?>" alt="" style="height:80px">
						<? } ?>
					</td>
					<td align="center">
						<? if ($active) { ?>
							<i class="fa fa-check-square-o"></i>
						<? } else { ?>
							<i class="fa fa-square-o"></i>
						<? } ?>
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

				<div class="col-lg-12">
					<?
					$form->title();
					$form->sum();
					$form->desc();
					$form->keyword();
					?>
				</div>
				<div class="col-lg-6">
					<?
					// $form->eTitle();
					// $form->eSum();
					// $form->eDesc();
					// $form->eKeyword();
					?>
				</div>

			</div>
			<div class="row">

				<div class="col-lg-3">
					<?
					$form->active();
					$form->img(NEWS_THUMB_WIDTH, NEWS_THUMB_HEIGHT);
					?>
					<div>
						<input type="hidden" name="do" value="update">
						<input type="hidden" name="eid" value="<?= $edit_id ?>"/>
						<input type="hidden" name="view" value="<?= NEWS_TYPE ?>"/>
						<? if ( $edit_id ) { ?>
							<button type="submit" class="btn btn-primary">Update</button>
						<? } else { ?>
							<button type="submit" class="btn btn-primary">Submit</button>
						<? } ?>
						<button type="reset" class="btn btn-default" id="reset">Reset</button>
					</div>
				</div>
				<div class="col-lg-9">
					<div class="form-group">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#vietnamese" data-toggle="tab">Chi tiết</a></li>
							<!-- <li class="active"><a href="#vietnamese" data-toggle="tab">Việt Nam</a></li> -->
							<!-- <li><a href="#english" data-toggle="tab">English</a></li> -->
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="vietnamese">
								<? $form->content() ?>
							</div>
							<div class="tab-pane" id="english">
								<? //$form->eContent() ?>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</form>
