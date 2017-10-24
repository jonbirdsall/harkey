<?php
// /public/admin/users/delete.php
// deletes admin with $id

require_once('../../../private/initialize.php');

require_login();

if (!isset($_GET['id'])) {
    redirect_to(url_for('/admin/users/index.php'));
}

$id = h($_GET['id']);
$user = find_user_by_id($id);
if (is_post_request()) {
    $result = delete_user($user);
    if ($result === true) {
        $_SESSION['message'] = "The user was deleted successfully.";
        redirect_to(url_for('/admin/users/index.php'));
    } else {
        // DELETE failed
        $errors = $result;
    }
}


?>

<?php $page_title = "Delete User"; ?>
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
                <li class="breadcrumb-item"><a href="
                    <?= url_for('/admin/users/show.php?id=' . $user['id']); ?>
                    ">User : <?= $user['username']; ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Delete</li>
            </ol>
        </nav>
    </div>

    <div class="subject delete">
        <h1>Delete User</h1>
        <?= display_errors($errors); ?>
        <p>Are you sure you want to delete this User?</p>
        <p class="item"><?= h($user['username']); ?></p>

        <form action="<?= url_for('/admin/users/delete.php?id=' .
            h(u($user['id']))); ?>" method="post">
            <div id="operations">
                <button type="submit" name="commit" class="btn btn-primary">
                    Delete User</button>
            </div>
        </form>
    </div>
</div>

<?php include(SHARED_PATH . '/admin_footer.php');
