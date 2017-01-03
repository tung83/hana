<?php

function mainProcess() {
	if (gett('pjid')) return project_photos();
	return projects();
}

function projects() {

	$table = 'project';
	$list_url = "index.php?act=".gett('act');
	$list = DB::select("SELECT * FROM {$table} ORDER BY id DESC");
	$delete_id = postt('did');
	$edit_id = gett('eid');
	if ($edit_id)
		$form = new Form(safe_html(DB::select_one("SELECT * FROM {$table} WHERE id={$edit_id}")));
	else
		$form=  new Form();

	if (postt('do') == 'delete') {
		if ($delete_id) {
			delete_attachment("$table.img", $delete_id);
			DB::delete($table, $delete_id);
			flash('success', "Dự án đã được xóa.");
			redirect($list_url);
		}
	}

	if (postt('do') == 'update') {

		escape_post('title,eTitle,sum,eSum,content,eContent,desc,eDesc,keyword,eKeyword');

		$fields = 'active,title,eTitle,sum,eSum,content,eContent,desc,eDesc,keyword,eKeyword';
		$fileName = handleUploaded('file');
		if ( !empty( $fileName ) ) {
			$fields .= ',img';
			$_POST['img'] = $fileName;
			if ($edit_id)
				$oldFileName = get_attachment("{$table}.img", $edit_id);
		}

		if ($edit_id) {
			DB::execute(post_to_update($table, $fields, $edit_id));
			@unlink($oldFileName);
			flash('success', "Dự án đã được cập nhật.");
		} else {
			DB::execute(post_to_insert($table, $fields));
			flash('success', "Dự án đã được tạo.");
		}

		redirect($list_url);
	}
	?>

	<? breadcrumbs("Dự án") ?>
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
				<? foreach ( $list as $row ) {
					extract((array)$row);
					$edit_url = "{$list_url}&eid=${id}";
					$view_url = "{$list_url}&pjid=${id}";
				?>
					<tr class="<?= $edit_id==$id ? 'warning' : '' ?>">
						<td>
							<small><?= $id ?></small>
						</td>
						<td>
							<a href="<?= $view_url ?>"><?= $title ?></a>
						</td>
						<td>
							<? if (!empty($img)) { ?>
								<img src="<?= PATH_UPLOAD . $img ?>" width="100" height="80"/>
							<? } else { ?>
								<!-- <img data-src="holder.js/100x80" alt=""> -->
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
							<? if ( gett('e') == $id ) { ?>
								<a href="<?= $list_url ?>" class="btn btn-link"><i class="fa fa-refresh"></i></a>
							<? } else { ?>
								<a href="<?= $edit_url ?>" class="btn btn-link"><i class="fa fa-pencil"></i></a>
							<? } ?>
							<a href="<?= $view_url ?>" class="btn btn-link"><i class="fa fa-photo"></i></a>
							<? delete_button('did',$id); ?>
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
						$form->title();
						$form->desc();
						$form->keyword();
						?>

					</div>
					<div class="col-lg-6">

						<?
						$form->eTitle();
						$form->eDesc();
						$form->eKeyword();
						?>

					</div>
					<div class="col-lg-3">

						<?
						$form->active();
						$form->img(PRODUCT_THUMB_WIDTH, PRODUCT_THUMB_HEIGHT);
						?>
						<div>
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
					<div class="col-lg-9">

						<div class="form-group">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#vietnamese" data-toggle="tab">Chi Tiết</a></li>
								<li><a href="#english" data-toggle="tab">Chi Tiết - English</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="vietnamese">
									<textarea name="content" class="ckeditor"><?= $edit->content ?></textarea>
								</div>
								<div class="tab-pane" id="english">
									<textarea name="eContent" class="ckeditor"><?= $edit->eContent ?></textarea>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</form>
<?
}

function project_photos() {

	$project_id = gett('pjid');
	$project = DB::select_one("SELECT * FROM project WHERE id={$project_id}");
	$project_list_url = "index.php?act=".gett('act');
	$table = 'project_photos';
	$list_url = "index.php?act=".gett('act')."&pjid=${project_id}";
	$list = DB::select("SELECT * FROM {$table} WHERE pId={$project_id} ORDER BY id DESC");
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
			flash('success', "Hình ảnh đã được xóa.");
			redirect($list_url);
		}
	}

	if (postt('do') == 'update') {

		escape_post('title,eTitle');

		$fields = 'active,title,eTitle,pId,ind';
		$fileName = handleUploaded('file', PROJECT_SLIDE_WIDTH, PROJECT_SLIDE_HEIGHT);
		if ( !empty( $fileName ) ) {
			$fields .= ',img';
			$_POST['img'] = $fileName;
			if ($edit_id)
				$oldFileName = get_attachment("{$table}.img", $edit_id);
		}

		if ($edit_id) {
			DB::execute(post_to_update($table, $fields, $edit_id));
			@unlink($oldFileName);
			flash('success', "Hình ảnh đã được cập nhật.");
		} else {
			DB::execute(post_to_insert($table, $fields));
			flash('success', "Hình đã được thêm.");
		}

		redirect($list_url);
	}
	?>

	<? breadcrumbs(array(
		"Dự án" => $project_list_url,
		$project->title => '')) ?>
	<? flash_out() ?>

	<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Hình ảnh</th>
					<th>Tiêu đề</th>
					<th align="center">Thứ tự</th>
					<th align="center">Hiển thị</th>
					<th align="center" style="width:12% !important">Options</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ( $list as $row ) {
					extract((array)$row);
					$edit_url = "{$list_url}&eid=${id}";
				?>
					<tr class="<?= $edit_id==$id ? 'warning' : '' ?>">
						<td>
							<? if (!empty($img)) { ?>
								<img src="<?= PATH_UPLOAD . $img ?>" width="100" height="80"/>
							<? } else { ?>
								<!-- <img data-src="holder.js/100x80" alt=""> -->
							<? } ?>
						</td>
						<td>
							<?= $title ?>
						</td>
						<td align="center">
							<small><?= $ind ?></small>
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
							<? delete_button('did',$id); ?>
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

						<? $form->img(PROJECT_SLIDE_WIDTH, PROJECT_SLIDE_HEIGHT) ?>

					</div>
					<div class="col-lg-6">

						<?
						$form->title();
						$form->eTitle();
						$form->ind();
						$form->active();
						?>

					</div>
					<div class="col-lg-12">

						<input type="hidden" name="do" value="update">
						<input type="hidden" name="eid" value="<?= $edit_id ?>"/>
						<input type="hidden" name="pId" value="<?= $project_id ?>"/>
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
<?
}
