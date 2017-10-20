<?php
// /public/admin/images/delete.php
// deletes subject with $id

require_once('../../../private/initialize.php');
require_login();

if (!isset($_GET['id'])) {
    redirect_to(url_for('/admin/categories/index.php'));
}

$id = h($_GET['id']);
$image = find_image_by_id($id);
$thumb_url = thumb_url($image['filename']);
$thumb_size = thumb_size($image['filename']);
if (is_post_request()) {
    $result = delete_image($image);
    if ($result === true) {
        $_SESSION['message'] = "The image was deleted successfully.";
        redirect_to(url_for('/admin/albums/show.php?album_id=' . $image['album_id']));
    } else {
        // DELETE failed
        $errors = $result;
    }

}


?>

<?php $page_title = 'Delete Image'; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

<div id="content">
    <div class="row">
        <a class="back-link" href="<?= url_for('/admin/categories/index.php'); ?>">
            &laquo; Back to Image: <?= $image['filename']; ?></a>
    </div>
    <div class="row">
        <div class="image delete col-sm-9">
            <h1>Delete Image</h1>
            <?= display_errors($errors); ?>
            <p>Are you sure you want to delete this image?</p>
            <p class="item"><?= h($image['filename']); ?></p>

            <form action="<?= url_for('/admin/images/delete.php?id=' .
                h(u($image['id']))); ?>" method="post">
                <div id="operations">
                    <input type="submit" name="commit" value="Delete Image">
                </div>
            </form>
        </div>
        <div class="col-sm-3">
            <img src="<?= $thumb_url; ?>" <?= $thumb_size; ?> class="img-responsive">
        </div>
    </div>
</div>
<?php include(SHARED_PATH . '/admin_footer.php');
