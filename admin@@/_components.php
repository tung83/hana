<?php

function breadcrumbs( $text ) {
    if ( is_string( $text ) ) { ?>
        <ol class="breadcrumb">
            <li class="active">
                <?= $text ?>
            </li>
        </ol>
    <? } else { ?>
        <ol class="breadcrumb">
        <?
        $segments = $text;
        foreach ( $segments as $text => $url ) {
            if (!empty($url)) { ?>
                <li><a href="<?= $url ?>"><?= $text ?></a></li>
            <? } else { ?>
                <li><?= $text ?></li>
            <? }
        }
        ?>
        </ol>
    <? }
}

function flash_out() {
    foreach ( flash() as $flash ) { ?>
        <div class="alert alert-<?= $flash->type ?>" role="alert" style="margin-top:10px"><?= $flash->msg ?></div>
    <? }
}

function delete_button( $target, $id ) {
    ?>
    <form method="POST" style="display:inline" onsubmit="return confirmDelete(this);">
        <input type="hidden" name="do" value="delete"/>
        <input type="hidden" name="<?= $target ?>" value="<?= $id ?>"/>
        <button type="submit" class="btn btn-link">
            <span class="fa fa-trash-o"></span>
        </button>
    </form>
    <?
}

function image_input( $value, $width, $height ) {
    ?>
    <div class="fileinput fileinput-new" data-provides="fileinput" style="max-width: <?= $width ?>px;">
        <div class="fileinput-new" style="padding-top: <?= $height / $width * 100 ?>%;">
            <? if (!empty($value)) { ?>
                <img src="<?= PATH_UPLOAD.$value ?>" alt="">
            <? } else { ?>
                <div class="placeholder"><?= $width ?> &times; <?= $height ?></div>
            <? } ?>
        </div>
        <div class="fileinput-preview fileinput-exists" style="padding-top: <?= $height / $width * 100 ?>%;"></div>
        <div class="fileinput-buttons">
            <span class="btn btn-warning btn-xs btn-file">
                <span class="fileinput-new">Chọn hình</span>
                <span class="fileinput-exists">Chọn lại</span>
                <input type="file" name="file">
            </span>
            <a href="#" class="btn btn-warning btn-xs fileinput-exists" data-dismiss="fileinput">Bỏ chọn</a>
        </div>
    </div>
    <?
}

function uiswitch( $name, $checked = FALSE, $disabled = FALSE ) {
    $checked = $checked ? ' checked="checked"' : '';
    $disabled = $disabled ? ' disabled="disabled"' : '';
    ?>
    <label class="lbswitch">
        <input type="checkbox" name="<?= $name ?>"<?= $checked ?><?= $disabled ?>>
        <span></span>
    </label>
    <?
}

function dropzone($url, $files = array()) {
    ?>
        <form action="dropzone.php?act=upload&amp;<?= $url ?>" class="dropzone" id="dropzone"></form>
        <link href="/bower_components/dropzone/dist/min/dropzone.min.css" rel="stylesheet">
        <script src="/bower_components/dropzone/dist/min/dropzone.min.js"></script>
        <script>
            Dropzone.options.dropzone = {
                acceptedFiles: 'image/*',
                addRemoveLinks: true,
                dictRemoveFileConfirmation: "Are you sure to delete this photo?",
                // previewTemplate: '<div class="dropzone_img"><img data-dz-thumbnail></div>',
                init: function() {
                    var dropzone = this;
                    var files = <?= json_encode($files) ?>;
                    $.each(files, function (key, value) {
                        var file = { name: value.img };
                        dropzone.options.addedfile.call(dropzone, file);
                        dropzone.options.thumbnail.call(dropzone, file, '<?= PATH_UPLOAD ?>' + value.img);
                        dropzone.emit("complete", file);
                    });
                },
                success: function (file, result) {
                    if (!result.success) {
                        alert("Upload failed.");
                        removePreview(file);
                    }
                },
                removedfile: function (file) {
                    var dropzone = this;
                    $.ajax({
                        type: 'POST',
                        url: 'dropzone.php?act=del&<?= $url ?>&img=' + encodeURIComponent(file.name),
                        dataType: 'json',
                        success: function (result) {
                            if (result.success)
                                removePreview(file);
                        },
                        fail: function () {
                            alert("Delete photo failed.");
                        }
                    });
                }
            }
            var removePreview = function (file) {
                $(file.previewElement).remove();
            }
        </script>
    <?
}
