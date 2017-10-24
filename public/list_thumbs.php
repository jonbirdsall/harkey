<div class="row align-items-center">
<?php while ($image = mysqli_fetch_assoc($image_set)) { ?>

    <div class="col-sm-4 align-self-center">
        <a href="<?= url_for('/images/show.php?id=' . $image['id']); ?>">
            <img src="<?= image_url('thumb_' . h(u($image['filename']))); ?>"
                alt="<?= h($image['alt_text']); ?>"
                class="thumbnail">
        </a>
    </div>
<?php } ?>
</div>
