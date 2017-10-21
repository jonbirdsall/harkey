<?php require_once('../../../private/initialize.php'); ?>
<?php require_login(); ?>
<?php

    $id = h($_GET['id']) ?? '1';

    $image = find_image_by_id($id);
    $thumb_url = IMAGE_URL . 'thumb_' . $image['filename'];
    $thumb_size = getimagesize(UPLOAD_PATH . 'thumb_' . $image['filename'])[3];
    $page_title = "Image: " . h($image['filename']);

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
                    <?= url_for('/admin/images/index.php'); ?>
                    ">Images</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    Image : <?= $image['filename']; ?></li>
            </ol>
        </nav>
    </div>
    <div class="image show">

        <h1>Image: <?= h($image['filename']); ?></h1>

        <div class="actions">
            <a class="action" href="<?= url_for('/admin/images/edit.php?id=' .
            h(u($image['id']))); ?>">Edit</a> |
            <a class="action" href="<?= url_for('/admin/images/delete.php?id=' .
            h(u($image['id']))); ?>">Delete</a>
        </div>

        <div class="attributes row">
            <div class="col-sm-9">
                <dl>
                    <dt>Filename</dt>
                    <dd><?= h($image['filename']); ?></dd>
                </dl>
                <dl>
                    <dt>Type</dt>
                    <dd><?= h($image['type']); ?></dd>
                </dl>
                <dl>
                    <dt>Taken</dt>
                    <dd><?= h($image['taken']); ?></dd>
                </dl>
                <dl>
                    <dt>Visible</dt>
                    <dd><?= h($image['visible']) == 1 ? 'true' : 'false'; ?></dd>
                </dl>
                <dl>
                    <dt>Alt Text</dt>
                    <dd><?= h($image['alt_text']); ?></dd>
                </dl>
                <dl>
                    <dt>Caption</dt>
                    <dd><?= h($image['caption']); ?></dd>
                </dl>
            </div>
            <div class="col-sm-3">
                <img src="<?= $thumb_url; ?>" <?= $thumb_size; ?> class="img-responsive">
            </div>
        </div>
    </div>
</div>

<?php include(SHARED_PATH . '/admin_footer.php');
