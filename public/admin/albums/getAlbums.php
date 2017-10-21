<?php

require_once('../../../private/initialize.php');
require_login();

if (is_post_request()) {
    $category_id = h($_POST['category_id']);
    $album_set = find_albums_by_category_id($category_id);
    $albums = array();
    while ($album = mysqli_fetch_assoc($album_set)) {
        $albums[] = $album;
    }
    echo json_encode($albums);
}
 ?>
