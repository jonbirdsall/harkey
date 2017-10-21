<?php
// /public/admin/users/new.php

require_once('../../../private/initialize.php');
require_login();

$user_set = find_all_users();
$user = [];
$user['username'] = '';
$user['first_name'] = '';
$user['last_name'] = '';
$user['email'] = '';
$user['password'] = '';
$user['confirm_password'] = '';

if (is_post_request()) {
    // Handle form values

    $user['username'] = $_POST['username'] ?? '';
    $user['first_name'] = $_POST['first_name'] ?? '';
    $user['last_name'] = $_POST['last_name'] ?? '';
    $user['email'] = $_POST['email'] ?? '';
    $user['password'] = $_POST['password'] ?? '';
    $user['confirm_password'] = $_POST['confirm_password'] ?? '';

    $result = insert_user($user);
    if ($result === true) {
        $new_id = mysqli_insert_id($db);
        $_SESSION['message'] = "The user was added successfully.";
        redirect_to(url_for('/admin/users/show.php?id=' . $new_id));
    } else {
        $errors = $result;
    }
} else {
    // display emtpy form
}


?>
<?php $page_title = "Add User"; ?>

<?php include(SHARED_PATH . '/admin_header.php'); ?>

<div id="content">
    <div id="breadcrumbs">
        <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="
                    <?= url_for('/admin/index.php'); ?>
                    ">Menu</a></li>
                <li class="breadcrumb-item"><a href="
                    <?= url_for('/admin/users/index.php'); ?>
                    ">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">New</li>
            </ol>
        </nav>
    </div>

    <div class="user new">
        <h1>Add User</h1>

        <?= display_errors($errors); ?>

        <form action="<?= url_for('/admin/users/new.php'); ?>" method="post">
            <?php require_once('user_form.php'); ?>
        </form>
    </div>
</div>

<?php include(SHARED_PATH . '/admin_footer.php');
