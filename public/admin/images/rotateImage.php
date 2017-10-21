<?php

require_once('../../../private/initialize.php');
require_login();

if (is_post_request()) {
    $filename = UPLOAD_PATH . h($_POST['filename']);
    $degrees = h($_POST['degrees']);
    $thumb_filename = UPLOAD_PATH . "thumb_" . h($_POST['filename']);
    if (rotate_image($filename, $degrees)) {
        if (rotate_image($thumb_filename, $degrees)) {
            echo "success";
        } else {
            exit;
        }
    } else {
        exit;
    }
} else {
    exit;
}
 ?>
