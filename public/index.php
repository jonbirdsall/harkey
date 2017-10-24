<?php
require_once('../private/initialize.php');
// This will be public landing page
// Will show a list of albums designated by a thumbnail of one of the
// images.  Also, navigation on the left can list albums.
$page_title = '';

// default behavior is no album selected
$album = false;
if (isset($_GET['album_id'])) {
    $album_selected = $_GET['album_id'] ?? '';
    $album = find_album_by_id($album_selected, ['visible' => true]);

    if (!$album) {
        redirect_to(url_for('index.php'));
    }
    $category_selected = $album['category_id'] ?? '';
} else {
    $category_selected = $_GET['category_id'] ?? '';
}


 ?>

 <?php include(SHARED_PATH . '/public_header.php'); ?>

 <div class="row">
    <div class="col-sm-3">
        <?php include(SHARED_PATH . '/public_navigation.php'); ?>
    </div>
    <div class="col-sm-9">

        <?php if (!$album) { ?>
            Select an album from the left to view images.
        <?php
            } else {
                $image_set = find_images_by_album_id(
                    $album['id'],
                    ['visible' => true]);
                include('list_thumbs.php');
            }


        ?>
    </div>
</div>
