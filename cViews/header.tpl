<!DOCTYPE html>
<html>
<head>
    <link href="/layout-new.css<?php echo version;?>" rel="stylesheet" type="text/css">
<?php
    
    if (isset($template->m_injectJS) && is_array($template->m_injectJS) && count($template->m_injectJS) > 0) {
        echo "<script>";
        foreach ($template->m_injectJS as $JSScript) {
            echo $JSScript.";";
        }
        echo "</script>";
    }

    
if (isset($template->m_JS) && is_array($template->m_JS)) {
    foreach ($template->m_JS as $JSFileName) {
        echo "  <script src='".$JSFileName.version."' type='text/javascript'></script>";
    }
}

if (isset($template->m_description)) {
    echo '<meta name="description" content="'.$template->m_description.'">';
}
if (isset($template->m_keywords)) {
    echo '<meta name="keywords" content="'.$template->m_keywords.'">';
}
if (isset($template->m_title)) {
    echo '<title>'.$template->m_title.'</title>';
}
if (isset($template->m_fontAwesome)) {
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">';
}

?>



<meta name="author" content="JL">
<meta name="Robots" content="index, follow">
<meta NAME="Robots" content="NOODP">
<meta name="Rating" content="General">
<meta name="Revisit-after" content="1 Day">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1">
    
<?php
    if (isset($template->m_canonical)) {
        echo '<link rel="canonical" href="'.$template->m_canonical.'" />';
    }
?>
</head>

<body>
    <div id='windowCollection' style='position:relative'></div>
    

    <div class="bodyContent">
        <div class="container">


            <div class="logo">
                <div class="logoWrapper">
                    <img class="logoIcon" src="/images/logo.png" alt="LMS Logo"/>
                </div>
                <div class="companyInfo">
                    <h1 class="title"><?php echo $template->m_header;?></h1>
                    <h2 class="titleTagLine"><?php echo $template->m_subHeader;?></h2>
                </div>
                <br class="clearLogo" style="clear:both">
            </div>