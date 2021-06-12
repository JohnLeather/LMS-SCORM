<div class="contentBody edit-user-details page-edit-user-details">
    <?php echo $template->m_message; ?>
    
    <p class="prompt-user">You may change the following details</p>
    
    
    <form method="post" autocomplete="off">
        
        <?php echo $template->m_CSRFTokens; ?>
        
        <div class="form-wrapper" style="margin:25px 0px">
            <div class="form-content">
                <div class="form-block">
                    <label for="first-name-field">First name</label>
                    <input id="first-name-field" name="first-name-field" maxlength="80" type="text" value="<?php echo $template->m_firstName;?>"/>
                </div>
                <div class="form-block">
                    <label for="last-name-field">Last name</label>
                    <input id="last-name-field" name="last-name-field" maxlength="80" type="text" value="<?php echo $template->m_lastName;?>"/>
                </div>
            </div>
        </div>
        
        <div class="form-wrapper">
            <div class="form-content">
                <p class="goldInstructionText">Select <b>Save</b> to change your details.</p>
                    <div class="button-wrapper-left" style="margin-top:20px;margin-bottom:20px">
                    <a href="/traininghub"><div class="button left-arrow-icon">Back</div></a>
                    <input class="right-arrow-icon disabled gapButton" type="submit" value="Save" id="submit" name="action" disabled="disabled">
                </div>
            </div>
        </div>
    </form>
</div>

