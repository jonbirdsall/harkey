<?php
// /admin/albums/edit.php
require_once('../../../private/initialize.php');
require_login();

if (!isset($_GET['id'])) {
    redirect_to(url_for('/admin/albums/index.php'));
}
$id = h($_GET['id']);
$album = [];
$album['menu_name'] = '';
$album['position'] = '';
$album['visible'] = '';
$album['category_id'] = '';

if (is_post_request()) {

// Handle form values

    $album['id'] = $id;
    $album['menu_name'] = h($_POST['menu_name']) ?? '';
    $album['position'] = h($_POST['position']) ?? '';
    $album['visible'] = h($_POST['visible']) ?? '';
    $album['category_id'] = h($_POST['category_id']) ?? '';

    $result = update_album($album);
    if ($result === true) {
        $_SESSION['message'] = "The album was updated successfully.";
        redirect_to(url_for('/admin/albums/edit.php?id=' . $id));
    } else {
        $errors = $result;
        // var_dump($errors);
    }
} else {
    $album = find_album_by_id($id);
}


$category_set = find_all_categories();


?>

<?php $page_title = 'Edit Album'; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

<div id="content">
    <div id="breadcrumbs">
        <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="
                    <?= url_for('/admin/index.php'); ?>
                    ">Menu</a></li>
                <li class="breadcrumb-item"><a href="
                    <?= url_for('/admin/albums/index.php'); ?>
                    ">Albums</a></li>
                <li class="breadcrumb-item"><a href="
                    <?= url_for('/admin/albums/show.php?id=' . $album['id']); ?>
                    ">Album : <?= $album['menu_name']; ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
    <div class="album edit">
        <h1>Edit Album</h1>

        <?= display_errors($errors); ?>
        <div class="container">
            <form action="<?= url_for('/admin/albums/edit.php?id=' . h(u($id))); ?>"
                method="post">
                <?php
                    $new = false;
                    require_once('album_form.php');
                ?>
            </form>
        </div>
    </div>
</div>
<?php require_once('updatePosition.php'); ?>
<?php include(SHARED_PATH . '/admin_footer.php'); ?>
