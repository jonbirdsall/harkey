<div class="albums listing">
    <?php if ($child) { ?>
        <h2>Albums</h2>
    <?php } else { ?>
        <h1>Albums</h1>
    <?php } ?>

        <div class="actions">
            <a class="action" href="<?= url_for('/admin/albums/new.php'); ?>">
                Create Album</a>
        </div>
        <div class="list">
        <table class="list table">
            <tr>
                <th>ID</th>
                <th>Position</th>
                <th>Visible</th>
                <th>Name</th>
                <th>Category</th>
                <th>Images</th>
                <th>Actions</th>

            </tr>

            <?php while ($album = mysqli_fetch_assoc($album_set)) { ?>
                <?php
                    $image_count = count_images_by_album_id($album['id']);
                ?>
                <tr>
                    <td><?= h($album['id']); ?></td>
                    <td><?= h($album['position']); ?></td>
                    <td><?= $album['visible'] == 1 ? 'true' : 'false'; ?></td>
                    <td><?= h($album['menu_name']); ?></td>
                    <?php if (isset($album['category_name'])) { ?>
                        <td><a href="<?= url_for('/admin/categories/show.php?id=' .
                            h(u($album['category_id']))); ?>">
                            <?= $album['category_name']; ?></a></td>
                    <?php } else { ?>
                        <td><?= $category['menu_name'] ?? ''; ?></td>
                    <?php } ?>
                    <td><?= h($image_count); ?></td>
                    <td><a class="action" href="<?=
                        url_for('/admin/albums/show.php?id=' .
                        h(u($album['id']))); ?>">View</a> |
                    <a class="action" href="<?=
                        url_for('/admin/albums/edit.php?id=' .
                        h(u($album['id']))); ?>">Edit</a> |
                    <a class="action" href="<?=
                        url_for('/admin/albums/delete.php?id=' .
                        h(u($album['id']))); ?>">Delete</a></td>
              </tr>
            <?php } ?>
        </table>
    </div>
