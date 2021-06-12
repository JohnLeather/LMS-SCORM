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
class cControl_admin extends cLib_cScreenCommon {
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    public function __construct($control) {
        parent::__construct($control);
        $this->m_control = $control;
        
        if (!$control->m_sessions->isRoleSuperAdmin() && !$control->m_sessions->isRoleAdmin()) {
            $control->m_sessions->logout();
            header("Location: /");
            die();
        }
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
	protected function model() {
		$this->setTitle("Admin Account");
		$this->setDescription("");
        $this->setKeywords("");
        
        $this->setPageHeader("Administration");
	}
    // --------------------------------------------------------------------------------------------
    // /
    // --------------------------------------------------------------------------------------------
	public function EXTERNAL_index($parameters) {
        if ($this->requiresAnyAdminRights()) {
            $this->setTitle("Admin Main Menu");
            $this->addModel("cAdminMainMenu", $this);
        }
	}
    // --------------------------------------------------------------------------------------------
    // /admin/import
    // --------------------------------------------------------------------------------------------
    public function EXTERNAL_import($parameters) {
        if ($this->requireSuperAdminRights()) {
            $this->setTitle("Import");
            $this->addModel("cAdminImport", $this);
        }
    }
    // --------------------------------------------------------------------------------------------
    // /admin/upload-courses
    // --------------------------------------------------------------------------------------------
    public function EXTERNAL_uploadCourses($parameters) {
        if ($this->requireSuperAdminRights()) {
            $this->setTitle("Admin - Manage Courses");
            $this->addModel("cAdminUploadCourses", $this);
        }
    }
    // --------------------------------------------------------------------------------------------
    // /admin/edit-course/id/XXXX
    // --------------------------------------------------------------------------------------------
    public function EXTERNAL_editCourse($parameters) {
        if ($this->requireSuperAdminRights()) {
            $this->setTitle("Admin - Edit Courses");
            $this->addModel("cAdminEditCourse", $this);
        }
    }
    // --------------------------------------------------------------------------------------------
    // /admin/delete-courses/id/XXXX
    // --------------------------------------------------------------------------------------------
    public function EXTERNAL_deleteCourses($parameters) {
        if ($this->requireSuperAdminRights()) {
            $this->setTitle("Admin - Delete Courses");
            $this->addModel("cAdminDeleteCourse", $this);
        }
    }
    // --------------------------------------------------------------------------------------------
    // /admin/add-new-user
    // --------------------------------------------------------------------------------------------
    public function EXTERNAL_addNewUser($parameters) {
        if ($this->requireSuperAdminRights()) {
            $this->setTitle("Admin - Add New User");
            $this->addModel("cAdminAddNewUser", $this);
        }
    }
    // --------------------------------------------------------------------------------------------
    // /admin/view-module-reports/page/1/order/name/in/asc
    // --------------------------------------------------------------------------------------------
    public function EXTERNAL_viewModuleReports($parameters) {
        if ($this->requiresAnyAdminRights()) {
            $this->setTitle("Admin - Module Report");
            $this->m_parameters = $parameters;
            $this->addModel("cAdminViewModuleReports", $this);
        }
    }
    // --------------------------------------------------------------------------------------------
    // /admin/view-users/page/1/order/name/in/asc
    // --------------------------------------------------------------------------------------------
    public function EXTERNAL_viewUsers($parameters) {
        if ($this->requiresAnyAdminRights()) {
            $this->setTitle("Admin - View Users");
            $this->m_parameters = $parameters;
            $this->addModel("cAdminViewUsers", $this);
        }
    }
    // --------------------------------------------------------------------------------------------
    // /admin/set-admin-password
    // --------------------------------------------------------------------------------------------
    public function EXTERNAL_setAdminPassword($parameters) {
        if ($this->requiresAnyAdminRights()) {
            $this->setTitle("Admin - Set Password");
            $this->addModel("cAdminSetAdminPassword", $this);
        }
    }
    // --------------------------------------------------------------------------------------------
    // /admin/settings
    // --------------------------------------------------------------------------------------------
    public function EXTERNAL_settings($parameters) {
        if ($this->requireSuperAdminAndAdminRights()) {
            $this->setTitle("Admin - LMS Settings");
            $this->addModel("cAdminSetSettings", $this);
        }
    }
    
    // --------------------------------------------------------------------------------------------
    // /admin/edit-user/page/1/order/name/in/asc/id/XXXXX
    // --------------------------------------------------------------------------------------------
    public function EXTERNAL_editUser($parameters) {
        if ($this->requiresAnyAdminRights()) {
            $this->setTitle("Admin - Edit User");
            $this->addModel("cAdminEditAnotherUser", $this);
        }
    }
    // --------------------------------------------------------------------------------------------
    // /admin/course-upload
    // --------------------------------------------------------------------------------------------
    public function EXTERNAL_courseUpload($parameters) {
        if ($this->requireSuperAdminRights()) {
            $this->addModel("cAdminUploadCourseViaAJAX", $this);
        }
    }
    // --------------------------------------------------------------------------------------------
    // /admin/course-validate
    // --------------------------------------------------------------------------------------------
    public function EXTERNAL_courseValidate($parameters) {
        if ($this->requireSuperAdminRights()) {
            $this->addModel("cAdminValidateCourseViaAJAX", $this);
        }
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    function requireSuperAdminRights() {
        return $this->validatedRights($this->m_control->m_sessions->isRoleSuperAdmin());
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    function validatedRights($gotRights) {
        if (!$gotRights) {
            $this->setPageHeader("Page not found - 404");
            //$this->m_control->m_sessions->logout();
            $this->setView("404.tpl");
        }
        return $gotRights;
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    function requireSuperAdminAndAdminRights() {
        return $this->validatedRights($this->m_control->m_sessions->isRoleSuperAdmin() || $this->m_control->m_sessions->isRoleAdmin());
    }
    // --------------------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------------------
    function requiresAnyAdminRights() {
        return $this->validatedRights($this->m_control->m_sessions->isRoleSuperAdmin() || $this->m_control->m_sessions->isRoleAdmin());
    }
}
?>
