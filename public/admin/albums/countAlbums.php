<?php

require_once('../../../private/initialize.php');
require_login();

if (is_post_request()) {
    $category_id = h($_POST['category_id']);
    $album_count = count_albums_by_category_id($category_id);

    echo $album_count;
}
 ?>
