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
if (!String.prototype.trim) {
    String.prototype.trim = function () {
        return this.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
    };
}
// --------------------------------------------------------------------------------------------------
//
// --------------------------------------------------------------------------------------------------
function adminLMSSettings() {
    this.construct();
}
// -------------------------------------------------------------------------
// 
// -------------------------------------------------------------------------
adminLMSSettings.prototype = {
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    construct : function() {
        this.CB = [];
        this.CBValue = [];
        
        this.submitButton = document.getElementById("submit-settings");
        this.CB[0] = document.getElementById("display-score-column");
        this.CB[1] = document.getElementById("reject-common-passwords");
        this.passwordAZ = document.getElementById("password-A-Z");
        this.passwordaz = document.getElementById("password-a-z");
        this.password09 = document.getElementById("password-0-9");
        this.passwordsym = document.getElementById("password-symbol");
        this.passwordLen = document.getElementById("password-length");
        this.passwordLenValue = this.passwordLen.options[this.passwordLen.selectedIndex].value
        
        this.passwordAZValue = this.passwordAZ.value.trim();
        this.passwordazValue = this.passwordaz.value.trim();
        this.password09Value = this.password09.value.trim();
        this.passwordsymValue = this.passwordsym.value.trim();
        
        var eventListenerCheckboxes = function(e) {
            this.eventOwner.handleSubmitButton();
        }
        
        this.totalCB = this.CB.length;
        for (var i = 0; i < this.totalCB; i++) {
            this.CBValue[i] = this.CB[i].checked;
            this.CB[i].eventOwner = this;
            this.CB[i].onclick = eventListenerCheckboxes;
        }
        
        var eventListenerTextField = function(e) {
            this.eventOwner.handleSubmitButton();
        };
        
        this.passwordAZ.eventOwner = this;
        this.password09.eventOwner = this;
        this.passwordsym.eventOwner = this;
        this.passwordaz.eventOwner = this;
        this.passwordAZ.addEventListener("keypress", eventListenerTextField);
        this.passwordAZ.addEventListener("keyup", eventListenerTextField);
        this.passwordaz.addEventListener("keypress", eventListenerTextField);
        this.passwordaz.addEventListener("keyup", eventListenerTextField);
        this.password09.addEventListener("keypress", eventListenerTextField);
        this.password09.addEventListener("keyup", eventListenerTextField);
        this.passwordsym.addEventListener("keypress", eventListenerTextField);
        this.passwordsym.addEventListener("keyup", eventListenerTextField);
        
        this.passwordLen.eventOwner = this;
        this.passwordLen.addEventListener("change", eventListenerTextField);
    },
    // -------------------------------------------------------------------------
    // Required on manage stores page which decides which buttons are available
    // -------------------------------------------------------------------------
    handleSubmitButton : function() {
        var different = false;
        
        for (var i = 0; i < this.totalCB; i++) {
            if (this.CBValue[i] != this.CB[i].checked) {
                different = this;
                break;
            }
        }
        
        if (this.passwordLen.options[this.passwordLen.selectedIndex].value != this.passwordLenValue) {
            different = true;
        }
        
        if (this.passwordAZValue != this.passwordAZ.value.trim()) {
            different = true;
        }
        
        if (this.passwordazValue != this.passwordaz.value.trim()) {
            different = true;
        }
        
        if (this.password09Value != this.password09.value.trim()) {
            different = true;
        }
        
        if (this.passwordsymValue != this.passwordsym.value.trim()) {
            different = true;
        }

        if (this.passwordAZ.value.trim() == "") {
            different = false;
        }
        
        if (this.passwordaz.value.trim() == "") {
            different = false;
        }
        
        if (this.password09.value.trim() == "") {
            different = false;
        }
        
        if (this.passwordsym.value.trim() == "") {
            different = false;
        }

        var b = this.submitButton;
        b.disabled = !different;
        b.className = !different ? "right-arrow-icon disabled gapButton" : "right-arrow-icon gapButton";
    },
};
// --------------------------------------------------------------------------------------------------
//
// --------------------------------------------------------------------------------------------------
var onload = function() {
    new adminLMSSettings();
}
window.addEventListener("load", onload);
