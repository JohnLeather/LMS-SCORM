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
class cLib_cParameters {
	const cLib_classname = 1;
	const cLib_method = 2;
	
	// enums as the type of parameters it returns...
	const asString 	= 1;
	const asArray = 2;
	
	private $URI_cmdLine = null;
	private $className = "";
	private $method = "";
	private $rootClass;
	private $classDirectory;
	private $defaultMethod;
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
	public function __construct($classDirectory, $rootClass, $defaultMethod, $offsetURL = 0) {
		$this->classDirectory = $classDirectory;
		$this->rootClass = $rootClass;
		$this->defaultMethod = $defaultMethod;
		$this->offsetURL = $offsetURL;
		
		$this->parameterise();
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
	private function parameterise() {
		$URL = $_SERVER['REQUEST_URI'];
		
		if ($URL == "" || strcmp(substr($URL, -1, 1), '/')) {
			$URL .= "/";
		}
					
		$this->URI_cmdLine = explode("/", $URL);
        
        
        $pathC = $this->prependPrefixPathC();
		$parameterCount = count($this->URI_cmdLine) - $pathC;		
		
		$offsetURL = -(2 - $pathC) + $this->offsetURL;
		
		switch ($parameterCount) {
			case 0:
				$this->className = $this->rootClass;
				break;
			
			case 1:
				$this->className = $this->URI_cmdLine[cLib_cParameters::cLib_classname + $offsetURL];
				break;
				
			default:
				$this->className = $this->URI_cmdLine[cLib_cParameters::cLib_classname + $offsetURL];
				$this->method = $this->cleanUpFunctionPath($this->URI_cmdLine[cLib_cParameters::cLib_method + $offsetURL]);
				break;
		}
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    public function getPath() {
        return sysPathRoot."/".$this->URI_cmdLine[1]."/".$this->URI_cmdLine[2]."/";
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    private function cleanUpFunctionPath($path) {
        $len = strlen($path);
        $newStr = "";
        $nextLetterUppercase = false;
        for ($i = 0; $i < $len; $i++) {
            $c = substr($path, $i, 1);
            if ($c == '-') {
                $nextLetterUppercase = true;
            }
            else {
                if ($nextLetterUppercase) {
                    $c = strtoupper($c);
                    $nextLetterUppercase = false;
                }
                $newStr .= $c;
            }
        }
        
        return $newStr;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
	private function getPrependPrefixPath() {
		return (defined('prependPrefixPath')) ? prependPrefixPath : "";
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
	private function prependPrefixPathC() {
		return defined('prependPrefixPath') ? 2 : 1;
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
	//
	// if the $_SERVER['REQUEST_URI'] is blank then <$directory>_<$onEmtpyStringUse> is returned
	// If the REQUEST_URI contains something then <$directory>_<URL> is returned where URL is the first portion of the /
	//
	public function getClassName() {
		return $this->classDirectory."_".$this->className;
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
	public function getMethod() {
		return $this->method != "" ? $this->method : $this->defaultMethod;
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
	public function getParameters($pKind) {
		$pathC = $this->prependPrefixPathC();
		$parameterCount = count($this->URI_cmdLine);
		
		$parameterStart = $pathC + 1 + $this->offsetURL;
		
		switch ($pKind) {
			case cLib_cParameters::asString:
				$result = "";
				break;
				
			case cLib_cParameters::asArray:
				$result = array();
				while ($parameterStart < $parameterCount) {
					if ($this->URI_cmdLine[$parameterStart] != "")
						$result[] = urldecode($this->URI_cmdLine[$parameterStart]);
					$parameterStart++;
				}
				break;
				
			default:
				trigger_error('cLib_cParameters::getParameters - Invalid kind range: '.$pKind);
				break;
		}
		
		return $result;
	}
}