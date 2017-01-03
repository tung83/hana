<div id="newproducts">
    <div id="newproducts_top">
        <div id="newproducts_bottom">
            <div id="newproducts_display">
                <div id="newproducts_title"><span class="title"><?= t("Sản phẩm mới", "New Products") ?></span></div>
                <div id="newproducts_list">
                    <div id="list">
                        <?php
                        /*
                        $new_products = new cproducts();
                        $new_products->TableVar = "right_new_product";
                        $new_products->setSessionWhere( "`Language`=" . $proLangID->getLangID() . " AND `Special`=1" );
                        $rs_NewProducts = $conn->Execute( $new_products->SelectSQL() );
                        if ( !$rs_NewProducts->EOF ) {
                            $rs_NewProducts->MoveFirst();
                            $iCount = 0;
                            while ( !$rs_NewProducts->EOF && $iCount < 5 ) {
                                $new_products->LoadListRowValues( $rs_NewProducts );
                                $new_products->RenderListRow();
                                ?>
                                <div style=" margin-bottom:12px;">
                                    <div style="width:auto;">
                                        <img src="/admin/<?php echo ew_UploadPathEx( FALSE, EW_UPLOAD_DEST_PATH ) . $new_products->Image->Upload->DbValue ?>" width="96" height="69" border="0" align="absmiddle" style="margin-right:7px;"/>
                                        <a href="/products/products/view/<?php echo $new_products->ViewUrl(); ?>"><?php echo $new_products->Title->ViewValue; ?></a>
                                    </div>
                                </div>
                                <?php
                                $iCount++;
                                $rs_NewProducts->MoveNext();
                            }
                        }
                        */
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
