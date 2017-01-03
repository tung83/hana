<?php
        $right_NewProjects = new cprojects();
        $right_NewProjects->TableVar = "right_projects";
        $right_NewProjects->setSessionWhere( "`Language`=" . $proLangID->getLangID() . " AND `Special`=1" );
        $rs_right_NewProjects = $conn->Execute( $right_NewProjects->SelectSQL() );
        if ( !$rs_right_NewProjects->EOF ) {
            $rs_right_NewProjects->MoveFirst();
            ?>
            <div id="new_projects">
                <div id="new_projects_title">
                    <div id="title"><font color="#71706e"><?php echo trim( $arrNewsPageList[ 1 ] ); ?></font> <font color="#eb9d0f"><?php echo trim( $arrNewsPageList[ 2 ] ); ?></font></div>
                </div>
                <div id="new_projects_content">
                    <div id="right_projects_big_image">
                        <div id="big_image">
                        </div>
                    </div>
                    <div id="new_projects_name_list">
                        <div id="list">
                            <div class="btnPrev"><img src="/img/new_projects_prev_btn.jpg" width="17" height="17" border="0"/></div>
                            <div class="img_list">
                                <ul class="_nostyle_1">
                                    <?php
                                    while ( !$rs_right_NewProjects->EOF ) {
                                        $right_NewProjects->LoadListRowValues( $rs_right_NewProjects );
                                        $right_NewProjects->RenderListRow();
                                        ?>
                                        <li><img src="/admin/<?php echo ew_UploadPathEx( FALSE, EW_UPLOAD_DEST_PATH ) . $right_NewProjects->Image->Upload->DbValue ?>" width="60" height="49" border="0" title="<?php echo $right_NewProjects->Title->ViewValue; ?>"/></li>
                                        <?php
                                        $rs_right_NewProjects->MoveNext();
                                    }
                                    ?>
                                </ul>
                            </div>
                            <div class="btnNxt"><img src="/img/new_projects_nxt_btn.jpg" width="17" height="17" border="0"/></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        $rs_right_NewProjects->Close();
        ?>
