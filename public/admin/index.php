<?php require_once('../../private/initialize.php'); ?>
<?php require_login(); ?>
<?php $page_title = 'Admin Menu'; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

        <div id="content">
            <div id="main-menu">
                <h2>Main Menu</h2>
                <ul>
                    <li><a href="<?= url_for('/admin/users/index.php');
                        ?>">Users</a></li>
                    <li><a href="<?= url_for('/admin/categories/index.php');
                        ?>">Categories</a></li>
                    <li><a href="<?= url_for('/admin/albums/index.php');
                        ?>">Albums</a></li>
                    <li><a href="<?= url_for('/admin/images/index.php');
                        ?>">Images</a></li>
                </ul>
            </div>
        </div>

<?php include(SHARED_PATH . '/admin_footer.php'); ?>
