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

    <div class="image show">

        <h1>Image: <?= h($image['filename']); ?></h1>

        <?= display_errors($errors); ?>
        <div class="container">
        <div class="row">
            <div class="col-sm-9">
                <form action="<?= url_for('/admin/images/edit.php?id=' .
                    $id); ?>" method="post">
                    <?php
                        $new = false;
                        require_once('image_form.php');
                    ?>
                </form>
            </div>
            <div class="col-sm-3">
                <img src="<?= $thumb_url; ?>"
                    class="img-responsive" id="thumbnail"><br>
                <img src="reply.svg" id="countercw" width="20" height="20">
                <img src="forward.svg" id="cw" width="20" height="20">
            </div>
        </div>
        </div>
    </div>
</div>
<?php include(SHARED_PATH . '/updateCategorySelect.php'); ?>
<script>
$(document).ready(function(){

    $("#countercw").click(function(){
        var degrees = 90;
        var filename = "<?= $image['filename']; ?>";
        $.ajax({
            url: '/public/admin/images/rotateImage.php',
            type: 'post',
            data: {filename:filename, degrees:degrees},
            dataType: 'text',
            success:function(response){

                $("#thumbnail").attr('src', $("#thumbnail").attr('src') + '?' + Math.random() );
            }
        });
    });
    $("#cw").click(function(){
        var degrees = -90;
        var filename = "<?= $image['filename']; ?>";
        $.ajax({
            url: '/public/admin/images/rotateImage.php',
            type: 'post',
            data: {filename:filename, degrees:degrees},
            dataType: 'text',
            success:function(response){

                $("#thumbnail").attr('src', $("#thumbnail").attr('src') + '?' + Math.random() )
            }
        });
    });

});
</script>

<?php include(SHARED_PATH . '/admin_footer.php');
