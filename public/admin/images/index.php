<?php
// /public/admin/albums/index.php
require_once('../../../private/initialize.php');
require_login();
?>

<?php
    $image_set = image_list();
 ?>

<?php $page_title = 'Images'; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

    <div id="content">
        <div id="breadcrumbs">
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="
                        <?= url_for('/admin/index.php'); ?>
                        ">Menu</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Images</li>
                </ol>
            </nav>
        </div>
        <div class="images listing">
            <h1>Images</h1>

            <div class="actions">
                <a class="action" href="<?= url_for('/admin/images/new.php'); ?>">
                    Upload New Image</a>
            </div>
            <table class="list table">
                <tr>
                    <th>ID</th>
                    <th>Filename</th>
                    <th>Visible</th>
                    <th>Type</th>
                    <th>Taken</th>
                    <th>Album</th>
                    <th>Category</th>
                    <th>Alt Text</th>
                    <th>Caption</th>
                    <th>Actions</th>
                </tr>
            <?php
                while ($image = mysqli_fetch_assoc($image_set)) {
                    if (strlen($image['alt_text']) > 20) {
                        $alt_text = substr($image['alt_text'], 0, 17) . "...";
                    } else {
                        $alt_text = $image['alt_text'];
                    }
                    if (strlen($image['caption']) > 20) {
                        $cap_text = substr($image['caption'], 0, 17) . "...";
                    } else {
                        $cap_text = $image['caption'];
                    }
                ?>
                <tr>
                    <td><?= h($image['id']); ?></td>
                    <td><?= h($image['filename']); ?></td>
                    <td><?= $image['visible'] == 1 ? 'true' : 'false'; ?></td>
                    <td><?= h($image['type']); ?></td>
                    <td><?= h($image['taken']); ?></td>
                    <td>
                        <a href="<?= url_for('/admin/albums/show.php?album_id=' .
                        h(u($image['album_id']))); ?>"</a>
                        <?= h($image['album_name']); ?></td>
                    <td><?= h($image['category_name']); ?></td>
                    <td><?= h($alt_text); ?></td>
                    <td><?= h($cap_text); ?></td>
                    <td><a class="action" href="<?=
                        url_for('/admin/images/show.php?id=' .
                        h(u($image['id']))); ?>">View</a>
                         | <a class="action" href="<?=
                        url_for('/admin/images/edit.php?id=' .
                        h(u($image['id']))); ?>">Edit</a>
                         | <a class="action" href="<?=
                        url_for('/admin/images/delete.php?id=' .
                        h(u($image['id']))); ?>">Delete</a></td>
                </tr>


            <?php
                }
                mysqli_free_result($image_set);
             ?>
            </table>
        </div>
    </div>

<?php include(SHARED_PATH . '/admin_footer.php'); ?>
