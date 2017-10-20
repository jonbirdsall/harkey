<?php
// /public/admin/users/edit.php
// update user with $id

require_once('../../../private/initialize.php');
require_login();

if (!isset($_GET['id'])) {
    redirect_to(url_for('/admin/users/index.php'));
}

$id = h($_GET['id']);
$user = [];
$user['username'] = '';
$user['password'] = '';
$user['first_name'] = '';
$user['last_name'] = '';
$user['email'] = '';

if (is_post_request()) {
    // Handle form values

    $user['id'] = $id;
    $user['username'] = h($_POST['username']) ?? '';
    $user['password'] = h($_POST['password']) ?? '';
    $user['confirm_password'] = h($_POST['confirm_password']) ?? '';
    $user['first_name'] = h($_POST['first_name']) ?? '';
    $user['last_name'] = h($_POST['last_name']) ?? '';
    $user['email'] = h($_POST['email']) ?? '';

    $result = update_user($user);
    if ($result === true) {
        $_SESSION['message'] = "The user account was updated successfully.";
        redirect_to(url_for('/admin/users/edit.php?id=' . $id));
    } else {
        $errors = $result;
    }
} else {
    $user = find_user_by_id($id);
}

?>


<?php $page_title = 'Edit User'; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

<div id="content">
    <a class="back-link" href="<?= url_for('/admin/users/index.php'); ?>">
        &laquo; Back to List</a>

    <div class="user edit">
        <h1>Edit User</h1>

        <?= display_errors($errors); ?>

        <form action="<?= url_for('/admin/users/edit.php?id=' . h(u($id))); ?>"
            method="post">
            <?php require_once('user_form.php'); ?>
        </form>
    </div>
</div>

<?php include(SHARED_PATH . '/admin_footer.php');
