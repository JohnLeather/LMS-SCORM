<div class="contentBody page-manager-login" id="manager-login-screen">
    <div class="login-block">
        Welcome to the Learning Management System. You will need to enter your username and password before you can view the courses available for you.
        <div class="loginHR"></div>
        <p class="goldInstructionText">Enter your Username and your Password to sign in</p>
        <?php echo $template->m_message; ?>
        <form action="/login/user" method="post" autocomplete="off">
            <?php echo $template->m_CSRFTokens; ?>
            <div class="form-wrapper">
                <div class="form-content">
                    <div class="form-block">
                        <label for="username-field">Username</label>
                        <input type="text" id="username-field" name="username-field" maxlength="80" autocomplete="off"  value=""/>
                    </div>
                    <div class="form-block">
                        <label for="password-field">Password</label>
                        <input type="text" id="password-field" name="password-field" maxlength="80" autocomplete="off"  value=""/>
                    </div>
                </div>
            </div>
            <div class="button-wrapper-left button-padding">
                <input class="right-arrow-icon disabled" type="submit" value="Login" id="login-button" name="action" disabled="disabled"/>
            </div>
        </form>
    </div>
    <br style="clear:both">
</div>