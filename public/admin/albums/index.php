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
        <div id="breadcrumbs">
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="
                        <?= url_for('/admin/index.php'); ?>
                        ">Menu</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Albums</li>
                </ol>
            </nav>
        </div>
        <?php require_once('album_list.php'); ?>



    </div>
<?php
    mysqli_free_result($album_set);
?>
<?php include(SHARED_PATH . '/admin_footer.php'); ?>
