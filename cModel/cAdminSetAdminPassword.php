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
class cModel_cAdminSetAdminPassword extends cLib_cPasswordManager {
    function __construct($owner) {
        $this->m_owner = $owner;
        $this->m_SQL = $this->m_owner->m_control->getDB()->sql;
        
        parent::__construct();
        $this->run();
    }
    
    // --------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------
    function run() {
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $this->m_owner->setPageSubHeader("Set Your Password");
        
        $this->m_owner->appendJS("/js/LMSEditUserDetails.js");
        
        if (isset($_POST['changePassword'])) {
            $userRecord = $this->m_owner->m_control->m_sessions->getUserRecord();

            $OK = $this->validatePassword($userRecord);
            if ($OK) {
                $this->savePassword($userRecord, "Your password has been updated");
            }
        }

        $this->m_owner->assignTemplateVar(array(
            "m_allFieldFilledIn" => false,
            "m_fontAwesome" => true,
        ));
        $this->injectPasswordJSVars("true");
        $this->m_owner->setView("adminSetAdminPassword.tpl");
    }
};
?>