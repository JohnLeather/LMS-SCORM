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
class cModel_cUserLogin {
    function __construct($owner) {
        $this->m_owner = $owner;
        $this->m_SQL = $this->m_owner->m_control->getDB()->sql;
        
        $this->run();
    }
    // --------------------------------------------------------------------------------------------
    // Entry point
    // --------------------------------------------------------------------------------------------
    function run() {
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $this->m_owner->setPageHeader("User login");
        $this->m_owner->setPageSubHeader("");
        
        if (isset($_POST['action']) && $_POST['action'] == "Login") {
            if ($this->signedInOK()) {
                $this->m_owner->location("/traininghub");
                die();
            }
        }

        $this->m_owner->appendJS("/js/loginLMSUserLogin.js");
        $this->m_owner->setView("userLoginScreen.tpl");
    }
    // --------------------------------------------------------------------------------------------
    // Invalid sign in, send a message back
    // --------------------------------------------------------------------------------------------
    private function invalidSignIn() {
        $this->m_owner->sendMessage("Username and/or Password is invalid");

        return false;
    }
    // --------------------------------------------------------------------------------------------
    // Can't trust client so some extra checks requires to make sure this is OK
    // --------------------------------------------------------------------------------------------
    function signedInOK() {
        $username = isset($_POST['username-field']) ? trim($_POST['username-field']) : "";
        $password = isset($_POST['password-field']) ? trim($_POST['password-field']) : "";
        //
        // Validate username
        //
        if (strlen($username) == 0 || strlen($username) > 80) {
            return $this->invalidSignIn();
        }
        //
        // Validate password
        //
        if (strlen($password) == 0 || strlen($password) > 80) {
            return $this->invalidSignIn();
        }
        //
        // Sign in
        //
        $OK = $this->m_owner->m_control->m_sessions->login($username, $password);
        
        if ($OK === false) {
            return $this->invalidSignIn();
        }
        
        return $OK;
    }
};