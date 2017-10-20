<?php

require_once('../../../private/initialize.php');
// $imagefile = 'uploads/family1.jpg';
// var_dump(basename($imagefile));
?>
<?php
if (is_post_request()) {
    $image = $_FILES['image'];
    var_dump($image);
    $saved_image = UPLOAD_PATH . $image['name'];
    move_uploaded_file($image['tmp_name'], $saved_image);
    $ext = explode('.', $image['name'])[1];
    make_thumb($saved_image, 200, 150, $ext);
    $thumb_image = UPLOAD_PATH . "thumb_" . $image['name'];

?>
<img src="<?= url_for('uploads/' . $image['name']); ?>"
<?= getimagesize($saved_image)[3]; ?>
>

<img src="<?= url_for('uploads/thumb_' . $image['name']); ?>" <?= getimagesize($thumb_image)[3]; ?>>


<?php }
echo PUBLIC_PATH;
 ?>
<form enctype="multipart/form-data" action="" method="post">
<input name="image" type="file">
<input type="submit" value="Submit">
</form>
