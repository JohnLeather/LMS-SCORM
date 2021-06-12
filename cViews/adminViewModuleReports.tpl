<div class="contentBody page-module-reports">
    <div class="form-wrapper" style="padding:0px 0px 5px 0px;text-align:left !important">
        <div class="form-content">
            <div class="form-block-ex">
                <div class="group">
                    <label for="find-text">Name</label> <input autocomplete="off" type="text" id="find-text" name="find-text" value="<?php echo $template->m_find;?>" style="width:173px">    
                    <img id="refresh" src="/images/refresh_symbol.png" style="vertical-align:middle;"/>
                </div>
            </div>
        </div>
    </div>
    
    <div class="desktop-table">
        <div class="moduleReportTable">
            <div class="tableHeader">
                <?php echo $template->m_orderNameLink;?><div class="h-1 <?php echo $template->m_orderName;?>">Name</div></a>
                <?php echo $template->m_orderModuleLink;?><div class="h-4 <?php echo $template->m_orderModule;?>">Module</div></a>
                <?php if ($template->m_gotScoreColumn) { ?>
                <?php echo $template->m_orderScoreLink;?><div class="h-5 <?php echo $template->m_orderScore;?>">Score</div></a>
                <?php } ?>
                <?php echo $template->m_orderStartDateLink;?><div class="h-6 <?php echo $template->m_orderStartDate;?>">Start</div></a>
                <?php echo $template->m_orderCompletedLink;?><div class="h-7 <?php echo $template->m_orderCompleted;?>">Completed</div></a>
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
    
    <div class="form-wrapper">
        <div class="form-content">
            <div class="form-block">
                <?php echo $template->m_pager; ?>
            </div>
        </div>
    </div>
    
    
    <div class="form-wrapper">
        <div class="form-content">
            <div class="button-wrapper-left" style="margin-top:20px;margin-bottom:20px">
                <form method="post" autocomplete="off">
                    <a href="/admin"><div class="button left-arrow-icon">Back</div></a>
                    <input class="button right-arrow-icon gapButton" type="submit" value="Export to CSV" id="export-to-csv" name="action"/>
                </form>
            </div>
        </div>
    </div>
</div>
