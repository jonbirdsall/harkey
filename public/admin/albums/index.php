<?php
// /public/admin/albums/index.php
require_once('../../../private/initialize.php');
require_login();
?>

<?php
    $album_set = find_all_albums();
 ?>

<?php $page_title = 'Albums'; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

    <div id="content">
        <div class="albums listing">
            <h1>Albums</h1>

            <div class="actions">
                <p>Please choose a category to add albums.</p>
                <a class="action" href="<?= url_for('/admin/categories/index.php'); ?>">
                    Categories List</a> |
                <a class="action" href="<?= url_for('/admin/albums/index.php'); ?>">
                    Albums List</a>
            </div>

            <?php require_once('album_list.php'); ?>

            <?php
                mysqli_free_result($album_set);
             ?>
        </div>
    </div>

<?php include(SHARED_PATH . '/admin_footer.php'); ?>
