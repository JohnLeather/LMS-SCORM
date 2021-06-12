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
class cLib_cPasswordManager {
    // --------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------
	public function __construct() {
        $this->passwordRequireLowerCase = intval(cLib_cConfig::getInstance()->getData(cLib_cConfig::_CONFIG_REQUIRE_PASSWORD_a_z_ID), 10);
        $this->passwordRequireUpperCase = intval(cLib_cConfig::getInstance()->getData(cLib_cConfig::_CONFIG_REQUIRE_PASSWORD_A_Z_ID), 10);
        $this->passwordRequireSymbols = intval(cLib_cConfig::getInstance()->getData(cLib_cConfig::_CONFIG_REQUIRE_PASSWORD_SYMBOL_ID), 10);
        $this->passwordRequireNumbers = intval(cLib_cConfig::getInstance()->getData(cLib_cConfig::_CONFIG_REQUIRE_PASSWORD_0_9_ID), 10);
        $this->passwordMinimumLength = intval(cLib_cConfig::getInstance()->getData(cLib_cConfig::_CONFIG_PASSWORD_LENGTH), 10);
	}
    // --------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------
    public function injectPasswordJSVars($requirePassword) {
        $this->m_owner->injectJS("var totalPasswordBubbles = 7");
        $this->m_owner->injectJS("var passwordRequireUpperCase = ".$this->passwordRequireUpperCase);
        $this->m_owner->injectJS("var passwordRequireLowerCase = ".$this->passwordRequireLowerCase);
        $this->m_owner->injectJS("var passwordRequireSymbols = ".$this->passwordRequireSymbols);
        $this->m_owner->injectJS("var passwordRequireNumbers = ".$this->passwordRequireNumbers);
        $this->m_owner->injectJS("var passwordMinimumLength = ".$this->passwordMinimumLength);
        $this->m_owner->injectJS("var passwordFieldRequired = ".$requirePassword);
    }
    // --------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------
    function updateFirstLastNameAndUsername($userRecord) {
        $firstName = $this->getPost("firstName");
        $lastName = $this->getPost("lastName");
        $username = $this->getPost("username");

        $userRecord->updateFirstLastAndUsername($firstName, $lastName, $username);

        $this->m_owner->sendMessage("This user record have been updated.", "success");
    }
    // --------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------
    function updateFirstLastName($userRecord) {
        $firstName = $this->getPost("firstName");
        $lastName = $this->getPost("lastName");
        
        $userRecord->updateUserRecord($firstName, $lastName);

        $this->m_owner->sendMessage("Your details have been updated.", "success");
    }
    // --------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------
    public function getPOST($post) {
        switch ($post) {
            case "firstName":
                return isset($_POST['first-name-field']) ? trim($_POST['first-name-field'])  : "";

            case "lastName":
                return isset($_POST['last-name-field'])  ? trim($_POST['last-name-field'])   : "";

            case "username":
                return isset($_POST['username-field'])  ? trim($_POST['username-field'])   : "";

            case "password":
                return isset($_POST['password-field'])  ? trim($_POST['password-field']) : "";

            case "confirmPassword":
                return isset($_POST['confirm-password-field']) ? trim($_POST['confirm-password-field'])  : "";

            case "currentPassword":
                return isset($_POST['current-password-field']) ? trim($_POST['current-password-field'])  : "";
        }
        throw new Exception("GetPost(".$post.") not implemented");
    }
    // --------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------
    public function savePassword($userRecord, $successMessage) {
        $password = $this->getPOST("password");
        $userRecord->changePassword($password);
        
        $this->m_owner->sendMessage($successMessage, "success");
        return true;
    }

