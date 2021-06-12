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
    class cControl_traininghub extends cLib_cScreenCommon {
        public function __construct($control) {
            parent::__construct($control);
            $this->m_control = $control;
        }
        // --------------------------------------------------------------------------------------------
        //
        // --------------------------------------------------------------------------------------------    
        protected function model() {
            $this->setDescription("");
            $this->setKeywords("");
        }
        // --------------------------------------------------------------------------------------------
        // /admin/index
        // --------------------------------------------------------------------------------------------
        public function EXTERNAL_index($parameters) {
            if ($this->mustBeLoggedIn()) {
                $this->setTitle("Training hub");
                $this->addModel("cTrainingHub", $this);
            }
        }
        // --------------------------------------------------------------------------------------------
        // /admin/edit-user-details
        // --------------------------------------------------------------------------------------------
        public function EXTERNAL_editUserDetails() {
            if ($this->mustBeLoggedIn()) {
                $this->setTitle("Training hub - Edit User Details");
                $this->addModel("cTrainingHubEditDetails", $this);
            }
        }
        // --------------------------------------------------------------------------------------------
        //
        // --------------------------------------------------------------------------------------------    
        private function mustBeLoggedIn() {
            if (!$this->m_control->m_sessions->isLoggedIn()) {
                header("Location: /");
                die();
            }
            return true;
        }
    }
?>