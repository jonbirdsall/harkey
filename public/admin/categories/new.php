<?php
// /public/staff/subjects/new.php
require_once('../../../private/initialize.php');
require_login();

// find out how many categories there are to populate position select
$category_set = find_all_categories();
$category_count = mysqli_num_rows($category_set) + 1;
$category = [];
$category['menu_name'] = '';
$category['position'] = $category_count;
$category['visible'] = '1';

if (is_post_request()) {

// Handle form values

    $category['menu_name'] = $_POST['menu_name'] ?? '';
    $category['position'] = $_POST['position'] ?? '';
    $category['visible'] = $_POST['visible'] ?? '';

    $result = insert_category($category);
    if ($result === true) {
        $new_id = mysqli_insert_id($db);
        $_SESSION['message'] = "The category was created successfully.";
        redirect_to(url_for('/admin/categories/show.php?id=' . $new_id));
    } else {
        $errors = $result;
        // var_dump($errors);
    }

} else {
    // display empty form
}


?>
<?php $page_title = 'Create Category'; ?>

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
                <li class="breadcrumb-item active" aria-current="page">New</li>
            </ol>
        </nav>
    </div>

    <div class="category new">
        <h1>Create Category</h1>

        <?= display_errors($errors); ?>

        <form action="<?= url_for('/admin/categories/new.php'); ?>" method="post">
            <dl>
                <dt>Menu Name</dt>
                <dd><input type="text" name="menu_name" value="<?=
                h($category['menu_name']); ?>" ></dd>
            </dl>
            <dl>
                <dt>Position</dt>
                <dd>
                    <select name="position">
                    <?php
                        for ($i=1; $i <= $category_count; $i++) {
                            echo "<option value=\"{$i}\"";
                            if ($category['position'] == $i) {
                                echo ' selected';
                            }
                            echo ">{$i}</option>";
                        }
                    ?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>Visible</dt>
                <dd>
                    <input type="hidden" name="visible" value="0">
                    <input type="checkbox" name="visible" value="1"
                    <?= $category['visible'] == '1' ? 'checked' : ''; ?>>
                </dd>
            </dl>
            <div id="operations">
                <input type="submit" value="Create Category">
            </div>
        </form>
    </div>
</div>

<?php include(SHARED_PATH . '/admin_footer.php'); ?>
