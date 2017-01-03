<?php

function mainProcess() {
	if ( gett('sid') ) return product_list();
	if ( gett('cid') ) return subcat_list();
	return category_list();
}

function category_list() {
	$table = 'categories';
	$list_url = "index.php?act=".gett('act');
	$list = DB::select("SELECT * FROM {$table} ORDER BY ind");
	$delete_id = postt('did');
	$edit_id = gett('eid');
	if ($edit_id)
		$form = new Form(safe_html(DB::select_one("SELECT * FROM {$table} WHERE id={$edit_id}")));
	else
		$form = new Form();

	if (postt('do') == 'delete') {
		if ($delete_id) {
			DB::delete($table, $delete_id);
			flash('success', "Danh mục đã được xóa.");
			redirect($list_url);
		}
	}

	if (postt('do') == 'update') {

		escape_post('title,eTitle');

		$fields = 'title,eTitle,active,ind';
		if ($edit_id) {
			DB::execute(post_to_update($table, $fields, $edit_id));
			flash('success', "Danh mục đã được cập nhật.");
		} else {
			DB::execute(post_to_insert($table, $fields));
			flash('success', "Danh mục đã được thêm.");
		}

		redirect($list_url);
	}
	?>

	<? breadcrumbs('Danh mục sản phẩm cấp 1') ?>
	<? flash_out() ?>

	<div class="table-responsive">
		<table class="table table-bordered table-hover table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Tiêu đề</th>
					<th>Thứ tự</th>
					<th align="center">Hiển thị</th>
					<th align="center" style="width:12% !important">Options</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($list as $row) {
					extract((array)$row);
					$edit_url = "{$list_url}&eid={$id}";
					$view_url = "{$list_url}&cid={$id}";
				?>
					<tr class="<?= $edit_id==$id ? 'warning' : '' ?>">
						<td>
							<small><?= $id ?></small>
						</td>
						<td>
							<a href="<?= $view_url ?>"><?= $title ?></a>
						</td>
						<td><?= $ind ?></td>
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
				<?php } ?>
			</tbody>
		</table>
	</div>

	<form enctype="multipart/form-data" method="post">
		<div class="panel panel-default">
			<div class="panel-heading">Cập nhật - Thêm mới thông tin</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12">
						<?
						$form->title();
						$form->ind();
						$form->active();
						?>
					</div>
					<div class="col-lg-6">
						<?
						// $form->eTitle();
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
	<?
}

