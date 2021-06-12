<div class="contentBody page-edit-user">
    <?php echo $template->m_message; ?>
    
    <p class="prompt-user">Please enter new user details</p>
    <form method="post" autocomplete="off">
        <?php echo $template->m_CSRFTokens; ?>
        <div class="form-wrapper" style="margin:25px 0px">
            <div class="form-content">
                <div class="form-block">
                    <label for="first-name-field">First name</label>
                    <input id="first-name-field" name="first-name-field" maxlength="80" type="text" value="<?php echo $template->m_firstName;?>" placeholder="Required"/>
                </div>
                <div class="form-block">
                    <label for="last-name-field">Last name</label>
                    <input id="last-name-field" name="last-name-field" maxlength="80" type="text" value="<?php echo $template->m_lastName;?>" placeholder="Required"/>
                </div>
                
                <div class="form-block">
                    <label for="username-field">Username</label>
                    <input id="username-field" name="username-field" maxlength="80" type="text" value="<?php echo $template->m_username;?>" placeholder="Required"/>
                </div>
                <?php include("passwordField.tpl"); ?>
            </div>
        </div>
        <div class="form-wrapper">
            <div class="form-content">
                <p class="prompt-user">Select <b>Save</b> to add this user.</p>
                <div class="button-wrapper-left" style="margin-top:20px;margin-bottom:20px">
                    <?php echo $template->m_backURL;?><div class="button left-arrow-icon">Back</div></a>
                    <input class="right-arrow-icon disabled gapButton" type="submit" value="Save" id="submit" name="saveChanges" disabled="disabled">
                </div>
            </div>
        </div>
    </form>
</div>