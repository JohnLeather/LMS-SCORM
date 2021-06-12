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

/* -----------------------------------------------------
   Automatically includes PHP files so you don't have to worry about adding all the necessary includes to get the program to compile.
   If PHP can't find the class at runtime due to missing inclues statement in the code then it fires classAutoload which will automatically include it for you.
   All classes must be in the naming format of <directory>_c<className>
   
   Example:
   new cLib_cSession; would cause this routine to load from include("cLib/cSession.php");
   new cModel_cMenu; would cause this routine to load from  include("cModel/cMenu.php");
   and so on...
   
   If the filename /class doesn't exists, then PHP would behave normally which is to cause a critial error.
   The only exception to the critial error rule above are classes prefixed with cControl_ 
   Any classes / files missing prefixed with cControl_ causes the program to fire cControl/cError404.php which simply displays page missing instead of issuing a critial error.
   -----------------------------------------------------*/
  
class cLib_cAutoLoader
{
	private $defaultClass = null;
	private $control = null;
	private $path;
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
	public function __construct($defaultClass, $control, $path = "") {
		$this->defaultClass = $defaultClass;
		$this->control = $control;
		$this->path = explode(",", $path);
		
		spl_autoload_register(array($this, $path == "" ? 'classAutoload' : 'classPathAutoload'));	
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
	public function classPathAutoload($className) {
		list($directory, $classReference) = explode("_", $className);
		$filename = $directory."/".$classReference . '.php';
		
		foreach ($this->path as $p) {
			$fName = prefixPath.$p.$filename;
			if (is_file($fName)) {
				require_once $fName;
				return true;
			}
		}
		return false;
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
	public function classAutoload($className) {
		list($directory, $classReference) = explode("_", $className);
		
		$filename = prefixPath.$directory."/".$classReference . '.php';
		
		if (prependPrefixPath != "") {
		
			$prefilename = $directory."/".$classReference . '.php';
			if (is_file($prefilename)) {
				require_once $prefilename;
				return true;
			}
		}
		// now try root
		if (is_file($filename)) { 
			require_once $filename;
			return true;
		}
		
		return false;
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
	public function classCanBeLoaded($className) {
		return $this->classAutoload($className);
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
	public function getClass($className) {
		return ($this->classCanBeLoaded($className) ?  $className : $this->defaultClass);
	}
}
?>