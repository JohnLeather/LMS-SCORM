<div class="contentBody page-admin-settings">
    <p class="prompt-user">Configure the way this LMS behave</p>
    <?php echo $template->m_message; ?>
    <form method="post" autocomplete="off">
        <div class="form-wrapper">
            <div class="form-content">
                <?php echo $template->m_CSRFTokens; ?>
                
                <div class="form-block">
                    <input class="checkbox-image" type="checkbox" id="display-score-column" name="display-score-column" <?php echo $template->m_requiresScore; ?>>
                    <label class="checkbox-label" for="display-score-column">Display score column in training hub / module reports?</label>
                </div>
                
                <div class="form-block">
                    <fieldset style="margin-top:10px;padding:10px">
                        <legend>Number of requires characters of the following kind</legend>


                    <div style="float:left;margin:5px 0px">
                        <div style="width:190px">
                            <label for="password-A-Z" class="labelPassword">Uppercase</label>
                            <input type="number" id="password-A-Z" name="password-A-Z" value="<?php echo $template->m_requiresPasswordAZ;?>"  min="0" max="3"/>
                        </div>
                    </div>
                    
                    <div style="float:left;margin:5px 0px">
                        <div style="width:190px">
                            <label for="password-a-z" class="labelPassword">Lowercase</label>
                            <input type="number" id="password-a-z" name="password-a-z" value="<?php echo $template->m_requiresPasswordaz;?>"  min="0" max="3"/>
                        </div>
                    </div>
                    
                    <div style="float:left;margin:5px 0px">
                        <div style="width:190px">
                            <label for="password-0-9" class="labelPassword">Digits</label>
                            <input type="number" id="password-0-9" name="password-0-9" value="<?php echo $template->m_requiresPassword09;?>"  min="0" max="3"/>
                        </div>
                    </div>
                    
                    <div style="float:left;margin:5px 0px">
                        <div style="width:190px">
                            <label for="password-symbol" class="labelPassword">Symbols</label>
                            <input type="number" id="password-symbol" name="password-symbol" value="<?php echo $template->m_requiresPasswordSymbol;?>" min="0" max="3"/>
                        </div>
                    </div>
                    
                    </fieldset>
                    <label for="password-length" style="width:auto;margin-top:20px;margin-bottom:0px">Password input length</label>
                    <select id="password-length" name="password-length" style="width:300px">
                        <option value="6"<?php echo (($template->m_passwordLength == 6) ? " selected" : "");?>>6 characters (Weakest - not recommended)</option>
                        <option value="7"<?php echo (($template->m_passwordLength == 7) ? " selected" : "");?>>7 characters (Weak)</option>
                        <option value="8"<?php echo (($template->m_passwordLength == 8) ? " selected" : "");?>>8 characters (Better)</option>
                        <option value="9"<?php echo (($template->m_passwordLength == 9) ? " selected" : "");?>>9 characters (Better)</option>
                        <option value="10"<?php echo (($template->m_passwordLength == 10) ? " selected" : "");?>>10 characters (Good)</option>
                        <option value="11"<?php echo (($template->m_passwordLength == 11) ? " selected" : "");?>>11 characters (Good)</option>
                        <option value="12"<?php echo (($template->m_passwordLength == 12) ? " selected" : "");?>>12 characters (Strong - mimimum recommendation)</option>
                        <option value="13"<?php echo (($template->m_passwordLength == 13) ? " selected" : "");?>>13 characters (Stronger)</option>
                        <option value="14"<?php echo (($template->m_passwordLength == 14) ? " selected" : "");?>>14 characters (Very strong)</option>
                    </select>
                </div>
                <div class="form-block">
                    <input class="checkbox-image" type="checkbox" value="reject-common-passwords" id="reject-common-passwords" name="reject-common-passwords" <?php echo $template->m_requiresRejectCommonPassword; ?>/>
                    <label class="checkbox-label" for="reject-common-passwords">Reject common passwords?</label>
                </div>
            </div>
        </div>

        <div class="form-wrapper">
            <div class="form-content">
                <div class="button-wrapper-left" style="margin-top:20px;margin-bottom:20px">
                    <a href="/admin"><div class="button left-arrow-icon">Back</div></a>
                    <input class="right-arrow-icon disabled gapButton" type="submit" value="Submit" id="submit-settings" name="action" disabled="disabled">
                </div>
            </div>
        </div>
    </form>
</div>