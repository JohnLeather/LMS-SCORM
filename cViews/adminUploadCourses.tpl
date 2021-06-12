<div class="contentBody page-course-list-view">
    <p class="prompt-user">Here are a list of installed courses</p>
    
    <?php echo $template->m_message; ?>
    
    
    <div style="display:block-inline; box-sizing : border-box !important;border:solid 1px black;text-align:center;height:375px;overflow:hidden;overflow-y:scroll">
        <?php echo $template->m_listOfAvailableModules; ?>
    
        <form id="form" method="post" autocomplete="off">
            <?php echo $template->m_CSRFTokens; ?>


            <div style="text-align:left;padding:0px 10px 10px 10px">
                <p>An empty directory <b>/courses/<?php echo $template->m_courseID;?></b> was created in preparation for next course to be uploaded.</p>
                <p>You can either manually upload your course via FTP into this directory and then click Validate or you can click Upload and choose a zip file to upload which will then be automatically unzipped and Validated. The Upload button disappears once there is a manifest file present in this directory.</p>
                
                <input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="30000000" />
                <div>
                    <div id="validate" class="button right-arrow-icon">Validate</div>
                    <input class="button right-arrow-icon" type="file" id="fileselect" name="fileselect[]" accept=".zip" />
                    <div id="fileDragZone" class="fileDropZone">or drag and drop your course zip file here</div>
                </div>
                
                <div id="submitbutton">
                    <button type="submit" class="button right-arrow-icon">Upload Files</button>
                </div>
            </div>
        </form>

        <div id="popup1" class="overlay">
            <div class="popup">
                
                <div class="content">
                    Uploading... please wait
                    
                    <div id="progress"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-wrapper">
        <div class="form-content">
            <div class="button-wrapper-left" style="margin-top:20px;margin-bottom:20px">
                <a href="/admin"><div class="button left-arrow-icon">Back</div></a>
            </div>
        </div>
    </div>
</div>

