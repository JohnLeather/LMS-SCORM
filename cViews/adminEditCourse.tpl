<div class="contentBody page-course-view">
    <div style="display:block-inline; box-sizing : border-box !important;margin : 0px 0px;">
        <div class="manifest-info-table">
            <div class="manifest-info-table-row-header">
                <div class="manifest-info-table-td-header">
                    Manifest information...
                </div>
            </div>
            
            
            
            <div class="manifest-info-table-row">
                <div class="manifest-info-table-td-element">
                    adlcp:mastery_score
                </div>
                <div class="manifest-info-table-td-value">
                    &nbsp;<?php echo $template->m_masteryScore;?>
                </div>
            </div>
            
            <div class="manifest-info-table-row">
                <div class="manifest-info-table-td-element">
                    adlcp:max_time_allowed
                </div>
                <div class="manifest-info-table-td-value">
                    &nbsp;<?php echo $template->m_maxTimeAllowed;?>
                </div>
            </div>
            
            <div class="manifest-info-table-row">
                <div class="manifest-info-table-td-element">
                    adlcp:data_from_lms
                </div>
                <div class="manifest-info-table-td-value">
                    &nbsp;<?php echo $template->m_dataFromLMS;?>
                </div>
            </div>
            
            <div class="manifest-info-table-row">
                <div class="manifest-info-table-td-element">
                    adlcp:time_limit_action
                </div>
                <div class="manifest-info-table-td-value">
                    &nbsp;<?php echo $template->m_timeLimitAction;?>
                </div>
            </div>
            
            <div class="manifest-info-table-row">
                <div class="manifest-info-table-td-element">
                    adlcp:prerequisites
                </div>
                <div class="manifest-info-table-td-value">
                    &nbsp;<?php echo $template->m_prerequisites;?>
                </div>
            </div>
        </div>
        <!--
        <div class="SCO-info-table" style="float:right;width:calc(50% - 5px);margin-left:5px">
            <div class="SCO-info-table-row-header">
                <div class="SCO-info-table-td-header">
                    SCO information...
                </div>
            </div>
            
            <div class="SCO-info-table-row">
                <div class="SCO-info-table-td-element">
                    <?php echo $template->m_moduleTitles;?>
                </div>
            </div>
        </div>
         -->

        <?php echo $template->m_message; ?>

        <form method="post" autocomplete="off">
            <?php echo $template->m_CSRFTokens; ?>
            <div class="form-wrapper">
                <div class="form-content" style="width:100% !important">
                    
                    <fieldset style="margin-top:10px">
                        <legend>Launch popup window size</legend>
                    
                    
                        <div class="form-block">
                            <label for="window-width" style="width:auto !important">Width</label>
                            <input id="window-width" name="window-width" maxlength="80" type="text" value="<?php echo $template->m_windowWidth;?>"/>
                      
                      
                            <label for="window-height">Height</label>
                            <input id="window-height" name="window-height" maxlength="80" type="text" value="<?php echo $template->m_windowHeight;?>"/>
                        
                        
                            <div id="moduleID<?php echo $template->m_moduleID;?>" class="button right-arrow-icon" style="float:right">Launch</div>
                        </div>
                        
                        
                    </fieldset>
                    
                </div>
            </div>
            
            <div class="form-wrapper">
                <div class="form-content" style="width:100% !important">
                    <fieldset style="margin-top:10px">
                        <legend>Module available to the following roles</legend>
                        
                        <div class="form-block">
                            <div style="float:left">
                                <input class="checkbox-image" type="checkbox" value="superAdmin-cb" id="superAdmin-cb" name="superAdmin-cb" <?php echo $template->m_superAdmin;?>/>
                                <label class="checkbox-label" for="superAdmin-cb">Super administrator</label>
                            </div>
                            
                            <div style="float:left">
                                <input class="checkbox-image" type="checkbox" value="admin-cb" id="admin-cb" name="admin-cb" <?php echo $template->m_admin;?>/>
                                <label class="checkbox-label" for="admin-cb">Administrator</label>
                            </div>
                            
                            <div style="float:left">
                                <input class="checkbox-image" type="checkbox" value="user-cb" id="user-cb" name="user-cb" <?php echo $template->m_user;?>/>
                                <label class="checkbox-label" for="user-cb">User</label>
                            </div>
                        </div>
                        <br style="clear:both">
                    </fieldset>
                </div>
            </div>
            
            <div class="form-wrapper">
                <div class="form-content">
                    <div class="button-wrapper-left" style="margin-top:20px;margin-bottom:20px">
                        <a href="/admin/upload-courses"><div class="button left-arrow-icon">Back</div></a>
                        <a href="/admin/delete-courses/id/<?php echo $template->m_moduleID;?>"><div class="button right-arrow-icon gapButton">Delete</div></a>
                        <input class="right-arrow-icon disabled gapButton" type="submit" value="Save" id="submit-course-settings" name="action" disabled="disabled">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>