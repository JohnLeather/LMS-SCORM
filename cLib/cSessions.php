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
class cLib_cSessions extends cLib_cCSRFTokens {
    const USING_COOKIES = false; // The computer may be in a training room where several different people are using the system - so cookies aren't suitable for this LMS
    
    //
    // references to classes
    //
    private $m_userProfile = null;
    private $control = null;
    
    // max setting
    const MAX_HOURS_LOGGED_IN  = 60 * 60 * 24; // 24 hours
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function __construct($control) {
        $this->control = $control;
        $this->m_SQL = $control->getDB()->sql;
                
        $this->killTimedOutSessions();
        $this->init();
    }
    // --------------------------------------------------------------------------------------------------
    // init session class based on $_SESSION['id'] being set
    // failing that, init using username and password stored in cookies
    // failing that, assumes user to be logged out
    // --------------------------------------------------------------------------------------------------
    private function init() {
        session_start();
        $this->userRecord = new cLib_cUser($this->control);
        
        if (isset($_SESSION['userID'])) {
            $this->userRecord->loginUsingUserID($_SESSION['userID']);
        }
       
        // notify who online database
        $this->keepSessionLive();
    }
    // --------------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------------
    public function getRole() {
        return $this->userRecord->getRoleID();
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    public function getUsername() {
        return $this->userRecord->getUsername();
    }
    // --------------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------------
    public function getUserID() {
        return $this->userRecord->getUserID();
    }
    // --------------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------------
    public function getPassword() {
        return $this->userRecord->getPassword();
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    public function login($username, $password, $rememberMe = false) {
        if ($this->isLoggedIn()) {
            $this->logout();
        }
        
        $OK = $this->userRecord->loginUsingUsernameAndPassword($username, $password);
        
        $id = $this->userRecord->getUserID();
        
        if (isset($id)) {
            $_SESSION['userID'] = $id;
        }
        else {
            unset($_SESSION['userID']);
        }
        $this->keepSessionLive();
        
        return $OK;
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    public function getCurrentXSRFToken() {
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    private function fetchLastXSRFToken() {
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    public function refreshXSRFToken() {
    }
    // --------------------------------------------------------------------------------------------------
    // Logging out does the following:
    // remove reference from online session database
    // remove cookies
    // clear and delete session
    // --------------------------------------------------------------------------------------------------
    public function logout() {
        if (!$this->isLoggedIn()) {
            return;
        }
        
        $this->deleteSessionFromOnlineTable();
        
        $this->userRecord->resetUserProfile();
        
        if (cLib_cSessions::USING_COOKIES) {
            $this->cCookies->clear("username");
            $this->cCookies->clear("password");
        }
        
        $CSRF = isset($_SESSION['CSRFToken']) ? $_SESSION['CSRFToken'] : "";
        
        $_SESSION = array();
        @session_destroy();
        
        session_start();
        $_SESSION['CSRFToken'] = $CSRF;
    }
    // --------------------------------------------------------------------------------------------------
    // return login status of current user
    // --------------------------------------------------------------------------------------------------
    public function isLoggedIn() {
        return $this->userRecord->getUserID() != NULL;
    }
    // --------------------------------------------------------------------------------------------------
    // Keep current session alive in the online table
    // --------------------------------------------------------------------------------------------------
    public function keepSessionLive() {
        if (!$this->isLoggedIn()) {
            return;
        }
        
        $timeStamp = time();
        $userID    = $this->getUserID();
        $sessionID = session_id();
        
        $stmt = $this->m_SQL->prepare("INSERT INTO sessions (userID, ping, sessionID) VALUES (?,?,?) ON DUPLICATE KEY UPDATE ping = ?");
        $stmt->bind_param('iisi', $userID, $timeStamp, $sessionID, $timeStamp);
        $stmt->execute();
        $count = $stmt->affected_rows;
        $stmt->close();
    }
    // --------------------------------------------------------------------------------------------------
    // Delete current userID from online table
    // --------------------------------------------------------------------------------------------------
    private function deleteSessionFromOnlineTable() {
        if (!$this->isLoggedIn()) {
            return;
        }
        
        $SQL = "DELETE FROM sessions WHERE userID = ?";
        $stmt = $this->m_SQL->prepare($SQL);
        $stmt->bind_param('i', $this->userRecord->getUserID());
        $stmt->execute();
        $stmt->close();
    }
    // --------------------------------------------------------------------------------------------------
    // Sessions are killed off automatically after a certain time to prevent loss of session resources
    // --------------------------------------------------------------------------------------------------
    private function killTimedOutSessions() {
        $lastping = time() - self::MAX_HOURS_LOGGED_IN;
 
        $stmt = $this->m_SQL->prepare("SELECT * FROM sessions WHERE ping < ?");

        $stmt->bind_param("i", $lastping);
        
        $stmt->execute();
        
        $stmt->bind_result($UserID, $sessionID, $ping);
        
        $deletedRows = 0;
        while ($stmt->fetch()) {
            if ($deletedRows == 0) {
                session_write_close();
            }
            $deletedRows++;
            @session_id($sessionID);
            @session_start();
            @session_destroy();
        }
        $stmt->close();

        if ($deletedRows > 0) {
            $SQL = "DELETE FROM sessions WHERE ping < ?";
            $stmt = $this->m_SQL->prepare($SQL);
            $stmt->bind_param('i', $lastping);
            $stmt->execute();
            $stmt->close();
        }
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    public function getFirstName() {
       return $this->userRecord->getFirstName();
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    public function getLastName() {
        return $this->userRecord->getLastName();
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    public function completed() {
        $this->userRecord->completed();
    }
    // --------------------------------------------------------------------------------------------------
    // Returns the user role of record or null if not set
    // --------------------------------------------------------------------------------------------------
    public function isRoleSuperAdmin() {
        return $this->userRecord->isRoleSuperAdmin();
    }
    // --------------------------------------------------------------------------------------------
    // Returns the user role of record or null if not set
    // --------------------------------------------------------------------------------------------
    public function isRoleUser() {
        return $this->userRecord->isRoleUser();
    }
    // --------------------------------------------------------------------------------------------
    // Returns the user role of record or null if not set
    // --------------------------------------------------------------------------------------------
    public function isRoleAdmin() {
        return $this->userRecord->isRoleAdmin();
    }
    // --------------------------------------------------------------------------------------------
    // Returns the user role of record or null if not set
    // --------------------------------------------------------------------------------------------
    public function getUserRecord() {
        return $this->userRecord->getUserRecord();
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    public function loginUsingUserID($ID) {
        if ($this->isLoggedIn()) {
            $this->logout();
        }
        $OK = $this->userRecord->loginUsingUserID($ID);

        $id = $this->userRecord->getUserID();
        
        if (isset($id)) {
            $_SESSION['userID'] = $id;
        }
        else {
            unset($_SESSION['userID']);
        }
        $this->keepSessionLive();
        
        return $OK;
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    public function updateUserRecord($firstName, $lastName) {
        $this->userRecord->updateUserRecord($firstName, $lastName);
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    public function changePassword($newPassword) {
        $this->userRecord->changePassword($newPassword);
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    public function hashPassword($password) {
        return $this->userRecord->hashPassword($password);
    }
};
