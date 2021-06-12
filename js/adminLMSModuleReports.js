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
if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (vMember, nStartFrom) {
        if (this == null) {
            throw new TypeError("Array.prototype.indexOf() - can't convert `" + this + "` to object");
        }
        
        var nIdx = isFinite(nStartFrom) ? Math.floor(nStartFrom) : 0;
        var oThis = this instanceof Object ? this : new Object(this);
        var nLen = isFinite(oThis.length) ? Math.floor(oThis.length) : 0;
        
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
// --------------------------------------------------------------------------------------------------
//
// --------------------------------------------------------------------------------------------------
function adminLMSModuleReport() {
    this.construct();
}
// -------------------------------------------------------------------------
//
// -------------------------------------------------------------------------
adminLMSModuleReport.prototype = {
    // -------------------------------------------------------------------------
    // Initialise
    // -------------------------------------------------------------------------
    construct : function() {
        this.sortByFilter = document.getElementById("sortByFilter");
        this.orderByFilter = document.getElementById("orderByFilter");
        
        this.refresh = document.getElementById("refresh");
        this.findText = document.getElementById("find-text");
        
        
        if (this.sortByFilter) {
            var self = this;
            var changedSortByFilter = function() {
                var sortByFilter = self.sortByFilter.options[self.sortByFilter.selectedIndex].value;
                self.changeURL("order", sortByFilter);
                self.goToURL();
            }
            this.sortByFilter.onchange = changedSortByFilter;
        }
        
        if (this.orderByFilter) {
            var self = this;
            var changedOrderByFilter = function() {
                var orderByFilter = self.orderByFilter.options[self.orderByFilter.selectedIndex].value;
                self.changeURL("in", orderByFilter);
                self.goToURL();
            }
            this.orderByFilter.onchange = changedOrderByFilter;
        }
        
        var nameEvent = function(e) {
            var key = document.all ? parseInt(e.keyCode) : parseInt(e.which);
            if (key == 13) {
                this.owner.returnPressed();
                return false;
            }
            
            if (key != 13 && key != 0 && key != 8) {
                if ((e.ctrlKey == false) && (e.altKey == false)) {
                    return (' ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-.\''.indexOf(String.fromCharCode(key)) != -1);
                }
                else {
                    return true;
                }
            }
            else {
                return true;
            }
        };
        
        var refreshEvent = function() {
            this.owner.returnPressed();
        };
        
        this.findText.owner = this;
        this.findText.onkeypress = nameEvent;
        
        this.refresh.owner = this;
        this.refresh.onclick = refreshEvent;
               
        this.urlParams = window.location.href.split("/");
        this.validateURL();
    },
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    returnPressed : function() {
        this.changeURL("page", 1);
        if (this.findText.value.trim() == "") {
            this.deleteURL("find");
        }
        else {
            this.changeURL("find", this.findText.value);
        }
        this.goToURL();
    },
    // -------------------------------------------------------------------------
    //
    // -------------------------------------------------------------------------
    changeURL : function(element, value) {
        for (var i = this.param; i < this.urlParams.length; i++) {
            if (this.urlParams[i] == element) {
                this.urlParams[i+1] = value;
                return;
            }
            i++;
        }
        this.urlParams.push(element);
        this.urlParams.push(value);
    },
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    deleteURL : function(element) {
    
        for (var i = this.param; i < this.urlParams.length; i++) {
            if (this.urlParams[i] == element) {
                this.urlParams.splice(i, 2);
                return;
            }
            i++;
        }
        
    },
    // -------------------------------------------------------------------------
    //
    // -------------------------------------------------------------------------
    goToURL : function() {
        var path = this.urlParams.join("/");
        window.location.href = path;
    },
    // -------------------------------------------------------------------------
    //
    // -------------------------------------------------------------------------
    validateURL : function() {
        this.param = this.urlParams.indexOf("view-module-reports") + 1;
        
        if (this.param < 1) {
            this.param = this.urlParams.indexOf("view-users") + 1;
        }
    },
};
// --------------------------------------------------------------------------------------------------
//
// --------------------------------------------------------------------------------------------------
window.onload = function() {
    new adminLMSModuleReport();
}