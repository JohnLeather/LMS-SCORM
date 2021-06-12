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
class cModel_cAdminViewModuleReports {
    function __construct($owner) {
        $this->m_owner = $owner;
        $this->m_SQL = $this->m_owner->m_control->getDB()->sql;
        
        $this->run();
    }
    // --------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------
    function run() {
        
        $this->m_owner->m_URLRewrite->clearArgsIfOdd();
        
        $this->m_owner->setPageSubHeader("View Module Reports");
        
        $this->requireScoreColumnNONSanitize = cLib_cConfig::getInstance()->getData(cLib_cConfig::_CONFIG_REQUIRES_SCORE_COLUMN_ID);
        
        if (isset($_POST['action']) && $_POST['action'] == "Export to CSV") {
            $this->exportToCSV();
            die();
        }
    
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $this->fetchReport(true);
        $outputList = $this->createListInHTMLFormat();
        $outputListMobile = $this->createMobileListInHTMLFormat($this->requireScoreColumnNONSanitize == 1);
        
        
        $this->m_owner->assignTemplateVar(array("m_listMobile" => $outputListMobile));
        $this->m_owner->assignTemplateVar(array("m_list" => $outputList));
        $this->m_owner->assignTemplateVar(array("m_pager" => $this->pager->m_HTML));
        $this->m_owner->assignTemplateVar(array("m_find" => htmlEntities($this->m_owner->m_URLRewrite->URLPairPart("find"))));
        $this->m_owner->assignTemplateVar(array("m_gotScoreColumn" => $this->requireScoreColumnNONSanitize == 1));
        
        $this->m_owner->appendJS("/js/adminLMSModuleReports.js");
        $this->generateSortList($this->requireScoreColumnNONSanitize == 1);
        
        $this->m_owner->setView("adminViewModuleReports.tpl");
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    function generateSortList($gotScoreColumn) {
        $html = "";
        $html .= '<select id="sortByFilter" class="mobile-table-inline">';
        $html .= '<option value="name"'.($this->orderByText == "name" ? " selected" : "").'>Name</option>';
        $html .= '<option value="module"'.($this->orderByText == "module" ? " selected" : "").'>Module</option>';
        if ($gotScoreColumn) {
            $html .= '<option value="score"'.($this->orderByText == "score" ? " selected" : "").'>Score</option>';
        }
        $html .= '<option value="start-date"'.($this->orderByText == "start-date" ? " selected" : "").'>Start date</option>';
        $html .= '<option value="date-completed"'.($this->orderByText == "date-completed" ? " selected" : "").'>Completed date</option>';
        $html .= '</select>';
        
        $this->m_owner->assignTemplateVar(array("m_sortDropDownList"  => $html));
        
        
        $html = "";
        $html .= '<select id="orderByFilter" class="mobile-table-inline">';
        $html .= '<option value="asc"'.($this->orderByAD == "ASC" ? " selected" : "").'>Ascending</option>';
        $html .= '<option value="desc"'.($this->orderByAD == "DESC" ? " selected" : "").'>Descending</option>';
        $html .= '</select>';
        
        $this->m_owner->assignTemplateVar(array("m_orderByDropDownList"  => $html));
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    function exportToCSV() {
        $this->fetchReport(false);
        
        error_reporting(0);
        
        header('Content-Description: File Transfer');
        //header("Content-type: text/csv");
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="export-module-reports.csv"');
        header("Pragma: no-cache");
        header("Expires: 0");
        
        echo "Name,Module";
        if ($this->requireScoreColumnNONSanitize == 1) {
            echo ",Score";
        }
        echo ",Started date,Completed date\n";
        
        foreach ($this->data as $data) {
            echo $data->firstName." ".$data->lastName;
            echo ",";
            echo htmlEntities($data->moduleTitle);
            if ($this->requireScoreColumnNONSanitize == 1) {
                echo ",";
                echo $data->score;
            }
            echo ",";
            echo $data->startedDate;
            echo ",";
            echo $data->completedDate;
            echo "\n";
        }
        die();
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    function prepareReportQuery($items) {
        $bindParamString = "";
        $bindParamVars = [];
        
        $SQL  = "SELECT ".$items." FROM users u ";
        $SQL .= " LEFT JOIN CMIResults r ON r.userID = u.userID";
        $SQL .= " LEFT JOIN modules ON modules.moduleID = r.moduleID";
        $SQL .= " WHERE u.roleID & 12 AND r.startedDate is NOT NULL";
                    
        $find = $this->m_owner->m_URLRewrite->URLPairPart("find");
        if ($find != NULL) {
            $SQL .= " AND u.fullName LIKE ? ";
            $bindParamString .= "s";
            $bindParamVars[] = "%". $find . "%";
        }
        
        if ($items != "count(*)") {
            $SQL .= " ORDER BY ".$this->orderBy." ".$this->orderByAD;
            $SQL .= " ".$this->pager->m_SQLLimit;
        }
        
        $stmt = $this->m_SQL->prepare($SQL);
        
        if (count($bindParamVars) > 0) {
            $stmt->bind_param($bindParamString, ...$bindParamVars);
        }
        
        $stmt->execute();
        
        return $stmt;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    function fetchReport($imposeLimits) {
        $this->data = [];
        
        $this->pager = $this->setPager(99999);
        
        $this->validateSort();
        
        $stmt = $this->prepareReportQuery("count(*)");
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        if ($count != 0) {
            $this->pager = $this->setPager($count);
            
            if (!$imposeLimits) {
                $this->pager->m_SQLLimit = "";
            }
            
            $stmt = $this->prepareReportQuery("u.userID, u.firstName, u.lastName, u.roleID, u.dateCreated, u.dateCompleted, r.courseID, r.moduleID, r.startedDate, r.status, r.completedDate, r.score, modules.moduleTitle");
            
            $stmt->bind_result($userID, $firstName, $lastName, $roleID, $dateCreated, $dateCompleted, $courseID, $moduleID, $startedDate, $status, $completedDate, $score, $moduleTitle);
            
            
            while($stmt->fetch()) {
                $row = new stdClass();
                
                $row->userID = $userID;
                $row->firstName = $firstName;
                $row->lastName = $lastName;
                $row->roleID = $roleID;
                $row->dateCreated = $dateCreated;
                $row->dateCompleted = $dateCompleted;
                $row->courseID = $courseID;
                $row->moduleID = $moduleID;
                $row->startedDate = $startedDate == "" || $startedDate == null ? "-" : $startedDate;
                $row->status = $status;
                $row->completedDate = ($completedDate == "" || strcmp($completedDate, null) === 0) ? "-" : $completedDate;
                $row->score = $score == "" ? "-" : $score;
                $row->moduleTitle = $moduleTitle;
                
                $this->data[] = $row;
            }
            
            $stmt->close();
        }
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    function createMobileListInHTMLFormat($showScore) {
        $moduleHTML = "";
        
        $total = count($this->data);
        
        foreach ($this->data as $data) {
            $this->m_owner->m_URLRewrite->replaceValue("id", $data->userID);
            
            $Ahref = "<a class='' href='/admin/edit-user/".$this->m_owner->m_URLRewrite->getURL()."'>";
            
            $moduleHTML .= '<div class="tab-1">'.$Ahref.htmlEntities($data->firstName)." ".htmlEntities($data->lastName).'</a></div>';
            $moduleHTML .= '<div class="tab-split"><div>Module</div><div>'.htmlEntities($data->moduleTitle).'</div></div>';
            
            if ($showScore) {
                $moduleHTML .= '<div class="tab-split"><div>Score</div><div>'.$data->score.'</div></div>';
            }
            
            $moduleHTML .= '<div class="tab-split"><div>Date started</div><div>'.$data->startedDate.'</div></div>';
            $moduleHTML .= '<div class="tab-split"><div>Date completed</div><div>'.$data->completedDate.'</div></div>';
            $total--;
            if ($total != 0) {
                $moduleHTML .= '<br>';
            }
        }
        $this->m_owner->m_URLRewrite->removeParameters("id");
        
        return $moduleHTML;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    function setPager($itemsFound) {
        $itemPerPage = 10;
        $totalPages = ceil($itemsFound / $itemPerPage);
        
        $page = $this->m_owner->m_URLRewrite->URLPairPart("page");
        
        
        if ($page == NULL) {
            $page = 1;
        }
        else {
            $page = intval("".$page, 10);
            
            if ($page < 1) {
                $page = 1;
            }
            if ($page > $totalPages) {
                $page = $totalPages;
            }
        }
        
        
        $pageInfo = new stdClass();
        
        $pageInfo->currentPage = $page;
        $pageInfo->itemPerPage = $itemPerPage;
        $pageInfo->maxItems = $itemsFound;
        $pageInfo->totalPages = $totalPages;
        $pageInfo->URLRewrite = $this->m_owner->m_URLRewrite;
        $pageInfo->rootPath = $this->m_owner->m_control->parameters->getPath();
    
        $this->pager = new cLib_cPager($pageInfo);
        
        return $this->pager;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    function validateSort() {
        $page = $this->m_owner->m_URLRewrite->URLPairPart("page");
        $templateOrderName = "";
        $templateOrderModule = "";
        $templateOrderScore = "";
        $templateOrderStartDate = "";
        $templateOrderCompleted = "";

        $this->orderBy = "u.firstName";
        $this->orderByAD = "ASC";
        
        $orderASCorDESC = $this->m_owner->m_URLRewrite->URLPairPart("in");
        
        if ($orderASCorDESC != null) {
            switch ($orderASCorDESC) {
                case "asc":
                    $this->orderByAD = "ASC";
                    break;
                    
                case "desc":
                    $this->orderByAD = "DESC";
                    break;
            }
        }
        
        $orderBy = $this->m_owner->m_URLRewrite->URLPairPart("order");
        $orderArrow = $this->orderByAD == "ASC" ? "order-down" : "order-up";
        
        $this->orderByText = "";
        
        if ($orderBy != null) {
            switch ($orderBy) {
                case "module":
                    $this->orderBy = "modules.moduleTitle";
                    $templateOrderModule  = $orderArrow;
                    break;
                
                case "name":
                    $this->orderBy = "u.firstName";
                    $templateOrderName  = $orderArrow;
                    break;
                                            
                case "score":
                    $this->orderBy = "r.scoreRaw";
                    $templateOrderScore  = $orderArrow;
                    break;
                    
                case "start-date":
                    $this->orderBy = "r.startedDate";
                    $templateOrderStartDate = $orderArrow;
                    break;
                    
                case "completed-date":
                    $this->orderBy = "r.completedDate";
                    $templateOrderCompleted = $orderArrow;
                    break;
            }
            if (isset($this->orderBy)) {
                $this->orderByText = $orderBy;
            }
        }
        
        if ($this->requireScoreColumnNONSanitize == 0) {
            $templateOrderModule .= " h-4-wide";
        }
        
        
        $this->m_owner->assignTemplateVar(array("m_orderName" => $templateOrderName));
        $this->m_owner->assignTemplateVar(array("m_orderModule" => $templateOrderModule));
        $this->m_owner->assignTemplateVar(array("m_orderScore" => $templateOrderScore));
        $this->m_owner->assignTemplateVar(array("m_orderStartDate" => $templateOrderStartDate));
        $this->m_owner->assignTemplateVar(array("m_orderCompleted" => $templateOrderCompleted));
        
        //
        // Sort out the links...
        //
        
        $templateOrderNameLink = $this->createAnchor("name", $templateOrderName != "" && $this->orderByAD == "ASC" ? "desc" : "asc");
        $templateOrderModuleLink = $this->createAnchor("module", $templateOrderModule != "" && $this->orderByAD == "ASC" ? "desc" : "asc");
        $templateOrderScoreLink = $this->createAnchor("score", $templateOrderScore != "" && $this->orderByAD == "ASC" ? "desc" : "asc");
        $templateOrderStartDateLink = $this->createAnchor("start-date", $templateOrderStartDate != "" && $this->orderByAD == "ASC" ? "desc" : "asc");
        $templateOrderCompletedLink = $this->createAnchor("completed-date", $templateOrderCompleted != "" && $this->orderByAD == "ASC" ? "desc" : "asc");
        
        $this->m_owner->assignTemplateVar(array("m_orderNameLink" => $templateOrderNameLink));
        $this->m_owner->assignTemplateVar(array("m_orderModuleLink" => $templateOrderModuleLink));
        $this->m_owner->assignTemplateVar(array("m_orderScoreLink" => $templateOrderScoreLink));
        $this->m_owner->assignTemplateVar(array("m_orderStartDateLink" => $templateOrderStartDateLink));
        $this->m_owner->assignTemplateVar(array("m_orderCompletedLink" => $templateOrderCompletedLink));
        
        $this->m_owner->m_URLRewrite->replaceValue("page", $page);
        $this->m_owner->m_URLRewrite->replaceValue("order", $orderBy);
        $this->m_owner->m_URLRewrite->replaceValue("in", $orderASCorDESC);
    }
    // --------------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------------
    function createAnchor($order, $ascDesc) {
        $this->m_owner->m_URLRewrite->replaceValue("page", 1);
        $this->m_owner->m_URLRewrite->replaceValue("order", $order);
        $this->m_owner->m_URLRewrite->replaceValue("in", $ascDesc);
        
        return "<a class='' href='".$this->m_owner->m_control->parameters->getPath().$this->m_owner->m_URLRewrite->getURL()."'>";
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    function createListInHTMLFormat() {
        $moduleHTML = "";
        
        $total = count($this->data);
        
        $moduleClass = ($this->requireScoreColumnNONSanitize == 0) ? " h-4-wide" : "";
        
        
        foreach ($this->data as $data) {
            $moduleHTML .= '<div class="'. ($total > 1 ? "tableRow" : "tableBottom").'">';
            $moduleHTML .= '<div class="h-1">'.htmlEntities($data->firstName)." ".htmlEntities($data->lastName).'</div>';
            $moduleHTML .= '<div class="h-4'.$moduleClass.'">'.htmlEntities($data->moduleTitle).'</div>';
            
            if ($this->requireScoreColumnNONSanitize == 1) {
                $moduleHTML .= '<div class="h-5">'.$data->score.'</div>';
            }
            
            $moduleHTML .= '<div class="h-6">'.$data->startedDate.'</div>';
            $moduleHTML .= '<div class="h-7">'.$data->completedDate.'</div>';
            $moduleHTML .= '</div>';
            $total--;
        }
        return $moduleHTML;
    }
};
?>
