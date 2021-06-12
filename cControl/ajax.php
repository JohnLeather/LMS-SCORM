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
class cControl_ajax extends cLib_cCSRFTokens {
    public function __construct($control) {
        $this->m_control = $control;
        $this->m_SQL = $control->getDB()->sql;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    public function view() {
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
	protected function model() {
	}
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function EXTERNAL_resetScormCmidata($parameters) {
        $this->userRecord = $this->m_control->m_sessions->getUserRecord();
        
        $moduleID = intval($parameters[0], 10);
        
        $this->getCourse($moduleID);
        
        if (count($this->modules) != 1) {
            return;
        }

        for ($i = 0; $i < count($this->modules); $i++) {
            $moduleInfo = $this->modules[$i];

            $SQL = "DELETE FROM CMIResults WHERE userID = ? AND moduleID = ? AND courseID = ?";
            $stmt = $this->m_SQL->prepare($SQL);

            $stmt->bind_param('iii', $this->userRecord->getUserID(), $moduleInfo->moduleID, $moduleInfo->courseID);
            $stmt->execute();
            $stmt->close();
        }
    }
    // --------------------------------------------------------------------------------------------
    // Fetch the CMIData for scorm
    // --------------------------------------------------------------------------------------------    //
    public function EXTERNAL_scormCmidata($parameters) {
        $this->userRecord = $this->m_control->m_sessions->getUserRecord();
        
        $moduleID = intval($parameters[0], 10);
        $userID = $this->userRecord->getUserID();
        
        // Logged out...
        if ($userID == null) {
            return;
        }

        $this->getCourse($moduleID);
        
        if (count($this->modules) != 1) {
            return;
        }
        
        $courseInfo = new stdClass;
        $courseInfo->masteryScore = $this->modules[0]->masteryScore;
        $courseInfo->maxTimeAllowed = $this->modules[0]->maxTimeAllowed;
        $courseInfo->timeLimitAction = $this->modules[0]->timeLimitAction;
        $courseInfo->launchData = $this->modules[0]->dataFromLMS;
        $courseInfo->studentName = $this->userRecord->getFullName();
        $courseInfo->studentID = $this->userRecord->getUserID();
        //
        //
        //
        $CMIData = new cLib_cSCORM12CMIData();
        
        $firstTime = $this->modules[0]->cmiUserID == NULL;
        
        //
        // Create SCORM CMI Data object or give it existing one...
        //
        if ($firstTime) {
            $CMIData->createCMIData();
        }
        else {
            $CMIDataBlob = $this->fetchBlob();
            $CMIData->setCMIData($CMIDataBlob);
        }
        //
        // Populate the fields with manifest parameters and
        //
        $CMIData->populateCMIDataWithManifestData($courseInfo);
        
        //
        // AJAX it...
        //
        echo $CMIData->getCMIData();
    }
    // --------------------------------------------------------------------------------------------
    // Save SCORM CMI Data
    // --------------------------------------------------------------------------------------------
    public function EXTERNAL_saveScormCmidata($parameters) {
        $this->userRecord = $this->m_control->m_sessions->getUserRecord();
        
        $moduleID = intval($parameters[0], 10);
        
        $this->getCourse($moduleID);
        
        if (count($this->modules) != 1) {
            return;
        }
        
        $CMIBlob = $_POST['cmiData'];
        $CMIData = new cLib_cSCORM12CMIData();
        $CMIData->setCMIData($CMIBlob);
        
        $userID = $this->userRecord->getUserID();
        $courseID = $this->modules[0]->courseID;
        $moduleID = $this->modules[0]->moduleID;
        $newStatus = $CMIData->cmi->core->lesson_status->value;
        $currentStatus = $this->modules[0]->cmiStatus;
        $status = ($currentStatus != "completed" && $currentStatus != "passed") || ($newStatus == "passed" || $newStatus == "completed") ? $newStatus : $currentStatus;
        $now = date("Y-m-d H:i:s");
        $nowFormatted = date('d/m/Y',strtotime($now));
        $startedDate = $this->modules[0]->cmiStartedDate == "-" ? $nowFormatted : $this->modules[0]->cmiStartedDate;
        $previousCompletedDate = $this->modules[0]->cmiCompletedDate;
        $completedDate = (($status == "completed" || $status == "passed") &&  $previousCompletedDate == "-") ? $nowFormatted : $previousCompletedDate;
        $score = $CMIData->cmi->core->score->raw->value;
        if ($score != "" && $this->modules[0]->score > $score) {
            $score  = $this->modules[0]->score;
        }
        
        $firstTime = $this->modules[0]->cmiUserID == NULL;
        
        if ($firstTime) {
            $SQL = "INSERT INTO CMIResults (userID, courseID, moduleID, SCORMCMIData, startedDate, status, completedDate, score, scoreRaw) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
            $stmt = $this->m_SQL->prepare($SQL);
            $stmt->bind_param("iiisssssd", $userID, $courseID, $moduleID, $CMIBlob, $startedDate, $status, $completedDate, $score, $score);
            $stmt->execute();
            $stmt->close();
        }
        else {
            $SQL = "UPDATE CMIResults SET SCORMCMIData = ?, startedDate = ?, status = ?, completedDate = ?, score = ?, scoreRaw = ? WHERE userID = ? AND courseID = ? AND moduleID = ?";
            $stmt = $this->m_SQL->prepare($SQL);
            $stmt->bind_param("sssssiiid", $CMIBlob, $startedDate, $status, $completedDate, $score, $score, $userID, $courseID, $moduleID);
            $stmt->execute();
            $stmt->close();
        }
        //
        // If we have completed this course, then check to see if we have completed them all...
        //
        $roleID = $this->userRecord->getRoleID();
        
        $SQL = "SELECT count(*) FROM CMIResults WHERE userID = ? AND moduleID IN (SELECT moduleID FROM modules WHERE roleID & ?) AND NOT (completedDate = '-')";
        $stmt = $this->m_SQL->prepare($SQL);
        $stmt->bind_param("ii", $userID, $roleID);
        $stmt->execute();
        $stmt->bind_result($countCompleted);
        $stmt->fetch();
        $stmt->close();
        
        $SQL = "SELECT count(*) FROM modules WHERE roleID & ?";
        $stmt = $this->m_SQL->prepare($SQL);
        $stmt->bind_param("i", $roleID);
        $stmt->execute();
        $stmt->bind_result($countModules);
        $stmt->fetch();
        $stmt->close();
        
        //
        // All completed, register it...
        //
        if ($countCompleted == $countModules) {
            $this->m_control->m_sessions->completed();
        }

        //
        // Write back the results of current module LMS outcome
        //
        $updatedResults = new stdClass();
        
        $allCompletedDate = "";
        $allCompletedTime = "";
        if ($this->userRecord->getDateCompleted() != null) {
            $completedTimeStamp = $this->userRecord->getDateCompleted();
            $allCompletedDate = date('l jS F Y', strtotime($completedTimeStamp));
            $allCompletedTime = date('g:i a', strtotime($completedTimeStamp));
        }
        
        $updatedResults->m_allCompleted = $allCompletedDate."&nbsp;-&nbsp;".$allCompletedTime;
        $updatedResults->m_moduleScore = $score == "" ? "-" : $score;
        $updatedResults->m_moduleCompletedDate = $completedDate;
        $updatedResults->m_moduleStartedDate = $startedDate;
        $updatedResults->m_moduleStatus = $status;
        
        echo json_encode($updatedResults);
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    function getCourse($moduleID) {
        $this->modules = [];
        
        $userID = $this->userRecord->getUserID();
        $roleID = $this->userRecord->getRoleID();
        
        $SQL = "SELECT r.userID, r.courseID, r.moduleID, r.startedDate, r.status, r.completedDate, r.score, ";
        $SQL .= "m.moduleID, m.moduleTitle, m.roleID, m.masteryScore, m.maxTimeAllowed, m.dataFromLMS, m.timeLimitAction, m.prerequisites, m.courseID ";
        $SQL .= "FROM modules m ";
        $SQL .= "LEFT JOIN CMIResults r ON r.moduleID = m.moduleID AND r.userID = ? WHERE roleID & ? AND m.moduleID = ?";
        
        $stmt = $this->m_SQL->prepare($SQL);
        $stmt->bind_param("iii", $userID, $roleID, $moduleID);
        $stmt->execute();
        
        $stmt->bind_result($cmiUserID, $cmiCourseID, $cmiModuleID, $cmiStartedDate, $cmiStatus, $cmiCompletedDate, $score, $moduleID, $moduleTitle, $roleID, $masteryScore, $maxTimeAllowed, $dataFromLMS, $timeLimitAction, $prerequisites, $courseID);
        
        while ($stmt->fetch()) {
            $moduleInfo = new stdClass();
            
            $moduleInfo->score = $score;
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
            
            $this->modules[] = $moduleInfo;
        }
        $stmt->close();
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    function fetchBlob() {
        $userID = $this->userRecord->getUserID();
        $moduleID = $this->modules[0]->moduleID;
        
        $SQL = "SELECT SCORMCMIData FROM CMIResults WHERE userID = ? AND moduleID = ?";
        $stmt = $this->m_SQL->prepare($SQL);
        $stmt->bind_param("ii", $userID, $moduleID); //$courseID,
        $stmt->execute();
        $stmt->bind_result($CMIDataBlob);
        $stmt->fetch();
        
        return $CMIDataBlob;
    }
}