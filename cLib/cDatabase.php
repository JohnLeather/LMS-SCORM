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
class cLib_cDatabase {
    public $m_SQL;
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    public function __construct() {
        $this->connect();
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    private function connect() {
        if ($_SERVER['SERVER_NAME'] == "localhost") {
            //
            // If it local server use these settings
            //
            $_cDB_PASS = "password";
            $_cDB_USER = 'root';
            $_cDB_NAME = 'LMS';
            $_cDB_HOST = 'localhost:3306';
        }
        else {
            //
            // If it live server, you may want to change this to different settings
            //
            $_cDB_PASS = "password";
            $_cDB_USER = 'root';
            $_cDB_NAME = 'LMS';
            $_cDB_HOST = 'localhost:3306';                
        }
        $conn = @new mysqli($_cDB_HOST, $_cDB_USER, $_cDB_PASS, $_cDB_NAME);
            
        if ($conn->connect_error) {
            //var_dump($conn);
            die('Failed to connect to database - Give it a few seconds and then refresh browser');
        }
            
        $this->m_SQL = $conn;
        $this->sql = $this->m_SQL;
    }
}