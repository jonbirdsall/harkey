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
        redirect_to(url_for('/admin/images/index.php'));
    } else {
        // DELETE failed
        $errors = $result;
    }

}


?>

<?php $page_title = 'Delete Image'; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

<div id="content">
    <div id="breadcrumbs">
        <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="
                    <?= url_for('/admin/index.php'); ?>
                    ">Menu</a></li>
                <li class="breadcrumb-item"><a href="
                    <?= url_for('/admin/images/index.php'); ?>
                    ">Images</a></li>
                <li class="breadcrumb-item"><a href="
                    <?= url_for('/admin/images/show.php?id=' . $image['id']); ?>
                    ">Image : <?= $image['filename']; ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
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
