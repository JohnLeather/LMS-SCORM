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
class cModel_cAdminValidateCourseViaAJAX extends cLib_cManifestFile {
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
        
        $JSONData->m_error = false;
        $JSONData->m_errorMessage = "";
        $course = $this->m_owner->m_URLRewrite->URLPairPart("courses");
        $this->course = $course;
        
        $this->unzipFile();
        
        $this->readLMSManifestFile($JSONData, "courses/course".$course."/imsmanifest.xml");
        
        $this->validate($JSONData);
        
        if ($JSONData->m_error === false) {
            $this->installCourse($JSONData);
        }
        
        echo json_encode($JSONData);
        die();
    }
    // --------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------
    function unzipFile() {
        $myDirectories = scandir("courses/course".$this->course."/");
        
        $zips = [];
        
        foreach ($myDirectories as $file) {
            if (strpos($file, "zip") !== false) {
                $zips[] = $file;
            }
        }
        
        if (count($zips) > 1) {
            $this->reportError("There is more than 1 zip file in courses/course".$this->course."/ and I don't know which one to unzip");
            return;
        }
        
        if (count($zips) == 1) {
            $zip = new ZipArchive;
            $zipFile = 'courses/course'.$this->course.'/'.$zips[0];
            if ($zip->open($zipFile) === TRUE) {
                $destination = 'courses/course'.$this->course.'/';
                $this->clearDirectory($destination, $zips[0]);
                $zip->extractTo($destination);
                $zip->close();
                unlink($zipFile);
            }
        }
    }
};
?>