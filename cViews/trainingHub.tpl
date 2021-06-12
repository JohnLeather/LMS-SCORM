<div class="contentBody page-training-hub">
    <div class="">
        <p>The list below shows the e-learning modules that are available to you. To begin, please click Launch to the right of the module now.</p>
        <p>You can complete the module in one sitting or save your progress and return at a later date.</p>
        <p>Upon completion this Learning Management System will save the status, start date and completion date for each module you take.</p>
    </div>
    
    <div class="login-blockX">
        <h3 style="margin-top:0px">Your Details:</h3>
        <div class="loginHR"></div>
        <br/>
    
        <b>Name:</b> <?php echo $template->m_fullName; ?>
        <br/>
        <b>Date Registered:</b> <?php echo $template->m_dateCreated; ?>
        <br/>
        <b>Date Finished:</b> <span id="global-date-completed"><?php echo $template->m_dateCompleted; ?></span>
    </div>
    <div class="login-blockY">
        <div class="button-wrapper-left" style="margin-top:20px;margin-bottom:0px">
        <a class="no-underline" href="/traininghub/edit-user-details"><div class="button hub-button right-arrow-icon">Edit details</div></a>
        <?php if ($template->m_canAccessAdminSection) { ?>
            <a class="no-underline" href="/admin"><div class="button hub-button right-arrow-icon">Admin menu</div></a>
        <?php } ?>
        <a class="no-underline" href="/"><div class="button hub-button cross-icon">Logout</div></a>
        </div>
    </div>
    <br style="clear:both"/>
    
    <?php echo $template->m_CSRFTokens; ?>
    
    
    <div class="desktop-table">
        <div class="trainingHubTable" style="margin-bottom:20px !important">
            <div class="tableHeader">
                <?php if ($template->m_hasScoreColumn) { ?>
                <div class="h-1">Modules</div>
                <div class="h-2">Score</div>
                <?php } else {?>
                <div class="h-1-noScore">Modules</div>
                <?php } ?>
                <div class="h-3">Status</div>
                <div class="h-4">Started</div>
                <div class="h-5">Completed</div>
                <div class="h-6">&nbsp;</div>
            </div>
            
            <?php echo $template->m_listOfAvailableModules; ?>
            
        </div>
    </div>
    
    <div class="mobile-table" style="margin-bottom:20px !important">
        <?php echo $template->m_listOfAvailableModulesMV; ?>
    </div>
</div>
