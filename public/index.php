<?php
require_once('../private/initialize.php');
// This will be public landing page
// Will show a list of albums designated by a thumbnail of one of the
// images.  Also, navigation on the left can list albums.
$page_title = '';

if (isset($_GET['album_id'])) {
    $album_id = $_GET['album_id'];
    $page_title = $albums[$album_id]['menu_name'];
}

 ?>

 <?php include(SHARED_PATH . '/public_header.php'); ?>

 <div id="main">
    <?php include(SHARED_PATH . '/public_navigation.php'); ?>
    <div id="page">
        <?php include(SHARED_PATH . '/static_homepage.php'); ?>
    </div>
</div>
