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
//
// Flags indicating the node access type
//
// at least one place, there is a missing _children that is not in the scorm specification and so rather than bodging a workaroud,
// it uses UNDOCUMENTED and any attempted access would treat it as
//
define('INVALID', 1 << 0);
define('READ', 1 << 1);
define('WRITE', 1 << 2);
define('UNDOCUMENTED', 1 << 3);

//
// Must never change values of VOCABULARIES due to the fact this data is stored in the database
//
define('VOCABULARY_UNDEFINED', 0);
define('VOCABULARY_MODE', 1);
define('VOCABULARY_STATUS', 2);
define('VOCABULARY_EXIT', 3);
define('VOCABULARY_CREDIT', 4);
define('VOCABULARY_ENTRY', 5);
define('VOCABULARY_INTERACTION', 6);
define('VOCABULARY_RESULT', 7);
define('VOCABULARY_TIME_LIMIT_ACTION', 8);

//
// Must never change values of DATATYPE due to the fact this data is stored in the database
//
define('CMIBlank', 1 << 0);
define('CMIBoolean', 1 << 1);
define('CMIDecimal', 1 << 2);
define('CMIFeedback', 1 << 3);
define('CMIIdentifier', 1 << 4);
define('CMIInteger', 1 << 5);
define('CMISInteger', 1 << 6);
define('CMIString255', 1 << 7);
define('CMIString4096', 1 << 8);
define('CMITime', 1 << 9);
define('CMITimeSpan', 1 << 10);
define('CMIVocabulary', 1 << 11);
define('CMIDecimal0To100', 1 << 12);
    
