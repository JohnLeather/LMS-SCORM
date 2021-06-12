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
'use strict';

if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (vMember, nStartFrom) {
        if (this == null) {
            throw new TypeError("Array.prototype.indexOf() - can't convert `" + this + "` to object");
        }
        
        var nIdx    = isFinite(nStartFrom) ? Math.floor(nStartFrom) : 0;
        var oThis   = this instanceof Object ? this : new Object(this);
        var nLen    = isFinite(oThis.length) ? Math.floor(oThis.length) : 0;
        
        if (nIdx >= nLen) {
            return -1;
        }
        
        if (nIdx < 0) {
            nIdx = Math.max(nLen + nIdx, 0);
        }
        
        if (vMember === undefined) {
            do {
                if (nIdx in oThis && oThis[nIdx] === undefined) {
                    return nIdx;
                }
            } while (++nIdx < nLen);
        }
        else {
            do {
                if (oThis[nIdx] === vMember) {
                    return nIdx;
                }
            } while (++nIdx < nLen);
        }
        
        return -1;
    };
}

var API = null;
var zIndex = 10000;

function SCORM12RTE() {this._constructor();}



SCORM12RTE.prototype = {
    SCORM_VERSION                               : 1.2,
    
    //
    // This JSON data below was produced by calling a class in cSCORM12DataObject.php by executing $CMIData = new cSCORM12DataObject(); $JSONCMIData = $CMIData->asJSON();
    //
    
    CMIJSONData                                 : '',
    
    //
    // CMI Data Property access flag
    //
    
    INVALID                                     : 1 << 0,
    READ_MASK                                   : 1 << 1,
    WRITE_MASK                                  : 1 << 2,
    UNDOCUMENTED                                : 1 << 3,
    
    //
    // Default values for CMITime and CMITimeSpan
    //
    
    CMITimeDefault                              : "00:00:00.00",
    CMITimeSpanDefault                          : "0000:00:00.00",
    
    //
    // SCORM 1.2 Error codes
    //
    _errorNoError                               : '0',
    _errorGeneralException                      : '101',
    _errorInvalidArgument                       : '201',
    _errorElementCannotHaveChildren             : '202',
    _errorElementNotAnArrayCannotHaveChildren   : '203',
    _errorAPINotInitialized                     : '301',
    _errorDataModelElementNotImplemented        : '401',
    _errorInvalidSetValueElementIsAKeyword      : '402',
    _errorInvalidSetValueElementIsReadOnly      : '403',
    _errorInvalidGetValueElementIsWriteOnly     : '404',
    _errorInvalidSetValueIncorrectDataType      : '405',
    
    //
    // CUSTOM Error codes not part of SCORM standards
    //
    _errorScormSessionFinished                  : '1000', // Attempted to call any API.LMSxxxxx() function after calling API.LMSFinish();
    _errorAlreadyInitialised                    : '1001', // Attempted to call the API.LMSInitialize() more than once
    _errorInvalidSetValueElementIsANode         : '1002', // Attempted to do API.LMSSetValue("cmi.core", "Oops") which is a node containing element(s) rather than a specific element.
    _errorInvalidLMSInitializeParameter1        : '1003', // LMSInitialise can only have "" as first parameter
    _errorLMSInitializeParameter1Missing        : '1004', // LMSInitialise must only have ""
    _errorLMSInitializeTooManyParameters        : '1005', // LMSInitialise recieved more than 1 parameters
    _errorLMSShutDownDueToExcessiveLMSInitialize: '1006', // LMSInitialise called more than once
    _errorInvalidLMSGetValueParameter1          : '1007', //
    _errorLMSGetValueParameter1Missing          : '1008', //
    _errorLMSGetValueTooManyParameters          : '1009', //
    _errorInvalidLMSSetValueParameter1          : '1010', //
    _errorLMSSetValueTooManyParameters          : '1011', //
    _errorLMSSetValueTooFewParameters           : '1012', //
    
    //
    // Vocab IDs
    //
    VOCABULARY_UNDEFINED                        : 0,
    VOCABULARY_MODE                             : 1,
    VOCABULARY_STATUS                           : 2,
    VOCABULARY_EXIT                             : 3,
    VOCABULARY_CREDIT                           : 4,
    VOCABULARY_ENTRY                            : 5,
    VOCABULARY_INTERACTION                      : 6,
    VOCABULARY_RESULT                           : 7,
    VOCABULARY_TIME_LIMIT_ACTION                : 8,
    
    //
    // Data types for LMSSetValue():
    //
    CMIBlank                                    : 1 << 0, // 1
    CMIBoolean                                  : 1 << 1, // 2
    CMIDecimal                                  : 1 << 2, // 4
    CMIFeedback                                 : 1 << 3, // 8
    CMIIdentifier                               : 1 << 4, // 16
    CMIInteger                                  : 1 << 5, // 32
    CMISInteger                                 : 1 << 6, // 64
    CMIString255                                : 1 << 7, // 128
    CMIString4096                               : 1 << 8, // 256
    CMITime                                     : 1 << 9, // 512
    CMITimeSpan                                 : 1 << 10, // 1024
    CMIVocabulary                               : 1 << 11, // 2048
    //
    // These are custom types...
    //
    CMIDecimal0To100                            : 1 << 12, //4096
    CMIFeedbackResponseTypeUnknown              : 1 << 13, //8192
    CMIFeedbackResponseTypeTrueFalse            : 1 << 14, //16384
    CMIFeedbackResponseTypeChoice               : 1 << 15,
    CMIFeedbackResponseTypeFillIn               : 1 << 16,
    CMIFeedbackResponseTypeMatching             : 1 << 17,
    CMIFeedbackResponseTypePerformance          : 1 << 18,
    CMIFeedbackResponseTypeLikert               : 1 << 19,
    CMIFeedbackResponseTypeSequencing           : 1 << 20,
    CMIFeedbackResponseTypeNumeric              : 1 << 21,
    
    //
    // Last error and it's message
    //
    errorCode                                   : 0,
    
    //
    // Status of the SCORM engine
    //
    APIinitialised                              : false,
    APIinitialisedTooManyTimes                  : false,
    LMSFinished                                 : false,
    
    restartSCORM : function() {
        this.APIinitialised = false;
        this.APIinitialisedTooManyTimes = false;
        this.LMSFinished = false;
        this.clearErrors();
        this.errorCode= 0;
    },
    
    _constructor : function() {
        this.initialiseError();
        this.clearErrors();
    },
    //
    // Special initialization rules
    //
    initCMIDataValues : function() {
        //
        // Initialise cmi data
        //
        
        
        //
        // page 3-24, cmi.core.lesson_status
        //
        // On re-entry into the SCO, the LMS may change the status to either passed, failed or browsed
        // #1 'passed' or 'failed' based on criteria defined in the manifest for mastery of the SCO
        // #2 'browsed', if the SCO was launched on its initial attempt with a lesson_mode of "browse"
        //
        
        //
        // #1...
        //
        if (this.cmi.core.credit == "credit") {
            // @toDo manifest issue of the above
            // This is not yet implemented and it meant to get the credit info from the manifest file
        }
        //
        // #2...
        //
        if (this.cmi.core.lesson_mode.value == "browse") {
            this.cmi.lesson_status.value = "browsed";
        }
        
        //
        // Page 3-32. cmi.core.exit
        //
        // If the SCO set the cmi.core.exit to 'suspend' the LMS should set the cmi.core.entry to resmume on next launching of the SCO
        //
        this.cmi.core.entry.value = (this.cmi.core.exit.value == 'suspend') ? 'resume' : '';
        this.cmi.core.exit.value = "";

        //
        // Page 3-32 cmi.core.exit
        //
        // The SCO should set cmi.core.exit entry to "" (empty) upon the next lanuching of the SCO.
        //
        this.cmi.core.entry.value = "";
        
        //
        // Page 3-30
        // The LMS should initialize cmi.core.session_time to "0000:00:00.00" upon initial launch
        // The SCO is then allowed to change this value during the session
        //
        this.cmi.core.session_time.value = this.CMITimeSpanDefault;
        
    },
    //
    // Special exit rules
    //
    cleanUpCMIDataValues : function() {
        //
        // The LMS should add the cmi.core.session_time to cmi.core.total_time.value upon exit
        // Page 3-30
        //
        var totalTime = this.convertCMITimeSpanToTimeObject(this.cmi.core.total_time.value);
        var sessionTime = this.convertCMITimeSpanToTimeObject(this.cmi.core.session_time.value);
        var newTotalTime = this.addTimeObjectTogether(totalTime, sessionTime);
        this.cmi.core.total_time.value = this.convertTimeObjectToCMITimeSpan(newTotalTime);
    },
    
    // ---------------------------------------------------------------------------
    // Initialise the SCORM API
    // ---------------------------------------------------------------------------
    LMSInitialize : function(param1, param2) {
        var returnValue = 0;
        
        if (arguments.length == 0) {
            returnValue = this.executeLMSInitialize();
        }
        else if (arguments.length == 1) {
            returnValue = this.executeLMSInitialize(param1);
        }
        else {
            returnValue = this.executeLMSInitialize(param1, param2);
        }
        
        if (SCORMDiagnostic) {
            
            var SCORMDiagnosticReference = SCORMDiagnostic;
            SCORMDiagnostic = null;
            API.LMSGetValue("cmi.interactions.0.objectives.0.id");
            API.LMSGetValue("cmi.objectives.0.id");
            SCORMDiagnostic = SCORMDiagnosticReference;
            
            SCORMDiagnostic.createTreeView();
            SCORMDiagnostic.log(SCORMDiagnostic._LOG_LMSINITIALISE, returnValue, param1, param2, null, arguments.length);
        }
        
        
        
        return returnValue;
    },
    
    // ---------------------------------------------------------------------------
    // Wrapper - Initialise the SCORM API
    // ---------------------------------------------------------------------------
    executeLMSInitialize : function(param1, param2) {
        this.clearErrors();
        
        if (arguments.length == 1 && (typeof param1 != "string" || param1 != "")) {
            this.reportError(this._errorInvalidLMSInitializeParameter1);
            return false;
        }
        
        if (arguments.length > 1) {
            this.reportError(this._errorLMSInitializeTooManyParameters);
            return false;
        }
        
        if (arguments.length == 0) {
            this.reportError(this._errorLMSInitializeParameter1Missing);
            return false;
        }
        
        if (this.LMSFinished) {
            this.reportError(this._errorScormSessionFinished);
            return false;
        }
        
        if (this.APIinitialised) {
            this.APIinitialisedTooManyTimes = true;
            this.reportError(this._errorAlreadyInitialised);
            return false;
        }
        
        if (this.CMIJSONData != null) {
            this.cmi = JSON.parse(this.CMIJSONData);
            this.CMIJSONData = null;
        }
        
        this.initCMIDataValues();
        
        this.APIinitialised = true;
        
        return true;
    },
    
    // ---------------------------------------------------------------------------
    // Finished the LMS session
    // ---------------------------------------------------------------------------
    LMSFinish : function(finishedParameter) {
        if (!this.APIOK()) {
            return false;
        }
        
        this.cleanUpCMIDataValues();
        
        var SCORMDiagnosticReference = null;
        if (SCORMDiagnostic) {
            SCORMDiagnosticReference = SCORMDiagnostic;
            SCORMDiagnostic = null;
        }
        
        this.LMSCommit("");
        
        SCORMDiagnostic = SCORMDiagnosticReference;
        
        this.LMSFinished = true;
        
        var returnValue = true;
        
        if (SCORMDiagnostic) {
            SCORMDiagnostic.createTreeView(this.cmi, "");
            SCORMDiagnostic.log(SCORMDiagnostic._LOG_LMSFINISH, returnValue, finishedParameter, null, null, arguments.length);
        }
        
        return returnValue;
    },
    // ---------------------------------------------------------------------------
    // Get the value from the CMI data model
    // ---------------------------------------------------------------------------
    LMSGetValue : function(cmiParameter, param2) {
        var returnValue;
        
        if (arguments.length == 0) {
            returnValue = this.executeLMSGetValue();
        }
        else if (arguments.length == 1) {
            returnValue = this.executeLMSGetValue(cmiParameter);
        }
        else {
            returnValue = this.executeLMSGetValue(cmiParameter, param2);
        }
        
        if (SCORMDiagnostic) {
            SCORMDiagnostic.log(SCORMDiagnostic._LOG_LMSGETVALUE, returnValue, cmiParameter, param2, null, arguments.length);
        }
        
        return returnValue;
    },
    
    
    // ---------------------------------------------------------------------------
    // Get the value from the CMI data model
    // ---------------------------------------------------------------------------
    executeLMSGetValue : function(cmiParameter, parameter2) {
        if (arguments.length == 1 && (typeof cmiParameter != "string")) {
            this.reportError(this._errorInvalidLMSGetValueParameter1);
            return "";
        }
        
        if (arguments.length > 1) {
            this.reportError(this._errorLMSGetValueTooManyParameters);
            return "";
        }
        
        if (arguments.length == 0) {
            this.reportError(this._errorLMSGetValueParameter1Missing);
            return "";
        }
        
        if (!this.APIOK()) {
            return "";
        }
        
        var readMode = true;
        var property = this.fetchCMIProperty(cmiParameter, readMode);
        
        if (property == null) {
            return "";
        }
        
        
        return property.value;
    },
    
    // ---------------------------------------------------------------------------
    // Set the value of the CMI data model
    // ---------------------------------------------------------------------------
    LMSSetValue : function(cmiParameter, value, param3) {
        
        var returnValue;
        
        if (arguments.length == 0) {
            returnValue = this.executeLMSSetValue();
        }
        else if (arguments.length == 1) {
            returnValue = this.executeLMSSetValue(cmiParameter);
        }
        else if (arguments.length == 2) {
            returnValue = this.executeLMSSetValue(cmiParameter, value);
        }
        else {
            returnValue = this.executeLMSSetValue(cmiParameter, value, param3);
        }
        
        if (SCORMDiagnostic) {
            SCORMDiagnostic.log(SCORMDiagnostic._LOG_LMSSETVALUE, returnValue, cmiParameter, value, param3, arguments.length);
        }

        //
        // Page 3-32 cmi.core.exit
        //
        // If the SCO set the cmi.core.exit to logout then the LMS should log the student out of the course
        // when the SCO that set the cmi.core.exit to "logout" has issued the LMSFinish() or the user navigates away.
        // 
        if (arguments.length == 2 && cmiParameter == "cmi.core.exit" && value == "logout") {
            //
            // When you close the course window the courseware will do a series of calls to SCORM in order to save whatever data that require saving.
            // That behavour is however down to the courseware to implement by adding a listener for when the courseware popup is closed. 
            // Ideally, before you call API.LMSSetValue("cmi.core.exit", "logout"); you should set all the relevent values to SCORM CMI Data such as the 
            // bookmark data and then call API.LMSCommit(); to write that data.
            //
            // Closing the course window, should invoke LMSFinish() by the courseware, if they implement this correctly.
            //
            this.closeCourseWindow(); 

            //
            // as a fail safe... We give the courseware 1 second to issue a LMSFinish(); only if LMSFinish() hasn't been called since the course window popup was closed.
            // 
            setTimeout(this.autoIssueLMSFinish.bind(this), 1000);

            return true;
        }
        
        
        return returnValue;
    },
    // ---------------------------------------------------------------------------
    // 
    // ---------------------------------------------------------------------------
    autoIssueLMSFinish : function() {
        if (!this.LMSFinished) {
            this.LMSFinish();
        }
    },
    // ---------------------------------------------------------------------------
    // wrapper to LMSSetValue() - Set the value of the CMI data model
    // ---------------------------------------------------------------------------
    executeLMSSetValue : function(cmiParameter, value, param3) {
        if (arguments.length == 2 && (typeof cmiParameter != "string")) {
            this.reportError(this._errorInvalidLMSSetValueParameter1);
            return "";
        }
        if (arguments.length > 2) {
            this.reportError(this._errorLMSSetValueTooManyParameters);
            return "";
        }
        
        if (arguments.length < 2) {
            this.reportError(this._errorLMSSetValueTooFewParameters);
            return "";
        }
        
        if (!this.APIOK()) {
            return false;
        }
        
        var readMode = true;
        
        var property = this.fetchCMIProperty(cmiParameter, !readMode);
        if (property == null) {
            return false;
        }
        
        if (!this.validateDataType(property.dataType, value, property)) {
            this.reportError(this._errorInvalidSetValueIncorrectDataType);
            return false;
        }
        
        this.expandDynamicNodes(property, cmiParameter, value);
        
        property.value = value;
        
        if (SCORMDiagnostic) {
            SCORMDiagnostic.createTreeView(this.cmi, "");
        }
        
        return true;
    },
    // ---------------------------------------------------------------------------
    // Once you write the data in any of the dynamic fields that the array index is same as the _count field
    // then we increase the counter and become "Live"
    // ---------------------------------------------------------------------------
    expandDynamicNodes : function(property, cmiParameter, value) {
        var params  = cmiParameter.split(".");
        var p       = params.length;
        
        if (p >= 1 && params[1] == "objectives") {
            if (this.cmi.objectives._count.value == params[2]) {
                this.cmi.objectives._count.value++;
            }
        }
        
        if (p >= 1 && params[1] == "interactions") {
            if (this.cmi.interactions._count.value == params[2]) {
                this.cmi.interactions._count.value++;
            }
            
            //
            // cmi.objectives.n.objective.n.id
            //
            if (p >= 6 && params[3] == "objectives" && params[5] == "id") {
                if (this.cmi.interactions.n[params[2]].objectives._count.value == params[4]) {
                    this.cmi.interactions.n[params[2]].objectives._count.value++;
                }
            }
            //
            // cmi.interactions.n.correct_responses.n.pattern
            //
            if (p >= 6 && params[3] == "correct_responses" && params[5] == "pattern") {
                if (this.cmi.interactions.n[params[2]].correct_responses._count.value == params[4]) {
                    this.cmi.interactions.n[params[2]].correct_responses._count.value++;
                }
            }
            //
            // cmi.interactions.n.type
            //
            if ((p == 4 && params[3] == "type")) {
                if (property.value != value) {
                    this.revalidatePatternsAndResponses(cmiParameter, value, params, p);
                }
            }
        }
    },
    
    // ---------------------------------------------------------------------------
    // Convert string feedback data type to data type ID
    // ---------------------------------------------------------------------------
    getCMIFeedbackDataTypeAsID : function(value) {
        switch (value) {
            case "true-false":
                return this.CMIFeedbackResponseTypeTrueFalse;
                
            case "choice":
                return this.CMIFeedbackResponseTypeChoice;
                
            case "fill-in":
                return this.CMIFeedbackResponseTypeFillIn;
                
            case "matching":
                return this.CMIFeedbackResponseTypeMatching;
                
            case "performance":
                return this.CMIFeedbackResponseTypePerformance;
                
            case "likert":
                return this.CMIFeedbackResponseTypeLikert;
                
            case "sequencing":
                return this.CMIFeedbackResponseTypeSequencing;
                
            case "numeric":
                return this.CMIFeedbackResponseTypeNumeric;
        }
        
        return this.CMIFeedbackResponseTypeUnknown;
    },
    // ---------------------------------------------------------------------------
    // If you change the interaction type to a different type of interaction then there is the potential problem in
    // that some of the cmi.interactions.n.correct_responses.n.pattern values are incorrect for the new interaction type
    // So, that means we have to iterate cmi.interactions.n.correct_responses.n.pattern, change their data type based
    // on cmi.interactions.n.type then revalidate cmi.interactions.n.correct_responses.n.pattern values and set them
    // to "" (Blank string) if they are found to be invalid
    //
    // Take for example:
    // you set cmi.interactions.n.type to "choice"
    // then you set cmi.interactions.n.correct_responses.n.pattern to "a,b,c"
    // when you change cmi.interactions.n.type to "true-false"
    // the cmi.interactions.n.correct_responses.n.pattern can now only contain the following legal values "0", "1", "t", "f" because it a "true-false" interaction. So "a,b,c" is no longer valid
    // which means all cmi.interactions.n.correct_responses.n.pattern will need to be cleared
    // ---------------------------------------------------------------------------
    revalidatePatternsAndResponses : function(cmiParamter, value, params, p) {
        
        var v = this.getCMIFeedbackDataTypeAsID(value);
        
        var interactions = this.cmi.interactions._count.value;
 
        for (var i = 0; i < interactions; i++) {
            var patterns = this.cmi.interactions.n[i].correct_responses._count.value;
            for (var p = 0; p < patterns; p++) {
                var pattern = this.cmi.interactions.n[i].correct_responses.n[p].pattern;
                if (pattern.dataType != v) {
                    pattern.dataType = v;
                    if (!this.validateDataType(pattern.dataType, pattern.value)) {
                        pattern.value = "";
                    }
                }
            }
            
            //
            // Oh, and we need to do same to student_response field
            //
            var studentResponse = this.cmi.interactions.n[i].student_response;
            if (studentResponse.dataType != v) {
                studentResponse.dataType = v;
                if (!this.validateDataType(studentResponse.dataType, studentResponse.value)) {
                    studentResponse.value = "";
                }
            }
        }
    },
    // ---------------------------------------------------------------------------
    // Commit the CMI data structure to the LMS
    // ---------------------------------------------------------------------------
    LMSCommit : function(commitParameter) {
        if (!this.APIOK()) {
            return false;
        }
    
        var CMIData = JSON.stringify(this.cmi);
        
        var parameters  = "cmiData=" + CMIData;
        
        API.AJAXSaveRequest.open("POST", '/ajax/save-scorm-cmidata/' + this.moduleID + '/' + this.userID + '/' + document.getElementById("CSRFTokens").value, true);
        API.AJAXSaveRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        API.AJAXSaveRequest.send(parameters)

        var returnValue = true;
        
        if (SCORMDiagnostic) {
            SCORMDiagnostic.log(SCORMDiagnostic._LOG_LMSCOMMIT, returnValue, commitParameter, null, null, arguments.length);
        }
        
        return returnValue;
    },    
    // ---------------------------------------------------------------------------
    // reset the SCORM API
    // ---------------------------------------------------------------------------
    reset : function(param) {
        this.errorCode                          = 0;
        this.APIinitialised                     = false;
        this.LMSFinished                        = false;
        this.APIinitialisedTooManyTimes         = false;
    },
    // ---------------------------------------------------------------------------
    // reset the SCORM API
    // ---------------------------------------------------------------------------
    APIOK : function() {
        if (this.LMSFinished) {
            this.reportError(this._errorScormSessionFinished);
            return false;
        }
        
        this.clearErrors();
        
        if (!this.APIinitialised) {
            this.reportError(this._errorAPINotInitialized);
            return false;
        }
        
        if (this.APIinitialisedTooManyTimes) {
            this.reportError(this._errorLMSShutDownDueToExcessiveLMSInitialize);
            return false;
        }
        
        return true;
    },
    // ---------------------------------------------------------------------------
    // Returns the last error code, if you haven't called
    // ---------------------------------------------------------------------------
    LMSGetLastError : function() {
        /*
         if (this.LMSFinished) {
         this.reportError(this._errorScormSessionFinished);
         }
         if (this.APIinitialisedTooManyTimes) {
         this.reportError(this._errorAlreadyInitialised);
         }*/
        
        return this.errorCode;
    },
    // ---------------------------------------------------------------------------
    // Returns the error string
    // ---------------------------------------------------------------------------
    LMSGetErrorString : function(errorCode) {
        if (this.errorMessages[errorCode] == undefined) {
            return "Unknown error code given - " + errorCode;
        }
        
        return this.errorMessages[errorCode];
    },
    // ---------------------------------------------------------------------------
    // This write to the DOM if you pass in a DOM id parameter
    // This is NOT SCORM Engine standard behaviour, it is far more intutive
    // Diagnostic than you would normally have
    // ---------------------------------------------------------------------------
    LMSGetDiagnostic : function(param) {
        this.clearErrors();
        if (!this.APIinitialised) {
            this.reportError()
            return "";
        }
        
        return "No additional details on error";
    },
    // ---------------------------------------------------------------------------
    // Common support routines for LMSXXXXXX() functions above
    // ---------------------------------------------------------------------------
    intiateWalk : function(cmiParameter, readMode) {
        var walkTheCMI = cmiParameter.split(".");
        
        if (walkTheCMI[0] != "cmi") {
            return null;
        }

        walkTheCMI.shift();
        var result = this.walkTheCMITree(this.cmi, walkTheCMI, 0, readMode);
        
        return result;
    },
    // ---------------------------------------------------------------------------
    // Fetch the property value if found or
    // report an errors if it doesn't succeed and return "" instead
    // ---------------------------------------------------------------------------
    fetchCMIProperty : function(cmiParameter, readMode) {
        var property = this.intiateWalk(cmiParameter, readMode);
        
        if (property == null) {
            this.reportError(this._errorDataModelElementNotImplemented);
            return null;
        }
        
        if (property.access == this.UNDOCUMENTED) {
            this.reportError(this._errorDataModelElementNotImplemented);
            return null;
        }
        
        if (property.access == this.INVALID) {
            this.reportError(this._errorInvalidSetValueElementIsANode);
            return null;
        }
        
        if (readMode) {
            if (!((property.access & this.READ_MASK) == this.READ_MASK)) {
                this.reportError(this._errorInvalidGetValueElementIsWriteOnly);
                return null;
            }
        }
        else {
            if (!((property.access & this.WRITE_MASK) == this.WRITE_MASK)) {
                this.reportError(this._errorInvalidSetValueElementIsReadOnly);
                return null;
            }
        }
        
        return property;
    },
    // ---------------------------------------------------------------------------
    // Read only property
    // ---------------------------------------------------------------------------
    readOnlyAccess : function(value, dataType, vocabulary) {
        return this.accessObject(value, this.READ_MASK, dataType, vocabulary);
    },
    // ---------------------------------------------------------------------------
    // Write only property
    // ---------------------------------------------------------------------------
    writeOnlyAccess : function(value, dataType, vocabulary) {
        return this.accessObject(value, this.WRITE_MASK, dataType, vocabulary);
    },
    // ---------------------------------------------------------------------------
    // Read / Write property
    // ---------------------------------------------------------------------------
    readWriteAccess : function(value, dataType, vocabulary) {
        return this.accessObject(value, this.WRITE_MASK | this.READ_MASK, dataType, vocabulary);
    },
    // ---------------------------------------------------------------------------
    // Undocumented property
    // ---------------------------------------------------------------------------
    undocumented : function(value, dataType, vocabulary) {
        return this.accessObject(value, this.UNDOCUMENTED, dataType, vocabulary);
    },
    // ---------------------------------------------------------------------------
    // Create object value, access
    // ---------------------------------------------------------------------------
    accessObject : function(value, accessMask, dataType, vocabulary) {
        var obj = {};
        obj.value = value;
        obj.access = accessMask;
        obj.dataType = dataType;
        if (obj.dataType == this.CMIVocabulary) {
            if (vocabulary != undefined) {
                obj.vocabulary = vocabulary;
            }
            else {
                alert("Bug in vocabulary == undefined");
            }
        }
        return obj;
    },
    // ---------------------------------------------------------------------------
    // Create object value, access
    // ---------------------------------------------------------------------------
    validateDataType : function(dataType, value, property) {
        switch (dataType) {
            case this.CMIBlank:
                return value == "";
                
            case this.CMIBoolean:
                return typeof value == "boolean";

            case this.CMIDecimal0To100 | this.CMIBlank:
                if (value == "") {
                    return true;
                }
                // break; delibrately missing here, it is meant to flow through to the next case
            case this.CMIDecimal0To100:
                if (typeof value == "string") {
                    value = parseFloat(value);
                }
                return typeof value == "number" && value >= 0 && value <= 100;
                
            case this.CMIFeedbackResponseTypeNumeric:
            case this.CMIDecimal:
                if (typeof value == "string") {
                    value = parseFloat(value);
                }
                return typeof value == "number";
                
            case this.CMIFeedback:
                return ["true-false", "choice", "fill-in", "matching", "performance", "likert", "sequencing", "numeric"].indexOf(value) != -1;
                
            case this.CMIIdentifier:
                return typeof value == "string" && value.length > 0 && value.length <= 255 && /^[a-zA-Z0-9-_]+$/.test(value);
                
            case this.CMIInteger:
                if (typeof value == "string") {
                    value = parseInt(value, 10);
                }
                
                return typeof value == "number" && Math.floor(value) == value && value >= 0 && value <= 65536;
                
            case this.CMISInteger:
                if (typeof value == "string") {
                    value = parseInt(value, 10);
                }
                return typeof value == "number" && Math.floor(value) == value && value >= -32768 && value <= 32768;
                
            case this.CMIString255:
                return value.length <= 255;
                
            case this.CMIString4096:
                return value.length <= 4096;
                
            case this.CMITime:
                return /^([0-1]\d|2[0-3]):([0-5]\d):([0-5]\d)(\.[0-9]{1,2})?$/.test(value);
                
            case this.CMITimeSpan:
                return /^([0-9]{1,3})\d:([0-5]\d):([0-5]\d)(\.[0-9]{1,2})?$/.test(value);
                
            case this.CMIVocabulary:
                return this.validateVocabularyData(property, value);
                
            case this.CMIFeedbackResponseTypeUnknown:
                return value.length <= 255;
                
            case this.CMIFeedbackResponseTypeTrueFalse:
                return ["0", "1", "t", "f"].indexOf(value) != -1;
                
            case this.CMIFeedbackResponseTypeChoice:
                return this.validateFeedbackResponseTypeChoice(value);
                
            case this.CMIFeedbackResponseTypeFillIn:
                return value.length <= 255;
                
            case this.CMIFeedbackResponseTypeMatching:
                return this.validateFeedbackResponseTypeMatching(value);
                
            case this.CMIFeedbackResponseTypePerformance:
                return value.length <= 255;
                
                //
                // CMIFeedbackResponseTypeSequencing and CMIFeedbackResponseTypeLikert are same validator
                //
            case this.CMIFeedbackResponseTypeSequencing:
            case this.CMIFeedbackResponseTypeLikert:
                return value.length == 0 || (value.length == 1 && ((value.charCodeAt(0) >= '0'.charCodeAt(0) && value.charCodeAt(0) <= '9'.charCodeAt(0)) || (value.charCodeAt(0) >= 'a'.charCodeAt(0) && value.charCodeAt(0) <= 'z'.charCodeAt(0))));
        }
        return false;
    },
    // ---------------------------------------------------------------------------
    // According the SCORM document:
    // choice, feedback is one or more single chacters separated by a comma
    // Legal charatcers are "0" to "9", and "a" to "z". If all the characters must be chsen to assume the feedback is
    // correct then the comma-separated list must be surrounded by curly brackets: {}
    // Clear as mud! I think that means the following format below are legal and so I'm going with that...
    // Example 1: 1,2,3,a,b,c
    // Example 2: {1,2,3,a,b,c}
    // Example 3: {1,2,3},{a,b,c} 
    // ---------------------------------------------------------------------------
    validateFeedbackResponseTypeChoice : function(value) {
        return this.validateCMIFeedbackRespondTypeChoiceOrMatching(value, this.CMIFeedbackResponseTypeChoice);
    },
    // ---------------------------------------------------------------------------
    // According the SCORM document:
        
    // Clear as mud! I think that means the following format below are legal and so I'm going with that...
    // Example 1: 1.a,2.b,3.c
    // Example 2: {1.a,2.b,3.c}
    // Example 3: {1.a,2.b},{3.c}
    // ---------------------------------------------------------------------------
    validateFeedbackResponseTypeMatching : function(value) {
        return this.validateCMIFeedbackRespondTypeChoiceOrMatching(value, this.CMIFeedbackResponseTypeMatching);
    },
    //----------------------------------------------------------------------------
    // Handle gritty stuff of above 2 functions
    //----------------------------------------------------------------------------
    validateCMIFeedbackRespondTypeChoiceOrMatching : function(value, dataType) {
        if (typeof value == "number") {
            return false;
        }
        
        if (value == "") {
            return true;
        }
        
        value = value.replace(/\s+/g, ''); // strip the spaces
        
        if (value == "") {
            return false;
        }
        
        var groupInfo = value.split("},{");
        var groups = groupInfo.length;
        
        var oddGroup = true;
        
        for (var g = 0; g < groups; g++) {
            var data = groupInfo[g];
            
            if (groups > 1) {
                if (oddGroup) {
                    data = data.substring(1, 256); // strip off the '{'
                }
                else {
                    data = data.substring(0, data.length - 1); // strip off the '}'
                }
                oddGroup = !oddGroup;
            }
            else {
                if (data.substring(0, 1) == "{" && data.substring(data.length - 1, data.length) == "}") {
                    data = data.substring(1, data.length - 3);
                }
            }
            
            var values = data.split(",");
            var count = values.length;

            for (var i = 0; i < count; i++) {
                var value = values[i];
                var OK;
                if (dataType == this.CMIFeedbackResponseTypeMatching) {
                    //  1.a, 2.c, 3.a are examples of legal values
                    OK = value.length == 3 && ((value.charCodeAt(0) >= '0'.charCodeAt(0) && value.charCodeAt(0) <= '9'.charCodeAt(0)) && (value.charCodeAt(2) >= 'a'.charCodeAt(0) && value.charCodeAt(2) <= 'z'.charCodeAt(0)) && value.charCodeAt(1) == '.'.charCodeAt(0));
                }
                else {
                    // 1, 2, 3, 9, a, b, s, z, e are examples of legal values
                    OK = value.length == 1 && ((value.charCodeAt(0) >= '0'.charCodeAt(0) && value.charCodeAt(0) <= '9'.charCodeAt(0)) || (value.charCodeAt(0) >= 'a'.charCodeAt(0) && value.charCodeAt(0) <= 'z'.charCodeAt(0)));
                }
                if (!OK) {
                    return false;
                }
            }
        }
        return true;
    },
    // ---------------------------------------------------------------------------
    // Vocabulary data...
    // ---------------------------------------------------------------------------
    validateVocabularyData : function(property, value) {
        switch (property.vocabulary) {
            case this.VOCABULARY_MODE:
                break;
                
            case this.VOCABULARY_STATUS:
                //
                // Special rules apply to this...
                //
                switch (this.cmi.core.lesson_mode.value) {
                    case "browse":
                        return ["browsed"].indexOf(value) != -1;
                        
                    case "normal":
                        switch (this.cmi.core.credit.value) {
                            case "credit":
                                return ["incomplete", "completed", "passed", "failed"].indexOf(value) != -1;
                                
                                //
                                // If you are taking the course for no-credit, then it should not be possible for the SCO to accidently set it to "passed" or "failed"
                                //
                            case "no-credit":
                                return ["incomplete", "completed"].indexOf(value) != -1;
                        }
                        break;
                        
                    case "review":
                        return ["incomplete", "completed"].indexOf(value) != -1;
                }
                break;
                
                
            case this.VOCABULARY_EXIT:
                return ["time-out", "suspend", "logout", ""].indexOf(value) != -1;
                
                
            case this.VOCABULARY_INTERACTION:
                return ["true-false", "choice", "fill-in", "matching", "performance", "likert", "sequencing", "numeric"].indexOf(value) != -1;
                
            case this.VOCABULARY_RESULT:
                return (this.validateDataType(CMIDecimal, value) || ["correct", "wrong", "unanticipated", "neutral"].indexOf() != -1);
                
            case this.VOCABULARY_TIME_LIMIT_ACTION:
                return ["exit,message", "exit,no message", "continue,message", "continue,no message"].indexOf(value) != -1;
                
                //
                // Read only properties so all these return false - code ever reach other developer call this function without realising the mistake
                // will harmlessly return false
                //
            case this.VOCABULARY_CREDIT:
            case this.VOCABULARY_ENTRY:
                return false;
                
        }
        
        return false;
    },
    // ---------------------------------------------------------------------------
    // The CMI structure can dynamically grow by the courseware by creating
    // either interactions or objectives.
    // While our LMS won't be displaying any visible information about these,
    // this SCORM RTE will however handle those data structure as to allow
    // the courseware to offer this functionality.
    // ---------------------------------------------------------------------------
    dynamicallyBuildCMI : function(cmi, arrayProperty, index) {
        switch (arrayProperty[index - 1]) {
            case 'interactions':
                this.buildInteractions(cmi, arrayProperty, index);
                break;
                
            case 'objectives':
                
                switch (arrayProperty[0]) {
                    case 'objectives':
                        this.buildObjectives(cmi, arrayProperty, index);
                        break;
                        
                    case 'interactions':
                        this.buildInteractionsObjectives(cmi, arrayProperty, index);
                        break;
                }
                break;
                
            case 'correct_responses':
                this.buildInteractionsCorrectResponses(cmi, arrayProperty, index);
                break;
        }
        return true;
    },
    // ---------------------------------------------------------------------------
    // Interactions are defined by the Courseware and is not declared in the manifest file in SCORM 1.2 RTE
    // This function builds the Interactions element with default values
    // It gets slightly more complicated in the fact, there are multiple dynamically growing nodes within this dyanamically growing nodes
    // ---------------------------------------------------------------------------
    buildInteractions : function(cmi, arrayProperty, index) {
        var elementIndex = cmi._count.value;
        
        if (cmi.n == undefined) {
            cmi.n = [];
        }
        else if (elementIndex == 0) {
            this.interactions = cmi.n[elementIndex];
            return;
        }
        //
        // The Interactions.n node created
        //
        cmi.n[elementIndex] = {};
        this.interactions = cmi.n[elementIndex];
        //
        // Populate with info...
        //
        cmi.n[elementIndex]._children = this.readOnlyAccess("id,objectives,time,type,correct_responses,weighting,student_response,result,latency", this.CMIString255);
        cmi.n[elementIndex].access = this.INVALID;
        cmi.n[elementIndex].id = this.readWriteAccess("", this.CMIIdentifier);
        cmi.n[elementIndex].time = this.writeOnlyAccess(this.CMITimeDefault, this.CMITime);
        cmi.n[elementIndex].type = this.writeOnlyAccess("", this.CMIVocabulary, this.VOCABULARY_INTERACTION);
        cmi.n[elementIndex].weighting = this.writeOnlyAccess(0.0, this.CMIDecimal);
        cmi.n[elementIndex].student_response = this.writeOnlyAccess("", this.CMIFeedbackResponseTypeUnknown);
        cmi.n[elementIndex].result = this.writeOnlyAccess("", this.CMIVocabulary, this.VOCABULARY_RESULT);
        cmi.n[elementIndex].latency = this.writeOnlyAccess(this.CMITimeSpanDefault, this.CMITimeSpan);
        
        //
        // Build the objectives
        //
        cmi.n[elementIndex].objectives = {};
        cmi.n[elementIndex].objectives._count = this.readOnlyAccess(0, this.CMIInteger);
        cmi.n[elementIndex].objectives._children = this.undocumented("-INDEX-", this.CMIString255);
        cmi.n[elementIndex].objectives.access = this.INVALID;
        this.buildInteractionsObjectives(cmi.n[elementIndex].objectives, arrayProperty, index);
        
        //
        // Build the correct reponses
        //
        cmi.n[elementIndex].correct_responses = {};
        cmi.n[elementIndex].correct_responses._count = this.readOnlyAccess(0, this.CMIInteger);
        cmi.n[elementIndex].correct_responses._children = this.undocumented("-INDEX-", this.CMIString255);
        cmi.n[elementIndex].correct_responses.access = this.INVALID;
        this.buildInteractionsCorrectResponses(cmi.n[elementIndex].correct_responses, arrayProperty, index);
    },
    
    // ---------------------------------------------------------------------------
    // Interactions objectives are defined by the SCO and is not declared in the manifest file in SCORM 1.2
    // This function builds the objective element with default values
    // While the LMS doesn't make use of it, this SCORM API does
    // ---------------------------------------------------------------------------
    buildInteractionsObjectives : function (cmi, arrayProperty, index) {
        var elementIndex = cmi._count.value;
        
        if (cmi.n == undefined) {
            cmi.n = [];
        }
        else if (elementIndex == 0) {
            return;
        }
        
        cmi.n[elementIndex] = {};
        cmi.n[elementIndex]._children = this.undocumented("id", this.CMIString255); // This should have been in the SCORM spec, it marked as undocumented
        cmi.n[elementIndex].access = this.INVALID;
        cmi.n[elementIndex].id = this.readWriteAccess("", this.CMIIdentifier);
    },
    // ---------------------------------------------------------------------------
    // Interactions correct_reponses are defined by the SCO and is not declared in the manifest file in SCORM 1.2
    // This function builds the correct_reponses element with default values
    // While the LMS doesn't make use of it, this SCORM API does
    // CMIFeedbackResponseTypeUnknown means this field can accept any data and there won't be any validation checking
    // when values are written to the .n.pattern fields.
    // EDGE CASE:
    // As soon as cmi.interactions.type is set, then all .n.pattern will change type according to the CMIFeedback data type.
    // It will then loop all .n.patterns to make sure the unvalidated values are validated based on the
    // cmi.interactions.type CMIFeedback value, if they aren't valid, the fields are cleared given they aren't
    // legal anyway
    // ---------------------------------------------------------------------------
    buildInteractionsCorrectResponses : function (cmi, arrayProperty, index) {
        var elementIndex = cmi._count.value;
        
        if (cmi.n == undefined) {
            cmi.n = [];
        }
        else if (elementIndex == 0) {
            return;
        }

        //var interactions = this.cmi.interactions._count.value;
        //this.interactions = cmi.n[elementIndex];
        
        var feedbackType = this.cmi.interactions.n[arrayProperty[1]].type.value;


        cmi.n[elementIndex] = {};
        cmi.n[elementIndex].access = this.INVALID;
        cmi.n[elementIndex]._children = this.undocumented("pattern", this.CMIString255); // This field should have been in the SCORM spec, it marked as undocumented
        cmi.n[elementIndex].pattern = this.readWriteAccess("", this.getCMIFeedbackDataTypeAsID(feedbackType)); // this.interactions.type.value
    },
    // ---------------------------------------------------------------------------
    // Objectives are defined by the SCO and is not declared in the manifest file in SCORM 1.2
    // This function builds the objective element with default values
    // While the LMS doesn't make use of it, this SCORM API does
    // ---------------------------------------------------------------------------
    buildObjectives : function(cmi, arrayProperty, index) {
        var elementIndex = cmi._count.value;
        
        if (cmi.n == undefined) {
            cmi.n = [];
        }
        else if (elementIndex == 0) {
            return;
        }
        
        cmi.n[elementIndex] = {};
        
        cmi.n[elementIndex]._children = this.readOnlyAccess("id,score,status", this.CMIString255);
        cmi.n[elementIndex].access = this.INVALID;
        cmi.n[elementIndex].id = this.readWriteAccess("", this.CMIIdentifier);
        cmi.n[elementIndex].score = {};
        cmi.n[elementIndex].score.access = this.INVALID;
        cmi.n[elementIndex].score._children = this.readOnlyAccess("raw,min,max", this.CMIString255);
        cmi.n[elementIndex].score.raw = this.readWriteAccess("", this.CMIDecimal0To100 | this.CMIBlank);
        cmi.n[elementIndex].score.min = this.readWriteAccess("", this.CMIDecimal0To100 | this.CMIBlank);
        cmi.n[elementIndex].score.max = this.readWriteAccess("", this.CMIDecimal0To100 | this.CMIBlank);
        cmi.n[elementIndex].status = this.readWriteAccess("not attempted", this.CMIVocabulary, this.VOCABULARY_STATUS);
    },
    
    // ---------------------------------------------------------------------------
    // Walk the CMI tree... It is a recursive function call until it find what
    // the user wants or it fails
    // Returns Object of the node or NULL if it didn't find the property
    // ---------------------------------------------------------------------------
    walkTheCMITree : function(cmi, arrayProperty, index, readMode) {
        if (cmi == undefined || cmi._children == undefined) {
            return null;
        }
        if (cmi._count != undefined && cmi._count.value == arrayProperty[index]) {
            this.dynamicallyBuildCMI(cmi, arrayProperty, index);
        }
        
        
        var children = cmi._children.value.split(",");
        var property = arrayProperty[index];
        
        
        
        if (property == "_children" || (property == "_count" && cmi._count != undefined) || children.indexOf(property) != -1) {
            cmi = cmi[property];
            
            if (index >= arrayProperty.length - 1) {
                return cmi;
            }
            return this.walkTheCMITree(cmi, arrayProperty, index + 1, readMode);
        }
        
        
        if (cmi._count != undefined && Number.isInteger(parseInt(arrayProperty[index], 10)) && arrayProperty[index] >= 0 && arrayProperty[index] <= cmi._count) {
            if (cmi.n != undefined) {
                cmi = cmi.n[arrayProperty[index]];
                return this.walkTheCMITree(cmi, arrayProperty, index + 1, readMode);
            }
        }
        return null;
    },
    
    // ---------------------------------------------------------------------------
    // Support function for errors
    // ---------------------------------------------------------------------------
    initialiseError : function() {
        this.errorMessages = {};
        
        this.errorMessages[this._errorNoError] = 'No Error';
        this.errorMessages[this._errorGeneralException] = 'General Exception';
        this.errorMessages[this._errorInvalidArgument] = 'Invalid Argument';
        this.errorMessages[this._errorElementCannotHaveChildren] = 'Element Cannot Have Children';
        this.errorMessages[this._errorElementNotAnArrayCannotHaveChildren] = 'Element Not an Array - Cannot Have Children';
        this.errorMessages[this._errorAPINotInitialized] = 'API Not Initialized';
        this.errorMessages[this._errorDataModelElementNotImplemented] = 'Data Model Element does not exists';
        this.errorMessages[this._errorInvalidSetValueElementIsAKeyword] = 'Invalid Set Value - Element is a Keyword';
        this.errorMessages[this._errorInvalidSetValueElementIsReadOnly] = 'Invalid Set Value - Element is Read Only';
        this.errorMessages[this._errorInvalidGetValueElementIsWriteOnly] = 'Invalid Get Value - Element is Write Only';
        this.errorMessages[this._errorInvalidSetValueIncorrectDataType] = 'Invalid Set Value - Incorrect Data Type / value / range / size';
        this.errorMessages[this._errorScormSessionFinished] = 'LMSFinish() called - no further calls to any LMSxxxxx() functions allowed';
        this.errorMessages[this._errorAlreadyInitialised] = 'Already Initialized - Call to LMSInitialize(""); failed because LMSInitialize("") has already been called';
        this.errorMessages[this._errorInvalidSetValueElementIsANode] = 'Invalid Set Value - Element is a node';
        this.errorMessages[this._errorInvalidLMSInitializeParameter1] = 'Unexpected value received as the parameter - LMSInitialise(<b>""</b>); expects a single parameter containing an empty string';
        this.errorMessages[this._errorLMSInitializeParameter1Missing] = 'Missing parameter - LMSInitialise(<b>""</b>); expects a single parameter containing an empty string';
        this.errorMessages[this._errorLMSInitializeTooManyParameters] = 'Too many parameters sent - LMSInitialise(<b>""</b>); expects a single parameter containing an empty string';
        this.errorMessages[this._errorLMSShutDownDueToExcessiveLMSInitialize] = 'SCORM RTE closed down due to too many calls to LMSInitialize("");';
        this.errorMessages[this._errorInvalidLMSGetValueParameter1] = 'Unexpected value received as the parameter - LMSGetValue(); expects a single parameter containing a valid Data Model Element name';
        this.errorMessages[this._errorLMSGetValueParameter1Missing] = 'Missing parameter - LMSGetValue(); expects a single parameter containing a valid Data Model Element name';
        this.errorMessages[this._errorLMSGetValueTooManyParameters] = 'Too many parameters sent - LMSGetValue(); expects a single parameter containing a valid Data Model Element name';
        this.errorMessages[this._errorInvalidLMSSetValueParameter1] = 'Unexpected value received as first parameter - LMSSetValue("<Data Model Element Name>", value);';
        this.errorMessages[this._errorLMSSetValueTooManyParameters] = 'Too many parameters sent - LMSSetValue("<Data Model Element Name>", value);';
        this.errorMessages[this._errorLMSSetValueTooFewParameters] = 'Too few parameters send - LMSSetValue("<Data Model Element Name>", value);';
        
    },
    // ---------------------------------------------------------------------------
    // clear errors
    // ---------------------------------------------------------------------------
    clearErrors : function() {
        this.reportError(this._errorNoError);
    },
    // ---------------------------------------------------------------------------
    // report error string
    // ---------------------------------------------------------------------------
    reportError : function(errorCode) {
        this.errorCode      = errorCode;
    },
    // ---------------------------------------------------------------------------
    // Time support functions
    // Converts "0000:00:00.00" and "00:00:00.00" into an object for easier processing
    // ---------------------------------------------------------------------------
    convertCMITimeSpanToTimeObject : function(timeString) {
        var splitDecPoint = timeString.split(".");
        var ss = parseInt(splitDecPoint.length == 2 ? splitDecPoint[1] : 0, 10);
        var splitHHHHMMSS = splitDecPoint[0].split(":");
        
        var HHHH = parseInt(splitHHHHMMSS[0]);
        var MM = parseInt(splitHHHHMMSS[1]);
        var SS = parseInt(splitHHHHMMSS[2]);
        
        return {"HHHH" : HHHH, "HH" : HHHH % 24, "MM" : MM, "SS" : SS, "ss" : ss, "HHSSMM" : HHHH * 3600 + MM * 60 + SS};
    },
    // ---------------------------------------------------------------------------
    // Give this two time objects created by convertCMITimeSpanToTimeObject() and it will
    // return a new time object with the combined time added together
    // ---------------------------------------------------------------------------
    addTimeObjectTogether : function(timeObject1, timeObject2) {
        var HHHH = timeObject1.HHHH + timeObject2.HHHH;
        var MM = timeObject1.MM + timeObject2.MM;
        var SS = timeObject1.SS + timeObject2.SS;
        var ss = timeObject1.ss + timeObject2.ss;
        
        if (ss >= 100) {
            ss -= 100;
            SS++;
        }
        
        if (SS >= 60) {
            SS -= 60;
            MM++;
        }
        
        if (MM >= 60) {
            MM -= 60;
            HHHH++;
        }
        
        return {"HHHH" : HHHH, "HH" : HHHH % 24, "MM" : MM, "SS" : SS, "ss" : ss, "HHSSMM" : HHHH * 3600 + MM * 60 + SS};
    },
    // ---------------------------------------------------------------------------
    // Convert time object back into CMITimeSpan string in this case "0000:00:00.00"
    // ---------------------------------------------------------------------------
    convertTimeObjectToCMITimeSpan : function(timeObject) {
        return this.makeTimeFromTimeObject(this.CMITimeSpan, timeObject);
    },
    // ---------------------------------------------------------------------------
    // Convert time object back into CMITime string in this case "00:00:00.00"
    // ---------------------------------------------------------------------------
    convertTimeObjectToCMITime : function(timeObject) {
        return this.makeTimeFromTimeObject(this.CMITime, timeObject);
    },
    // ---------------------------------------------------------------------------
    // If dataType is CMITime then the time object value is returned as "00:00:00.00"
    // If dataType is CMITimeSpan then the time object value is returned as "0000:00:00.00"
    // ---------------------------------------------------------------------------
    makeTimeFromTimeObject : function(dataType, timeObject) {
        var HHHHorHH = dataType == this.CMITimeSpan ? this.leadingZeros(timeObject.HHHH, 4) : this.leadingZeros(timeObject.HH, 2);
        
        return HHHHorHH + ":" + this.leadingZeros(timeObject.MM, 2) + ":" + this.leadingZeros(timeObject.SS, 2) + "." + this.leadingZeros(timeObject.ss, 2);
    },
    // ---------------------------------------------------------------------------
    // Padding leading zeros
    // ---------------------------------------------------------------------------
    leadingZeros : function(num, size) {
        var str = "000000000" + num;
        return str.substr(str.length - size);
    },
    // ---------------------------------------------------------------------------
    // 
    // ---------------------------------------------------------------------------
    closeCourseWindow : function() {
        if (window.API.SCOPopup) {
            window.API.SCOPopup.close();
            window.API.SCOPopup = null;
        }

        window.onbeforeunload = null;
    },
    // ---------------------------------------------------------------------------
    // report error string
    // ---------------------------------------------------------------------------
    launchCourse : function(moduleID, courseID, userID) {
        if (this.SCOPopup) {
            this.SCOPopup.close();
        }
        window.onbeforeunload = this.closeCourseWindow.bind(this);
        
        var width = 400;
        var height = 400;
        var left = (screen.width/2)-(width/2);
        var top = (screen.height/2)-(height/2);
        
        window.API.SCOPopup = window.open("", "Training", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,width=" + width + ",height=" + height+",top=" + top +",left=" + left);
        
        this.moduleID = moduleID;
        this.userID = userID;
        
        this.AJAXRequest.open('GET', '/ajax/scorm-cmidata/' + moduleID + '/' + userID + '/' + document.getElementById("CSRFTokens").value);
        this.AJAXRequest.send();
    },
    // ---------------------------------------------------------------------------
    // Init Read AJAX
    // ---------------------------------------------------------------------------
    initReadAJAX : function() {
        var request = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
        
        this.AJAXRequest = request;
        
        request.onreadystatechange = function() {
            if (request.readyState === 4) {
                
                if (request.status === 200) {
                    request.eventOwner.onLaunchedCourseSuccess(request.responseText);
                }
                else {
                    request.eventOwner.onLaunchedCourseFailed("{}");
                }
            }
        }
        request.eventOwner = this;
    },
    // ---------------------------------------------------------------------------
    // Init Write AJAX
    // ---------------------------------------------------------------------------
    initWriteAJAX : function() {
        var request = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
        this.AJAXSaveRequest = request;
        
        request.onreadystatechange = function() {
            if (request.readyState === 4) {
                
                if (request.status === 200) {
                    request.eventOwner.onWriteCMIDataSuccess(request.responseText);
                }
                else {
                    request.eventOwner.onWriteCMIDataFailed("{}");
                }
            }
        }
        request.eventOwner = this;
    },
    // ---------------------------------------------------------------------------
    // CMI data structure save successfully
    // ---------------------------------------------------------------------------
    onWriteCMIDataSuccess : function(data) {
        var results = JSON.parse(data);
        
        var dateCompletedElement = document.getElementById("global-date-completed");
        
        if (dateCompletedElement) {
            dateCompletedElement.innerHTML = results.m_allCompleted;
        
            var scoreElem = document.getElementById("score-" + this.moduleID);
            if (scoreElem) {
                scoreElem.innerHTML = results.m_moduleScore;
            }
            
            document.getElementById("status-" + this.moduleID).innerHTML = results.m_moduleStatus;
            document.getElementById("start-date-" + this.moduleID).innerHTML = results.m_moduleStartedDate;
            document.getElementById("completed-date-" + this.moduleID).innerHTML = results.m_moduleCompletedDate;
            
            
            var scoreElem = document.getElementById("MVscore-" + this.moduleID);
            if (scoreElem) {
                scoreElem.innerHTML = results.m_moduleScore;
            }
            
            document.getElementById("MVstatus-" + this.moduleID).innerHTML = results.m_moduleStatus;
            document.getElementById("MVstart-date-" + this.moduleID).innerHTML = results.m_moduleStartedDate;
            document.getElementById("MVcompleted-date-" + this.moduleID).innerHTML = results.m_moduleCompletedDate;
        }
    },
    // ---------------------------------------------------------------------------
    // CMI data structure save failed
    // ---------------------------------------------------------------------------
    onWriteCMIDataFailed : function(data) {
    },
    // ---------------------------------------------------------------------------
    // CMI data structure loaded successfully
    // ---------------------------------------------------------------------------
    onLaunchedCourseSuccess : function(courseInfo) {
        if (SCORMDiagnostic) {
            SCORMDiagnostic.initialiseLog();
            
            var windowCollection = document.getElementById("windowCollection");
            windowCollection.innerHTML = "";
            
            var LMSLogWindow = new WINDOWS();
            LMSLogWindow.create("WINDOW-LMSLog WINDOW", "LMS Event Log", "SCORMTRALog", "SCORMTRA-log");
            var CMIDataTreeviewWindow = new WINDOWS();
            CMIDataTreeviewWindow.create("WINDOW-CMIData WINDOW", "CMIData", "SCORMTRAtreeview", "SCORMTRA-treeview");
            var CommandLineWindow = new WINDOWS();
            CommandLineWindow.create("WINDOW-CommandLine WINDOW", "Command Line", "SCORMTRACmdLine", "SCORMTRA-cmdLine");
            
            LMSLogWindow.activateEventListeners();
            CMIDataTreeviewWindow.activateEventListeners();
            CommandLineWindow.activateEventListeners();

            SCORMDiagnostic.initialized(LMSLogWindow, CMIDataTreeviewWindow, CommandLineWindow);
        }

        this.restartSCORM();
        this.CMIJSONData = "" + courseInfo;
        
        for (var i in this.modInfo) {
            var m = this.modInfo[i];
            if (m.moduleID == this.moduleID) {
                var path = "/courses/course" + this.leadingZeros(m.courseID, 4) + "/" + m.URL;
                this.coursePopup(path, "Training", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no", m.windowWidth, m.windowHeight);
                break;
            }
        }
    },
    // ---------------------------------------------------------------------------
    // Course popup
    // ---------------------------------------------------------------------------
    coursePopup : function(url, name, options, width, height) {
        var left = (screen.width/2)-(width/2);
        var top = (screen.height/2)-(height/2);
        
        options += ", width=" + width + ", height=" + height + ", top=" + top + ", left=" + left;
        
        this.SCOPopup = window.open(url, name, options);
        
        if (!this.SCOPopup) {
            return null;
        }
        this.SCOPopup.moveTo(left, top);
        this.SCOPopup.resizeTo(width, height);
        this.SCOPopup.focus();
    },
    // ---------------------------------------------------------------------------
    // CMI data structure fail to load
    // ---------------------------------------------------------------------------
    onLaunchedCourseFailed : function() {
        alert("Could not access server to launch course");
    },
    // ---------------------------------------------------------------------------
    // initialise LMS modules
    // ---------------------------------------------------------------------------
    initialiseLMSModules : function() {
        this.initReadAJAX();
        this.initWriteAJAX();
        
        var launchModule = function() {
            this.owner.launchCourse(this.moduleID, this.courseID, userID);
        }
        
        this.modInfo = JSON.parse(moduleInfo);
        
        for (var i in this.modInfo) {
            var mInfo = this.modInfo[i];
            var moduleID = mInfo.moduleID;
            var courseID = mInfo.courseID;
            var launchButton = document.getElementById("moduleID" + moduleID);
            
            if (launchButton) {
                launchButton.owner = this;
                launchButton.onclick = launchModule;
                launchButton.moduleID = moduleID;
                launchButton.courseID = courseID;
            }
        }
        
        for (var i in this.modInfo) {
            var mInfo = this.modInfo[i];
            var moduleID = mInfo.moduleID;
            var courseID = mInfo.courseID;
            var launchButton = document.getElementById("MVmoduleID" + moduleID);
            
            if (launchButton) {
                launchButton.owner = this;
                launchButton.onclick = launchModule;
                launchButton.moduleID = moduleID;
                launchButton.courseID = courseID;
            }
        }
    }
    
    
};
var SCORMDiagnostic = null;
if (typeof SCORMRTA === "function") {
    SCORMDiagnostic = new SCORMRTA();
}

API = new SCORM12RTE();
window.API = API;

//
// Initialise courses
//
var onload = function() {
    API.initialiseLMSModules();
}

window.addEventListener("load", onload);