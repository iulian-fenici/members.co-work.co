<?php

function _e($str){
    return htmlspecialchars($str);
}
function _eq($str){
    return htmlspecialchars($str,ENT_QUOTES);
}
?>
