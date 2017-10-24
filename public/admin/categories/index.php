<?php
// /admin/categories/index.php
require_once('../../../private/initialize.php');
require_login();
?>

<?php

    $category_set = find_all_categories();
 ?>

<?php $page_title = 'Categories'; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

    <div id="content">
        <div id="breadcrumbs">
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="
                        <?= url_for('/admin/index.php'); ?>
                        ">Menu</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Categories</li>
                </ol>
            </nav>
        </div>
        <div class="categories listing">
            <h1>Categories</h1>

            <div class="actions">
                <a class="action" href="<?= url_for('/admin/categories/new.php'); ?>">
                    Create New Category</a>
            </div>

            <table class="list table">
                <tr>
                    <th>ID</th>
                    <th>Position</th>
                    <th>Visible</th>
                    <th>Name</th>
                    <th>Albums</th>
                    <th>Actions</th>

                </tr>

                <?php while ($category = mysqli_fetch_assoc($category_set)) { ?>
                    <?php $album_count = count_albums_by_category_id($category['id']); ?>
                    <tr>
                        <td><?= h($category['id']); ?></td>
                        <td><?= h($category['position']); ?></td>
                        <td><?= $category['visible'] == 1 ? 'true' : 'false'; ?></td>
                        <td><?= h($category['menu_name']); ?></td>
                        <td><?= h($album_count); ?></td>
                        <td><a class="action" href="<?=
                            url_for('/admin/categories/show.php?id=' .
                            h(u($category['id']))); ?>">View</a> |
                        <a class="action" href="<?=
                            url_for('/admin/categories/edit.php?id=' .
                            h(u($category['id']))); ?>">Edit</a> |
                        <a class="action" href="<?=
                            url_for('/admin/categories/delete.php?id=' .
                            h(u($category['id']))); ?>">Delete</a></td>
                  </tr>
                <?php } ?>
            </table>

            <?php
                mysqli_free_result($category_set);
             ?>
        </div>
    </div>

<?php include(SHARED_PATH . '/admin_footer.php'); ?>
