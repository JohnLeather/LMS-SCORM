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
class cLib_cPager {
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function __construct($pageInfo) {
        $this->m_rootPath = $pageInfo->rootPath;
        $this->m_URLRewrite = $pageInfo->URLRewrite;
        $this->m_currentPage = intval($pageInfo->currentPage, 10);
        $this->m_itemPerPage = intval($pageInfo->itemPerPage, 10);
        $this->m_maxItems = intval($pageInfo->maxItems, 10);
        $this->m_maxPage = $pageInfo->totalPages;
        
                
        
        $this->m_SQLLimit = "LIMIT ".$pageInfo->itemPerPage." OFFSET ".(($this->m_currentPage - 1) * $this->m_itemPerPage);
        
        $this->m_HTML = ($this->m_maxPage > 1 && $this->m_maxItems < 99999) ? $this->createPagerHTML() : "";
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    function createPagerHTML() {
        $HTML = "";
        $HTML = "<div style='margin-top:20px;margin-bottom:5px'>";
        if ($this->m_maxPage > 1) {
            if ($this->m_currentPage == 1) {
                $HTML .= "<div class='button previousPage nav-disabled' style='margin:5px 2px'>&lt;&lt;</div>";
                $HTML .= "<div class='button previousPage nav-disabled' style='margin:5px 2px'>&lt;</div>";
            }
            else {
                $HTML .= $this->getAnchor(1)."<div class='button previousPage' style='margin:5px 2px'>&lt;&lt;</div></a>";
                $HTML .= $this->getAnchor($this->m_currentPage - 1)."<div class='button previousPage' style='margin:5px 2px'>&lt;</div></a>";
            }
        }
        
        $jumpToPage = $this->m_currentPage - 2;
        
        if ($jumpToPage + 4 > $this->m_maxPage) {
            $jumpToPage = $this->m_maxPage - 4;
        }
        
        if ($jumpToPage < 1) {
            $jumpToPage = 1;
        }
        
        for ($p = 0; $p < 5 && $jumpToPage <= $this->m_maxPage; $p++) {
            $HTML .= "";
            $this->m_URLRewrite->replaceValue("page", $jumpToPage);
            
            if ($jumpToPage == $this->m_currentPage) {
                $HTML .= "<div class='button navigatePage nav-selected' style='margin:5px 2px'>".$jumpToPage."</div>";
            }
            else {
                $HTML .= $this->getAnchor($jumpToPage);
                $HTML .= "<div class='button navigatePage' style='margin:5px 2px'>".$jumpToPage."</div>";
                $HTML .= "</a>";
            }
            
            $jumpToPage++;
        }
        
        if ($this->m_maxPage > 1) {
            if ($this->m_currentPage == $this->m_maxPage) {
                $HTML .= "<div class='button nextPage nav-disabled' style='margin:5px 2px'>&gt;</div>";
                $HTML .= "<div class='button nextPage nav-disabled' style='margin:5px 2px'>&gt;&gt;</div>";
            }
            else {
                $HTML .= $this->getAnchor($this->m_currentPage + 1)."<div class='button nextPage' style='margin:5px 2px'>&gt;</div></a>";
                $HTML .= $this->getAnchor($this->m_maxPage)."<div class='button nextPage' style='margin:5px 2px'>&gt;&gt;</div></a>";
                
            }
        }
        
        $HTML .= "</div>";
        
        $this->m_URLRewrite->replaceValue("page", $this->m_currentPage);
        return $HTML;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    function getAnchor($page) {
        $this->m_URLRewrite->replaceValue("page", $page);
        return "<a class='pagerLink' href='".$this->m_rootPath.$this->m_URLRewrite->getURL()."'>";
    }
}
