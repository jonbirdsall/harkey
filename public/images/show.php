<?php
require_once('../../private/initialize.php');

if (isset($_GET['id'])) {
    $image_id = $_GET['id'] ?? '';
} else {
    redirect_to(url_for(index.php));
}

$image = find_image_by_id($image_id);
$album = find_album_by_id($image['album_id']);
$category = find_category_by_id($album['category_id']);
$readable_date = date('F d, Y', strtotime($image['taken']));
if (!$image) {
    $_SESSION['message'] = "The image was not found.";
    redirect_to(url_for(index.php));
}


?>

<?php include(SHARED_PATH . '/public_header.php'); ?>

<div class="row">
    <div class="col-sm-3">
        <?php include(SHARED_PATH . '/public_navigation.php'); ?>
    </div>
    <div class="col-sm-9">
        <div class="row">
        <img src="<?= image_url($image['filename']); ?>"
            alt="<?= $image['alt_text']; ?>"
            class="img-responsive">
        </div>
        <div class="row">
            <div class="col-sm-4">
                Category: <?= $category['menu_name']; ?><br>
                Album: <?= $album['menu_name']; ?><br>
                Taken: <?= $readable_date; ?>
            </div>
            <div class="col-sm-8">
                Caption: <?= $image['caption']; ?>
            </div>
        </div>
    </div>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>
