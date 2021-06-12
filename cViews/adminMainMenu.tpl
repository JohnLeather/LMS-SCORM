<div class="contentBody page-admin-main-menu">
    <p class="prompt-user">Select what you would like to do from the options below:</p>
    
    <div class="form-wrapper">
        <div class="form-menu-content">
            <div class="form-block">
                <div class="admin-menu">
                    <ul>
                        <?php if ($template->m_userRole == "SuperAdmin") { ?>
                            <li><div><a href="/admin/upload-courses"><div class="button">Manage Courses</div></a></div></li>
                        <?php } ?>
                        <li><div><a href="/admin/view-users/page/1/order/name/in/asc"><div class="button">View / Edit Users</div></a></div></li>
                        <li><div><a href="/admin/view-module-reports/page/1/order/name/in/asc"><div class="button">View Module Reports</div></a></div></li>                                                
                        <li><div><a href="/admin/set-admin-password"><div class="button">Set Your Password</div></a></div></li>
                        <?php if ($template->m_userRole == "SuperAdmin") { ?>
                            <li><div><a href="/admin/settings"><div class="button">Set LMS Settings</div></a></div></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-wrapper">
        <div class="form-content">
            <div class="" style="padding-bottom:20px">
                <a href="/traininghub"><div class="button right-arrow-icon">Training Hub</div></a>
                <a href="/"><div class="button cross-icon gapButton">Logout</div></a>
            </div>
        </div>
    </div>
</div>