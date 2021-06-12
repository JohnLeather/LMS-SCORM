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
    class cModel_cAdminAddNewUser extends cLib_cPasswordManager {
        function __construct($owner) {
            $this->m_owner = $owner;
            $this->m_SQL   = $this->m_owner->m_control->getDB()->sql;
            parent::__construct();
            $this->run();
        }
        // --------------------------------------------------------------------------------------------
        // 
        // --------------------------------------------------------------------------------------------
        function run() {
            
            $this->m_owner->setPageSubHeader("Edit &lt;Unknown&gt; account");
                              
            $userRecord = $this->m_owner->m_control->m_sessions->getUserRecord();
            
            //
            // You are strictly not allowed to add a user unless you have super administrator or admin account... 404 it if you are neither of those
            //
            if (($userRecord->getRoleID() & (cLib_cUser::_cROLE_SUPERADMIN | cLib_cUser::_cROLE_ADMIN)) == 0) {
                header("Cache-Control: no-cache, no-store, must-revalidate"); 
                header("Pragma: no-cache");
                header("Expires: 0"); 

                $this->m_owner->setView("404.tpl");
                return;
            }
            
            $this->userRecord = $userRecord;
            
            
            $this->m_owner->setPageHeader("Administration");
            $this->m_owner->setPageSubHeader("Add New User");

            $OK = false;

            if (isset($_POST) && count($_POST) > 0) {
                if (isset($_POST["saveChanges"])) {
                    $OK = $this->validateFirstAndLastName();

                    if ($OK) {
                        $OK = $this->validateUserName();
                    }

                    if ($OK) {
                        $OK = $this->validatePassword();
                    }

                    if ($OK) {
                        //
                        // All validation passes - Create new user...
                        //
                        $userRecord = new cLib_cUser($this->m_owner->m_control);

                        $firstName = $this->getPOST("firstName");
                        $lastName = $this->getPOST("lastName");
                        $username = $this->getPOST("username");
                        $password = $this->getPOST("password");

                        $userRecord->createNewUser($firstName, $lastName, $username, $password);
                        $this->m_owner->sendMessage("New user ".$userRecord->getFullName()." created successfully.", "success");
                    }
                    //
                    // Redirect to self to prevent duplicate form posting on page refresh
                    //
                    header("Location: " . $_SERVER["REQUEST_URI"]);
                    exit;
                }
            }

            header("Cache-Control: no-cache, no-store, must-revalidate"); 
            header("Pragma: no-cache"); 
            header("Expires: 0"); 
            
            
            $this->m_owner->setView("adminAddNewUser.tpl");
            $this->m_owner->appendJS("/js/LMSEditUserDetails.js");
            
            $backURL = "<a class='' href='/admin/view-users/".$this->m_owner->m_URLRewrite->getURL()."'>";
            
            $this->m_owner->assignTemplateVar(array("m_backURL" => $backURL));
            $this->m_owner->assignTemplateVar(array("m_firstName" => ""));
            $this->m_owner->assignTemplateVar(array("m_lastName" => ""));
            $this->m_owner->assignTemplateVar(array("m_username" => ""));
            $this->m_owner->assignTemplateVar(array("m_password" => ""));
            
            $this->m_owner->assignTemplateVar(array(
                "m_allFieldFilledIn" => false,
                "m_fontAwesome" => true,
            ));
            
            $this->injectPasswordJSVars("true");
        }
    };
?>