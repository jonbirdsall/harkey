<?php
// /public/admin/albums/new.php

require_once('../../../private/initialize.php');
require_login();

if (isset($_GET['category_id'])) {
    // came from category page, set the category
    $category_id = $_GET['category_id'];
    $category = find_category_by_id($category_id);
} else {
    $category = get_first_category();
    $category_id = $category['id'];
}

$album = [];

if (is_post_request()) {
    // Handle form values

    $album['category_id'] = h($_POST['category_id']) ?? '';
    $album['menu_name'] = h($_POST['menu_name']) ?? '';
    $album['position'] = h($_POST['position']) ?? '';
    $album['visible'] = h($_POST['visible']) ?? '';

    $result = insert_album($album);
    if ($result === true) {
        $new_id = mysqli_insert_id($db);
        $_SESSION['message'] = "The album was created successfully.";
        redirect_to(url_for('/admin/albums/show.php?id=' . $new_id));
    } else {
        $errors = $result;
    }
} else {

    $album['category_id'] = '';
    $album['menu_name'] = '';
    $album['position'] = '';
    $album['visible'] = '';
}


$album_count = count_albums_by_category_id($category_id) + 1;
$album['position'] = $album_count;


$category_set = find_all_categories();

$page_title = "Create Album"; ?>

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
                <li class="breadcrumb-item active" aria-current="page">New</li>
            </ol>
        </nav>
    </div>
    <div class="album new">
        <h1>Create Album</h1>

        <?= display_errors($errors); ?>
        <div class="container">
            <form action="" method="post">
                <?php
                    $new = true;
                    require_once('album_form.php');
                ?>
            </form>
        </div>
    </div>
</div>
<?php require_once('updatePosition.php'); ?>
<?php include(SHARED_PATH . '/admin_footer.php'); ?>
