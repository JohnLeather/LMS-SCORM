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

var progress;

function adminLMSUploadCourse() {
    this.construct();
}

// -------------------------------------------------------------------------
// Manage User registration
// -------------------------------------------------------------------------
//
//
//
//
//
//

adminLMSUploadCourse.prototype = {
    // -------------------------------------------------------------------------
    // Initialise
    // -------------------------------------------------------------------------
    
    construct : function() {
        //document.getElementById("file").onchange = function() {
        //    document.getElementById("form").submit();
        //};
        if (window.File && window.FileList && window.FileReader) {
         
            var validate        = document.getElementById("validate");
            var fileselect      = document.getElementById("fileselect");
            var filedrag        = document.getElementById("fileDragZone");
            var submitButton    = document.getElementById("submitbutton");
            
            // file select
            fileselect.addEventListener("change", this.fileSelectHandler, false);
            
            // is XHR2 available?
            var xhr = new XMLHttpRequest();
            if (xhr.upload) {
                
                // file drop
                filedrag.addEventListener("dragover", this.fileDragHover, false);
                filedrag.addEventListener("dragleave", this.fileDragHover, false);
                filedrag.addEventListener("drop", this.fileSelectHandler, false);
                filedrag.style.display = "block";
                
                fileselect.classOwner = this;
                filedrag.classOwner = this;
                
                
                // remove submit button
                submitButton.style.display = "none";
            }
            
            validate.addEventListener("click", this.validateCourse, false);
            validate.classOwner = this;
        }
    },
    
    fileSelectHandler : function(e) {
        //console.log(e);
        //console.log(e.target);
        // cancel event and hover styling
        e.target.classOwner.fileDragHover(e);
        
        // fetch FileList object
        var files = e.target.files || e.dataTransfer.files;
        
        // process all File objects
        for (var i = 0, f; f = files[i]; i++) {
            //e.target.classOwner.parseFile(f);
            e.target.classOwner.uploadFile(f);
        }
    },
    
    
    
    // file drag hover
    fileDragHover : function(e) {
        e.stopPropagation();
        e.preventDefault();
        //e.target.className = (e.type == "dragover" ? "hover" : "");
    },
    
    
    uploadFile : function(file) {
        var xhr = this.getXHR();
        if (xhr.upload && file.type == "application/zip" && file.size <= document.getElementById("MAX_FILE_SIZE").value) {
            
            // create progress bar
            var o = document.getElementById("progress");
            o.innerHTML = "";
            progress = o;
            
            
            
            // progress bar
            xhr.upload.addEventListener("progress", function(e) {
                                        var pc = parseInt(100 - (e.loaded / e.total * 100));
                                        //progress.style.backgroundPosition = pc + "% 0";
                                        progress = document.getElementById("progress");
                                        progress.innerHTML = "<p>" + (100-pc) + "\%</p>";
                                        }, false);
            var popup = document.getElementById("popup1");
            popup.style.visibility = "visible";
            popup.style.opacity    = 1;
            
            // start upload
            xhr.open("POST", "courseUpload/courses/" + courseID, true);
            xhr.setRequestHeader("X-FILENAME", file.name);
            xhr.send(file);
        }
    },
    
    getXHR : function() {
        var xhr = new XMLHttpRequest();
        xhr.classOwner = this;
        xhr.onreadystatechange = function(e) {
            if (xhr.readyState == 4) {
                //progress.className = (xhr.status == 200 ? "success" : "failure");
                
                var popup = document.getElementById("popup1");
                popup.style.visibility = "hidden";
                popup.style.opacity    = 0;
                
                //console.log(xhr);
                
                this.classOwner.data = JSON.parse(xhr.response);
                
                //console.log(this.classOwner.data);
                
                if (this.classOwner.data.m_error == false) {
                    var url = document.createElement('a');
                    window.location.href = "/admin/edit-course/id/"+this.classOwner.data.m_insertID;
                    return;
                }
                else {
                    alert(this.classOwner.data.m_errorMessage);
                }
            }
        };
        
        return xhr;
    },
    
    validateCourse : function(e) {
        var xhr = this.classOwner.getXHR();
        
        var o = document.getElementById("progress");
        o.innerHTML = "";
        progress = o;

        xhr.open("POST", "course-validate/courses/" + courseID, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("");
        
    },
};

//




var onload = function() {
    new adminLMSUploadCourse();
}

window.addEventListener("load", onload);

