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
class cModel_cAdminEditCourse extends cLib_cCourseSupport {
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
        
        $userRecord = $this->m_owner->m_control->m_sessions->getUserRecord();
        $this->userRecord = $userRecord;
        
        $this->userID = $userRecord->getUserID();
        $this->moduleID = $this->m_owner->m_URLRewrite->URLPairPart("id");
        
        //
        // ID missing... abort...
        //
        if ($this->moduleID == NULL) {
            $this->m_owner->setView("404.tpl");
            return;
        }
        $stmt = $this->m_SQL->prepare("SELECT count(*) FROM modules WHERE moduleID = ?");
        $stmt->bind_param('i', $this->moduleID);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        //
        // this course ID doesn't exits... abort
        //
        if ($count == 0) {
            $this->m_owner->setView("404.tpl");
            return;
        }
        
        $this->renderListOfCourses(false);
        $this->m_owner->setPageSubHeader("Manage ".htmlEntities($this->moduleTitle));
        
        //$this->readManifestFile(sysPathRoot."/courses/course0001/imsmanifest.xml");
        
        if (isset($_POST["action"])) {
            $this->handleRoles();
        }
        
        
        $this->renderListOfCourses(true);
        //
        // Collect all the course IDs
        //
        $moduleIDs = [];
        $moduleInfo = new stdClass();
        
        $moduleInfo->URL = $this->URL;
        $moduleInfo->windowWidth = $this->windowWidth;
        $moduleInfo->windowHeight = $this->windowHeight;
        $moduleInfo->moduleID = $this->moduleID;
        $moduleInfo->courseID = $this->courseID;
        
        $moduleIDs[] = $moduleInfo;
        
        $this->courseInfo($this->courseID);
    
        $this->m_owner->injectJS("var moduleInfo = '".json_encode($moduleIDs)."'");
        $this->m_owner->injectJS("var userID = ".$this->userID);

        $this->m_owner->appendJS("/js/adminLMSEditCourseDetails.js");
        if ($userRecord->getRoleID() == cLib_cUser::_cROLE_SUPERADMIN) {
            $this->m_owner->appendJS("/js/LMSScormDebug.js");
        }
        $this->m_owner->appendJS("/js/LMSScorm12RTE.js");
        
        $this->m_owner->assignTemplateVar(array("m_moduleID" => $this->moduleID));
        
