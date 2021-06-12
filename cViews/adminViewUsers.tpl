<div class="contentBody page-user-reports">
    
    <div class="form-wrapper" style="padding:0px 0px 5px 0px;text-align:left !important">
        <div class="form-content">
            <div class="form-block-ex">
                <div class="group">
                    <label for="find-text" >Name</label> <input autocomplete="off" type="text" id="find-text" name="find-text" value="<?php echo $template->m_find;?>" style="width:173px">
                    
                    <img id="refresh" src="/images/refresh_symbol.png" style="vertical-align:middle;"/>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="desktop-table">
        <div class="userReportTable">
            <div class="tableHeader">
                <?php echo $template->m_orderNameLink;?><div class="h-1 <?php echo $template->m_orderName;?>">Name</div></a>
                <?php echo $template->m_orderStartDateLink;?><div class="h-2 <?php echo $template->m_orderStartDate;?>">Registered</div></a>
                <?php echo $template->m_orderCompletedLink;?><div class="h-3 <?php echo $template->m_orderCompleted;?>">Completed</div></a>
            </div>
            
            <?php echo $template->m_list; ?>
        </div>
    </div>
    
    <div class="mobile-table">
        <div class="form-wrapper">
            <div class="form-content" style="display:block">
                <div class="form-block">
                    <?php echo $template->m_listMobile; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($template->m_pager != "") { ?>
    <div class="form-wrapper">
        <div class="form-content">
            <div class="form-block" style="text-align:center">
                <?php echo $template->m_pager; ?>
            </div>
        </div>
    </div>
    <?php } ?>
    
    <div class="form-wrapper">
        <div class="form-content">
            <div class="button-wrapper-left" style="margin-top:20px;margin-bottom:20px">
                <form method="post" autocomplete="off">
                    <a href="/admin"><div class="button left-arrow-icon">Back</div></a>
                    <a href="/admin/add-new-user"><div class="button right-arrow-icon">Add User</div></a>
                    <input class="button right-arrow-icon gapButton" type="submit" value="Export to CSV" id="export-to-csv" name="action"/>
                </form>
            </div>
        </div>
    </div>
</div>
