<?php
    
function renderTitle($className, $text) {
    echo '<div class="content">';
    echo '    <div class="headlinesContainer" style="position:relative">';
//    echo '           <div class="'.$className.'">';
    echo '           <div class="'.$className.'Inside">';
//    echo '           </div>';
    echo '        <div class="headlines">';
    echo '           <h1>'.$text.'</h1>';
    echo '        </div>';
    echo '</div>';
    echo '    </div>';
    echo '</div>';
};
?>