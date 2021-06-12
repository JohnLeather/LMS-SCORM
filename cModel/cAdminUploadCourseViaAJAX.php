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
class cModel_cAdminUploadCourseViaAJAX extends cLib_cManifestFile {
    function __construct($owner) {
        $this->m_owner = $owner;
        $this->m_SQL = $this->m_owner->m_control->getDB()->sql;
        
        $this->run();
    }
    // --------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------
    function run() {
        $JSONData = new stdClass();
        
        $JSONData->completedZip = false;
        $JSONData->uploadedFileOK = false;
        $JSONData->m_error = false;
        $JSONData->m_errorMessage = "";
        
        $course = $this->m_owner->m_URLRewrite->URLPairPart("courses");
        $fn = "uploadedCourse.zip";
        
        $filePath = 'courses/course'.$course.'/' . $fn;
        
        if ($fn) {
            $filePath = 'courses/course'.$course.'/' . $fn;
            
            file_put_contents($filePath, file_get_contents('php://input'));
            
            $JSONData->uploadedFileOK = true;
            
            $zip = new ZipArchive;
            if ($zip->open($filePath) === TRUE) {
                $destination = 'courses/course'.$course.'/';
                $this->clearDirectory($destination, $fn);
                
                $zip->extractTo($destination);
                $zip->close();
                unlink($filePath);
                
                $JSONData->completedZip = true;
                $this->readLMSManifestFile($JSONData, "courses/course".$course."/imsmanifest.xml");
            }
        }
        
        $this->validate($JSONData);
        if ($JSONData->m_error === false) {
            $this->installCourse($JSONData);
        }
        
        echo json_encode($JSONData);
        die();
    }
};
?>