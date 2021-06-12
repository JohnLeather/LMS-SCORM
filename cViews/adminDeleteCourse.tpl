<div class="contentBody page-course-delete">
    <p class="prompt-user">Do you really want to delete this course?</p>
    
    <?php echo $template->m_message; ?>
    
    <form method="post" autocomplete="off">
        <?php echo $template->m_CSRFTokens; ?>
        <div class="form-wrapper">
            <div class="form-content" >
                <p style="text-align:center">Deleting this course will also delete <?php echo $template->m_userRecords;?> user record(s)</p>
                
                <?php if (!$template->m_isSingleSCO) { ?>
                    <p style='text-align:center'>This course is a multiple SCO which means deleting this course will delete the following:</p>
                    <div style="height:200px;width:100%;border:solid black 1px;overflow-y:scroll;overflow-x:hidden;padding:10px">
                        <?php echo $template->m_moduleTitles;?>
                    </div>
                    
                <?php } ?>
                
            </div>
        </div>
        
        <div class="form-wrapper">
            <div class="form-content">
                <div class="button-wrapper-left" style="margin-top:20px;margin-bottom:20px">
                    <a href="/admin/edit-course/id/<?php echo $template->m_moduleID;?>"><div class="button left-arrow-icon">Cancel Delete</div></a>
                    <input class="right-arrow-icon gapButton" type="submit" value="Delete" id="submit-delete" name="action">
                </div>
            </div>
        </div>
    </form>
</div>

