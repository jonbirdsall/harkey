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

        <form action="" method="post">
            <dl>
                <dt>Menu Name</dt>
                <dd><input type="text" name="menu_name" value="<?= h($album['menu_name']); ?>">
                </dd>
            </dl>
            <dl>
                <dt>Category</dt>
                <dd>
                    <select name="category_id" id="category">
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
                <dt>Position</dt>
                <dd>
                    <select name="position" id="position">
                    <?php
                        for ($i=1; $i <= $album_count; $i++) {
                            echo "<option value=\"{$i}\"";
                            if ($album['position'] == $i) {
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
                        <?= $album['visible'] == '1' ? 'checked' : ''; ?>>
                </dd>
            </dl>
            <div id="operations">
                <input type="submit" value="Create Album">
            </div>
        </form>
    </div>
</div>
<script>
$(document).ready(function(){

    $("#category").change(function(){
        var category = $(this).val();

        $.ajax({
            url: '/public/admin/albums/countAlbums.php',
            type: 'post',
            data: {category_id:category},
            dataType: 'json',
            success:function(response){

                var album_count = response + 1;

                $("#position").empty();
                for( var i = 1; i <= album_count; i++){
                    if (i = album_count) {
                        $("#position").append("<option value='"+i+"' checked>"+i+"</option>");
                    } else {
                        $("#position").append("<option value='"+i+"'>"+i+"</option>");
                    }
                }

            }
        });
    });

});
</script>
<?php include(SHARED_PATH . '/admin_footer.php'); ?>