class cLib_cSCORM12CMIData {
    public function __construct() {
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function setCMIData($CMIDataInJSONFormat) {
        $this->cmi = json_decode($CMIDataInJSONFormat);
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function getCMIData() {
        return json_encode($this->cmi);
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function createCMIData() {
        $this->cmi = new stdClass();
        $this->cmi->core = new stdClass();
        $this->cmi->core->score = new stdClass();
        $this->cmi->objectives = new stdClass();
        $this->cmi->student_data = new stdClass();
        $this->cmi->student_preference = new stdClass();
        $this->cmi->interactions = new stdClass();
        
        $this->cmi->access = INVALID;
        $this->cmi->core->access = INVALID;
        $this->cmi->core->score->access = INVALID;
        $this->cmi->objectives->access = INVALID;
        $this->cmi->student_data->access = INVALID;
        $this->cmi->student_preference->access = INVALID;
        $this->cmi->interactions->access = INVALID;
        
        $this->populateCMI();
        $this->populateCMICore();
        $this->populateCMICoreScore();
        $this->populateCMIObjectives();
        $this->populateCMIStudentData();
        $this->populateCMIStudentPreference();
        $this->populateCMIInteractions();
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    private function readWrite($value, $dataType, $vocabulary = VOCABULARY_UNDEFINED) {
        return $this->createAccessObject($value, READ | WRITE, $dataType, $vocabulary);
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    private function writeOnly($value, $dataType, $vocabulary = VOCABULARY_UNDEFINED) {
        return $this->createAccessObject($value, WRITE, $dataType, $vocabulary);
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    private function readOnly($value, $dataType, $vocabulary = VOCABULARY_UNDEFINED) {
        return $this->createAccessObject($value, READ, $dataType, $vocabulary);
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    private function undocumented($value) {
        return $this->createAccessObject($value, UNDOCUMENTED, NULL, NULL);
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    private function createAccessObject($value, $accessMask, $dataType, $vocabulary) {
        $obj = new stdClass();

        $obj->value = $value;
        $obj->access = $accessMask;
        
        if (($accessMask & (WRITE | READ)) != 0) {
            if (isset($dataType)) {
                $obj->dataType = $dataType;
            
                if ($dataType == CMIVocabulary) {
                    if (isset($vocabulary) && $vocabulary != VOCABULARY_UNDEFINED) {
                        $obj->vocabulary = $vocabulary;
                    }
                    else {
                        throw new Exception("Datatype == CMIVocabulary but $$vocabulary not specified");
                    }
                }
            }
            else {
                throw new Exception("Expected a data type");
            }
        }
        
        return $obj;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    private function populateCMI() {
        $this->cmi->_children = $this->readOnly("_version,access,core,objectives,student_data,student_preference,interactions,suspend_data,launch_data,comments,comments_from_lms", CMIString255);
        
        $this->cmi->suspend_data = $this->readWrite("", CMIString4096);
        $this->cmi->launch_data = $this->readOnly("", CMIString4096);
        $this->cmi->comments = $this->readWrite("", CMIString4096);
        $this->cmi->comments_from_lms = $this->readOnly("", CMIString4096);
        $this->cmi->_version = $this->readOnly(3.4, CMIDecimal);
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    private function populateCMICore() {
        $this->cmi->core->_children = $this->readOnly("student_id,student_name,lesson_location,credit,lesson_status,entry,score,total_time,lesson_mode,exit,session_time", CMIString255);
        $this->cmi->core->student_id = $this->readOnly(0, CMIIdentifier);
        $this->cmi->core->student_name = $this->readOnly("", CMIString255);
        $this->cmi->core->lesson_location = $this->readWrite("", CMIString255);
        $this->cmi->core->credit = $this->readOnly("credit", CMIVocabulary, VOCABULARY_CREDIT);
        $this->cmi->core->lesson_status = $this->readWrite("not attempted", CMIVocabulary, VOCABULARY_STATUS);
        $this->cmi->core->entry = $this->readOnly("ab-initio", CMIVocabulary, VOCABULARY_ENTRY);
        $this->cmi->core->total_time = $this->readOnly("0000:00:00.00", CMITimeSpan);
        $this->cmi->core->lesson_mode = $this->readOnly("normal", CMIVocabulary, VOCABULARY_MODE);
        $this->cmi->core->exit = $this->writeOnly("", CMIVocabulary, VOCABULARY_EXIT);
        $this->cmi->core->session_time = $this->writeOnly("0000:00:00.00", CMITimeSpan);
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    private function populateCMICoreScore() {
        $this->cmi->core->score->_children = $this->readOnly("raw,min,max", CMIString255);
        $this->cmi->core->score->raw = $this->readWrite("", CMIDecimal0To100 | CMIBlank);
        $this->cmi->core->score->max = $this->readWrite("", CMIDecimal0To100 | CMIBlank);
        $this->cmi->core->score->min = $this->readWrite("", CMIDecimal0To100 | CMIBlank);
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    private function populateCMIObjectives() {
        $this->cmi->objectives->_children = $this->readOnly("id,score,status", CMIString255);
        $this->cmi->objectives->_count = $this->readOnly(0, CMIInteger);

        //
        // The rest of this data structure is created dynamically on the client side as and when needed
        //
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    private function populateCMIStudentData() {
        $this->cmi->student_data->_children = $this->readOnly("mastery_score,max_time_allowed,time_limit_action", CMIString255);

        $this->cmi->student_data->mastery_score = $this->readOnly("", CMIDecimal0To100);
        $this->cmi->student_data->max_time_allowed = $this->readOnly("0000:00:00.00", CMITimeSpan);
        $this->cmi->student_data->time_limit_action = $this->readOnly("", CMIVocabulary, VOCABULARY_TIME_LIMIT_ACTION);
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    private function populateCMIStudentPreference() {
        $this->cmi->student_preference->_children = $this->readOnly("audio,language,speed,text", CMIString255);
        $this->cmi->student_preference->audio = $this->readWrite(0, CMISInteger);
        $this->cmi->student_preference->language = $this->readWrite("", CMIString255);
        $this->cmi->student_preference->speed = $this->readWrite(0, CMISInteger);
        $this->cmi->student_preference->text = $this->readWrite(0, CMISInteger);
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    private function populateCMIInteractions() {
        $this->cmi->interactions->_children = $this->readOnly("id,objectives,time,type,correct_responses,weighting,student_response,result,latency", CMIString255);
        $this->cmi->interactions->_count = $this->readOnly(0, CMIInteger);

        //
        // The rest of this data structure is created dynamically on the client side as and when needed
        //
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function populateCMIDataWithManifestData($courseInfo) {
        $this->cmi->student_data->mastery_score->value = isset($courseInfo->masteryScore) ? $courseInfo->masteryScore : "";
        $this->cmi->student_data->max_time_allowed->value = isset($courseInfo->maxTimeAllowed) ? $courseInfo->maxTimeAllowed : "0000:00:00.00";
        $this->cmi->student_data->time_limit_action->value = isset($courseInfo->timeLimitAction) ? $courseInfo->timeLimitAction : "";
        $this->cmi->launch_data->value = isset($courseInfo->launchData) ? $courseInfo->launchData : "";
        $this->cmi->core->student_name->value = isset($courseInfo->studentName) ? $courseInfo->studentName : "Error";
        $this->cmi->core->student_id->value = isset($courseInfo->studentID) ? $courseInfo->studentID : 0;
    }
}