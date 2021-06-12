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
class cLib_cCourseSupport {
    function resetComplete($maskBefore, $maskAfter) {
        
        $masks = [];
        
        $mask[] = cLib_cUser::_cROLE_SUPERADMIN;
        $mask[] = cLib_cUser::_cROLE_USER;
        $mask[] = cLib_cUser::_cROLE_ADMIN;
        
        $SQL = "SELECT count(*) FROM modules WHERE roleID & ?";
        
        $countCoursesPerRole = [];
        
        foreach ($mask as $m) {
            $roleInfo = new stdClass();
            
            $wasInstalled = ($maskBefore & $m) != 0;
            $nowInstalled = ($maskAfter & $m) != 0;
            
            $dataChanged = ($wasInstalled != $nowInstalled);
            
            $roleInfo->m_dataChanged = $dataChanged;
            $roleInfo->m_wasInstalled = $wasInstalled;
            $roleInfo->m_nowInstalled = $nowInstalled;
            
            if ($dataChanged) {
                $stmt = $this->m_SQL->prepare($SQL);
                $stmt->bind_param('i', $m);
                $stmt->execute();
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();

                $roleInfo->m_count  = $count;
            }
            
            $roleInfo->m_mask   = $m;
            $countCoursesPerRole[] = $roleInfo;
        }
        
        $SQLCompleted  = "";
        $SQLCompleted .= "SELECT r.userID FROM modules m ";
        $SQLCompleted .= " LEFT JOIN CMIResults r ON r.moduleID = m.moduleID AND completedDate != 0";
        $SQLCompleted .= " LEFT JOIN users u ON u.userID = r.userID AND u.roleID & ?";
        $SQLCompleted .= " WHERE u.roleID & 15 != 0 AND m.roleID & ?";

        $SQLIncomplete = $SQLCompleted;
        
        $SQLCompleted .= " GROUP BY r.userID HAVING COUNT(*) = ?";
        $SQLIncomplete.= " GROUP BY r.userID HAVING COUNT(*) != ?";
        
        $now = date("Y-m-d H:i:s");
        
        foreach ($countCoursesPerRole as $c) {
            //
            // Get all the completed course information...
            //
            
            if ($c->m_dataChanged) {
                //
                // Mark as complete
                //
                $stmt = $this->m_SQL->prepare($SQLCompleted);
                $stmt->bind_param('iii', $c->m_mask, $c->m_mask, $c->m_count);
                $stmt->execute();
                $stmt->bind_result($userID);
                
                $userIDs = [];
                while ($stmt->fetch()) {
                    $userIDs[] = $userID;
                }
                $stmt->close();
                
                if (count($userIDs) > 0) {
                    $userIDInList = implode(",", $userIDs);
                    
                    $SQL = "UPDATE users SET dateCompleted = ? WHERE userID IN(".$userIDInList.")";
                    
                    $stmt = $this->m_SQL->prepare($SQL);
                    $stmt->bind_param("s", $now);
                    $stmt->execute();
                    $stmt->close();
                }

                //
                // Mark as incomplete
                //
                $stmt = $this->m_SQL->prepare($SQLIncomplete);
                $stmt->bind_param('iii', $c->m_mask, $c->m_mask, $c->m_count);
                $stmt->execute();
                $stmt->bind_result($userID);
                
                $userIDs = [];
                while ($stmt->fetch()) {
                    $userIDs[] = $userID;
                }
                $stmt->close();
                
                if (count($userIDs) > 0) {
                    $userIDInList = implode(",", $userIDs);
                    
                    $SQL = "UPDATE users SET dateCompleted = 0 WHERE userID IN(".$userIDInList.")";

                    $stmt = $this->m_SQL->prepare($SQL);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }
    }
}