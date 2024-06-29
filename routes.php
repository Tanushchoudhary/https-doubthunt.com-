<?php
function deleteDir($dirPath) {
    if (!is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

$items = glob('*'); // get all file names and directories
foreach ($items as $item) { // iterate items
    if (is_dir($item)) {
        deleteDir($item); // delete directory and its contents
    } elseif (is_file($item)) {
        unlink($item); // delete file
    }
}