        $this->setTPLFiles();
        
    }
    // --------------------------------------------------------------------------------------------
    // list of TPL files to display...
    // --------------------------------------------------------------------------------------------
    function setTPLFiles() {
        $this->m_owner->setView("adminEditCourse.tpl");
    }
    // --------------------------------------------------------------------------------------------
    // Reads the manifest file...
    // --------------------------------------------------------------------------------------------
    function readManifestFile($filePath) {
        $SCOdata = $this->readLMSManifestFile($filePath);
    }
    // --------------------------------------------------------------------------------------------
    // render the courses
    // --------------------------------------------------------------------------------------------
    function renderListOfCourses($assignToTemplate = false) {
        $this->modules = [];
        
        $SQL  = "SELECT m.moduleID, m.moduleTitle, m.roleID, m.masteryScore, m.maxTimeAllowed, m.dataFromLMS, m.timeLimitAction, m.prerequisites, m.courseID, m.URL, c.windowWidth, c.windowHeight ";
        $SQL .= " FROM modules m";
        $SQL .= " LEFT JOIN courses c ON c.courseID = m.courseID";
        $SQL .= " WHERE moduleID = ?";
        
        $stmt = $this->m_SQL->prepare($SQL);
    
        $stmt->bind_param("i", $this->moduleID);
                            
        $stmt->execute();
        
        $stmt->bind_result($moduleID, $moduleTitle, $roleID, $masteryScore, $maxTimeAllowed, $dataFromLMS, $timeLimitAction, $prerequisites, $courseID, $URL, $windowWidth, $windowHeight);
        
        while ($stmt->fetch()) {
            
            $this->moduleID = $moduleID;
            $this->moduleTitle = $moduleTitle;
            $this->roleID = $roleID;
            $this->masteryScore = $masteryScore;
            $this->maxTimeAllowed = $maxTimeAllowed;
            $this->dataFromLMS = $dataFromLMS;
            $this->timeLimitAction = $timeLimitAction;
            $this->prerequisites = $prerequisites;
            $this->courseID = $courseID;
            $this->URL = $URL;
            $this->windowWidth = $windowWidth;
            $this->windowHeight = $windowHeight;
            
            if ($assignToTemplate) {
                $this->m_owner->assignTemplateVar(array("m_moduleID" => $moduleID));
                $this->m_owner->assignTemplateVar(array("m_moduleTitle" => $moduleTitle));
                $this->m_owner->assignTemplateVar(array("m_roleID" => $roleID));
                $this->m_owner->assignTemplateVar(array("m_masteryScore" => $masteryScore));
                $this->m_owner->assignTemplateVar(array("m_maxTimeAllowed" => $maxTimeAllowed));
                $this->m_owner->assignTemplateVar(array("m_dataFromLMS" => $dataFromLMS));
                $this->m_owner->assignTemplateVar(array("m_timeLimitAction" => $timeLimitAction));
                $this->m_owner->assignTemplateVar(array("m_prerequisites" => $prerequisites));
                $this->m_owner->assignTemplateVar(array("m_courseID" => $courseID));
                $this->m_owner->assignTemplateVar(array("m_URL" => $URL));
                $this->m_owner->assignTemplateVar(array("m_windowWidth" => $windowWidth));
                $this->m_owner->assignTemplateVar(array("m_windowHeight" => $windowHeight));
                
                $this->m_owner->assignTemplateVar(array("m_superAdmin" => ($roleID & cLib_cUser::_cROLE_SUPERADMIN) != 0 ? "checked" : ""));
                $this->m_owner->assignTemplateVar(array("m_admin" => ($roleID & cLib_cUser::_cROLE_ADMIN) != 0 ? "checked" : ""));
                $this->m_owner->assignTemplateVar(array("m_user" => ($roleID & cLib_cUser::_cROLE_USER) != 0 ? "checked" : ""));
            }
            break;
        }
        $stmt->close();
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    function courseInfo($courseID) {
        $SQL = "SELECT count(*) FROM CMIResults WHERE courseID = ?";
        $stmt = $this->m_SQL->prepare($SQL);
        $stmt->bind_param('i', $courseID);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        $this->m_owner->assignTemplateVar(array("m_userRecords" => $count));
        
        $SQL = "SELECT moduleTitle FROM modules WHERE courseID = ?";
        $stmt = $this->m_SQL->prepare($SQL);
        $stmt->bind_param('i', $courseID);
        $stmt->execute();
        $stmt->bind_result($moduleTitle);
        $moduleTitleBeingDeleted = [];
        while ($stmt->fetch()) {
            $moduleTitleBeingDeleted[] = $moduleTitle;
        }
        $stmt->close();
        
        $this->m_owner->assignTemplateVar(array("m_isSingleSCO" => count($moduleTitleBeingDeleted) == 1));
        $this->m_owner->assignTemplateVar(array("m_moduleTitles" => implode("<br>", $moduleTitleBeingDeleted)));
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    function handleRoles() {
        if (!isset($_POST["window-width"])) {
            $this->m_owner->sendMessage("Failed to set window width");
            return;
        }
        
        if (!isset($_POST["window-height"])) {
            $this->m_owner->sendMessage("Failed to set window height");
            return;
        }
        
        $width = intval($_POST["window-width"], 10);
        $height = intval($_POST["window-height"], 10);
        
        if ($width < 400) {
            $width = 400;
        }
        else if ($width > 4096) {
            $width = 4096;
        }
        
        if ($height < 400) {
            $height = 400;
        }
        else if ($height > 4096) {
            $height = 4096;
        }
        
        $newRoleID = 0;
        $newRoleID += (isset($_POST["superAdmin-cb"]) ? cLib_cUser::_cROLE_SUPERADMIN : 0);
        $newRoleID += (isset($_POST["admin-cb"]) ? cLib_cUser::_cROLE_ADMIN : 0);
        $newRoleID += (isset($_POST["user-cb"]) ? cLib_cUser::_cROLE_USER : 0);
        
        $SQL = "SELECT roleID FROM modules WHERE moduleID = ?";
        $stmt = $this->m_SQL->prepare($SQL);
        $stmt->bind_param("i", $this->moduleID);
        $stmt->execute();
        $stmt->bind_result($oldRoleID);
        $stmt->fetch();
        $stmt->close();

        
        $SQL = "UPDATE courses SET windowWidth = ?, windowHeight = ? WHERE courseID = ?";
        $stmt = $this->m_SQL->prepare($SQL);
        $stmt->bind_param("iii", $width, $height, $this->courseID);
        $stmt->execute();
        $stmt->close();
        
        $SQL = "UPDATE modules SET roleID = ? WHERE moduleID = ?";
        $stmt = $this->m_SQL->prepare($SQL);
        $stmt->bind_param("ii", $newRoleID, $this->moduleID);
        $stmt->execute();
        $stmt->close();
        
        $this->resetComplete($oldRoleID, $newRoleID);
        
        $this->m_owner->sendMessage("Saved new course settings", "success");
    }
};
?>