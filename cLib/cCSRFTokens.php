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
class cLib_cCSRFTokens {
    const URL_SALT = "3b4w8ucywzd98d4va55cza532qe8e141c";
    
    private $oldCSRFToken = NULL;
    private $newCSRFToken = NULL;
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    function generateNewCSRFTokens($URL) {
        if ($this->oldCSRFToken == null) {
            $this->oldCSRFToken = isset($_SESSION['CSRFToken']) ? $_SESSION['CSRFToken'] : "";
            
            $ip = $_SERVER['REMOTE_ADDR'];
            $uniqid = uniqid(mt_rand(), true);
            
            $this->newCSRFToken = md5($ip.$uniqid);
            $this->URL = $URL;
            $_SESSION['CSRFToken'] = $this->newCSRFToken;
        }
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    function simpleCSRFTokenValidation($token) {
        $activeCSRFToken = isset($_SESSION['CSRFToken']) ? $_SESSION['CSRFToken'] : "";
        $tokenA = "";
        $tokenB = "";
        $toggle = 0;
        for ($i = 0; $i < strlen($token) && $i < 64; $i++) {
            if ($toggle == 0) {
                $tokenA .= substr($token, $i, 1);
            }
            else {
                $tokenB .= substr($token, $i, 1);
            }
            $toggle = !$toggle;
        }
        
       return $activeCSRFToken == $tokenA;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    function validateCSRFTokens($token) {
        return true;//$token == $this->shuffle($this->oldCSRFToken, $this->getURLasMD5());
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    function getCSRFToken() {
        return $this->shuffle($this->newCSRFToken, $this->getURLasMD5());
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    function getURLasMD5() {
        return @md5($this->URL.cLib_cCSRFTokens::URL_SALT);
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    function shuffle($str1, $str2) {
        $newStr = "";
        for ($i = 0; $i < strlen($str1); $i++) {
            $newStr .= substr($str1, $i, 1);
            $newStr .= substr($str2, $i, 1);
        }
        return $newStr;
    }
};
