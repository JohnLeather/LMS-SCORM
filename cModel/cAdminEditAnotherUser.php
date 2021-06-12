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
class cModel_cAdminEditAnotherUser extends cLib_cPasswordManager {
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
        
        $this->m_owner->setPageSubHeader("Edit &lt;Unknown&gt; account");
                                            
        $userID = $this->m_owner->m_URLRewrite->URLPairPart("id");
        //
        // ID is missing in URL, we can't deal with the request so just 404 it
        //
        if ($userID == NULL) {
            $this->m_owner->setView("404.tpl");
            return;
        }
        $this->m_thirdPartyUser = new cLib_cUser($this->m_owner->m_control);
        $userID = $this->m_thirdPartyUser->loginUsingUserID($userID);
        //
        // does this user even exist? no ? 404 it
        //
        if ($userID == NULL) {
            $this->m_owner->setView("404.tpl");
            return;
        }
        $userRecord = $this->m_thirdPartyUser->getUserRecord();
        //
        // You are strictly not allowed to edit or delete a super administrator or admin account... 404 it if you attempt this
        // If the admin change the ID in the URL to a super administrator account, the following lines prevent you from editing that account
        //
        if (($userRecord->getRoleID() & (cLib_cUser::_cROLE_USER)) == 0) {
            $this->m_owner->setView("404.tpl");
            return;
        }
        //
        // ID is legal at this point, we are signed as a super admin, or as an admin in which case, the id in the URL is trusted 
        // and so they are allowed to edit any normal user id even if they fiddle with the ID in the URL
        //
        $this->m_owner->setPageHeader("Administration");
        $this->m_owner->setPageSubHeader("Edit ".$userRecord->getFullName()." details");

        $firstName = $this->getPost("firstName");
        $lastName = $this->getPost("lastName");
        $username = $this->getPost("username");

        $OK = false;
        if (isset($_POST["saveChanges"])) {
            
            $OK = $this->validateFirstAndLastName();
            if ($OK) {
                $OK = $this->validateUserName($userRecord->getUserID());
                if ($OK) {
                    
                    $this->updateFirstLastNameAndUsername($userRecord);

                    $this->m_owner->sendMessage("User ".$userRecord->getFullName()." details have been updated successfully.", "success");
                }
            }
        }
        else if (isset($_POST["deleteUser"])) {
            $this->deleteUser($userRecord->getUserID());
            $this->m_owner->sendMessage("This user has been deleted.", "success");
            $this->m_owner->setView("adminDeletedUserDetails.tpl");

            $this->m_owner->setPageSubHeader("Deleted");

            $backURL = "<a class='' href='/admin/view-users/".$this->m_owner->m_URLRewrite->getURL()."'>";
            $this->m_owner->assignTemplateVar(array("m_backURL" => $backURL));
            
            return;
        }

        if ($this->validateFirstAndLastName(false)) {
            $firstName = htmlentities($firstName);
            $lastName = htmlentities($lastName);
            $username = htmlentities($username);
        }
        else {
            $firstName = $userRecord->getFirstName();
            $lastName = $userRecord->getLastName();
            $username = $userRecord->getUsername();
        }

        $password = $userRecord->getPassword();
        
        $this->m_owner->setView("adminEditAnotherUserDetails.tpl");
        $this->m_owner->appendJS("/js/LMSEditUserDetails.js");
        
        
        $backURL = "<a class='' href='/admin/view-users/".$this->m_owner->m_URLRewrite->getURL()."'>";
        
        $this->m_owner->assignTemplateVar(array("m_backURL" => $backURL));
        $this->m_owner->assignTemplateVar(array("m_firstName" => $firstName));
        $this->m_owner->assignTemplateVar(array("m_lastName" => $lastName));
        $this->m_owner->assignTemplateVar(array("m_username" => $username));
        $this->m_owner->assignTemplateVar(array("m_password" => $password));
        
        $this->m_owner->assignTemplateVar(array(
            "m_allFieldFilledIn" => $firstName != "" && $firstName != "" && $username != "",
            "m_fontAwesome" => true,
        ));

        $this->injectPasswordJSVars("false");
    }
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    function deleteUser($userID) {
        //
        // Delete user record
        //
        $SQL = "DELETE FROM users WHERE userID = ?";
        
        $stmt = $this->m_SQL->prepare($SQL);
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $stmt->close();

        //
        // Delete session belonging to userID
        //
        $SQL = "DELETE FROM sessions WHERE userID = ?";
        $stmt = $this->m_SQL->prepare($SQL);
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $stmt->close();

        //
        // Delete user records belonging to userID
        //
        $SQL = "DELETE FROM CMIResults WHERE userID = ?";
        $stmt = $this->m_SQL->prepare($SQL);
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $stmt->close();
        
    }
};
?>