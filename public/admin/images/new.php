<?php
// /public/admin/images/new.php
require_once('../../../private/initialize.php');
require_login();

$album_id = $_GET['album_id'] ?? '';


if (is_post_request()) {
    // Handle form values
    if (is_uploaded_file($_FILES['image']['tmp_name'])) {
        $uploaded_image = $_FILES['image'];

        $image = [];
        $image['album_id'] = h($_POST['album_id']) ?? '';
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
            redirect_to(url_for('/admin/images/index.php'));
        } else {
            $errors = $result;
        }
    } else {
        $errors[] = "Please choose file to upload.";
    }

} else {
    // display empty form
}

$category_set = find_all_categories();
$album_set = find_all_albums();

?>
<?php $page_title = 'Upload Image'; ?>

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
                <li class="breadcrumb-item active" aria-current="page">New</li>
            </ol>
        </nav>
    </div>
    <div class="upload_image new">
        <h1>Upload Image</h1>

        <?= display_errors($errors); ?>

        <form enctype="multipart/form-data" action="<?=
            url_for('/admin/images/new.php'); ?>" method="post">
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
                <dt>Category</dt>
                <dd>
                    <select name="category_id" id="category">
                    <?php
                        while ($category = mysqli_fetch_assoc($category_set)) {
                            echo "<option value=\"{$category['id']}\"";
                            echo ">{$category['menu_name']}</option>";
                        }
                    ?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>Album</dt>
                <dd>
                    <select name="album_id" id="album">
                    <?php
                        while ($album = mysqli_fetch_assoc($album_set)) {
                            echo "<option value=\"{$album['id']}\"";
                            echo ">{$album['menu_name']}</option>";
                        }
                    ?>
                    </select>
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
<?php include('updateCategorySelect.php'); ?>
<?php include(SHARED_PATH . '/admin_footer.php'); ?>
