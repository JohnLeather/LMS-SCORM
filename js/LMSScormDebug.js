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

function SCORMRTA() {}
function WINDOWS() {}

// ---------------------------------------------------------------------------------
// Simple draggable window class
// 8th December 2017
// Written by John Leather - www.sphericalgames.co.uk
// ---------------------------------------------------------------------------------
WINDOWS.prototype = {
    // ---------------------------------------------------------------------------------
    // Create window, x, y, w, h are in the CSS
    // ---------------------------------------------------------------------------------
    create : function(windowClass, title, containerID, className) {
        this.windowID = "WINDOW-" + containerID;
        this.titleID = this.windowID + "-title";
        this.containerID = containerID;
        this.closeWindow = this.windowID + "-close";
        
        var win = "<div id='" + this.windowID + "' class='" + windowClass + "'><div id='" + this.titleID + "' class='WINDOWTitle'>" + title + "<div id='" + this.closeWindow + "'>&nbsp;</div></div><div class='" + className + "' id='" + containerID + "'></div><div class='WINDOWFooter'></div></div>";
        
        var windowCollection = document.getElementById("windowCollection");
        
        windowCollection.innerHTML = windowCollection.innerHTML + win;
        windowCollection.style.display = "block";
    },
    
    // ---------------------------------------------------------------------------------
    // WH of browser window
    // ---------------------------------------------------------------------------------
    getScreenWidthHeight : function() {
        var access = document.documentElement.clientWidth ? document.documentElement : document.body;
        return {w : parseInt(access.clientWidth, 10), h : parseInt(access.clientHeight, 10)};
    },
    // ---------------------------------------------------------------------------------
    // Not ideal, you have to create all the windows and get it in the DOM then for each window call activateEventListeners
    // ---------------------------------------------------------------------------------
    activateEventListeners : function() {
        var win = document.getElementById(this.windowID);
        
        var bringToFrontEvent = function() {
            document.getElementById(this.owner.windowID).style.zIndex = zIndex;
            zIndex++;
        }
        
        win.owner = this;
        win.onmousedown = bringToFrontEvent;
        
        var beginDragEvent = function(e) {
            var dragEvent = function(e) {
                var me = document.getElementById(this.owner.windowID);
                e = e || window.event;
            
                var newX = e.clientX - this.owner.windowDragX;
                var newY = e.clientY - this.owner.windowDragY;

                var desktopWH = this.owner.getScreenWidthHeight();
                
                desktopWH.w -= parseInt(me.offsetWidth, 10);
                desktopWH.h -= parseInt(me.offsetHeight, 10);
                
                me.style.left = Math.max(0, Math.min(newX, desktopWH.w)) + "px";
                me.style.top = Math.max(0, Math.min(newY, desktopWH.h)) + "px";
            }
            
            var endDragEvent = function(e) {
                document.onmousemove = null;
                document.onmouseup = null;
                document.onselectstart = null;
                document.ondragstart = null;
            }
            
            e = e || window.event;
            this.owner.windowDragX = e.clientX - this.parentNode.offsetLeft;
            this.owner.windowDragY = e.clientY - this.parentNode.offsetTop;
            
            document.owner = this.owner;
            document.onmousemove = dragEvent;
            document.onmouseup = endDragEvent;
            document.onselectstart = this.owner.onSelectStart;
            document.ondragstart = this.owner.onSelectStart;
        }
        var titleBar = document.getElementById(this.titleID);
        
        titleBar.owner = this;
        titleBar.onmousedown = beginDragEvent;
        
        var closeEvent = function() {
            this.window.parentNode.style.display = "none";
        };
        
        var close = document.getElementById(this.closeWindow);
        close.window = win;
        close.onclick = closeEvent;
        this.window = win;
    },
    
    onSelectStart : function (e) {
        return false;
    },
};
// ---------------------------------------------------------------------------
// Written by JL
// Date: 28th November 2017
// SCORM 1.2 Run Time Environment
// ---------------------------------------------------------------------------
SCORMRTA.prototype = {
    _LOG_LMSINITIALISE : 1,
    _LOG_LMSGETVALUE : 2,
    _LOG_LMSSETVALUE : 3,
    _LOG_LMSCOMMIT : 4,
    _LOG_LMSFINISH : 5,
    
    _EVENT_EXTERNAL : 1,
    _EVENT_INTERNAL : 2,
    _EVENT_LOCKDOWN : 3,
    _EVENT_SYSTEM_MESSAGE : 4,
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    initialiseLog : function() {
        this.logs = [];
        this.logIndex = 0;
        this.loggedOff = true;
        this.event = this._EVENT_EXTERNAL;
        this.lockedDueToMultipleLMSInitializeCall = false;
    },
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    log : function(LMSCommandID, returnValue, p1, p2, p3, pArgs) {
        if (typeof returnValue == "boolean") {
            returnValue = returnValue ? "true" : "false";
        }
        else if (typeof returnValue == "number") {
            returnValue = "" + returnValue;
        }
        else {
            returnValue = "" + returnValue;
        }
        
        var error   = API.LMSGetLastError();
        var command = "";
        var resolution = null;
        
        switch (LMSCommandID) {
            case this._LOG_LMSINITIALISE:
                command = 'LMSInitialize';
                if (error == API._errorAlreadyInitialised) {
                    resolution = 'Do not call LMSInitialize(""); more than once in your code.';
                }
                break;
                
            case this._LOG_LMSGETVALUE:
                command = 'LMSGetValue';
                break;
                
            case this._LOG_LMSSETVALUE:
                command = 'LMSSetValue';
                break;
                
            case this._LOG_LMSCOMMIT:
                command = 'LMSCommit';
                break;
                
            case this._LOG_LMSFINISH:
                command = 'LMSFinish';
                break;
                
        }
        
        if (command != "") {
            this.logMessage(this.event, command, returnValue, error, p1, p2, p3, pArgs, resolution);
        }
        
        //
        // API shut down due to multiple calls to LMSInitialise();
        //
        if (API.APIinitialisedTooManyTimes && !this.lockedDueToMultipleLMSInitializeCall) {
            this.lockedDueToMultipleLMSInitializeCall = true;
            
            this.logMessage(this._EVENT_LOCKDOWN, 'Multiple calls to LMSInitialized(""); has caused SCORM RTE to shut down as per SCORM standards. Any further calls to the following functions will always return an error.</p> <div style="text-align:center"><div style="margin:0px auto;width:250px"> <ul style="text-align:left"><li>LMSInitialize("");</li><li>LMSSetValue("", "");</li><li>LMSGetValue("");</li><li>LMSCommit("");</li><li>LMSFinish("");</li></ul></div></div><p>Address the issue that caused the multiple calls to LMSInitialized(""); error.</p>', "", 1, "", "", "", 0, "");
        }
        
        if (LMSCommandID == this._LOG_LMSFINISH) {
            this.logMessage(this._EVENT_LOCKDOWN, 'LMSFinish(""); has caused SCORM RTE to shut down as per SCORM standards. Any calls to the following functions will always return an error from now on.</p> <div style="text-align:center"><div style="margin:0px auto;width:250px"> <ul style="text-align:left"><li>LMSInitialize("");</li><li>LMSSetValue("", "");</li><li>LMSGetValue("");</li><li>LMSCommit("");</li><li>LMSFinish("");</li></ul></div></div>', "", 1, "", "", "", 0, "");
        }
        
        
        if ((command == 'LMSSetValue' || command == 'LMSFinish') && error == 0) {
            this.refreshTree();
        }
    },
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    logMessage : function(eventType, command, returnValue, error, p1, p2, p3, pArgs, resolution) {
        var logWindow = document.getElementById("SCORMRTA-LMSLog");
        if (logWindow) {
            if (this.loggedOff) {
                this.loggedOff = false;
                logWindow.innerHTML = "";
            }
            
            var html = "";
            var logType = (error == 0) ? "SCORMRTA-logSuccess" : "SCORMRTA-logError";
            
            if (eventType == this._EVENT_INTERNAL || eventType == this._EVENT_EXTERNAL) {
                var sourceOfEvent = (eventType == this._EVENT_INTERNAL) ? "Command line" : "Course";
                html += "<fieldset class='" + logType + "'>";
                html += "<legend style='inline-block'>" + sourceOfEvent + "</legend>";
                html += "<table class='SCORMRTA-logInfoTable'>";
                if (eventType == this._EVENT_INTERNAL) {
                    html += "<tr><td>Command</td><td style='overflow:hidden;max-width:500px'>" + this.internalCommand + "</td></tr>";
                }
                else {
                    var cmd = "";
                    cmd += "" + command + "(";
                    
                    if (pArgs >= 1) {
                        if (typeof p1 == 'string') {
                            cmd += '"' + p1 + '"';
                        }
                        else {
                            cmd += p1;
                        }
                    }
                    if (pArgs >= 2) {
                        if (typeof p2 == 'string') {
                            cmd += ', "' + p2 + '"';
                        }
                        else {
                            cmd += ', ' + p2;
                        }
                    }
                    cmd += ");";
                    html += "<tr><td>Command</td><td style='overflow:hidden;max-width:500px'>" + cmd + "</td></tr>";
                }
                
                html += "<tr><td>Return value</td><td style='overflow:hidden;max-width:500px'>" + returnValue + "</td></tr>";
                html += "<tr><td>Error</td><td>" + error + " : " + API.LMSGetErrorString(error) + "</td></tr>";
                if (resolution != null) {
                    html += "<tr><td>Resolution</td><td>" + resolution + "</td></tr>";
                }
                html += "</table>"
                html += "</fieldset>";
            }
            else if (eventType == this._EVENT_LOCKDOWN) {
                html += "<fieldset class='" + logType + "'>";
                html += "<legend style='inline-block'>SCORM 1.2 RTE Shut down</legend>";
                html += "<p>" + command + "</p>";
                html += "</table>"
                html += "</fieldset>";
            }
            else if (eventType == this._EVENT_SYSTEM_MESSAGE) {
                html += "<fieldset class='" + logType + "'>";
                html += "<legend style='inline-block'>General Command Line Error</legend>";
                html += "<p>" + command + "</p>";
                html += "</table>"
                html += "</fieldset>";
            }
            else {
                var cmd = "";
                cmd += "API." + command + "(";
                
                if (pArgs == 1) {
                    if (typeof p1 == 'string') {
                        cmd += '"' + p1 + '"';
                    }
                }
                cmd += ");";
                
                html += "<div class='" + logType + "'>";
                html += cmd;
                
                html += "</div>";
            }
            
            logWindow.className = "SCORMRTA-LMSLoggingScrollWindowEnabled";
            logWindow.innerHTML = logWindow.innerHTML + html;
            
            logWindow.scrollTop = logWindow.scrollHeight;
        }
        
        this.logs.push(html);
        this.logIndex++;

        this.event = this._EVENT_EXTERNAL;
    },
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    commandLineError : function(message) {
    },
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    resetSCO : function() {
        API.LMSSetValue("cmi.core.exit", "logout");
        setTimeout(this.deleteUserSCODataObject.bind(this), 1100);    
    },
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    deleteUserSCODataObject() {
        var request = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
        
        request.onreadystatechange = function() {
            if (request.readyState === 4) {
                
                if (request.status === 200) {
                    
                }
                else {
                    
                }
            }
        }
        request.eventOwner = this;

        request.open("POST", '/ajax/reset-scorm-cmidata/' + API.moduleID + '/0/' + document.getElementById("CSRFTokens").value, true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send("");

        this.resetGUI(API.moduleID);
        this.closeSCORMDiagnosticWindows();
    },
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    executeCommandLine : function() {
        
        var commandLine = document.getElementById("SCORMRTA-CommandLine");
        if (commandLine) {
            this.internalCommand = commandLine.value;
            //var command = this.stripSpacesNotBetweenQuotes(commandLine.value);
            var command = commandLine.value;
            if (command == "") {
                return;
            }
            if (command == "reset") {
                this.resetSCO();
                return;
            }

            var LMSCommand = this.tokenization(command);
            if (LMSCommand.error != "") {
                this.logMessage(this._EVENT_SYSTEM_MESSAGE, LMSCommand.error, "", 1, "", "", "", 0, "");
                return;
            }
            
            this.event = this._EVENT_INTERNAL;
            
            if (LMSCommand == null) {
                this.commandLineError(commandLine.value);
            }
            else {
                switch (LMSCommand.command) {
                    case "LMSInitialize":
                        if (LMSCommand.parameterCount == 0) {
                            API.LMSInitialize();
                        }
                        else if (LMSCommand.parameterCount == 1) {
                            API.LMSInitialize(LMSCommand.parameters[0]);
                        }
                        else {
                            API.LMSInitialize(LMSCommand.parameters[0], LMSCommand.parameters[1]);
                        }
                        break;
                        
                    case "LMSGetValue":
                        if (LMSCommand.parameterCount == 0) {
                            API.LMSGetValue();
                        }
                        else if (LMSCommand.parameterCount == 1) {
                            API.LMSGetValue(LMSCommand.parameters[0]);
                        }
                        else {
                            API.LMSGetValue(LMSCommand.parameters[0], LMSCommand.parameters[1]);
                        }
                        break;
                        
                    case "LMSSetValue":
                        if (LMSCommand.parameterCount == 0) {
                            API.LMSSetValue();
                        }
                        else if (LMSCommand.parameterCount == 1) {
                            API.LMSSetValue(LMSCommand.parameters[0]);
                        }
                        else if (LMSCommand.parameterCount == 2) {
                            API.LMSSetValue(LMSCommand.parameters[0], LMSCommand.parameters[1]);
                        }
                        else {
                            API.LMSSetValue(LMSCommand.parameters[0], LMSCommand.parameters[1], LMSCommand.parameters[2]);
                        }
                        break;
                        
                    default:
                        this.logMessage(this._EVENT_SYSTEM_MESSAGE, "Command:" + LMSCommand.command + " not recognised", "", 1, "", "", "", 0, "");
                        break;
                }
            }
            this.event = this._EVENT_EXTERNAL;
        }
    },
    // ---------------------------------------------------------------------------
    // 
    // ---------------------------------------------------------------------------
    resetGUI(moduleID) {
        var scoreElem = document.getElementById("score-" + moduleID);
        if (scoreElem) {
            scoreElem.innerHTML = "-";
        }
        
        document.getElementById("status-" + moduleID).innerHTML = "-";
        document.getElementById("start-date-" + moduleID).innerHTML = "-";
        document.getElementById("completed-date-" + moduleID).innerHTML = "-";
    },
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    cleanParameter : function(str) {
        str = str.trim();
        var firstChar = str.charAt(str);
        if (firstChar == '"' || firstChar == "'") {
            
            if (firstChar != str.charAt(str.length-1) || str.length == 1) {
                return null;
            }
            
            return "" + str.substring(1, str.length - 1);
        }
        return parseFloat(str);
    },
    
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    tokenization : function(str) {
        var lexicon = {p : 0, error : "", result : "", parameterPortion : false, matched : 0};
        lexicon = this.fetchLexiconStr(str, lexicon, [{str : "(", increasePointer : 1, excludeResultPointer : 0}]);
        
        var commandInfo = {};
        commandInfo.error = "";
        commandInfo.command = "";
        commandInfo.parameters = [];
        commandInfo.parameterCount = 0;
        
        if (lexicon.error != "") {
            commandInfo.error = lexicon.error;
            return commandInfo;
        }
        
        commandInfo.command = lexicon.result;
        
        var parameterMatch = [{str : ")", increasePointer : 1, excludeResultPointer : 0}, {str : ",", increasePointer : 1, excludeResultPointer : 0}];
        lexicon.error = "";
        lexicon.parameterPortion = true;
        lexicon = this.fetchLexiconStr(str, lexicon, parameterMatch);
        var timedOut = 4;
        
        while (lexicon.error == "" && lexicon.matched != 0 && --timedOut > 0) {
            var p = this.cleanParameter(lexicon.result);
            if (p == null) {
                commandInfo.error = "Malformed parameter";
                return commandInfo;
            }
            else {
                commandInfo.parameters.push(p);
                commandInfo.parameterCount++;
            }
            lexicon = this.fetchLexiconStr(str, lexicon, parameterMatch);
        }
        
        if (lexicon.error == "" && lexicon.result != "") {
            var p = this.cleanParameter(lexicon.result);
            if (p == null) {
                commandInfo.error = "Malformed parameter";
                return commandInfo;
            }
            else {
                commandInfo.parameters.push(p);
                commandInfo.parameterCount++;
            }
        }
        
        if (timedOut <= 0) {
            commandInfo.error = "Too many parameters";
        }
        
        return commandInfo;
    },
    
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    fetchLexiconStr : function(str, lexicon, match) {
        var len = str.length;
        var insideQuoute = "";
        
        lexicon.result = "";
        lexicon.matched = -1;
        
        for (var i = lexicon.p; i < len; i++) {
            var char = str.charAt(i);
            if (lexicon.parameterPortion) {
                if ((char == '"' && insideQuoute == "") || ((char == '"' && insideQuoute == '"'))) {
                    insideQuoute = insideQuoute == "" ? char : "";
                }
                else if ((char == "'" && insideQuoute == "") || ((char == "'" && insideQuoute == "'"))) {
                    insideQuoute = insideQuoute == "" ? char : "";
                }
            }
            
            if (insideQuoute == "") {
                for (var m = 0; m < match.length; m++) {
                    if (char == match[m].str) {
                        lexicon.result = str.substring(lexicon.p, i + match[m].excludeResultPointer);
                        lexicon.p = i + match[m].increasePointer;
                        lexicon.matched = m;
                        return lexicon;
                    }
                }
            }
        }
        
        lexicon.error = "Missing " + match[0].str;
        return lexicon;
    },
    
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    stripSpacesNotBetweenQuotes : function(str) {
        var len = str.length;
        
        if (len == 0) {
            return "";
        }
        
        var stripped = "";
        var terminatedQuote = "";
        var stripDown = true;
        
        for (var i = 0; i < len; i++) {
            var char = str.charAt(i);
            if (stripDown && char == ' ') {
                // skip, deleting this space...
            }
            else {
                stripped += char;
                if (char === "'" && (terminatedQuote === "" || terminatedQuote === "'")) {
                    stripDown != stripDown;
                    terminatedQuote = (stripDown) ? "'" : "";
                }
                else if (char === '"' && (terminatedQuote === "" || terminatedQuote === '"')) {
                    stripDown != stripDown;
                    terminatedQuote = (stripDown) ? '"' : "";
                }
            }
        }
        
        return stripped;
    },
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    createCommandLine : function() {
        var html = "";
        html += "<div class='SCORMRTA-fixedCommandLine'>";
        html += "<div>";
        html += "<div id='SCORMRTA-button-LMSInitialize' class='SCORMRTA-button'>LMSInitialize(\"\");</div>";
        html += "<div id='SCORMRTA-button-LMSGetValue' class='SCORMRTA-button'>LMSGetValue(\"\");</div>";
        html += "<div id='SCORMRTA-button-LMSSetValue' class='SCORMRTA-button'>LMSSetValue(\"\", \"\");</div>";
        //html += "<div id='SCORMRTA-button-LMSCommit'         class='SCORMRTA-button'>LMSCommit(\"\");</div>";
        //html += "<div id='SCORMRTA-button-LMSFinish'         class='SCORMRTA-button'>LMSFinish(\"\");</div>";
        //html += "<div id='SCORMRTA-button-LMSGetLastError'   class='SCORMRTA-button'>LMSGetLastError();</div>";
        //html += "<div id='SCORMRTA-button-LMSGetErrorString' class='SCORMRTA-button'>LMSGetErrorString();</div>";
        //html += "<div id='SCORMRTA-button-LMSGetDiagnostic'  class='SCORMRTA-button'>LMSGetDiagnostic(\"\");</div>";
        html += "</div>";
        
        html += "<section style='margin: 5px;'>";
        html += "<fieldset class='SCORMRTA-fieldset'>";
        html += "<input id='SCORMRTA-CommandLine' class='SCORMRTA-input' placeholder='&nbsp;Command line'>";
        html += "</fieldset>";
        html += "</section>";
        html += "</div>";
        
        return html;
    },
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    createCommandLineButtonEvents : function() {
        var eventInfo = [];
        
        eventInfo.push({button : "SCORMRTA-button-LMSInitialize", str : 'LMSInitialize("");', cursorPosition : 'eos'});
        eventInfo.push({button : "SCORMRTA-button-LMSGetValue", str : 'LMSGetValue("");', cursorPosition : 13});
        eventInfo.push({button : "SCORMRTA-button-LMSSetValue", str : 'LMSSetValue("", "");', cursorPosition : 13});
        
        //eventInfo.push({button : "SCORMRTA-button-LMSCommit",           str : 'LMSCommit("");',         cursorPosition : 'eos'});
        //eventInfo.push({button : "SCORMRTA-button-LMSFinish",           str : 'LMSFinish("");',         cursorPosition : 'eos'});
        //eventInfo.push({button : "SCORMRTA-button-LMSGetLastError",     str : 'LMSGetLastError()',      cursorPosition : 'eos'});
        //eventInfo.push({button : "SCORMRTA-button-LMSGetErrorString",   str : 'LMSGetErrorString();',   cursorPosition : 'eos'});
        //eventInfo.push({button : "SCORMRTA-button-LMSGetDiagnostic",    str : 'LMSGetDiagnostic();',    cursorPosition : 'eos'});
        
        for (var i = 0; i < eventInfo.length; i++) {
            var button = document.getElementById(eventInfo[i].button);
            
            if (button) {
                var clickAction = function() {
                    var commandLine = document.getElementById("SCORMRTA-CommandLine");
                    if (commandLine) {
                        commandLine.value = this.LMSCommandString;
                        commandLine.focus();
                        var cursorPosition = this.cursorPositionAfterPastingLMSString;
                        if (cursorPosition != 'eos') {
                            commandLine.setSelectionRange(cursorPosition, cursorPosition);
                        }
                    }
                }
                button.LMSCommandString = eventInfo[i].str;
                button.cursorPositionAfterPastingLMSString   = eventInfo[i].cursorPosition;
                button.onclick = clickAction;
            }
        }
        
        var commandLine = document.getElementById("SCORMRTA-CommandLine");
        if (commandLine) {
            var dectectEnterKeyEvent = function(e) {
                if (e.keyCode == 13) {
                    SCORMDiagnostic.executeCommandLine();
                    return false;
                }
            }
            commandLine.onkeydown = dectectEnterKeyEvent;
        }
    },
    
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    createExpandCollapseObject : function(html) {
        html += "<div class='SCORMRTA-expandCollapseWidget'><div class='SCORMRTA-expandCollapseWidgetLineAcross'></div><div class='SCORMRTA-expandCollapseWidgetLineUpDown'></div></div>";
        return html;
    },
    
    _NODE_ID_ARRAY              : 1,
    _NODE_ID_FEEDBACK           : 2,
    _NODE_ID_EXPANDER_EVENTS    : 3,
    
    trackObject : function(nodeInfo) {
        this.nodeCollection[this.nodeCollectionID] = nodeInfo;
        this.nodeCollectionID++
    },
    
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    refreshTree : function() {
        for (var i = 0; i < this.elementTreeValueID; i++) {
            var info = this.elementTreeValue[i];
            
            if (info.currentValue != info.cmiDataReference.value) {
                var HTMLObject = document.getElementById(info.HTMLElementID);
                if (HTMLObject) {
                    HTMLObject.innerHTML = info.cmiDataReference.value;
                }
                info.currentValue = info.cmiDataReference.value;
            }
        }

        var expanderAction = function() {
            this.parentNode.nextSibling.style.display = this.parentNode.nextSibling.style.display == 'none' ? 'block' : 'none';
        };
        
        var totalNodes = this.nodeCollectionID;
        var rebuild = false;
        for (var i = 0; i < totalNodes; i++) {
            var nodeInfo = this.nodeCollection[i];
            switch (nodeInfo.id) {
                case this._NODE_ID_ARRAY: {
                    if (nodeInfo.currentArrayValue != nodeInfo.countPointer.value) {
                        nodeInfo.currentArrayValue = nodeInfo.countPointer.value;
                        rebuild = true;
                    }
                    break;
                }
                    
                case this._NODE_ID_FEEDBACK: {
                    if (nodeInfo.cmiDataElement.dataType != nodeInfo.cmiDataElementDataType) {
                        nodeInfo.cmiDataElementDataType = nodeInfo.cmiDataElement.dataType;
                        var div = document.getElementById(nodeInfo.HTMLID);
                        if (div) {
                            div.innerHTML = this.getCMIFeedbackType(nodeInfo.cmiDataElement.dataType);
                        }
                    }
                    break;
                }
                    
                case this._NODE_ID_EXPANDER_EVENTS : {
                    var expander = document.getElementById(nodeInfo.HTMLID);
                    if (expander) {
                        expander.onclick = expanderAction;
                    }
                    break;
                }
            }
        }
        if (rebuild) {
            this.initialisedTreeView = false;
            this.createTreeView();
            this.refreshTree();
        }
    },
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    fetchTrackObject : function(objectID, propertyName, matchValue) {
        var totalNodes = this.nodeCollectionID;
        
        for (var i = 0; i < totalNodes; i++) {
            var n = this.nodeCollection[i];
            if (n.id == objectID) {
                if (n[propertyName] == matchValue) {
                    return n;
                }
            }
        }
        
        return null;
    },
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    createTreeViewWalkerCreateArrayElement : function(cmi, html, path, depth, index, val, alpha, lastCountReference) {
        var nodeID = this.createNodeID(true);

        
        if (!this.fetchTrackObject(this._NODE_ID_ARRAY, "countPointer", lastCountReference)) {
            var nodeInfo = {
                id : this._NODE_ID_ARRAY,
                HTMLID : nodeID,
                countPointer : lastCountReference,
                arrayPointer : val,
                currentArrayValue : lastCountReference.value,
                path : path.slice(0),
                depth : depth,
            };
            this.trackObject(nodeInfo);
        }
        
        var expanderInfo = {
            id : this._NODE_ID_EXPANDER_EVENTS,
            HTMLID : nodeID,
        };
        this.trackObject(expanderInfo);

        var message = lastCountReference.value != 0 ? "" : this.disabledMessage;
        
        alpha = lastCountReference.value == 0 ? "Alpha" : "";
        
        html += "<li><div><div id='" + nodeID + "' class='SCORMRTA-TreeViewRoot" + alpha + "'>";
        html = this.createExpandCollapseObject(html);
        
        html += (index + "<span><span id='" + nodeID + "-message' class='SCORMRTA-TreeViewTinyInfoFont' >" + message + "</span></div></div>");
        
        depth++;
        path[depth] = index;
        html = this.createTreeViewWalker(cmi, html, path, depth, alpha, lastCountReference);
        html += '</li>';
        depth--;
        path.pop();
        
        return html;
    },
    
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    createTreeViewWalker : function(cmi, html, path, depth, alpha, lastCountReference) {
        html += "<ul class='SCORMRTA-TreeView" + alpha + "'>";
        
        for (var pass = 0; pass < 2; pass++) {
            for (var key in cmi) {
                if (Object.prototype.hasOwnProperty.call(cmi, key)) {
                    var val = cmi[key];
                    if (key == "_count") {
                        lastCountReference = cmi[key];
                        alpha = lastCountReference.value == 0 ? "Alpha" : "";
                    }
                    
                    if (val.access !== API.UNDOCUMENTED) {
                        if (key == 'n') {
                            if (pass == 0) {
                                for (var i = 0; i < val.length; i++) {
                                    html = this.createTreeViewWalkerCreateArrayElement(cmi[key][i], html, path, depth, i, val, alpha, lastCountReference);
                                }
                            }
                        }
                        else {
                            var isNode = typeof val === "object" && val.value === undefined;
                            if (key != 'access' && ((pass == 0 && !isNode) || (pass == 1 && isNode))) {
                                
                                var className = (isNode ? "SCORMRTA-TreeViewRoot" + alpha : "SCORMRTA-TreeViewCmiData");
                                var nodeID = this.createNodeID(isNode);
                                html += "<li><div><div id='"+ nodeID +"' class='" + className + "'>";
                                if (isNode) {
                                    html = this.createExpandCollapseObject(html);
                                    var expanderInfo = {
                                        id                  : this._NODE_ID_EXPANDER_EVENTS,
                                        HTMLID              : nodeID,
                                    };
                                    this.trackObject(expanderInfo);
                                    
                                }
                                html += (isNode ? key : path.join(".") + "." + key) + "</div>";
                                
                                if (typeof val === "object") {
                                    if (val.value === undefined) {
                                        html += '</div>';
                                        
                                        depth++
                                        path[depth] = key;
                                        
                                        html = this.createTreeViewWalker(val, html, path, depth, alpha, lastCountReference);
                                        html += '</li>';
                                        depth--;
                                        path.pop();
                                    }
                                    else {
                                        html += "<div id='" + this.getTreeViewValueID(val) + "' class='SCORMRTA-TreeViewValue'>" + (val.value === "" ? "&nbsp;" : val.value) + "</div>";
                                        html += "<div class='SCORMRTA-TreeViewReadWriteInfo";
                                        
                                        if (val.access === API.READ_MASK) {
                                            html += "'>READ";
                                        }
                                        else if (val.access === API.WRITE_MASK) {
                                            html += "'>WRITE";
                                        }
                                        else if (val.access === API.READ_MASK | API.WRITE_MASK) {
                                            html += "ReadWrite'>READ WRITE";
                                        }
                                        
                                        html += "</div>";
                                        
                                        if (val.dataType == API.CMIVocabulary) {
                                            html += "<div class='SCORMRTA-TreeViewDataTypeInfoSplitA'>" + this.getDataTypeAsString(val.dataType) + "</div>";
                                            html += "<div class='SCORMRTA-TreeViewDataTypeInfoSplitB'>" + this.getVocabularyName(val.vocabulary) + "</div>";
                                        }
                                        else if (this.isTypeOfFeedback(val.dataType)) {
                                            
                                            var nodeID = this.createNodeID(true);
                                            
                                            var nodeInfo = {
                                                id                      : this._NODE_ID_FEEDBACK,
                                                HTMLID                  : nodeID,
                                                cmiDataElement          : val,
                                                cmiDataElementDataType  : val.dataType,
                                            };
                                            this.trackObject(nodeInfo);
                                            
                                            html += "<div class='SCORMRTA-TreeViewDataTypeInfoSplitA'>" + this.getDataTypeAsString(val.dataType) + "</div>";
                                            html += "<div id='" + nodeID + "' class='SCORMRTA-TreeViewDataTypeInfoSplitB'>" + this.getCMIFeedbackType(val.dataType) + "</div>";
                                            
                                        }
                                        else {
                                            html += "<div class='SCORMRTA-TreeViewDataTypeInfo'>" + this.getDataTypeAsString(val.dataType) + "</div>";
                                        }
                                        html += "</div></li>";
                                    }
                                }
                                else {
                                    html += '</div></li>';
                                }
                            }
                        }
                    }
                }
            }
        }
        
        html += "</ul>";
        return html;
    },
    
    
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    getTreeViewValueID : function(val) {
        var nodeValueID = "valueID" + this.elementTreeValueID;
        this.elementTreeValueID++;
        
        var currentDataInfo = {
            currentValue : val.value,
            HTMLElementID : nodeValueID,
            cmiDataReference : val,
        };
        
        this.elementTreeValue.push(currentDataInfo);
        return nodeValueID;
    },
    
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    createNodeID : function(isANode) {
        var nodeID = "nodeID" + this.nodeID;
        this.nodeID++;
        if (isANode) {
            this.nodeTree.push(nodeID);
        }
        return nodeID;
    },
    
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    resetNodeAndElementIDs : function() {
        this.nodeCollection = [];
        this.nodeCollectionID = 0;
        
        this.elementTreeValue = [];
        this.elementTreeValueID = 0;
        this.nodeTree = [];
        this.nodeID = 0;
        this.elementTree = [];
        this.elementID = 0;
    },
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    createElementID : function(isAnElement) {
        var elementID = "nodeID" + this.elementID;
        this.elementID++;
        if (isAnElement) {
            this.elementTree.push(elementID);
        }
        return elementID;
    },
    
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    createTreeView : function(cmi) {
        this.initializedWhenSCORMInitalized();
        this.refreshTree();
    },
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    initializedWhenSCORMInitalized : function() {
        if (!this.initialisedTreeView) {
            
            this.initialisedTreeView = true;
            var html = "";
            
            html += "<ul class='SCORMRTA-TreeView SCORMRTA-root' style='width:100%'>";
            html += "<li id='" + this.createNodeID(true) + "'><div>";
            html += "<div class='SCORMRTA-TreeViewRoot'>";
            html = this.createExpandCollapseObject(html);
            html += "cmi</div></div></li>";
            
            html = this.createTreeViewWalker(API.cmi, html, ["cmi"], 0, "", null) + "</ul>";
            
            var tv = document.getElementById("SCORMRTA-CMIDataScrollWindow");
            if (tv != null) {
                tv.innerHTML = html;
                tv.className = "SCORMRTA-CMIDataScrollWindow";
            }
        }
    },
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    initialized : function(LMSLogWindow, CMIDataTreeviewWindow, CommandLineWindow) {
        this.disabledMessage = " - The following faded out properties indicate these don't yet exist that is until you write a value to any of these fields.";
        this.LMSLogWindow = LMSLogWindow;
        this.CMIDataTreeviewWindow = CMIDataTreeviewWindow;
        this.CommandLineWindow = CommandLineWindow;

        this.initialisedTreeView = false;
        
        this.initialiseLog();
        this.resetNodeAndElementIDs();
        
        var commandLineHTML = this.createCommandLine();
        
        var html = "";
        html += "<div id='SCORMRTA-CMIDataScrollWindow' class='SCORMRTA-CMIDataScrollWindowDisabled'>SCORM 1.2 RTE not initialized</div>";

        var tv = document.getElementById("SCORMTRACmdLine");
        if (tv != null) {
            tv.innerHTML = "<div id='SCORMRTA-CLine' class='SCORMRTA-CommandLine'>" + commandLineHTML + "</div>";
        }
        
        var tv = document.getElementById("SCORMTRAtreeview");
        if (tv != null) {
            tv.innerHTML = html;
        }
      
        var tv = document.getElementById("SCORMTRALog");
        if (tv != null) {
            tv.innerHTML = "<div id='SCORMRTA-LMSLog' class='SCORMRTA-LMSLoggingScrollWindowDisabled'>No log events</div>";
        }
        this.createCommandLineButtonEvents();
    },
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    getDataTypeAsString : function(dataType) {
        switch (dataType) {
            case API.CMIDecimal0To100 | API.CMIBlank : return "CMIBlank <b>or</b> CMIDecimal";
            case API.CMIBlank : return "CMIBlank";
            case API.CMIBoolean : return "CMIBoolean";
            case API.CMIDecimal : return "CMIDecimal";
            case API.CMIFeedback : return "CMIFeedback";
            case API.CMIIdentifier : return "CMIIdentifier";
            case API.CMIInteger : return "CMIInteger";
            case API.CMISInteger : return "CMISInteger";
            case API.CMIString255 : return "CMIString255";
            case API.CMIString4096 : return "CMIString4096";
            case API.CMITime : return "CMITime";
            case API.CMITimeSpan : return "CMITimespan";
            case API.CMIVocabulary : return "CMIVocabulary";
            case API.CMIDecimal0To100 : return "CMIDecimal0To100";
            case API.CMIFeedbackResponseTypeUnknown : return "CMIFeedback"; 
            case API.CMIFeedbackResponseTypeTrueFalse : return "CMIFeedback"; 
            case API.CMIFeedbackResponseTypeChoice : return "CMIFeedback"; 
            case API.CMIFeedbackResponseTypeFillIn : return "CMIFeedback"; 
            case API.CMIFeedbackResponseTypeMatching : return "CMIFeedback"; 
            case API.CMIFeedbackResponseTypePerformance : return "CMIFeedback"; 
            case API.CMIFeedbackResponseTypeLikert : return "CMIFeedback"; 
            case API.CMIFeedbackResponseTypeSequencing : return "CMIFeedback"; 
            case API.CMIFeedbackResponseTypeNumeric : return "CMIFeedback"; 
            default : return dataType;
        }
    },
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    isTypeOfFeedback : function(dataType) {
        return this.getCMIFeedbackType(dataType) != "BUG";
    },
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    getCMIFeedbackType : function(dataType) {
        switch (dataType) {
            case API.CMIFeedbackResponseTypeUnknown : return "UNKNOWN";
            case API.CMIFeedbackResponseTypeTrueFalse : return "TRUE FALSE"; 
            case API.CMIFeedbackResponseTypeChoice : return "CHOICE";
            case API.CMIFeedbackResponseTypeFillIn : return "FILL IN";
            case API.CMIFeedbackResponseTypeMatching : return "MATCHING";
            case API.CMIFeedbackResponseTypePerformance : return "PERFORMANCE";
            case API.CMIFeedbackResponseTypeLikert : return "LIKERT";
            case API.CMIFeedbackResponseTypeSequencing : return "SEQUENCING";
            case API.CMIFeedbackResponseTypeNumeric : return "NUMERIC";
        }
        return "BUG";
    },
    // ---------------------------------------------------------------------------
    //
    // ---------------------------------------------------------------------------
    getVocabularyName : function(vocabularyName) {
        switch (vocabularyName) {
            case API.VOCABULARY_MODE : return "MODE";
            case API.VOCABULARY_STATUS : return "STATUS";
            case API.VOCABULARY_EXIT : return "EXIT";
            case API.VOCABULARY_CREDIT : return "CREDIT";
            case API.VOCABULARY_ENTRY : return "ENTRY";
            case API.VOCABULARY_INTERACTION : return "INTERACTION";
            case API.VOCABULARY_RESULT : return "RESULT";
            case API.VOCABULARY_TIME_LIMIT_ACTION : return "TIME LIMIT ACTION";
        }
        return "BUG";
    },
    // ---------------------------------------------------------------------------
    // 
    // ---------------------------------------------------------------------------
    closeSCORMDiagnosticWindows : function() {
        this.LMSLogWindow.window.parentNode.style.display = "none";
        this.CMIDataTreeviewWindow.window.parentNode.style.display = "none";
        this.CommandLineWindow.window.parentNode.style.display = "none";
    },
};
