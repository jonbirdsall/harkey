<?php
// /admin/categories/show.php
require_once('../../../private/initialize.php');
require_login();
?>

<?php

    $id = h($_GET['id']) ?? '1';

    $category = find_category_by_id($id);

    $page_title = 'Category: ' . h($category['menu_name']);

    include(SHARED_PATH . '/admin_header.php');

?>

    <div id="content">
        <a class="back-link" href="<?= url_for('/admin/categories/index.php'); ?>">
            &laquo; Back to List</a>

        <div class="category show">

            <h1>Category: <?= h($category['menu_name']); ?></h1>

            <div class="attributes">
                <dl>
                    <dt>Menu Name</dt>
                    <dd><?= h($category['menu_name']); ?></dd>
                </dl>
                <dl>
                    <dt>Position</dt>
                    <dd><?= h($category['position']); ?></dd>
                </dl>
                <dl>
                    <dt>Visible</dt>
                    <dd><?= h($category['visible']) == 1 ? 'true' : 'false'; ?></dd>
                </dl>
            </div>

            <hr>
            <?php $album_set = find_albums_by_category_id($category['id']); ?>
            <div class="pages listing">
                <h2>Albums</h2>

                <div class="actions">
                    <a class="action" href="<?= url_for('/admin/albums/new.php?category_id=' . $id); ?>">
                        Create New Album</a>
                </div>
                <?php require_once('../albums/album_list.php'); ?>


                <?php mysqli_free_result($album_set); ?>
            </div>
        </div>
    </div>

<?php include(SHARED_PATH . '/admin_footer.php'); ?>
