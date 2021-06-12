<?php
// --------------------------------------------------------------------------------------------
// Copyright 2021 John Leather - www.sphericalgames.co.uk
// Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated 
// documentation files (the "Software"), to deal in the Software without restriction, including without limitation 
// the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, 
// and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in all copies or substantial 
// portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED 
// TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL 
// THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF 
// CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS 
// IN THE SOFTWARE.
// --------------------------------------------------------------------------------------------
class cModel_cAdminSetSettings {
    function __construct($owner) {
        $this->m_owner = $owner;
        $this->m_SQL = $this->m_owner->m_control->getDB()->sql;
        $this->run();
    }
    
    // --------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------
    function run() {
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $userRecord = $this->m_owner->m_control->m_sessions->getUserRecord();
        $this->userRecord = $userRecord;
        
        $this->m_owner->setPageHeader("Administration");
        $this->m_owner->setPageSubHeader("Set LMS Settings");

        if (isset($_POST['action'])) {
            $this->processForm();
        }
        
        $requireScoreNONSanitize = cLib_cConfig::getInstance()->getData(cLib_cConfig::_CONFIG_REQUIRES_SCORE_COLUMN_ID);
        $requirePasswordAZNONSanitize = cLib_cConfig::getInstance()->getData(cLib_cConfig::_CONFIG_REQUIRE_PASSWORD_A_Z_ID);
        $requirePasswordazNONSanitize = cLib_cConfig::getInstance()->getData(cLib_cConfig::_CONFIG_REQUIRE_PASSWORD_a_z_ID);
        $requirePassword09NONSanitize = cLib_cConfig::getInstance()->getData(cLib_cConfig::_CONFIG_REQUIRE_PASSWORD_0_9_ID);
        $requirePasswordSymbolNONSanitize = cLib_cConfig::getInstance()->getData(cLib_cConfig::_CONFIG_REQUIRE_PASSWORD_SYMBOL_ID);
        $rejectCommonPasswordNONSanitize = cLib_cConfig::getInstance()->getData(cLib_cConfig::_CONFIG_REJECT_COMMON_PASSWORDS_ID);
        $passwordLengthNONSanitize = cLib_cConfig::getInstance()->getData(cLib_cConfig::_CONFIG_PASSWORD_LENGTH);

        $this->m_owner->assignTemplateVar(array("m_requiresScore" => ($requireScoreNONSanitize == 1 ? "checked" : "")));
        $this->m_owner->assignTemplateVar(array("m_requiresPasswordAZ" => ($requirePasswordAZNONSanitize)));
        $this->m_owner->assignTemplateVar(array("m_requiresPasswordaz" => ($requirePasswordazNONSanitize)));
        $this->m_owner->assignTemplateVar(array("m_requiresPassword09" => ($requirePassword09NONSanitize)));
        $this->m_owner->assignTemplateVar(array("m_requiresPasswordSymbol" => ($requirePasswordSymbolNONSanitize)));
        $this->m_owner->assignTemplateVar(array("m_passwordLength" => ($passwordLengthNONSanitize)));
        $this->m_owner->assignTemplateVar(array("m_requiresRejectCommonPassword" => ($rejectCommonPasswordNONSanitize == 1 ? "checked" : "")));
        
        $this->m_owner->appendJS("/js/adminLMSSettings.js");
        $this->m_owner->setView("adminSettings.tpl");
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    function processForm() {
        $OK = $this->processScoreColumn();
        
        if ($OK) {
            $this->m_owner->sendMessage("LMS Settings saved.", "success");
        }
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    function processScoreColumn() {
        $requiredAZ = isset($_POST['password-A-Z']) ? intval($_POST['password-A-Z'], 10) : 0;
        $requiredaz = isset($_POST['password-a-z']) ? intval($_POST['password-a-z'], 10) : 0;
        $required09 = isset($_POST['password-0-9']) ? intval($_POST['password-0-9'], 10) : 0;
        $requiredSy = isset($_POST['password-symbol']) ? intval($_POST['password-symbol'], 10) : 0;
        $passwordLen = isset($_POST['password-length']) ? intval($_POST['password-length'], 10) : 6;
        
        if ($passwordLen < 6) {
            $passwordLen = 6;
        }
        
        if ($passwordLen > 14) {
            $passwordLen = 14;
        }

        $OK = cLib_cConfig::getInstance()->writeData(cLib_cConfig::_CONFIG_REQUIRES_SCORE_COLUMN_ID, isset($_POST["display-score-column"]) ? 1 : 0);
        $OK = cLib_cConfig::getInstance()->writeData(cLib_cConfig::_CONFIG_REQUIRE_PASSWORD_A_Z_ID, $requiredAZ);
        $OK = cLib_cConfig::getInstance()->writeData(cLib_cConfig::_CONFIG_REQUIRE_PASSWORD_a_z_ID, $requiredaz);
        $OK = cLib_cConfig::getInstance()->writeData(cLib_cConfig::_CONFIG_REQUIRE_PASSWORD_0_9_ID, $required09);
        $OK = cLib_cConfig::getInstance()->writeData(cLib_cConfig::_CONFIG_REQUIRE_PASSWORD_SYMBOL_ID, $requiredSy);
        $OK = cLib_cConfig::getInstance()->writeData(cLib_cConfig::_CONFIG_PASSWORD_LENGTH, $passwordLen);
        
        return $OK;
    }

};
?>
