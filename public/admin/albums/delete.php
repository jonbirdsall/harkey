<?php
// /public/admin/albums/delete.php
// deletes subject with $id

require_once('../../../private/initialize.php');
require_login();

if (!isset($_GET['id'])) {
    redirect_to(url_for('/admin/albums/index.php'));
}

$id = h($_GET['id']);
$album = find_album_by_id($id);

if (is_post_request()) {
    $result = delete_album($album);
    if ($result === true) {
        $_SESSION['message'] = "The album was deleted successfully.";
        redirect_to(url_for('/admin/categories/show.php?id=' .
            $album['category_id']));
    } else {
        // DELETE failed
        $errors = $result;
    }

}


?>

<?php $page_title = 'Delete Album'; ?>
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
                <li class="breadcrumb-item active" aria-current="page">Delete</li>
            </ol>
        </nav>
    </div>

    <div class="album delete">
        <h1>Delete Albums</h1>
        <?= display_errors($errors); ?>
        <p>Are you sure you want to delete this album?</p>
        <p class="item"><?= h($album['menu_name']); ?></p>

        <form action="<?= url_for('/admin/albums/delete.php?id=' .
            h(u($album['id']))); ?>" method="post">
            <div id="operations">
                <input type="submit" name="commit" value="Delete Album">
            </div>
        </form>
    </div>
</div>

<?php include(SHARED_PATH . '/admin_footer.php');
