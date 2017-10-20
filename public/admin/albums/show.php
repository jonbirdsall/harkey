<?php
// /public/admin/albums/show.php
require_once('../../../private/initialize.php');
require_login();
?>
<?php

    $id = $_GET['id'] ?? '1';

    $album = find_album_by_id($id);
    $category = find_category_by_id($album['category_id']);
    $page_title = 'Album: ' . h($id);

    include(SHARED_PATH . '/admin_header.php');

?>

    <div id="content">
        <a class="back-link" href="<?= url_for('/admin/categories/show.php?id=' .
            h(u($category['id']))); ?>">
            &laquo; Back to Category: <?= $category['menu_name']; ?></a>

        <div class="album show">

            <h1>Album: <?= h($album['menu_name']); ?></h1>

            <div class="attributes">
                <dl>
                    <dt>Menu Name</dt>
                    <dd><?= h($album['menu_name']); ?></dd>
                </dl>
                <dl>
                    <dt>Category</dt>
                    <dd><?= h($category['menu_name']); ?></dd>
                </dl>
                <dl>
                    <dt>Position</dt>
                    <dd><?= h($album['position']); ?></dd>
                </dl>
                <dl>
                    <dt>Visible</dt>
                    <dd><?= h($album['visible']) == 1 ? 'true' : 'false'; ?></dd>
                </dl>
            </div>
            <div id="operations">
                <a href="<?=
                    url_for('/index.php?id=' . h(u($album['id'])) . '&preview=true');
                ?>" target="_blank">Preview</a>
            </div>
        </div>
        <hr>
        <a href="<?= url_for('/admin/images/new.php?album_id=' . h(u($album['id']))); ?>">
            Upload a picture</a>
        <div class="gallery">
            <?php
                $image_set = find_images_by_album_id($album['id']);
                $image_count = count_images_by_album_id($album['id']);
            ?>
            <br>
            <table>
                <tr>
                    <?php
                    $image_number = 1;

                    $column = 1;
                    while ($image = mysqli_fetch_assoc($image_set)) {
                        $thumb_size = getimagesize(UPLOAD_PATH . 'thumb_' . $image['filename'])[3];
                        $thumb_url = IMAGE_URL . 'thumb_' . $image['filename'];

                    ?>
                    <td>
                        <a href="<?= url_for('/admin/images/show.php?id=' . $image['id']); ?>">
                            <img src="<?= $thumb_url; ?>" <?= $thumb_size; ?>>
                            <br><?= $image['filename']; ?>
                        </a>
                    </td>
                    <?php
                    if ($column < 3 && $image_number < $image_count) {
                        $column++;
                        $image_number++;
                    } elseif ($image_number < $image_count){
                        $column = 1;
                        ?>
                        </tr>
                        <tr>
                        <?php
                    } else {
                        echo "</tr>";
                    }
                    ?>

                <?php } // while $image_set ?>
            </table>
        </div>
    </div>

<?php include(SHARED_PATH . '/admin_footer.php'); ?>
