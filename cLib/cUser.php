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
class cLib_cUserProfile {

    //
    // WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING
    // 
    // When you change the SALT, all existing password won't work and there is nothing I can do about that other than do a reset.
    // So only change the SALT once. What this LMS will do is reset all admin and super admin account password to "password" using the 
    // revised SALT as to not lock you out. This means once you've changed the SALT, you need to update all super admin and admin passwords.
    //
    // WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING
    //
    const   SALT = "9xX643fAy25w83b8fxfde3!£x98X76232";

    private $m_username = null;
	private $m_password = null;
	private $m_userID = null;
	private $m_roleID = cLib_cUser::_cROLE_ANONYMOUS;
    private $m_firstName = null;
    private $m_lastName = null;
    private $m_fullName = null;
    private $m_dateCreated = null;
    private $m_dateCompleted = null;
// ----------------------------------------------------------------------------------------------
//
// ----------------------------------------------------------------------------------------------
    public function resetUserProfile() {
        $this->m_username = null;
        $this->m_password = null;
        $this->m_userID = null;
        $this->m_roleID = cLib_cUser::_cROLE_ANONYMOUS;
        $this->m_dateCreated = null;
        $this->m_dateCompleted = null;
        $this->m_firstName = null;
        $this->m_lastName = null;
        $this->m_dateCreated = null;
        $this->m_dateCompleted = null;
    }
// ----------------------------------------------------------------------------------------------
//
// ----------------------------------------------------------------------------------------------
    public function hashPassword($password) {
        return md5(self::SALT.$password);
    }
// ----------------------------------------------------------------------------------------------
//
// ----------------------------------------------------------------------------------------------
    public function getUsername() {
        return htmlEntities($this->m_username);
    }
// ----------------------------------------------------------------------------------------------
//
// ----------------------------------------------------------------------------------------------
    public function getPassword() {
        return $this->m_password;
    }
// ----------------------------------------------------------------------------------------------
//
// ----------------------------------------------------------------------------------------------
    public function getUserID() {
        return $this->m_userID;
    }
// ----------------------------------------------------------------------------------------------
//
// ----------------------------------------------------------------------------------------------
    public function getRoleID() {
        return $this->m_roleID;
    }
// ----------------------------------------------------------------------------------------------
//
// ----------------------------------------------------------------------------------------------
    public function getFirstName() {
        return htmlEntities($this->m_firstName);
    }
// ----------------------------------------------------------------------------------------------
//
// ----------------------------------------------------------------------------------------------
    public function getLastName() {
        return htmlEntities($this->m_lastName);
    }
// ----------------------------------------------------------------------------------------------
//
// ----------------------------------------------------------------------------------------------
public function getFirstNameRaw() {
    return $this->m_firstName;
}
// ----------------------------------------------------------------------------------------------
//
// ----------------------------------------------------------------------------------------------
public function getLastNameRaw() {
    return $this->m_lastName;
}
// ----------------------------------------------------------------------------------------------
//
// ----------------------------------------------------------------------------------------------
    public function getDateCreated() {
        return $this->m_dateCreated;
    }
// ----------------------------------------------------------------------------------------------
//
// ----------------------------------------------------------------------------------------------
    public function getDateCompleted() {
        return $this->m_dateCompleted;
    }
// ----------------------------------------------------------------------------------------------
//
// ----------------------------------------------------------------------------------------------
    public function setUsername($username) {
        $this->m_username = $username;
    }
// ----------------------------------------------------------------------------------------------
//
// ----------------------------------------------------------------------------------------------
    public function setPassword($password) {
        $this->m_password = $this->hashPassword($password);
    }
// ----------------------------------------------------------------------------------------------
//
// ----------------------------------------------------------------------------------------------
    public function setPasswordDoNotHash($password) {
        $this->m_password = $password;
    }
// ----------------------------------------------------------------------------------------------
//
// ----------------------------------------------------------------------------------------------
    public function setUserID($userID) {
        $this->m_userID = $userID;
    }
// ----------------------------------------------------------------------------------------------
//
// ----------------------------------------------------------------------------------------------
    public function setRoleID($roleID) {
        $this->m_roleID = $roleID;
    }
// ----------------------------------------------------------------------------------------------
//
// ----------------------------------------------------------------------------------------------
    public function setFirstName($firstName) {
        $this->m_firstName = $firstName;
    }
    // ----------------------------------------------------------------------------------------------
    //
    // ----------------------------------------------------------------------------------------------
    public function setLastName($lastName) {
        $this->m_lastName = $lastName;
    }
    // ----------------------------------------------------------------------------------------------
    //
    // ----------------------------------------------------------------------------------------------
    public function setDateCreated($dateCreated) {
        $this->m_dateCreated = $dateCreated;
    }
    // ----------------------------------------------------------------------------------------------
    //
    // ----------------------------------------------------------------------------------------------
    public function setDateCompleted($dateCompleted) {
        $this->m_dateCompleted = $dateCompleted;
    }
    // ----------------------------------------------------------------------------------------------
    //
    // ----------------------------------------------------------------------------------------------
    public function getFullName() {
        return $this->getFirstName()." ".$this->getLastName();
    }
    // ----------------------------------------------------------------------------------------------
    //
    // ----------------------------------------------------------------------------------------------
    public function getFullNameRaw() {
        return $this->getFirstNameRaw()." ".$this->getLastNameRaw();
        
    }
    // ----------------------------------------------------------------------------------------------
    //
    // ----------------------------------------------------------------------------------------------
    public function updateAllSalt() {
        $previousPasswordSalt = cLib_cConfig::getInstance()->getData(cLib_cConfig::_CONFIG_LAST_SALT_ID);

        $saltedAgain = md5("amcAef&6ca".self::SALT);

        //
        // SALT been change, we change all RoleID passwords to "password" using new salt.
        // This requires admin and super admin to change their password.
        //
        if ($saltedAgain != $previousPasswordSalt) {
            $SQL = "UPDATE users SET password = ? WHERE roleID in (".cLib_cUser::_cROLE_SUPERADMIN.", ".cLib_cUser::_cROLE_ADMIN.")";

            //
            // Change all super admin and admin passwords to "password" with new SALT
            //
            $stmt = $this->m_SQL->prepare($SQL);
            $newPassword = $this->hashPassword("password");
            $stmt->bind_param("s", $newPassword);
            $stmt->execute();
            $stmt->close();
        
            //
            // Write the encypted SALT to the config table
            //
            cLib_cConfig::getInstance()->writeData(cLib_cConfig::_CONFIG_LAST_SALT_ID, $saltedAgain);
        }
    }
}
// --------------------------------------------------------------------------------------------------
//
// 
//
//
// 
//
// --------------------------------------------------------------------------------------------------
class cLib_cUser extends cLib_cUserProfile {
	const _cROLE_ANONYMOUS      = 0;
    const _cROLE_SUPERADMIN     = 1;
	const _cROLE_ADMIN          = 2;
	const _cROLE_USER           = 4;
    const _cROLE_ALL            = cLib_cUser::_cROLE_SUPERADMIN | cLib_cUser::_cROLE_USER | cLib_cUser::_cROLE_ADMIN;
	private $db                 = null;
    // ----------------------------------------------------------------------------------------------
    //
    // ----------------------------------------------------------------------------------------------
	private function validRole($role) {
        return (($role | cLib_cUser::cROLE_ALL) != 0) && $role != cLib_cUser::_cROLE_ANONYMOUS;
	}
    // ----------------------------------------------------------------------------------------------
    //
    // ----------------------------------------------------------------------------------------------
	private function validateRole($role) {
		if (!$this->validRole($role)) {
			throw new Exception('cUser::validateRole illegal value - '.$role);
		}
	}
	// ----------------------------------------------------------------------------------------------
	// you can create a user record...
	// by being blank
	// by giving an id
	// by giving username and password
	// by creating a new user
	// ----------------------------------------------------------------------------------------------
	public function __construct($control) {
		$this->control  = $control;
		$this->m_SQL    = $control->getDB()->sql;

        $this->updateAllSalt();

        $this->resetUserProfile();
    }
    // ----------------------------------------------------------------------------------------------
    //
    // ----------------------------------------------------------------------------------------------
    function loginUsingUsernameAndPassword($username, $password) {
        $password = $this->hashPassword($password);
        
        $stmt = $this->m_SQL->prepare("SELECT userID, firstName, lastName, username, password, roleID, dateCreated, dateCompleted FROM users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        
        return $this->login($stmt);
    }
    // ----------------------------------------------------------------------------------------------
    //
    // ----------------------------------------------------------------------------------------------
    private function login($stmt) {
        $stmt->execute();
        
        $stmt->bind_result($userID, $firstName, $lastName, $username, $password, $roleID, $dateCreated, $dateCompleted);
        $OK = $stmt->fetch();
        
        if ($OK === true) {
            $this->setUserID($userID);
            $this->setFirstName($firstName);
            $this->setLastName($lastName);
            $this->setUsername($username);
            $this->setPasswordDoNotHash($password);
            $this->setRoleID($roleID);
            $this->setDateCreated($dateCreated);
            $this->setDateCompleted($dateCompleted);
        }
        else {
            $this->resetUserProfile();
        }
        
        $stmt->free_result();
        $stmt->close();
        
        return $OK === true ? $userID : false;
    }
    // ----------------------------------------------------------------------------------------------
    //
    // ----------------------------------------------------------------------------------------------
    function loginUsingUserID($userID) {
        $stmt = $this->m_SQL->prepare("SELECT userID, firstName, lastName, username, password, roleID, dateCreated, dateCompleted FROM users WHERE userID = ?");
        $stmt->bind_param("i", $userID);
        return $this->login($stmt);
    }
	// ----------------------------------------------------------------------------------------------
	// create a new user, validation empty string and string limits must me checked elsewhere...
	// ----------------------------------------------------------------------------------------------
	public function createNewUser($firstName, $lastName, $username, $password) {
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setRoleID(cLib_cUser::_cROLE_USER);
        $this->setDateCreated($this->getCurrentDate());
        
        $dateCreated = $this->getCurrentDate();
        $SQL = "INSERT INTO users (firstName, lastName, username, password, roleID, dateCreated, fullName) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->m_SQL->prepare($SQL);
        if ($stmt) {
            $stmt->bind_param("ssssiss", $firstName, $lastName, $username, $this->getPassword(), $this->getRoleID(), $this->getDateCreated(), $this->getFullNameRaw());
            $stmt->execute();
            
            $insertID = $stmt->insert_id;
            $rowAffected = $stmt->affected_rows;
            $stmt->close();
            
            if ($rowAffected == 0) {
                return false;
            }

            $this->setUserID($insertID);
            
            return $insertID;
        }
        return false;
	}
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    public function completed() {
        if ($this->getUserID() === null || $this->getDateCompleted() != null) {
            return;
        }
        
        $this->setDateCompleted($this->getCurrentDate());
        
        $stmt = $this->m_SQL->prepare("UPDATE users SET dateCompleted = ? WHERE userID = ?");
        
        if ($stmt) {
            $stmt->bind_param("si", $this->getDateCompleted(), $this->getUserID());
            $stmt->execute();
            $stmt->close();
        }
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    function getCurrentDate() {
        return date("Y-m-d H:i:s");
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    public function getUserRecord() {
        return $this;
    }
    // ----------------------------------------------------------------------------------------------
    // Returns the user role of record or null if not set
    // ----------------------------------------------------------------------------------------------
    public function isRoleSuperAdmin() {
		return $this->isLoggedIn() && ($this->getRoleID() & cLib_cUser::_cROLE_SUPERADMIN) == cLib_cUser::_cROLE_SUPERADMIN;
	}
    // --------------------------------------------------------------------------------------------------
    // Returns the user role of record or null if not set
    // --------------------------------------------------------------------------------------------------
    public function isRoleUser() {
		return $this->isLoggedIn() && ($this->getRoleID() & cLib_cUser::_cROLE_USER) == cLib_cUser::_cROLE_USER;
	}
    // --------------------------------------------------------------------------------------------------
    // Returns the user role of record or null if not set
    // --------------------------------------------------------------------------------------------------
    public function isRoleAdmin() {
		return $this->isLoggedIn() && ($this->getRoleID() & cLib_cUser::_cROLE_ADMIN) == cLib_cUser::_cROLE_ADMIN;
	}
	// --------------------------------------------------------------------------------------------------
    // Returns the user role of record or null if not set
    // --------------------------------------------------------------------------------------------------
    public function isLoggedIn() {
        return $this->getUserID() !== null;
    }
    // --------------------------------------------------------------------------------------------------
    // You can change the user record... null means no changes, return true if OK
    // --------------------------------------------------------------------------------------------------
    public function changeUserRecord($newUsername = null, $newPassword = null, $newRoleID = null, $newFirstName = null, $newLastName = null) {
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    public function updateFirstLastAndUsername($firstName, $lastName, $username) {
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
        $this->setUsername($username);

        $fullName = $firstName." ".$lastName;
        
        $stmt = $this->m_SQL->prepare("UPDATE users SET firstName = ?, lastName = ?, fullName = ?, username = ? WHERE userID = ?");
        $stmt->bind_param("ssssi", $firstName, $lastName, $fullName, $username, $this->getUserID());
        $stmt->execute();
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    public function updateUserRecord($firstName, $lastName) {
        $this->setFirstName($firstName);
        $this->setLastName($lastName);

        $fullName = $firstName." ".$lastName;
        
        $stmt = $this->m_SQL->prepare("UPDATE users SET firstName = ?, lastName = ?, fullName = ? WHERE userID = ?");
        $stmt->bind_param("sssi", $firstName, $lastName, $fullName, $this->getUserID());
        $stmt->execute();
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    public function changePassword($newPassword) {
        $this->setPassword($newPassword);
        
        $stmt = $this->m_SQL->prepare("UPDATE users SET password = ? WHERE userID = ?");
        $stmt->bind_param("si", $this->getPassword(), $this->getUserID());
        $stmt->execute();
    }
}