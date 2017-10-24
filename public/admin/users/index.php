<?php require_once('../../../private/initialize.php'); ?>
<?php require_login(); ?>
<?php
    $user_set = find_all_users();
 ?>

<?php $page_title = "Users"; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

<div id="content">
    <div id="breadcrumbs">
        <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="
                    <?= url_for('/admin/index.php'); ?>
                    ">Menu</a></li>
                <li class="breadcrumb-item active" aria-current="page">Users</li>
            </ol>
        </nav>
    </div>
    <div class="users listing">
        <h1>Users</h1>

        <div class="actions">
            <a class="action" href="<?= url_for('/admin/users/new.php'); ?>">
                Add New User</a>

        </div>

        <table class="list table">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>

            <?php while ($user = mysqli_fetch_assoc($user_set)) { ?>
                <tr>
                    <td><?= h($user['id']); ?></td>
                    <td><?= h($user['username']); ?></td>
                    <td><?= h($user['last_name']); ?></td>
                    <td><?= h($user['first_name']); ?></td>
                    <td><?= h($user['email']); ?></td>
                    <td><a class="action" href="<?=
                        url_for('/admin/users/show.php?id=' .
                        h(u($user['id']))); ?>">View</a>
                     | <a class="action" href="<?=
                        url_for('/admin/users/edit.php?id=' .
                        h(u($user['id']))); ?>">Edit</a>
                     | <a class="action" href="<?=
                        url_for('/admin/users/delete.php?id=' .
                        h(u($user['id']))); ?>">Delete</a></td>
                </tr>
            <?php } // while ($admins)
                mysqli_free_result($user_set);
            ?>
        </table>
    </div>
</div>


<?php include(SHARED_PATH . '/admin_footer.php');
