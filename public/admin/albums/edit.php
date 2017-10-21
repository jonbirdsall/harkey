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

$album_set = find_all_albums();
$category_set = find_all_categories();
$album_count = mysqli_num_rows($album_set);
mysqli_free_result($album_set);

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

        <form action="<?= url_for('/admin/albums/edit.php?id=' . h(u($id))); ?>"
            method="post">
            <dl>
                <dt>Category</dt>
                <dd>
                    <select name="category_id">
                    <?php
                    while ($category = mysqli_fetch_assoc($category_set)) {

                            echo "<option value=\"{$category['id']}\"";
                            if ($album['category_id'] == $category['id']) {
                                echo ' selected';
                            }
                            echo ">{$category['menu_name']}</option>";
                        }
                    ?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>Menu Name</dt>
                <dd><input type="text" name="menu_name" value="<?=
                h($album['menu_name']); ?>">
                </dd>
            </dl>
            <dl>
                <dt>Position</dt>
                <dd>
                    <select name="position">
                    <?php
                        for ($i=1; $i <= $album_count; $i++) {
                            echo "<option value=\"{$i}\"";
                            if ($album['position'] == $i) {
                                echo ' selected';
                            }
                            echo ">{$i}</option>:";
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
                        <?= $album['visible'] == '1' ? 'checked' : ''; ?>>
                </dd>
            </dl>
            <div id="operations">
                <input type="submit" value="Edit Album">
            </div>
        </form>
    </div>
</div>

<?php include(SHARED_PATH . '/admin_footer.php'); ?>
