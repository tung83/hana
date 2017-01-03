<div class="container">
<div class="promo row">
<?
    $slides = DB::select( "SELECT * FROM news WHERE active=1 AND view='promo'" );
    foreach ( $slides as $row ) {
        extract( (array)$row );
        $url = url('promo', $id, $title);
        ?>
        <div class="col-sm-4">
            <a href="<?= $url ?>"><img src="<?= PATH_UPLOAD.$img ?>" alt="<?= $title ?>" title="<?= $title ?>"/></a>
        </div>
    <? }
?>
</div>
</div>
