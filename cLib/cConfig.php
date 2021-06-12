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
class cLib_cConfig {
    const CONFIG_MAX_STRING_LENGTH = 100;
    
    // missing numbers below are 2, 9, 10... these numbers were removed and so can be reused for something else if you want

    const _CONFIG_LAST_SALT_ID = 1;
    const _CONFIG_REQUIRES_SCORE_COLUMN_ID = 3;
    const _CONFIG_REQUIRE_PASSWORD_A_Z_ID = 4;
    const _CONFIG_REQUIRE_PASSWORD_a_z_ID = 5;
    const _CONFIG_REQUIRE_PASSWORD_0_9_ID = 6;
    const _CONFIG_REQUIRE_PASSWORD_SYMBOL_ID = 7;
    const _CONFIG_REJECT_COMMON_PASSWORDS_ID = 8;
    const _CONFIG_PASSWORD_LENGTH = 11;
    const _CONFIG_COURSES = 12;
    //
    // config values
    //
    static private $m_instance = null;
    private $m_allocated = false;
    private $m_configData = null;
    private $m_SQL = null;
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    public function __construct($SQL) {
        if ($this->m_allocated) {
            $error = "cLib_cConfig is a singleton, you can't do new cLib_cConfig() use cLib_cConfig::getInstance()->getData(); instead";
            throw new Exception($error);
            die();
        }
        $this->m_allocated = true;
        
        $this->m_configData = [];
        
        for ($i = 0; $i < 100; $i++) {
            $this->m_configData[$i] = null;
        }
        
        $this->m_SQL = $SQL;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    static public function getInstance($SQL = null) {
        if ($SQL != null) {
            cLib_cConfig::$m_instance = new cLib_cConfig($SQL);
        }
        return cLib_cConfig::$m_instance;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    function getData($whichKeyValue) {
        //
        // Check and see if we have a cache version, if it null, read from database...
        //
        if ($this->m_configData[$whichKeyValue] == null) {

            $stmt = $this->m_SQL->prepare("SELECT keyValue FROM config WHERE keyID = ?");
            $stmt->bind_param("i", $whichKeyValue);
            
            $stmt->execute();
            
            $stmt->bind_result($keyValue);
            $OK = $stmt->fetch();
            
            if ($OK === true) {
                $this->m_configData[$whichKeyValue] = $keyValue;
            }
            else {
                $this->m_configData[$whichKeyValue] = NULL;
            }
            $stmt->free_result();
            $stmt->close();
        }
        
        return $this->m_configData[$whichKeyValue];
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    function writeData($whichKeyValue, $value) {
        //
        // Check and see if we have a cache version and if value is same, quit
        //
        $value .= "";
        if (strlen($value) > cLib_cConfig::CONFIG_MAX_STRING_LENGTH) {
            return;
        }
        
        if ($this->m_configData[$whichKeyValue] != null) {
            if ($this->m_configData[$whichKeyValue] === $value) {
                return true;
            }
        }
        
        switch ($whichKeyValue) {
            case cLib_cConfig::_CONFIG_LAST_SALT_ID:
                $referenceName = "Last Password Salt";
                break;
                                
            case cLib_cConfig::_CONFIG_REQUIRES_SCORE_COLUMN_ID:
                $referenceName = "Requires score column";
                break;
                
            case cLib_cConfig::_CONFIG_REQUIRE_PASSWORD_A_Z_ID:
                $referenceName = "Requires password A-Z";
                break;
                
            case cLib_cConfig::_CONFIG_REQUIRE_PASSWORD_a_z_ID:
                $referenceName = "Requires password a-z";
                break;
                
            case cLib_cConfig::_CONFIG_REQUIRE_PASSWORD_0_9_ID:
                $referenceName = "Requires password 0-9";
                break;
                
            case cLib_cConfig::_CONFIG_REQUIRE_PASSWORD_SYMBOL_ID:
                $referenceName = "Requires password symbol";
                break;
                
            case cLib_cConfig::_CONFIG_REJECT_COMMON_PASSWORDS_ID:
                $referenceName = "Reject common passwords";
                break;

            case cLib_cConfig::_CONFIG_PASSWORD_LENGTH:
                $referenceName = "Password length";
                break;
                
            case cLib_cConfig::_CONFIG_COURSES:
                $referenceName = "Course ID";
                break;
                
            default:
                $error = "cLib_cConfig->writeData() referenceName for key value:".$whichKeyValue." not added";
                throw new Exception($error);
                die();
        }
        
        //
        // Save data...
        //
        
        $stmt = $this->m_SQL->prepare("INSERT INTO config (keyID,keyName,keyValue) VALUES (?,?,?) ON DUPLICATE KEY UPDATE keyValue = ?");
        
        $stmt->bind_param('isss', $whichKeyValue, $referenceName, $value, $value);
        $stmt->execute();
        $stmt->close();
        
        //
        // Cache the save value...
        //
        $this->m_configData[$whichKeyValue] = $value;
        
        return true;
    }
}