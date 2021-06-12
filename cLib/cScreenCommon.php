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
abstract class cLib_cScreenCommon {
    public  $m_control = null;
    public  $validator = null;
    public  $m_URLRewrite = null;
    public  $m_TPLFile = null;
    public  $m_message;
    private $m_messageHTML = "";
    private $m_template;
    private $m_TPLFileForSidePanel;
    private $m_ParametersForSidePanel;

    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
	public function __construct($control) {
        $this->m_control = $control;
        $this->m_template = (object) array();
        $this->m_message = "";
        $this->m_template->m_JS = array();
        $this->m_template->m_injectJS = array();
        
        $this->m_template->m_message = "";
        $this->m_template->m_header = "";
        $this->m_template->m_subHeader = "";
        
		$this->validator = new cLib_cValidator($control->getParameters()->getParameters(cLib_cParameters::asArray));
		$this->m_URLRewrite = new cLib_cURL($this->validator);
		$this->m_TPLFile = array();
		$this->m_TPLFileForSidePanel = array();
		$this->m_ParametersForSidePanel = array();
        
		//parent::__construct();
		$this->model();
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function setPageHeader($header) {
        $this->m_template->m_header = $header;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function setPageSubHeader($subHeader) {
        $this->m_template->m_subHeader = $subHeader;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function sendMessage($message, $cssClassName = "error") {
        $this->m_template->m_message = "<div id='event-message' class='event-message'><div class='".$cssClassName."'>".$message."</div></div>";
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function assignTemplateVar($vars) {
        foreach ($vars as $name => $value) {
            $this->m_template->$name = $value;
        }
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function getTemplate() {
        return $this->m_template;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function setTemplate($template) {
        $this->m_template = $template;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function setPath($path) {
        $this->m_template->m_path = $path;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
	public function setTitle($title) {
        $this->m_template->m_title = $title;
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
	public function setDescription($description) {
        $this->m_template->m_description = $description;
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function setKeywords($keywords) {
        $this->m_template->m_keywords = $keywords;
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function setParameters($parameters) {
        $this->m_template->m_parameters = $parameters;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function setCanonical($path) {
        $this->m_template->m_canonical = $path;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function addModel($modal, $owner) {
        //$template = $this->m_template;
        $moduleName = "cModel_".$modal;
        $this->model = new $moduleName($owner);
                
        //$this->m_template = $template;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function appendJS($JS) {
        $this->m_template->m_JS[] = $JS;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function injectJS($JS) {
        $this->m_template->m_injectJS[] = $JS;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function setSidePanel($sidePanelTPL, $sidePanelParameters) {
		$this->m_TPLFileForSidePanel[] = $sidePanelTPL;
		$this->m_ParametersForSidePanel[] = $sidePanelParameters;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    private function setUserRole() {
        $canAccessAdminSection = false;
        
        if ($this->m_control->m_sessions->isRoleUser()) {
            $this->m_template->m_userRole = "User";
            
        }
        else if ($this->m_control->m_sessions->isRoleSuperAdmin()) {
            $this->m_template->m_userRole = "SuperAdmin";
            $canAccessAdminSection = true;
        }
        else if ($this->m_control->m_sessions->isRoleAdmin()) {
            $this->m_template->m_userRole = "Admin";
            $canAccessAdminSection = true;
        }
        else {
            $this->m_template->m_userRole = "Anon";
        }
        
        $this->m_template->m_canAccessAdminSection = $canAccessAdminSection;
                     
    }
    // --------------------------------------------------------------------------------------------
    // this draws the site wide template common to all web pages... header, menus, footer, whatever requires
    // --------------------------------------------------------------------------------------------
	public function view() {
        
        $template = $this->m_template;
        
        $template->m_TPLFileForSidePanel = $this->m_TPLFileForSidePanel;
        $template->m_CSRFTokens = "<input type='hidden' value='".$this->generateNewCSRFTokens()."' name='CSRFTokens' id='CSRFTokens'>";
        $this->setUserRole();
        
        if (count($template->m_TPLFileForSidePanel) > 0) {
            $template->m_ParametersForSidePanel = $this->m_ParametersForSidePanel;
        }
        
		if (count($this->m_TPLFile) == 0) {
			if ($this->m_control->getDebug()) {
				$content = "<div style='background:red'>Contact admin with the following error - cLib_cScreenCommon::view() failed</div>";
			}
		}
        
        { // leave this alone, it closes the scope of any local php vars
            include "./cViews/header.tpl";
        }
        
        { // leave this alone, it closes the scope of any local php vars
            if (!isset($content)) {
                foreach ($this->m_TPLFile as $t) {
                    include "./cViews/".$t;
                }
            }
            else {
                echo $content;
            }
        }

        { // leave this alone, it closes the scope of any local php vars
            include "./cViews/footer.tpl";
        }
	}
    // --------------------------------------------------------------------------------------------
    // Set the TPL file of the current web page you are viewing. Multiple TPL files allowed.
    // --------------------------------------------------------------------------------------------
	public function setView($TPLFile) {
		$this->m_TPLFile[] = $TPLFile;
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
	protected function redirect($path) {
		$this->m_control->redirect(sysPath.$path);
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
	protected function changeLanguage() {
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    protected function generateNewCSRFTokens() {
        return $this->m_control->m_sessions->getCSRFToken();
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function fixJSQuotes($str) {
        return str_replace('"', '\"', str_replace("'", "\'", $str));
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function location($url) {
        //      if ($this->m_template->m_message != "") {
        //    $_SESSION['message'] = $this->m_template->m_message;
        //}
        header('Location: '.sysPathRoot.$url);//, true, 301);
        die();
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function locationEx($url) {
        //      if ($this->m_template->m_message != "") {
        //    $_SESSION['message'] = $this->m_template->m_message;
        //}
        header('Location: '.$url);//, true, 301);
        die();
    }
}