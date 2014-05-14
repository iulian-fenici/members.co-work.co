<?php

function rmkdir($path, $mode = 0777) {
    return is_dir($path) || ( rmkdir(dirname($path), $mode) && _mkdir($path, $mode) );
}

function _mkdir($path, $mode = 0777) {
    $old = umask(0);
    $res = @mkdir($path, $mode);
    umask($old);
    return $res;
}

function _rmfiles($path) {
    $files = glob($path . '*'); // get all file names
    foreach ($files as $file){ // iterate files
        if(is_file($file))
            unlink($file); // delete file
    }
}

function deleteDirectory($dir) {
    if(!file_exists($dir))
        return true;
    if(!is_dir($dir) || is_link($dir))
        return unlink($dir);
    foreach (scandir($dir) as $item){
        if($item == '.' || $item == '..')
            continue;
        if(!deleteDirectory($dir . "/" . $item)){
            chmod($dir . "/" . $item, 0777);
            if(!deleteDirectory($dir . "/" . $item))
                return false;
        };
    }
    return rmdir($dir);
}

function prepareImages($image, $additionalPath = '') {

    $thumb = json_decode(base64_decode($image), true);
    //decode urls
    $thumb['url'] = urldecode($thumb['url']);
    //replace name and site url
    $thumb['url'] = str_replace(base_url(), '', $thumb['url']);
    $thumb['url'] = str_replace($thumb['name'], '', $thumb['url']);
    $new_abs_path = FCPATH . str_replace('temp/', '/files/', $thumb['url']);
    $new_rel_path = str_replace('/temp/', '/files/', $thumb['url']);
    if(!empty($additionalPath)){
        $new_abs_path .= $additionalPath . '/';
        $new_rel_path .= $additionalPath . '/';
    }
    rmkdir($new_abs_path);
    copy(FCPATH . $thumb['url'] . $thumb['name'], $new_abs_path . $thumb['name']);
    if(isset($thumb['thumbnail_url']) && !empty($thumb['thumbnail_url'])){
        $thumb['thumbnail_url'] = urldecode($thumb['thumbnail_url']);
        $thumb['thumbnail_url'] = str_replace(base_url(), '', $thumb['thumbnail_url']);
        $thumb['thumbnail_url'] = str_replace($thumb['name'], '', $thumb['thumbnail_url']);
        $new_abs_thumb_path = FCPATH . str_replace('/temp/', '/files/', $thumb['thumbnail_url']);
        $new_rel_thumb_path = str_replace('/temp/', '/files/', $thumb['thumbnail_url']);
        if(!empty($additionalPath)){
            $new_abs_thumb_path = str_replace('/thumbnail/', '/' . $additionalPath . '/thumbnail/', $new_abs_thumb_path);
            $new_rel_thumb_path = str_replace('/thumbnail/', '/' . $additionalPath . '/thumbnail/', $new_rel_thumb_path);
        }
        rmkdir($new_abs_thumb_path);
        copy(FCPATH . $thumb['thumbnail_url'] . $thumb['name'], $new_abs_thumb_path . $thumb['name']);
    }

    //delete tmp files
    //_rmfiles(FCPATH.$thumb['thumbnail_url']);
    //_rmfiles(FCPATH.$thumb['url']);

    return array(
        'file_name' => $thumb['name'],
        'file_type' => $thumb['type'],
        'new_abs_path' => $new_abs_path,
        'new_abs_thumb_path' => isset($new_abs_thumb_path) && !empty($new_abs_thumb_path) ? $new_abs_thumb_path : '',
        'new_rel_path' => $new_rel_path,
        'new_rel_thumb_path' => isset($new_rel_thumb_path) && !empty($new_rel_thumb_path) ? $new_rel_thumb_path : '',
    );
}

?>
