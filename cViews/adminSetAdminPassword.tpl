<div class="contentBody page-set-password">
    <?php echo $template->m_message; ?>
    
    <p class="prompt-user">Please enter your old and new password then press <b>Submit</b>.</p>
    
    <form method="post" autocomplete="off">
        <?php echo $template->m_CSRFTokens; ?>
        
        <div class="form-wrapper" style="margin:25px 0px">
            <div class="form-content">
                <div class="form-block">
                    <label for="current-password-field">Current Password</label>
                    <input id="current-password-field" name="current-password-field" maxlength="80" type="password" value=""/><i class="far fa-eye togglePassword" id="toggleCurrentPassword"></i>
                </div>
                <?php include("passwordField.tpl"); ?>
            </div>
        </div>
        
        <div class="form-wrapper">
            <div class="form-content">
                <div class="button-wrapper-left" style="margin-top:20px;margin-bottom:20px">
                    <a href="/admin"><div class="button left-arrow-icon">Back</div></a>
                    <input class="right-arrow-icon disabled gapButton" type="submit" value="Submit" id="submit" name="changePassword" disabled="disabled">
                </div>
            </div>
        </div>
    </form>
</div>