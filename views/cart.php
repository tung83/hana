<?php

if ( gett('act') == 'add' ) {
    Cart::add(gett('id'));
    return redirect(url('cart'));
}

if ( gett('act') == 'remove' ) {
    Cart::remove(gett('id'));
    return redirect(url('cart'));
}

if ( gett('act') == 'empty' ) {
    Cart::destroy();
    return redirect(url('cart'));
}

if ( gett('act') == 'update' ) {
    foreach ( Cart::items() as $id => $item ) {
        $qty = postt('qty-' . $id);
        Cart::add( $id, $qty, 1 );
    }
    return redirect(url('cart'));
}

$stt = '#';
$nameM = t( "Sản phẩm", "Product" );
$qtyM = t( "Số lượng", "Qty" );
$priceM = t( "Giá <sup>VNĐ</sup>", "Price <sup>USD</sup>" );
$subtotalM = t( "Tổng <sup>VNĐ</sup>", "Subtotal <sup>USD</sup>" );
$totalM = t( "Tổng cộng:", "Total" );
$continueM = t( "Tiếp tục mua hàng", "Continue shopping" );
$checkoutM = t( "Đặt hàng", "Checkout" );
$updateM = t( "Cập nhật giỏ hàng", "Update cart" );
$removeM = t( "Hủy bỏ", "Remove" );
$destroyM = t( "Hủy toàn bộ", "Empty cart" );
$destroyConfirmM = t( "Bạn có chắc hủy toàn bộ đơn hàng?", "Are you sure?" );
$emptyCartM = t( "Giỏ hàng của bạn không có sản phẩm nào", "Cart is empty" );
$shop_url = url('products');
$checkout_url = url('checkout');
$destroy_url = url('cart') . '/empty/';
$update_url = url('cart') . '/update/';

?>

<h2 class="section__head"><span>Giỏ Hàng Của Bạn</span></h3>
<div class="container">
    <article class="card">

    <form action="<?= $update_url ?>" method="POST">
    <div class="table-responsive">
        <table class="table cart">
            <thead>
                <tr>
                    <th align="center"><?= $stt ?></th>
                    <th width="60"></th>
                    <th><?= $nameM ?></th>
                    <th width="80"><?= $qtyM ?></th>
                    <th align="right"><?= $priceM ?></th>
                    <th align="right"><?= $subtotalM ?></th>
                    <th align="center"><?= $removeM ?></th>
                </tr>
            </thead>

            <? if ( !count( Cart::items() ) ) { ?>

                <tbody>
                    <tr class="empty-cart">
                        <td colspan="7" align="center" style="padding-top: 40px; padding-bottom: 40px;"><?= $emptyCartM ?></td>
                    </tr>
                </tbody>

            <? } else { ?>

                <tbody>

                <?

                $total = 0;
                $i = 0;

                foreach ( Cart::items() as $id => $item ) {
                    $i++;

                    $qty = $item[ 'qty' ];

                    $row = tt(DB::select_one("SELECT * FROM products WHERE id={$id}"));
                    extract( (array)$row );
                    $url = url('products', $id, $title);

                    $subtotal = $qty * $price;
                    $total += $subtotal;

                    $img = empty( $img ) ? '' : PATH_UPLOAD.$img;
                    $priceF = price($price);
                    $subtotalF = price($subtotal);

                    $removeUrl = url('cart') . '/remove/?id=' . $id;

                    ?>
                    <tr>
                        <td align="center"><?= $i ?></td>
                        <td>
                            <a href="<?= $url ?>">
                                <img src="<?= $img ?>" width="60"/>
                            </a>
                        </td>
                        <td>
                            <a href="<?= $url ?>"><?= $title ?></a>
                        </td>
                        <td align="center">
                            <input type="text" class="form-control" name="qty-<?= $id ?>" value="<?= $qty ?>"/>
                        </td>
                        <td align="right"><?= $priceF ?></td>
                        <td align="right"><?= $subtotalF ?></td>
                        <td align="center"><a href="<?= $removeUrl ?>"><i class="fa fa-times"></i></a></td>
                    </tr>
                    <?
                }

                $totalF = price( $total );

                ?>

                </tbody>
                <tfoot>
                    <tr>
                        <td align="right" colspan="5"><?= $totalM ?></td>
                        <td align="right" colspan="2"><?= $totalF ?></td>
                    </tr>
                </tfoot>
                <?
            }
        ?>

        </table>
    </div>
    <div class="buttons">
        <a href="<?= $shop_url ?>" class="btn btn-default pull-left"><?= $continueM ?></a>
        <button class="btn btn-primary"><?= $updateM ?></button>
        <!-- <a href="<?= $destroy_url ?>" onclick="return confirm('<?= $destroyConfirmM ?>')" class="btn btn-default"><?= $destroyM ?></a> -->
        <a href="<?= $checkout_url ?>" class="btn btn-default"><?= $checkoutM ?></a>
    </div>
    </form>

        </article>
    </div>
</div>
