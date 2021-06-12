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
class cLib_cManifestFile {
    //
    // load the imsmanifest.xml file
    //
    function readLMSManifestFile($data, $filePath) {
        $this->data = $data;
        
        if (!file_exists($filePath)) {
            $this->data->LMSManifestFileLoadedOK = false;
            return;
        }
        
        $this->loadIntoDOM($filePath);
        $this->data->LMSManifestFileLoadedOK = true;
        
        
        
        $this->readResourceList();
        $this->readSCO();
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    function loadIntoDOM($filePath) {
        $this->m_xmlDOM = new DomDocument;
        $this->m_xmlDOM->preserveWhiteSpace = FALSE;
        $this->m_xmlDOM->load($filePath);
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    function readResourceList() {
        $this->resourceID = [];
        $this->resources = [];
        
        $resources = $this->m_xmlDOM->getElementsByTagName('resource');
        
        $str = "";
        
        foreach ($resources as $resource) {
            $identifier = $resource->getAttribute('identifier');
            
            $this->resourceID[] = $identifier;
            
            $resourceInfo = new stdClass();
            
            $resourceInfo->identifier = $identifier;
            $resourceInfo->type = $resource->getAttribute('type');
            $resourceInfo->scormtype = $resource->getAttribute('adlcp:scormtype');
            $resourceInfo->href = $resource->getAttribute('href');
            $resourceInfo->files = [];
            $resourceInfo->dependencies = [];
            
            $files = $resource->getElementsByTagName('file');
            
            foreach ($files as $file) {
                $resourceInfo->files[] =  $file->getAttribute('href');
            }
            
            $dependencies = $resource->getElementsByTagName('dependency');
            
            foreach ($dependencies as $dependence) {
                $resourceInfo->dependencies[]   = $dependence->getAttribute('identifierref');
            }
            $this->resources[] = $resourceInfo;
        }
        
        $this->data->resources = $this->resources;
        $this->data->resourceID = $this->resourceID;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    function getSCOItemNodeValue($item, $index, $returnIfNotFound) {
    
        $node = $item->getElementsByTagName($index);
        
        if ($node->length != 0) {
            return $node->item(0)->nodeValue;
        }
        
        return $returnIfNotFound;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    function readSCO() {
        $manifest = $this->m_xmlDOM->getElementsByTagName('manifest');
        $adlcp = $manifest->item(0)->getAttribute('xmlns:adlcp');
        
        $this->data->m_adlcp = $adlcp;
        
        // arrays to store the results
        $itemData = array();
        //
        // The course title...
        //
        $courseList = $this->m_xmlDOM->getElementsByTagName('organization');
        foreach ($courseList as $courseItem) {
            $identifier = $courseItem->getAttribute('identifier');
            $this->data->m_courseName = $courseItem->getElementsByTagName('title')->item(0)->nodeValue;
        }
        
        
        // arrays to store the results
        $itemData = array();
        // get the list of resource element
        $itemList = $this->m_xmlDOM->getElementsByTagName('item');
        
        $courseIdentifier = [];
        $parent = 0;
        foreach ($itemList as $item) {
            
            // decode the resource attributes
            $identifier = $item->getAttribute('identifier');
            
            $itemData[$identifier]['title'] = $item->getElementsByTagName('title')->item(0)->nodeValue;
            
            //$itemData[$identifier]['masteryscore'] = $item->getElementsByTagNameNS($adlcp,'masteryscore')->item(0)->nodeValue;
            
            $itemData[$identifier]['masteryscore'] = $this->getSCOItemNodeValue($item, "masteryscore", "");
            $itemData[$identifier]['maxtimeallowed'] = $this->getSCOItemNodeValue($item, "maxtimeallowed", "");
            $itemData[$identifier]['datafromlms'] = $this->getSCOItemNodeValue($item, "datafromlms", "");
            $itemData[$identifier]['timelimitaction'] = $this->getSCOItemNodeValue($item, "timelimitaction", "");
            $itemData[$identifier]['prerequisites'] = $this->getSCOItemNodeValue($item, "prerequisites", "");
            
            if ($item->getAttribute('identifierref')) {
                $itemData[$identifier]['identifierref'] = $item->getAttribute('identifierref');
                $itemData[$identifier]['parent'] = $parent;
            }
            else {
                $parent = $itemData[$identifier];
            }

            $courseIdentifier[] = $identifier;
        }
        
        $this->data->m_SCOItemData = $itemData;
        
        $this->data->m_courseIdentifier = $courseIdentifier;
        
        
        // array for the results
        $SCOdata = array();
        
        // loop through the list of items
        foreach ($itemData as $identifier => $item) {
            
            //if (isset($item['identifierref'])) {
                // find the linked resource
            //    $identifierref = $item['identifierref'];
            //}
            
            // save data that we want to the output array
            $SCOdata[$identifier]['title'] = $item['title'];
        }
        $this->data->m_SCOdata = $SCOdata;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    function reportError($error) {
        $this->data->m_error = true;
        $this->data->m_errorMessage = $error;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    function validate($data) {
        $this->data = $data;
        
        if (isset($this->data->completedZip) && $this->data->completedZip === false) {
            $this->reportError("The zip file is either missing or corrupted");
            return;
        }
        
        if ($this->data->LMSManifestFileLoadedOK == false) {
            $this->reportError("imsmanifest.xml file is missing");
            return;
        }
        if (!isset($this->data->m_courseIdentifier) || count($this->data->m_courseIdentifier) == 0) {
            $this->reportError("Course Identifier is missing in imsmanifest.xml");
            return;
        }
        
        $index = $this->data->m_courseIdentifier[0];
        
        $title = $this->data->m_SCOdata[$index]['title'];

        $SQL = "SELECT shortTitle FROM modules WHERE shortTitle = ?";
        
        $stmt = $this->m_SQL->prepare($SQL);
        $stmt->bind_param('s', $title);
        $stmt->execute();
        $stmt->bind_result($moduleTitle);
        $stmt->fetch();
        $stmt->close();
       
        if ($moduleTitle !== NULL) {
            $this->reportError("The course: ".$title." already installed.");
            return;
        }
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    function installCourse($data) {
        $this->data = $data;
        //
        // Course entry already exists..., we are just going to update previous unvalidated entry
        //
        
        
        $totalModules = count($this->data->m_SCOItemData);
        
        $courseIDSanitize = intVal(cLib_cConfig::getInstance()->getData(cLib_cConfig::_CONFIG_COURSES), 10);

        
        
        $parents = [];
        
        //
        // Now we need to assign every module to this courseID
        //
        $SQL = "INSERT INTO modules (moduleTitle, shortTitle, roleID, masteryScore, maxTimeAllowed, dataFromLMS, timeLimitAction, prerequisites, courseID, URL, parentID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $SQLParent = "INSERT INTO parents (title, courseID) VALUES (?,?)";
        
        $addedAModule = false;
        
        $p = new stdClass();
        
        $p->m_title = "";
        $p->m_insertID = 0;
        
        
        foreach ($this->data->m_SCOItemData as $items) {
            if (isset($items['identifierref'])) {
                $identifierRef = $items['identifierref'];
                $found = false;
                $resources = count($this->data->resources);
                for ($i = 0; $i < $resources; $i++) {
                    $resource = $this->data->resources[$i];
                    if ($resource->identifier == $identifierRef) {
                        $found = true;
                        break;
                    }
                }
                if ($found) {
                    $parent = (!isset($items['title'])) ? $items['parent'] : $items;
                        
                    if (isset($items['title']) && strcmp($p->m_title, $items['title']) !== 0) {
                        $stmt = $this->m_SQL->prepare($SQLParent);
                        $stmt->bind_param('si', $items['title'], $courseIDSanitize);
                        $stmt->execute();
                        
                        
                        $p = new stdClass();
                        
                        $p->m_title = $items['title'];
                        $p->m_insertID = $stmt->insert_id;
                        
                        $parents[] = $p;
                        
                        $stmt->close();
                    }

                    
                    $role = cLib_cUser::_cROLE_SUPERADMIN;
                    $href = $resource->href;
                    $stmt = $this->m_SQL->prepare($SQL);
                    
                    if ($p->m_title == "") {
                        $longTitle = $items["title"];
                    }
                    else {
                        $longTitle = $this->data->m_courseName." - ".$p->m_title." - ".$items["title"];
                    }

                    $stmt->bind_param('ssisssssisi', $longTitle, $items["title"], $role, $items["masteryscore"], $items["maxtimeallowed"], $items["datafromlms"], $items["timelimitaction"], $items["prerequisites"], $courseIDSanitize, $href, $p->m_insertID);
                    $courseTitle = $items["title"];
                    $stmt->execute();
                    $this->data->m_insertID = $stmt->insert_id;
                    $stmt->close();

                    $addedAModule = true;
                }
            }
        }
        
        if ($addedAModule) {
            
            $SQL = "UPDATE courses SET courseName = ?, validated = 1, windowWidth = 800, windowHeight = 600, modules = ? WHERE courseID = ?";
        
            $stmt = $this->m_SQL->prepare($SQL);
            $stmt->bind_param('sii', $this->data->m_courseName, $totalModules, $courseIDSanitize);
            $stmt->execute();
            $stmt->close();
            
            //
            // Add new blank entry... for next upload
            //
            $SQL = "INSERT INTO courses (courseName, validated) VALUES ('undefined', 0);";
            $stmt = $this->m_SQL->prepare($SQL);
            $stmt->execute();
            $stmt->close();
            
            //
            // Update config record...
            //
            cLib_cConfig::getInstance()->writeData(cLib_cConfig::_CONFIG_COURSES, $courseIDSanitize + 1);
        }
        else {
            $this->reportError("The manifest file is probably malformed given I could not find resource for ".$identifierRef);
            return;
        }
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    function rrmdir($dir, $exceptFile) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != ".." && strcmp($object, $exceptFile) !== 0) {
                    if (is_dir($dir."/".$object))
                        $this->rrmdir($dir."/".$object, $exceptFile);
                    else
                        unlink($dir."/".$object);
                }
            }
            @rmdir($dir);
        }
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------    
    function clearDirectory($dir, $exceptFile) {
        $this->rrmdir($dir, $exceptFile);
    }
}
