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
    
if (!defined('version')) {
    define('version', "?v=1.000");
}

if (!defined('prefixPath')) {
	define('prefixPath', 			"");
	define('prependPrefixPath',		"");
}

if ($_SERVER['SERVER_NAME'] == "localhost") {
    define ('sysPathRoot', 				"http://localhost");
    define ('sysPath', 					"http://localhost/".prependPrefixPath);
    
}
else {
    define ('sysPathRoot', 				"http://".$_SERVER['SERVER_NAME']);
    define ('sysPath', 					"http://".$_SERVER['SERVER_NAME']);
}

require_once(prefixPath."cLib/cErrorHandler.php");
require_once(prefixPath."cLib/cAutoLoader.php");
    
class cControl  {
    const   cookiePath          = "LMSCookies";
    
	private $debug 				= true;
	private $db 				= null;
	public  $m_sessions         = null;
	public  $parameters 		= null;
	private $autoLoader 		= null;
	private	$errorHandler		= null;
	private $error				= null;
    private $tokenProblem       = false;
	public function __construct() {	
		// setup internal controllers
		$this->errorHandler = new cLib_cErrorHandler($this->debug);		
        
        $this->noError();
        $this->autoLoader = new cLib_cAutoLoader("cControl_cError404", $this);
		
		// setup common structures
		$this->db = new cLib_cDatabase();
		cLib_cConfig::getInstance($this->db->m_SQL);

		$this->m_sessions = new cLib_cSessions($this);
		$this->parameters = new cLib_cParameters("cControl", "index", "index");
        //
        // generate a new CSRF Token and validate if $_POST contains CSRFToken
        //
        if ($this->parameters->getClassName() != "cControl_ajax") {
            /*
             $this->m_sessions->generateNewCSRFTokens($this->parameters->getMethod());
            if (count($_POST) > 0) {
                if (!(isset($_POST['CSRFTokens']) && $this->m_sessions->validateCSRFTokens($_POST['CSRFTokens']))) {
                    unset($_POST);
                    $_POST = array();
                    $this->tokenProblem       = true;
                }
            }
             @done */
        }
        
		
		// go...
		$this->fire();
		
		// clean up
		$this->errorHandler->close();
	}
	
	private function fire() {
		$root = "index";
		
		$classToInstantiate	= $this->parameters->getClassName();
		
		$methodToCall		= "EXTERNAL_".$this->parameters->getMethod();
		$properClassName 	= $this->autoLoader->getClass($classToInstantiate);
		
		// cLib_cAutoLoader class will automatically include() the relevent PHP class file
		$firingClass = new $properClassName($this);
		
		if (method_exists($firingClass, "____EXCLUDE_TEMPLATE____")) {
			
			if (method_exists($firingClass, $methodToCall)) {
				
				$firingClass->$methodToCall(isset($parametersToPass) ? $parametersToPass : NULL);
				return;
			}
		}
		
        $parametersToPass = $this->parameters->getParameters(cLib_cParameters::asArray);
        
		if (method_exists($firingClass, $methodToCall)) {
			$firingClass->$methodToCall(isset($parametersToPass) ? $parametersToPass : NULL);
			$firingClass->view();
		}
		else {
			
			if ($this->debug) {
				echo "<div style='background:red'>";
				echo("Does not exists > ".$properClassName."->".$methodToCall."(".$parametersToPass.")");
				echo "</div>";
			}
            else {
                $pageNotFound = new cControl_cError404($this);
                $pageNotFound->EXTERNAL_index($parametersToPass);
                $pageNotFound->view();
            }
		}
	}
	
	// returns pointer to database class
	public function getDB()
	{
		return $this->db;
	}
	
	// returns pointer to session class
	public function getSession()
	{
		return $this->sessions;
	}
	
	// returns pointer to URL parameters class
	public function getParameters()
	{
		return $this->parameters;
	}
	
	public function getDebug()
	{
		return $this->debug;
	}
	
	public function getLanguageName()
	{
		debug_print_backtrace();
		$language = "English";		
		return $language;
	}
	
	public function getLanguageISO()
	{
		return "EN";
	}
	
	
	
	public function redirect($path = "") {
		header('location: '.$this->addErrorReportToURL($path), true, 302);
		exit(0);
	}
	
	// error reporting...
	public function reportError($errorStr) {
		$this->error = (object) array("error" => true, "reason" => $errorStr);
	}
	
	public function noError() {
		$this->error = (object) array("error" => false, "reason" => "");
	}
	
	public function getError() {
		return $this->error;
	}
	
	private function addErrorReportToURL($path) {
		if ($this->error->error) {
			if (substr($path, -1) != "/") {
				$path .= "/";
			}
			$path .= "error/".$this->error->reason;
		}
		return $path;
	}
}

new cControl();
?>