function subcat_list() {
	$categories = DB::select("SELECT * FROM categories ORDER BY title");
	$category_id = gett('cid');
	$category = DB::select_one("SELECT * FROM categories WHERE id={$category_id}");
	$category_list_url = "index.php?act=".gett('act');
	$table = 'subcategories';
	$list_url = "index.php?act=".gett('act')."&cid=${category_id}";
	$list = DB::select("SELECT * FROM {$table} WHERE cId={$category_id} ORDER BY ind");
	$delete_id = postt('did');
	$edit_id = gett('eid');

	if ($edit_id)
		$form = new Form(safe_html(DB::select_one("SELECT * FROM {$table} WHERE id={$edit_id}")));
	else
		$form = new Form();

	if (postt('do') == 'delete') {
		if ($delete_id) {
			DB::delete($table, $delete_id);
			flash('success', "Danh mục đã được xóa.");
			redirect($list_url);
		}
	}

	if (postt('do') == 'update') {

		escape_post('title,eTitle');

		$fields = 'title,eTitle,active,ind,cId';

		if ($edit_id) {
			DB::execute(post_to_update($table, $fields, $edit_id));
			flash('success', "Danh mục đã được cập nhật.");
		} else {
			DB::execute(post_to_insert($table, $fields));
			flash('success', "Danh mục đã được thêm.");
		}

		redirect($list_url);
	}
	?>

	<? breadcrumbs("Danh mục sản phẩm cấp 2: <b>{$category->title}</b>") ?>
	<? flash_out() ?>

	<div class="table-responsive">
		<table class="table table-bordered table-hover table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Tiêu đề</th>
					<th align="center">Thứ tự</th>
					<th align="center">Hiển thị</th>
					<th align="center" style="width:12% !important">Options</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($list as $row) {
					extract((array)$row);
					$view_url = "{$list_url}&sid=${id}";
					$edit_url = "{$list_url}&eid=${id}";
				?>
					<tr class="<?= $edit_id==$id ? 'warning' : '' ?>">
						<td>
							<small><?= $id ?></small>
						</td>
						<td>
							<a href="<?= $view_url ?>"><?= $title ?></a>
							<div><i><a href="<?= $view_url ?>"><?= $eTitle ?></a></i></div>
						</td>
						<td align="center"><?= $ind ?></td>
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
				<?php } ?>
			</tbody>
		</table>
	</div>

	<form enctype="multipart/form-data" method="post">
		<div class="panel panel-default">
			<div class="panel-heading">Cập nhật - Thêm mới thông tin</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12">
						<?
						$form->title();
						?>
						<div class="form-group">
							<label>Thuộc chuyên mục:</label>
							<select name="cId" class="form-control">
								<? foreach ($categories as $c) { ?>
									<option value="<?= $c->id ?>"<?= selected($c->id == $category_id) ?>>
										<?= $c->title ?>
									</option>
								<? } ?>
							</select>
						</div>
						<?
						$form->ind();
						$form->active();
						?>
					</div>
					<div class="col-lg-6">
						<?
						// $form->eTitle();
						// $form->img(PRODUCT_THUMB_WIDTH, PRODUCT_THUMB_HEIGHT);
						?>
					</div>
					<div class="col-lg-12">
						<input type="hidden" name="do" value="update">
						<input type="hidden" name="eid" value="<?= $edit_id ?>"/>
						<input type="hidden" name="cId" value="<?= $category_id ?>">
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

function product_list() {
	$categories = DB::select("SELECT * FROM categories ORDER BY title");
	$sub_id = gett('sid');
	$sub = DB::select_one("SELECT * FROM subcategories WHERE id={$sub_id}");
	$sub_list_url = "index.php?act=".gett('act');
	$table = 'products';
	$list_url = "index.php?act=".gett('act')."&sid={$sub_id}&page=".gett('page');
	$list = DB::select("SELECT * FROM {$table} WHERE sId={$sub_id} ORDER BY id DESC");
	$delete_id = postt('did');
	$edit_id = gett('eid');

	$pager = new Pager( $list, gett('page'));
	$list = $pager->get();

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

		escape_post('title,eTitle,sum,eSum,content,eContent,price,ePrice,sku,status,desc,eDesc,keyword,eKeyword');

		$fields = 'title,eTitle,sum,eSum,content,eContent,active,sId,price,ePrice,sku,status,desc,eDesc,keyword,eKeyword';
		$fileName = handleUploaded('file', PRODUCT_THUMB_WIDTH, PRODUCT_THUMB_HEIGHT);
		if ( !empty( $fileName ) ) {
			$fields .= ',img';
			$_POST['img'] = $fileName;
			if ($edit_id)
				$oldFileName = get_attachment("{$table}.img", $edit_id);
		}

		if ($edit_id) {
			DB::execute(post_to_update($table, $fields, $edit_id));
			@unlink($oldFileName);
			flash('success', "Sản phẩm đã được cập nhật.");
		} else {
			DB::execute(post_to_insert($table, $fields));
			flash('success', "Sản phẩm đã được thêm.");
		}

		redirect($list_url);
	}
	?>

	<? breadcrumbs("Danh mục sản phẩm: <b>{$sub->title}</b>") ?>
	<? flash_out() ?>

	<div class="table-responsive">
		<table class="table table-bordered table-hover table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Tiêu đề</th>
					<th>Hình ảnh</th>
					<th>Thông tin</th>
					<th align="center">Hiển thị</th>
					<th align="center" style="width:12% !important">Options</th>
				</tr>
			</thead>
			<tbody>
				<?php
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
								<img src="<?= PATH_UPLOAD . $img ?>" class="img-responsive img-thumbnail" style="height:80px"/>
							<? } ?>
						</td>
						<td>
							<small>
								<div><b>SKU:</b> <?= $sku ?></div>
								<div><b>Giá (VND):</b> <?= priceVi( $price ) ?></div>
								<!-- <div><b>Giá (USD):</b> <?= priceEn( $price ) ?></div> -->
								<!-- <div><b>Tình trạng:</b> <?= status( $status ) ?></div> -->
							</small>
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
				<?php } ?>
			</tbody>
		</table>
	</div>

	<?= $pager->render() ?>

	<form enctype="multipart/form-data" method="post">
		<div class="panel panel-default">
			<div class="panel-heading">Cập nhật - Thêm mới thông tin</div>
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
					<div class="col-lg-12">
						<?
						// $form->eTitle();
						// $form->eSum();
						// $form->eDesc();
						// $form->eKeyword();
						?>
						<div class="form-group">
							<label>Thuộc danh mục:</label>
							<select name="sId" class="form-control">
								<? foreach ($categories as $c) {
									$subs = DB::select("SELECT * FROM subcategories WHERE cId={$c->id} ORDER BY title");
									if ( !empty( $subs ) ) { ?>
										<optgroup label="<?= $c->title ?>">
											<? foreach ($subs as $s) { ?>
												<option value="<?= $s->id ?>"<?= selected($s->id == $sub_id) ?>>
													<?= $s->title ?>
												</option>
											<? } ?>
										</optgroup>
									<? }
								} ?>
							</select>
						</div>
						<div class="form-group">
							<label>SKU:</label>
							<input class="form-control" name="sku" value="<?= $rowEdit->sku ?>"/>
						</div>
						<div class="form-group">
							<label>Giá <sup>(VND)</sup>:</label>
							<input class="form-control" name="price" value="<?= $rowEdit->price ?>"/>
						</div>
						<? $form->active() ?>
						<!--
						<div class="form-group">
							<label>Giá <sup>(USD)</sup>:</label>
							<input class="form-control" name="ePrice" value="<?= $rowEdit->ePrice ?>"/>
						</div>
						-->
					</div>
				</div>
				<div class="row">
					<div class="col-lg-3">
						<!--
						<div class="form-group">
							<label>Tình trạng:</label>
							<input class="form-control" name="status" value="<?= $rowEdit->status ?>"/>
						</div>
						-->
						<? $form->img(PRODUCT_THUMB_WIDTH, PRODUCT_THUMB_HEIGHT) ?>
					</div>
					<div class="col-lg-9">
						<div class="form-group">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#vietnamese" data-toggle="tab">Chi Tiết</a></li>
								<!-- <li><a href="#english" data-toggle="tab">Chi Tiết - English</a></li> -->
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="vietnamese">
									<textarea name="content" class="ckeditor"><?= $rowEdit->content ?></textarea>
								</div>
								<!--
								<div class="tab-pane" id="english">
									<textarea name="eContent" class="ckeditor"><?= $rowEdit->eContent ?></textarea>
								</div>
								-->
							</div>
						</div>
					</div>
					<div class="col-lg-12">
						<input type="hidden" name="do" value="update">
						<input type="hidden" name="eid" value="<?= $edit_id ?>">
						<input type="hidden" name="sId" value="<?= $sub_id ?>"/>
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
	if ($edit_id) {
		$photos = DB::select("SELECT * FROM product_photos WHERE pId={$edit_id}");
	?>
		<div class="panel panel-default">
			<div class="panel-heading">Hình ảnh sản phẩm</div>
			<div class="panel-body">
				<? dropzone("id={$edit_id}", $photos) ?>
			</div>
		</div>
	<? }
}
