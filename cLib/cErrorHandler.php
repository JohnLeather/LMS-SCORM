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
class cLib_cErrorHandler {
	// constructor
	private $debug = false;
	private $closed = false;
	// --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    public function __construct($debug) {
		$this->debug = $debug;
		set_error_handler(array($this, 'errorHandler'), E_ALL ^ E_NOTICE);
		register_shutdown_function(array($this, 'handleShutdown'));
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
	public function close() {
		$this->closed = true;
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
	public function handleShutdown() {
		if ($this->closed) {
			return;
		}
        $error = error_get_last();
		
        if ($error !== NULL) {
			echo "<div style='background:red'>Error</div>";
            echo "[File]:".$error['file']."<br>";
			echo "[Line]:".$error['line']."<br>";
			echo "[Mess]:".$error['message']."<br>";
        }
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    public function errorHandler($errno, $errstr='', $errfile='', $errline='') {
        /*
        $stacktrace = debug_backtrace();
        print str_repeat("=", 50) ."<br>";
        $i = 1;
        foreach($stacktrace as $node) {
            //var_dump($node);
            print "$i. ".basename($node['file']) .":" .$node['function'] ."(" .$node['line'].")<br>";
            $i++;
        }*/
        
        // if error has been supressed with an @
        if (error_reporting() == 0 || $this->closed) {
            return;
        }

        // check if function has been called by an exception
        if(func_num_args() == 5) {
            // called by trigger_error()
            $exception = null;
            list($errno, $errstr, $errfile, $errline) = func_get_args();

            $backtrace = array_reverse(debug_backtrace());
        } 
        else {
            // caught exception
            $exc = func_get_arg(0);
            $errno = $exc->getCode();
            $errstr = $exc->getMessage();
            $errfile = $exc->getFile();
            $errline = $exc->getLine();

            $backtrace = $exc->getTrace();
        }

        $errorType = array (
                E_ERROR            => 'ERROR',
                E_WARNING        => 'WARNING',
                E_PARSE          => 'PARSING ERROR',
                E_NOTICE         => 'NOTICE',
                E_CORE_ERROR     => 'CORE ERROR',
                E_CORE_WARNING   => 'CORE WARNING',
                E_COMPILE_ERROR  => 'COMPILE ERROR',
                E_COMPILE_WARNING => 'COMPILE WARNING',
                E_USER_ERROR     => 'USER ERROR',
                E_USER_WARNING   => 'USER WARNING',
                E_USER_NOTICE    => 'USER NOTICE',
                E_STRICT         => 'STRICT NOTICE',
                E_RECOVERABLE_ERROR  => 'RECOVERABLE ERROR'
                );

        // create error message
        if (array_key_exists($errno, $errorType)) {
            $err = $errorType[$errno];
        } else {
            $err = 'CAUGHT EXCEPTION';
        }

        $errMsg = "$err: $errstr in $errfile on line $errline";

        // start backtrace
        foreach ($backtrace as $v) {

            if (isset($v['class'])) {

                $trace = 'in class '.$v['class'].'::'.$v['function'].'(';

                if (isset($v['args'])) {
                    $separator = '';

                    foreach($v['args'] as $arg ) {
                        $trace .= "$separator".$this->getArgument($arg);
                        $separator = ', ';
                    }
                }
                $trace .= ')';
            }

            elseif (isset($v['function']) && empty($trace)) {
                $trace = 'in function '.$v['function'].'(';
                if (!empty($v['args'])) {

                    $separator = '';

                    foreach($v['args'] as $arg ) {
                        $trace .= "$separator".$this->getArgument($arg);
                        $separator = ', ';
                    }
                }
                $trace .= ')';
            }
        }

        // display error msg, if debug is enabled
        if($this->debug) {
            echo '<h2>Debug Msg</h2>'.nl2br($errMsg).'<br />
                Trace: '.nl2br($trace).'<br />';
        }

        // what to do
        switch ($errno) {
            case E_NOTICE:
            case E_USER_NOTICE:
                return;
                break;

            default:
                if(!$this->debug){
                    // end and display error msg
                    exit($this->displayClientMessage());
                }
                else
                    exit('<p>aborting.</p>');
                break;

        }
        
                echo("<table cellspacing='1' cellpadding='1' border='1'><tr><td>Function</td><td>Line</td><td>File</td></tr>");
                
                foreach(debug_backtrace() as $row)
                    echo("<tr><td>".$row['function']."</td><td>".$row['line']."</td><td>".$row['file']."</td></tr>");
                
                echo("</table><br/>");


    } // end of errorHandler()
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    private function displayClientMessage() {
        echo 'A Critical error has occured in code. Script aborted for security reasons.';
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    private function getArgument($arg) {
        switch (strtolower(gettype($arg))) {

            case 'string':
                return( '"'.str_replace( array("\n"), array(''), $arg ).'"' );

            case 'boolean':
                return (bool)$arg;

            case 'object':
                return 'object('.get_class($arg).')';

            case 'array':
                $ret = 'array(';
                $separtor = '';

                foreach ($arg as $k => $v) {
                    $ret .= $separtor.$this->getArgument($k).' => '.$this->getArgument($v);
                    $separtor = ', ';
                }
                $ret .= ')';

                return $ret;

            case 'resource':
                return 'resource('.get_resource_type($arg).')';

            default:
                return var_export($arg, true);
            }
        }
    }
?>