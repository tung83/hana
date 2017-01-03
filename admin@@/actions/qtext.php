<?php

$table = 'qtext';
$list_url = "index.php?act=".gett('act');
$list = DB::select("SELECT * FROM {$table} ORDER BY id ASC");
$delete_id = postt('did');
$edit_id = gett('eid');
if ($edit_id)
    $form = new Form(safe_html(DB::select_one("SELECT * FROM {$table} WHERE id={$edit_id}")));
else
    $form = new Form();

if (postt('do') == 'update') {

    escape_post('content,eContent');

    $fields = 'content,eContent';

    if ($edit_id) {
        DB::execute(post_to_update($table, $fields, $edit_id));
        flash('success', "Text đã được cập nhật.");
    }

    $edit_url = "{$list_url}&eid={$edit_id}";
    redirect($edit_url);
}
?>

<? breadcrumbs("Quản lý text") ?>
<? flash_out() ?>

<div class="row">
    <div class="col-lg-3">

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tiêu đề</th>
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
                                <a href="<?= $edit_url ?>"><?= $title ?></a>
                            </td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>

    </div>
    <div class="col-lg-9">

        <form enctype="multipart/form-data" method="post">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Cập nhật - Thêm mới thông tin
                </div>
                <div class="panel-body">

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
                            <? $form->eContent() ?>
                        </div>
                    </div>

                    <br>

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
        </form>

    </div>
</div>
