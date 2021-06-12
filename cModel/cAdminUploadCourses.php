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
class cModel_cAdminUploadCourses {
    function __construct($owner) {
        $this->m_owner = $owner;
        $this->m_SQL = $this->m_owner->m_control->getDB()->sql;
        $this->run();
    }
    // --------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------
    function run() {
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache"); 
        header("Expires: 0"); 
        
        $this->m_owner->setPageSubHeader("Manage Courses");
        
        $this->renderListOfCourses();
        $moduleList = $this->createHTMLFromCourseList();
        
        $this->m_owner->assignTemplateVar(array("m_listOfAvailableModules"  => $moduleList));
        
        $this->m_owner->appendJS("/js/adminLMSUploadCourse.js");
        
        $courseIDSanitize = intVal(cLib_cConfig::getInstance()->getData(cLib_cConfig::_CONFIG_COURSES), 10);
        
        $courseID = str_pad($courseIDSanitize, 4, '0', STR_PAD_LEFT);
        
        $this->m_owner->injectJS("var courseID = '".$courseID."'");
        $this->m_owner->assignTemplateVar(array("m_courseID"  => $courseID));
        $this->m_owner->setView("adminUploadCourses.tpl");
        $this->scanDirectories();
    }
    // --------------------------------------------------------------------------------------------
    // Reads the manifest file...
    // --------------------------------------------------------------------------------------------
    function readManifestFile($filePath) {
        $SCOdata = $this->readLMSManifestFile($filePath);
    }
    // --------------------------------------------------------------------------------------------
    // rend the courses
    // --------------------------------------------------------------------------------------------
    function renderListOfCourses() {
        $this->modules = [];
        
        $SQL  = "SELECT moduleID, ModuleTitle, roleID FROM modules";
        
        $stmt = $this->m_SQL->prepare($SQL);
    
        $stmt->execute();
        
        $stmt->bind_result($moduleID, $moduleTitle, $roleID);
        
        while ($stmt->fetch()) {
            $moduleInfo = new stdClass();
            
            $moduleInfo->moduleID = $moduleID;
            $moduleInfo->moduleTitle = $moduleTitle;
            $moduleInfo->roleID = $roleID;

            $this->modules[] = $moduleInfo;
        }
        $stmt->close();
    }
    // --------------------------------------------------------------------------------------------
    // render the courses to HMTL
    // --------------------------------------------------------------------------------------------
    function createHTMLFromCourseList() {
        $moduleHTML = "";
        
        $total = count($this->modules);
        
        foreach ($this->modules as $module) {
            $roleID = $module->roleID;
            
            $roles = [];
            
            if (($roleID & cLib_cUser::_cROLE_SUPERADMIN) != 0) {
                $roles[] = "Super Administrator";
            }
            if (($roleID & cLib_cUser::_cROLE_ADMIN) != 0) {
                $roles[] = "Administrator";
            }
            if (($roleID & cLib_cUser::_cROLE_USER) != 0) {
                $roles[] = "User";
            }
            
            $rolesDetails = implode(", ", $roles);
            
            if ($rolesDetails == "") {
                $rolesDetails = "None";
            }
            
            $moduleHTML .= '<div class="" style="border-bottom:1px solid black; padding : 10px;width:100%;text-align:left">';
            $moduleHTML .= '<div style="float:left"><span>'.htmlEntities($module->moduleTitle).'</span><br><span>Roles: '.$rolesDetails.'</span></div>';
            $moduleHTML .= '<div style="float:right"><a href="/admin/edit-course/id/'.$module->moduleID.'"><div class="button">Config</div></a></div>';
            $moduleHTML .= '<br style="clear:both">';
            $moduleHTML .= '</div>';
            $total--;
        }
        return $moduleHTML;
    }
    // --------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------
    function scanDirectories() {
        $this->createNewCourseDirectory();
    }
    // --------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------
    function createNewCourseDirectory() {
        $courseIDSanitize = intVal(cLib_cConfig::getInstance()->getData(cLib_cConfig::_CONFIG_COURSES), 10);
        $courseIDSanitize = str_pad($courseIDSanitize, 4, '0', STR_PAD_LEFT);
        $myDirectories = scandir("courses");
        $courseDirectory = "course".$courseIDSanitize;
        $uploadPath = "courses/".$courseDirectory;
        
        if (!in_array($courseDirectory, $myDirectories)) {
            mkdir($uploadPath);
        }
        
        $myDirectories = scandir($uploadPath);
        $directoryIsEmpty = count($myDirectories) <= 2;
    }
};
?>