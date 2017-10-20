<?php require_once('../../../private/initialize.php'); ?>
<?php require_login(); ?>
<?php

    $id = h($_GET['id']) ?? '1';

    $admin = find_admin_by_id($id);

    $page_title = "Admin: " . h($admin['username']);

    include(SHARED_PATH . '/staff_header.php');

?>

<div id="content">
    <a class="back-link" href="<?= url_for('/staff/admins/index.php'); ?>">
        &laquo; Back to List</a>

    <div class="admin show">

        <h1>Admin: <?= h($admin['username']); ?></h1>

        <div class="actions">
            <a class="action" href="<?= url_for('/staff/admins/edit.php?id=' .
            h(u($admin['id']))); ?>">Edit</a>
            <a class="action" href="<?= url_for('/staff/admins/delete.php?id=' .
            h(u($admin['id']))); ?>">Delete</a>
        </div>

        <div class="attributes">
            <dl>
                <dt>Username</dt>
                <dd><?= h($admin['username']); ?></dd>
            </dl>
            <dl>
                <dt>First Name</dt>
                <dd><?= h($admin['first_name']); ?></dd>
            </dl>
            <dl>
                <dt>Last Name</dt>
                <dd><?= h($admin['last_name']); ?></dd>
            </dl>
            <dl>
                <dt>Email</dt>
                <dd><?= h($admin['email']); ?></dd>
            </dl>
        </div>
    </div>
</div>

<?php include(SHARED_PATH . '/staff_footer.php');
