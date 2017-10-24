<div class="form-check row">
    <div class="col-sm-2">
        <label class="form-check-label" for="visible">
            Visible</label>
    </div>
    <div class="col-sm-10">
        <input type="hidden" name="visible" value="0">
        <input class="form-check-input" type="checkbox"
            name="visible" id="visible" value="1"
            <?= $album['visible'] == '1' || $new ? 'checked' : ''; ?>>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="menu_name">
        Menu Name</label>
    <div class="col-sm-3">
        <input class="form-control" type="text" name="menu_name" id="menu_name" value="<?=
            h($album['menu_name']); ?>">
    </div>
</div>
<div class="row">
    <div class="col-sm-2">
        <label for="category">Category</label>
    </div>
    <div class="col-sm-3">
        <select name="category_id" class="form-control" id="category">
        <?php
            $i = 1;

            while ($category = mysqli_fetch_assoc($category_set)){
                echo "<option value=\"{$category['id']}\"";
                if ($category['id'] == $album['category_id']) {
                    echo "selected";
                }
                echo ">{$category['menu_name']}</option>";
                if ($i == 1) {$category_id = $category['id'];}
            }
        ?>
        </select>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="position">
        Position</label>
        <div class="col-sm-1">
            <select class="form-control" name="position" id="position">

            </select>
        </div>
</div>

<div class="form-group row">
    <div class="col-sm-2">
        <button class="btn btn-primary" type="submit">
            <?= $new ? 'Create' : 'Update'; ?> Album</button>
    </div>
</div>
