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
class cLib_cValidator {
	private $args = null;
	private $argsC = null;

    // --------------------------------------------------------------------------------------------------
	// Expects an array of parameters, if object passed as args then it gets the URL parameters for you if you pass the smarty class
    // --------------------------------------------------------------------------------------------------
	public function __construct($args) {
		if (is_object($args)) {
			$args = $args->control->getParameters()->getParameters(cLib_cParameters::asArray);
		}
		
		$this->setArgs($args);
	}
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    public function clearArgsIfOdd() {
        if ($this->argsC % 2 == 1) {
            $this->args = [];
            $this->argsC = 0;
        }
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
	public function setArgs($args) {
		$this->args = $args;
		$this->argsC = count($args);
	}
    // --------------------------------------------------------------------------------------------------
	// getArgs() returns the entire array
	// getArgs(4) returns the 5th element in the args array
	// attempts to read outside array element results in null
    // --------------------------------------------------------------------------------------------------
	public function getArgs($n = null) {
		if ($n == null) {
			return $this->args;
		}
		
		return ($n < 1 || $n > $this->argsC) ? null : $this->args[$n-1];
	}
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
	public function getArgsC() {
		return $this->argsC;
	}
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
	public function asPositiveNumber($n, $onFailureDefaultTo = null) {
		$value = $this->getArgs($n);
		if (!isset($value)) {
			return $onFailureDefaultTo;
		}
		
		if (!ctype_digit($value)) {
			return $onFailureDefaultTo;
		}
			
		$value = (int)$value;
		
		return is_int($value) && $value > 0 ? $value : $onFailureDefaultTo;
	}
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
	public function asID($n) {
		return $this->asPositiveNumber($n, 0);
	}
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
	public function asString($n) {
		return $this->getArgs($n);
	}
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
	public function getURL() {
		$result = "";
		$comma = "";
		
		for ($p = 0; $p < $this->argsC; $p++) {
			$result .= $comma.$this->args[$p];
			$comma = "/";
		}
		return $result;
	}
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
	public function setParameter($inx, $value) {
		$this->args[$inx-1] = $value;
	}
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
	public function addParameter($postParam, $value) {
		$this->args[] = $postParam;
		$this->args[] = $value;
		$this->argsC += 2;
	}
}
