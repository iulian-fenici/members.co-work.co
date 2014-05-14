<?php

function getIconByType($fileType) {
    $fileType = str_replace('"', '', $fileType);
    $fileType = str_replace("'", '', $fileType);
    switch ($fileType) {
        case "application/msword":
        case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
        case "application/vnd.openxmlformats-officedocument.word";    
            $icon = '/img/file-types-icons/docx_mac-80_32.png';
            break;
        case "application/pdf":
            $icon = '/img/file-types-icons/pdf-80_32.png';
            break;
        case "application/zip":
        case "application/x-zip":
            $icon = '/img/file-types-icons/zip-80_32.png';
            break;
        case "application/rar":
            $icon = '/img/file-types-icons/rar-80_32.png';
            break;
        case "text/html":
            $icon = '/img/file-types-icons/html-80_32.png';
            break;
        case "text/plain":
            $icon = '/img/file-types-icons/text-80_32.png';
            break;
        case "image/vnd.adobe.photoshop":
            $icon = '/img/file-types-icons/psd-80_32.png';
            break;
        case "text/css":
            $icon = '/img/file-types-icons/css-80_32.png';
            break;

        default:
            $icon = '/img/main/no_picture.png';

            break;
    }
    return $icon;
}

?>
