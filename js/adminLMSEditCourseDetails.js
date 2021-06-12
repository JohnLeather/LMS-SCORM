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
function adminLMSEditCourseDetails() {
    this.construct();
}
// -------------------------------------------------------------------------
// 
// -------------------------------------------------------------------------
adminLMSEditCourseDetails.prototype = {
    // -------------------------------------------------------------------------
    // Initialise
    // -------------------------------------------------------------------------
    construct : function() {
        this.submitButton = document.getElementById("submit-course-settings");
        this.superAdminCB = document.getElementById("superAdmin-cb");
        this.adminCB = document.getElementById("admin-cb");
        this.userCB = document.getElementById("user-cb");
        
        this.superAdminCBValue = this.superAdminCB.checked;
        this.adminCBValue = this.adminCB.checked;
        this.userCBValue = this.userCB.checked;
        
        this.windowWidth = document.getElementById("window-width");
        this.windowHeight = document.getElementById("window-height");
        
        this.windowWidthValue = this.windowWidth.value.trim();
        this.windowHeightValue = this.windowHeight.value.trim();
        
        var eventListenerCheckboxes = function(e) {
            this.eventOwner.handleSubmitButton();
        }
        
        this.superAdminCB.eventOwner = this;
        this.adminCB.eventOwner = this;
        this.userCB.eventOwner = this;
        this.superAdminCB.onclick = eventListenerCheckboxes;
        this.adminCB.onclick = eventListenerCheckboxes;
        this.userCB.onclick = eventListenerCheckboxes;
        //
        // first and last name changed...
        //
        var eventListenerForEditingNameField    = function(e) {
            var returnValue = true;
            e = e || window.event;
            var key = (typeof e.which == "number") ? e.which : e.keyCode;
            
            if (key != 13 && key != 0 && key != 8 && key != 9) {
                if ((e.ctrlKey == false) && (e.altKey == false)) {
                    returnValue =  ('0123456789'.indexOf(String.fromCharCode(key)) != -1);
                }
            }
            if (!returnValue) {
                e.preventDefault();
                e.stopPropagation();
            }
            return false;
        };
        
        var eventListenerAfterEditingNameField  = function(e) {
            this.eventOwner.handleSubmitButton();
            
            return false;
        }
        
        this.windowWidth.eventOwner = this;
        this.windowWidth.addEventListener("keypress", eventListenerForEditingNameField);
        this.windowWidth.addEventListener("keyup", eventListenerAfterEditingNameField);
        
        this.windowHeight.eventOwner = this;
        this.windowHeight.addEventListener("keypress", eventListenerForEditingNameField);
        this.windowHeight.addEventListener("keyup", eventListenerAfterEditingNameField);
    },
    // -------------------------------------------------------------------------
    // 
    // -------------------------------------------------------------------------
    handleSubmitButton : function() {
        var different = false;
        if (this.superAdminCBValue != this.superAdminCB.checked) {
            different = true;
        }
        
        if ( this.adminCBValue != this.adminCB.checked) {
            different = true;
        }

        if (this.userCBValue != this.userCB.checked) {
            different = true;
        }

        if (this.windowWidthValue != this.windowWidth.value.trim()) {
            different = true;
        }
        
        if (this.windowHeightValue != this.windowHeight.value.trim()) {
            different = true;
        }
        
        var b = this.submitButton;
        b.disabled = !different;
        b.className = !different ? "right-arrow-icon disabled" : "right-arrow-icon";
    },
};
// --------------------------------------------------------------------------------------------------
//
// --------------------------------------------------------------------------------------------------
var onload = function() {
    new adminLMSEditCourseDetails();
}
window.addEventListener("load", onload);