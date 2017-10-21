<?php
    if(!isset($page_title)) { $page_title = 'Staff Area';}
 ?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <title>THF - <?= h($page_title); ?></title>
        <meta charset="utf-8">

        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" media="all"
            href="<?= url_for('/stylesheets/admin.css'); ?>" />
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>

    <body>
        <div class="container">
            <div class="row">
                <header>
                    <h1>The Harkey Family Admin Area</h1>
                </header>
            </div>
            <div class="row">
                <navigation>
                    <ul>
                        <li>User: <?= $_SESSION['username'] ?? ''; ?></li>
                        <li><a href="<?= url_for('/admin/index.php'); ?>">Menu</a></li>
                        <li><a href="<?= url_for('/admin/users/index.php'); ?>">
                            Users</a></li>
                        <li><a href="<?= url_for('/admin/categories/index.php'); ?>">
                            Categories</a></li>
                        <li><a href="<?= url_for('/admin/albums/index.php'); ?>">
                            Albums</a></li>
                        <li><a href="<?= url_for('/admin/images/index.php'); ?>">
                            Images</a></li>
                        <li><a href="<?= url_for('/admin/logout.php'); ?>">Logout</a></li>
                    </ul>
                </navigation>
            </div>
            <div class="row">
                <?= display_session_message(); ?>
            </div>
            <div class="row">
