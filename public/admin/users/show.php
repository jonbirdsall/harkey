<?php require_once('../../../private/initialize.php'); ?>
<?php require_login(); ?>
<?php

    $id = h($_GET['id']) ?? '1';

    $user = find_user_by_id($id);

    $page_title = "User: " . h($user['username']);

    include(SHARED_PATH . '/admin_header.php');

?>

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
                <li class="breadcrumb-item active" aria-current="page">
                    User : <?= $user['username']; ?></li>
            </ol>
        </nav>
    </div>

    <div class="user show">

        <h1>User: <?= h($user['username']); ?></h1>

        <div class="actions">
            <a class="action" href="<?= url_for('/admin/users/edit.php?id=' .
            h(u($user['id']))); ?>">Edit</a> |
            <a class="action" href="<?= url_for('/admin/users/delete.php?id=' .
            h(u($admin['id']))); ?>">Delete</a>
        </div>

        <div class="attributes">
            <dl>
                <dt>Username</dt>
                <dd><?= h($user['username']); ?></dd>
            </dl>
            <dl>
                <dt>First Name</dt>
                <dd><?= h($user['first_name']); ?></dd>
            </dl>
            <dl>
                <dt>Last Name</dt>
                <dd><?= h($user['last_name']); ?></dd>
            </dl>
            <dl>
                <dt>Email</dt>
                <dd><?= h($user['email']); ?></dd>
            </dl>
        </div>
    </div>
</div>

<?php include(SHARED_PATH . '/admin_footer.php');
