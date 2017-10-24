<?php
// /admin/categories/edit.php
require_once('../../../private/initialize.php');
require_login();

if (!isset($_GET['id'])) {
    redirect_to(url_for('/admin/categories/index.php'));
}
$id = h($_GET['id']);
$category = find_category_by_id($id);

if (is_post_request()) {

// Handle form values

    $category['id'] = $id;
    $category['menu_name'] = h($_POST['menu_name']) ?? '';
    $category['position'] = h($_POST['position']) ?? '';
    $category['visible'] = h($_POST['visible']) ?? '';


    $result = update_category($category);
    if ($result === true) {
        $_SESSION['message'] = "The category was updated successfully.";
        redirect_to(url_for('/admin/categories/edit.php?id=' . $id));
    } else {
        $errors = $result;
        // var_dump($errors);
    }
} else {

}


$category_set = find_all_categories();
$category_count = mysqli_num_rows($category_set);
mysqli_free_result($category_set);

?>

<?php $page_title = 'Edit Category'; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

<div id="content">
    <div id="breadcrumbs">
        <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="
                    <?= url_for('/admin/index.php'); ?>
                    ">Menu</a></li>
                <li class="breadcrumb-item"><a href="
                    <?= url_for('/admin/categories/index.php'); ?>
                    ">Categories</a></li>
                <li class="breadcrumb-item"><a href="
                    <?= url_for('/admin/categories/show.php?id=' . $category['id']); ?>
                    ">Category : <?= $category['menu_name']; ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>

    <div class="category edit">
        <h1>Edit Category</h1>

        <?= display_errors($errors); ?>
        <div class="container">
            <form action="<?= url_for('/admin/categories/edit.php?id=' . h(u($id))); ?>"
                method="post">

                <?php
                    $new = false;
                    require_once('category_form.php');
                ?>

            </form>
        </div>
    </div>
</div>

<?php include(SHARED_PATH . '/admin_footer.php'); ?>
