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
class cLib_cURL {
	private $validator;
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
	public function __construct($validator) {
		$this->validator = $validator;
		$this->removeParameters("error", 2);
	}	
	// Typical call consist of pairs such as "Page/5" or "Order/date", e.t.c.
	// $postParam would be "Page" or "Order" if the above is used.
	// $valueMethod is the method to call to obtain the second parameter in these paired part.
	// In "Page/5" the correct method to use would be "asPositiveNumber"
	// In "Order/Date" the method to use would be "asString"
	// The validator class contains all the various kind of valid parameter you can call.
	//
	public function URLPairPart($postParam, $valueMethod = "asString", $defaultValue = null) {
		$result = null;
		
		$argsC = $this->validator->getArgsC();
		
		for ($param = 1; $param < $argsC; $param++) {
			
			// get the URL parameter
			$p = $this->validator->asString($param);
		
			// does it match what being searched for...?
			if ($p == $postParam) {
				// yes, now get the value which is the next parameter along
				if ($valueMethod == "asPositiveNumber") {
					$value = $this->validator->$valueMethod($param + 1, $defaultValue);
					return $value;
				}
				else {
					$value = $this->validator->$valueMethod($param + 1);
					return $value;
				}
			}
            $param++;
		}
		return null; // Can't find it
	}
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
	public function replaceValue($postParam, $value) {
		$argsC = $this->validator->getArgsC();
		
		for ($param = 1; $param < $argsC; $param++) {
			// get the URL parameter
			$p = $this->validator->asString($param);
		
			if ($p == $postParam) {
				$this->validator->setParameter($param + 1, $value);
				return;
			}
            $param++;
		}
		$this->validator->addParameter($postParam, $value);
	}
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
	public function getURL() {
		return $this->validator->getURL();
	}
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
	public function removeParameters($postParam, $amount = 2) {
		if ($amount < 1) {
			$amount = 1;
		}
		
		$argsC = $this->validator->getArgsC();
		$args = $this->validator->getArgs();
		
		for ($i = 0; $i <= $argsC-$amount; $i++) {
			if (strcmp($args[$i], $postParam) == 0) {
				array_splice($args, $i, $amount);
				$this->validator->setArgs($args);		
				return true;
			}
		}
		
		return false;
	}
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    public function clearArgsIfOdd() {
        $this->validator->clearArgsIfOdd();
    }
}