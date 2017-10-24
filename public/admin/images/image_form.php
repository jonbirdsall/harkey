<div class="container">
    <div class="form-check row">
        <div class="col-sm-2">
            <label class="form-check-label" for="visible">Visible</label>
        </div>
        <div class="col-sm-10">
            <?php $visible = $image['visible'] ?? true; ?>
            <input type="hidden" name="visible"  value="0">
            <input class="form-check-input" type="checkbox" name="visible"
                id="visible" value="1" <?= $visible ? 'checked' : ''; ?>>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2">
        <?php if ($new) { ?>
            <label for="image-file">Image to upload</label>
        </div>
        <div class="col-sm-10">
            <input type="hidden" name="MAX_FILE_SIZE" value="3000000">
            <input class="form-control-file" id="image-file" name="image" type="file">
        <?php } else { ?>
            <label class="col-form-label" for="filename">
                Filename</label>
            </div>
            <div class="col-sm-10">
            <input type="text" readonly class="form-control-plaintext"
                id="filename" value="<?= h($image['filename']); ?>">
        <?php } ?>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-2">
            <label for="category">Category</label>
        </div>
        <div class="col-sm-4">
            <select name="category_id" class="form-control" id="category">
            <?php
                while ($category = mysqli_fetch_assoc($category_set)) {
                    if (count_albums_by_category_id($category['id']) == 0) {
                        break;
                    }
                    echo "<option value=\"{$category['id']}\"";
                    echo ">{$category['menu_name']}</option>";
                }
            ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2">
            <label for="album">Album</label>
        </div>
        <div class="col-sm-4">
            <select name="album_id" class="form-control" id="album">

            </select>
        </div>
    </div>
    <?php if (!$new) { ?>
        <div class="form-group row">
            <div class="col-sm-2">
                <label class="col-form-label" for="type">Type</label>
            </div>
            <div class="col-sm-2">
            <input type="text" readonly class="form-control-plaintext"
                id="type" value="<?= h($image['type']) ?? ''; ?>">
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <div class="col-sm-2">
            <script>
            $( function() {
                // $( "#datepicker" ).datepicker();
                $( "#datepicker" ).datepicker({
                    dateFormat: "yy-mm-dd",
                });

            });
            </script>
            <label for="datepicker">Date taken</label>
        </div>
        <div class="col-sm-2">
            <input type="date" class="form-control" id="datepicker" name="taken"
                value="<?= $image['taken'] ?? ''; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2">
            <label for="alt_text">Alt Text</label>
        </div>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="alt_text" name="alt_text"
                value="<?= $image['alt_text'] ?? ''; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2">
            <label for="caption">Caption Text</label>
        </div>
        <div class="col-sm-8">
            <textarea class="form-control" id="caption" rows="3"
                name="caption"><?= $image['caption'] ?? ''; ?></textarea>
        </div>
    </div>

    <div class="row">

        <div class="col-sm-2 offset-sm-2">
            <button type="submit" class="btn btn-primary">
                <?= $new ? 'Upload' : 'Update'; ?> Image</button>
        </div>
    </div>
</div>
