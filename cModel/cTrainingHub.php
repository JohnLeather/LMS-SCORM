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
class cModel_cTrainingHub {
    function __construct($owner) {
        $this->m_owner = $owner;
        $this->m_SQL = $this->m_owner->m_control->getDB()->sql;
        
        $this->run();
    }
    // --------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------
    function run() {
        $userRecord = $this->m_owner->m_control->m_sessions->getUserRecord();
        $this->userRecord = $userRecord;

        $this->userID = $userRecord->getUserID();
        $this->m_owner->setPageHeader("Training hub");
        $this->m_owner->setPageSubHeader("<span style='color:black'>Welcome</span> ".$userRecord->getFirstName());
        
        $this->requireScoreColumnNONSanitize = cLib_cConfig::getInstance()->getData(cLib_cConfig::_CONFIG_REQUIRES_SCORE_COLUMN_ID);
        
        //
        // Logic goes here
        //
        $this->m_owner->setView("trainingHub.tpl");
        
        $this->getAllCourses();
        
        $moduleList = $this->createModuleListInHTMLFormat();
        $moduleListMV = $this->createModuleListMobileViewInHTMLFormat();
        
        $fullName = $userRecord->getFullName();
        $createdTimeStamp = $userRecord->getDateCreated();
        $createdDate = date('l jS F Y', strtotime($createdTimeStamp));
        $createdTime = date('g:i a', strtotime($createdTimeStamp));
        //$createdTime = date('H:i:s', strtotime($createdTimeStamp));
        
        $completedDate = "";
        $completedTime = "";
        if ($userRecord->getDateCompleted() != null) {
            $completedTimeStamp = $userRecord->getDateCompleted();
            $completedDate = date('l jS F Y', strtotime($completedTimeStamp));
            $completedTime = date('g:i a', strtotime($completedTimeStamp));
        }
        
        $this->m_owner->assignTemplateVar(array("m_listOfAvailableModulesMV"    => $moduleListMV));
        $this->m_owner->assignTemplateVar(array("m_listOfAvailableModules"      => $moduleList));
        $this->m_owner->assignTemplateVar(array("m_fullName"                    => $fullName));
        $this->m_owner->assignTemplateVar(array("m_dateCreated"                 => $createdDate." - ".$createdTime));
        $this->m_owner->assignTemplateVar(array("m_dateCompleted"               => $completedDate."&nbsp;-&nbsp;".$completedTime));
        $this->m_owner->assignTemplateVar(array("m_hasScoreColumn"              => $this->requireScoreColumnNONSanitize == 1));
        
        if ($userRecord->getRoleID() == cLib_cUser::_cROLE_SUPERADMIN) {
            $this->m_owner->appendJS("/js/LMSScormDebug.js");
        }
        $this->m_owner->appendJS("/js/LMSScorm12RTE.js");
        //
        // Collect all the course IDs
        //
        $moduleIDs = [];
        foreach ($this->modules as $module) {
            $moduleInfo = new stdClass();
            
            $moduleInfo->URL                = $module->URL;
            $moduleInfo->windowWidth        = $module->windowWidth;
            $moduleInfo->windowHeight       = $module->windowHeight;
            $moduleInfo->moduleID           = $module->moduleID;
            $moduleInfo->courseID           = $module->courseID;
            $moduleIDs[]                    = $moduleInfo;
        }
        $this->m_owner->injectJS("var moduleInfo = '".json_encode($moduleIDs)."'");
        $this->m_owner->injectJS("var userID = ".$userRecord->getUserID());
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    function getAllCourses() {
        $this->modules = [];
        
        $userID = $this->userRecord->getUserID();
        $roleID = $this->userRecord->getRoleID();
        
        $SQL  = "SELECT r.userID, r.courseID, r.moduleID, r.startedDate, r.status, r.completedDate, r.score, ";
        $SQL .= "m.moduleID, m.moduleTitle, m.roleID, m.masteryScore, m.maxTimeAllowed, m.dataFromLMS, m.timeLimitAction, m.prerequisites, m.courseID, m.URL, ";
        $SQL .= "c.windowWidth, c.windowHeight ";
        $SQL .= "FROM modules m ";
        $SQL .= "LEFT JOIN CMIResults r ON r.moduleID = m.moduleID AND r.userID = ? ";
        $SQL .= "LEFT JOIN courses c ON c.courseID = m.courseID ";
        $SQL .= "WHERE roleID & ?";
        
        //$SQL = "SELECT moduleID, courseID, moduleTitle, roleID, masteryScore, maxTimeAllowed, dataFromLMS, timeLimitAction, prerequisites FROM modules";
        $stmt = $this->m_SQL->prepare($SQL);
        $stmt->bind_param("ii", $userID, $roleID);
        $stmt->execute();
        
        $stmt->bind_result($cmiUserID, $cmiCourseID, $cmiModuleID, $cmiStartedDate, $cmiStatus, $cmiCompletedDate, $score, $moduleID, $moduleTitle, $roleID, $masteryScore, $maxTimeAllowed, $dataFromLMS, $timeLimitAction, $prerequisites, $courseID, $URL, $windowWidth, $windowHeight);
        
        while ($stmt->fetch()) {
            $moduleInfo = new stdClass();
            
            $moduleInfo->score = $score == "" ? "-" : $score;
            $moduleInfo->moduleID = $moduleID;
            $moduleInfo->courseID = $courseID;
            $moduleInfo->moduleTitle = $moduleTitle;
            $moduleInfo->roleID = $roleID;
            $moduleInfo->masteryScore = $masteryScore;
            $moduleInfo->maxTimeAllowed = $maxTimeAllowed;
            $moduleInfo->dataFromLMS = $dataFromLMS;
            $moduleInfo->timeLimitAction = $timeLimitAction;
            $moduleInfo->prerequisites = $prerequisites;
            $moduleInfo->cmiUserID = $cmiUserID;
            $moduleInfo->cmiCourseID = $cmiCourseID;
            $moduleInfo->cmiModuleID = $cmiModuleID;
            $moduleInfo->cmiStartedDate = isset($cmiStartedDate) ? $cmiStartedDate : "-";
            $moduleInfo->cmiStatus = isset($cmiStatus) ? $cmiStatus : "-";
            $moduleInfo->cmiCompletedDate = isset($cmiCompletedDate) ? $cmiCompletedDate : "-";
            $moduleInfo->URL = $URL;
            $moduleInfo->windowWidth = $windowWidth;
            $moduleInfo->windowHeight = $windowHeight;
            
            $this->modules[] = $moduleInfo;
        }
        $stmt->close();
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    function createModuleListInHTMLFormat() {
        $moduleHTML = "";
        
        $total = count($this->modules);
        
        foreach ($this->modules as $module) {
            $moduleHTML .= '<div class="'. ($total > 1 ? "tableRow" : "tableBottom").'">';
            
            if ($this->requireScoreColumnNONSanitize == 1) {
                $moduleHTML .= '<div class="h-1">'.htmlEntities($module->moduleTitle).'</div>';
                $moduleHTML .= '<div id="score-'.$module->moduleID.'" class="h-2">'.$module->score.'</div>';
            }
            else {
                $moduleHTML .= '<div class="h-1-noScore">'.htmlEntities($module->moduleTitle).'</div>';
            }
            $moduleHTML .= '<div id="status-'.$module->moduleID.'" class="h-3">'.$module->cmiStatus.'</div>';
            $moduleHTML .= '<div id="start-date-'.$module->moduleID.'"  class="h-4">'.$module->cmiStartedDate.'</div>';
            $moduleHTML .= '<div id="completed-date-'.$module->moduleID.'"  class="h-5">'.$module->cmiCompletedDate.'</div>';
            $moduleHTML .= '<div class="h-6"><div id="moduleID'.$module->moduleID.'">Launch</div></div>';
            $moduleHTML .= '</div>';
            $total--;
        }
        return $moduleHTML;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    function createModuleListMobileViewInHTMLFormat() {
        $moduleHTML = "";
        $total = count($this->modules);
        
        foreach ($this->modules as $module) {
            $moduleHTML .= '<div class="tab-1">'.htmlEntities($module->moduleTitle).'</div>';
                if ($this->requireScoreColumnNONSanitize == 1) {
                    $moduleHTML .= '<div class="tab-split"><div>Score</div><div id="MVscore-'.$module->moduleID.'" class="h-2">'.$module->score.'</div></div>';
                }
                $moduleHTML .= '<div class="tab-split"><div>Status</div><div id="MVstatus-'.$module->moduleID.'" class="h-2">'.$module->cmiStatus.'</div></div>';
            
            $moduleHTML .= '<div class="tab-split"><div>Start date</div><div id="MVstart-date-'.$module->moduleID.'" class="h-2">'.$module->cmiStartedDate.'</div></div>';
            
            $moduleHTML .= '<div class="tab-split"><div>Completed date</div><div id="MVcompleted-date-'.$module->moduleID.'" class="h-2">'.$module->cmiCompletedDate.'</div></div>';
            $moduleHTML .= '<div class="tab-1" style="border-top:none"><div class="h-6" id="MVmoduleID'.$module->moduleID.'">Launch</div></div>';
            
            $total--;
            if ($total != 0) {
                $moduleHTML .= '<br>';
            }
        }
        
        return $moduleHTML;
    }
};
?>