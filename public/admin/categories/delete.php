<?php
// /public/damin/categories/delete.php
// deletes subject with $id

require_once('../../../private/initialize.php');
require_login();

if (!isset($_GET['id'])) {
    redirect_to(url_for('/admin/categories/index.php'));
}

$id = h($_GET['id']);
$category = find_category_by_id($id);

if (is_post_request()) {
    $result = delete_category($category);
    if ($result === true) {
        $_SESSION['message'] = "The category was deleted successfully.";
        redirect_to(url_for('/admin/categories/index.php'));
    } else {
        // DELETE failed
        $errors = $result;
    }

}


?>

<?php $page_title = 'Delete Category'; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

<div id="content">
    <a class="back-link" href="<?= url_for('/admin/categories/index.php'); ?>">
        &laquo; Back to List</a>

    <div class="category delete">
        <h1>Delete Category</h1>
        <?= display_errors($errors); ?>
        <p>Are you sure you want to delete this category?</p>
        <p class="item"><?= h($category['menu_name']); ?></p>

        <form action="<?= url_for('/admin/categories/delete.php?id=' .
            h(u($category['id']))); ?>" method="post">
            <div id="operations">
                <input type="submit" name="commit" value="Delete Category">
            </div>
        </form>
    </div>
</div>

<?php include(SHARED_PATH . '/admin_footer.php');
