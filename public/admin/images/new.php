<?php
// /public/staff/subjects/new.php
require_once('../../../private/initialize.php');
require_login();

if(!isset($_GET['album_id'])) {
    $_SESSION['message'] = "Choose a category/album to upload to.";
    redirect_to(url_for('/admin/categories/index.php'));
} else {
    $album_id = $_GET['album_id'];
}

if (is_post_request()) {
    // Handle form values
    $uploaded_image = $_FILES['image'];

    $image = [];
    $image['album_id'] = $album_id;
    $image['filename'] = $uploaded_image['name'];
    $image['tmp_name'] = $uploaded_image['tmp_name'];
    $image['type'] = getimagesize($uploaded_image['tmp_name'])['mime'];
    $image['visible'] = h($_POST['visible']);
    $image['taken'] = h($_POST['taken']) ?? '';
    $image['alt_text'] = h($_POST['alt_text']) ?? '';
    $image['caption'] = h($_POST['caption']) ?? '';

    $result = insert_image($image);

    if ($result === true) {
        $new_id = mysqli_insert_id($db);
        $_SESSION['message'] = "The image was uploaded successfully.";
        redirect_to(url_for('/admin/albums/show.php?id=' . $album_id));
    } else {
        $errors = $result;
    }


} else {
    // display empty form
}


?>
<?php $page_title = 'Create Category'; ?>

<?php include(SHARED_PATH . '/admin_header.php'); ?>

<div id="content">
    <a class="back-link" href="<?= url_for('/admin/albums/show.php?id=' .
        $album_id); ?>">&laquo; Back to Album</a>

    <div class="upload_image new">
        <h1>Upload Image</h1>

        <?= display_errors($errors); ?>

        <form enctype="multipart/form-data" action="<?=
            url_for('/admin/images/new.php?album_id=' .
            $album_id); ?>" method="post">
            <dl>
                <dt>Visible</dt>
                <dd>
                    <input type="hidden" name="visible" value="0">
                    <input type="checkbox" name="visible" value="1">
                </dd>
            </dl>
            <dl>
                <dt>Image to upload</dt>
                <dd>
                    <input type="hidden" name="MAX_FILE_SIZE" value="3000000">
                    <input name="image" type="file">
                </dd>
            </dl>
            <dl>
                <script>
                $( function() {
                    $( "#datepicker" ).datepicker();
                    $( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
                });
                </script>
                <dt>Date taken</dt>
                <dd>
                    <input type="text" id="datepicker" name="taken" size="30">
                </dd>
            </dl>
            <dl>
                <dt>Alt Text</dt>
                <dd>
                    <input type="text" name="alt_text" size="50">
                </dd>
            </dl>
            <dl>
                <dt>Caption Text</dt>
                <dd>
                    <textarea cols="80" rows="20" name="caption"></textarea>
                </dd>
            </dl>
            <div id="operations">
                <input type="submit" value="Upload Image">
            </div>
        </form>
    </div>
</div>

<?php include(SHARED_PATH . '/admin_footer.php'); ?>
