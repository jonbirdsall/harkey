<?php
    $page_id = $page_id ?? '';
    $album_id = $album_id ?? '';
?>

<navigation>
    <?php $nav_albums = $albums; ?>
    <h2>Albums</h2>
    <ul class="albums">
        <?php foreach ($nav_albums as $nav_album) { ?>
            <li class="<?php if ($nav_album['id'] == $album_id) {
                echo 'selected';
            } ?>">
                <a href="<?= url_for('index.php?album_id=' . h(u($nav_album['id']))) ?>">
                    <?= h($nav_album['menu_name']); ?>
                </a>
            </li>
        <?php } //foreach $nav_albums ?>
    </ul>
</navigation>
