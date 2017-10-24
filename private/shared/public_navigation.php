<?php



    // get the list of visible categories
    $nav_category_set = find_all_categories(['visible' => true]);
?>

<navigation>

    <h2>Albums</h2>
    <ul id="categories">
        <?php
            while ($nav_category = mysqli_fetch_assoc($nav_category_set)) {
                if (count_albums_by_category_id($nav_category['id']) == 0) {
                    continue;
                }
        ?>

            <li class="<?php if ($nav_category['id'] == $category_selected) {
                echo 'selected';
            } ?>">
                <a href="<?= url_for('index.php?category_id=' .
                    h(u($nav_category['id']))); ?>">
                    <?= h($nav_category['menu_name']); ?>
                </a>
                <ul id="albums">
                    <?php $nav_album_set = find_albums_by_category_id(
                        $nav_category['id'],
                        ['visible' => true]
                    );
                    while ($nav_album = mysqli_fetch_assoc($nav_album_set)) { ?>
                        <li class="<?php if ($nav_album['id'] == $album_selected) {
                            echo 'selected';
                        } ?>">
                            <a href="<?= url_for('index.php?album_id=' .
                                h(u($nav_album['id'])) . '&category_id=' .
                                h(u($nav_album['category_id']))); ?>">
                                <?= h($nav_album['menu_name']); ?>
                            </a>
                        </li>
                    <?php
                        } //while $album_set
                        mysqli_free_result($nav_album_set);
                    ?>
                </ul>
            </li>
        <?php
            } //while $category_set
            mysqli_free_result($nav_category_set);
        ?>
    </ul>
</navigation>
