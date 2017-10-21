<?php
// /public/admin/images/edit.php?id=$id
// allow editing of image data saved in database
// editable fields:
//   - album
//   - taken
//   - alt_text
//   - caption
require_once('../../../private/initialize.php');
require_login();


$id = h($_GET['id']) ?? '1';

$image = find_image_by_id($id);
$album_set = find_all_albums();
$category_set = find_all_categories();
$thumb_url = thumb_url($image['filename']);
$thumb_size = thumb_size($image['filename']);
$page_title = "Edit Image: " . h($image['filename']);


if (is_post_request()) {

// Handle form values

    $image['id'] = $id;
    $image['visible'] = h($_POST['visible']);
    $image['album_id'] = h($_POST['album_id']) ?? '';
    $image['taken'] = h($_POST['taken']) ?? '';
    $image['alt_text'] = h($_POST['alt_text']) ?? '';
    $image['caption'] = h($_POST['caption']) ?? '';

    $result = update_image($image);
    if ($result === true) {
        $_SESSION['message'] = "The image was updated successfully.";
        redirect_to(url_for('/admin/images/edit.php?id=' . $id));
    } else {
        $errors = $result;
        // var_dump($errors);
    }
}



include(SHARED_PATH . '/admin_header.php');

?>

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
    <a class="back-link" href="<?= url_for('/admin/albums/show.php?album_id=' .
        $image['album_id']); ?>">
        &laquo; Back to Album</a>

    <div class="image show">

        <h1>Image: <?= h($image['filename']); ?></h1>


        <div class="attributes row">
            <div class="col-sm-9">
                <form action="<?= url_for('/admin/images/edit.php?id=' .
                    $id); ?>" method="post">
                    <dl>
                        <dt>Filename</dt>
                        <dd><?= h($image['filename']); ?></dd>
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
                                    if ($image['album_id'] == $album['id']) {
                                        echo ' selected';
                                    }
                                    echo ">{$album['menu_name']}</option>";
                                }
                            ?>
                            </select>
                        </dd>
                    <dl>
                        <dt>Type</dt>
                        <dd><?= h($image['type']) ?? ''; ?></dd>
                    </dl>
                    <dl>
                        <script>
                        $( function() {
                            $( "#datepicker" ).datepicker();
                            $( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
                            $( "#datepicker" ).datepicker( "setDate", "<?= $image['taken']; ?>");
                        });
                        </script>
                        <dt>Date taken</dt>
                        <dd>
                            <input type="text" id="datepicker" name="taken" size="30"
                                value="<?= $image['taken'] ?? ''; ?>">
                        </dd>
                    </dl>
                    <dl>
                        <dt>Visible</dt>
                        <dd>
                            <input type="hidden" name="visible" value="0">
                            <input type="checkbox" name="visible" value="1"
                                <?php if($image['visible'] == '1') {echo 'checked';} ?>>
                        </dd>
                    </dl>
                    <dl>
                        <dt>Alt Text</dt>
                        <dd>
                            <input type="text" name="alt_text" size="50"
                                value="<?= $image['alt_text'] ?? ''; ?>">
                        </dd>
                    </dl>
                    <dl>
                        <dt>Caption Text</dt>
                        <dd>
                            <textarea cols="80" rows="20" name="caption"><?= $image['caption'] ?? ''; ?></textarea>
                        </dd>
                    </dl>
                    <div id="operations">
                        <input type="submit" value="Edit Image">
                    </div>
                </form>
            </div>
            <div class="col-sm-3">
                <img src="<?= $thumb_url; ?>" <?= $thumb_size; ?> class="img-responsive">
            </div>
        </div>
    </div>
</div>
<?php include('updateCategorySelect.php'); ?>

<?php include(SHARED_PATH . '/admin_footer.php');