    // --------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------
    public function validateFirstAndLastName($displayReasonMessage = true) {
        $firstName = $this->getPost("firstName");
        $lastName = $this->getPost("lastName");
        
        $firstNameStrlen    = strlen($firstName);
        $lastNameStrlen     = strlen($lastName);
        //
        // Validate that the string length > 0
        //
        if ($firstNameStrlen === 0) {
            if ($displayReasonMessage) {
                $this->m_owner->sendMessage("First name field is empty - try again");
            }
            return false;
        }
        //
        // Validate that the string length > 0
        //
        if ($lastNameStrlen === 0) {
            if ($displayReasonMessage) {
                $this->m_owner->sendMessage("Last name field is empty - try again");
            }
            return false;
        }
        //
        // Validate that the string length < 80
        //
        if ($firstNameStrlen > 80) {
            if ($displayReasonMessage) {
                $this->m_owner->sendMessage("First name is too long - try again");
            }
            return false;
        }
        //
        // Validate that the string length < 80
        //
        if ($lastNameStrlen > 80) {
            if ($displayReasonMessage) {
                $this->m_owner->sendMessage("Last name is too long - try again");
            }
            return false;
        }

        return true;
    }
    // --------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------
    public function validateUsername($userID = null) {
        $username = $this->getPost("username");

        $usernameStrlen = strlen($username);
        //
        // Validate that the string length > 0
        //
        if ($usernameStrlen === 0) {
            $this->m_owner->sendMessage("Username field is empty - try again");
            return false;
        }
        //
        // Validate that the string length < 80
        //
        if ($usernameStrlen > 80) {
            $this->m_owner->sendMessage("Username is too long - try again");
            return false;
        }
        //
        // Duplicate usernames aren't allowed... so lets validate it...
        //
        if ($userID !== null) {
            $SQL = "SELECT count(*) FROM users WHERE username = ? AND userID <> ?";
            $stmt = $this->m_SQL->prepare($SQL);
            $stmt->bind_param('si', $username, $userID);
        }
        else {
            $SQL = "SELECT count(*) FROM users WHERE username = ?";
            $stmt = $this->m_SQL->prepare($SQL);
            $stmt->bind_param('s', $username);
        }
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        if ($count > 0) {
            $this->m_owner->sendMessage("This username already exists");
            return false;
        }

        //
        // All validation done, return success
        //
        return true;
    }
    // --------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------
    public function validatePassword($userRecord = null) {
        $password = $this->getPOST("password");
        $confirmPassword = $this->getPOST("confirmPassword");

        $passwordStrlen = strlen($password);
        $confirmPasswordStrlen = strlen($confirmPassword);

        //
        // User did not change password
        //
        if ($passwordStrlen == 0 && $confirmPasswordStrlen == 0) {
            return true;
        }

        //
        // Password != confirmed password
        //
        if ($passwordStrlen != $confirmPasswordStrlen || $password != $confirmPassword) {
            $this->m_owner->sendMessage("Password doesn't match");
            return;
        }
        //
        // Validate password contains certain characters before saving...
        //
        $passwordStr = $password;
        $pwCharLowerCase = 0;
        $pwCharUpperCase = 0;
        $pwCharSymbol = 0;
        $pwCharDigit = 0;
        $pwCharLength = 0;
        
        for ($i = 0; $i < $passwordStrlen; $i++) {
            $c = $passwordStr[$i];
            if ($c >= 'a' && $c <='z') {
                $pwCharLowerCase++;
            }
            else if ($c >= 'A' && $c <='Z') {
                $pwCharUpperCase++;
            }
            else if ($c >= '0' && $c <='9') {
                $pwCharDigit++;
            }
            else {
                $pwCharSymbol++;
            }
            $pwCharLength++;
        }
        $outOf = 500;
        if ($this->passwordRequireLowerCase == 0) {
            $outOf -= 100;
        }
        if ($this->passwordRequireSymbols == 0) {
            $outOf -= 100;
        }
        if ($this->passwordRequireUpperCase == 0) {
            $outOf -= 100;
        }
        if ($this->passwordRequireNumbers == 0) {
            $outOf -= 100;
        }
        //
        // Lower case...
        //
        $lcPercentage = $this->passwordRequireLowerCase == 0 ? 100 : (($pwCharLowerCase / $this->passwordRequireLowerCase) * 100);
        if ($lcPercentage > 100) {
            $lcPercentage = 100;
        }
        //
        // upper case...
        //
        $ucPercentage = $this->passwordRequireUpperCase == 0 ? 100 : (($pwCharUpperCase / $this->passwordRequireUpperCase) * 100);
        if ($ucPercentage > 100) {
            $ucPercentage = 100;
        }
        //
        // symbol
        //
        $symbolPercentage = $this->passwordRequireSymbols == 0 ? 100 : (($pwCharSymbol / $this->passwordRequireSymbols) * 100);
        if ($symbolPercentage > 100) {
            $symbolPercentage = 100;
        }
        //
        // digit
        //
        $digitPercentage = $this->passwordRequireNumbers == 0 ? 100 : (($pwCharDigit / $this->passwordRequireNumbers) * 100);
        if ($digitPercentage > 100) {
            $digitPercentage = 100;
        }
        //
        // length
        //
        $lenPercentage = (($pwCharLength / $this->passwordMinimumLength) * 100);
        if ($lenPercentage > 100) {
            $lenPercentage = 100;
        }
    
        $percentage = (($lcPercentage + $ucPercentage + $symbolPercentage + $digitPercentage + $lenPercentage) - (500 - $outOf)) / $outOf;
        $passwordIsPerfect = $percentage == 1.00;
    
        //
        // Failed...
        //
        if (!$passwordIsPerfect) {
            $this->m_owner->sendMessage("Password requirement not met");
            return false;
        }

        if ($userRecord != null) {
            $currentPassword = $this->getPOST("currentPassword");
            if ($userRecord->hashPassword($currentPassword) != $userRecord->getPassword()) {
                $this->m_owner->sendMessage("Password is incorrect");
                return false;
            }
        }
        return true;
    }
}