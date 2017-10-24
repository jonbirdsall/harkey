<div class="form-check row">
    <div class="col-sm-2">
        <label class="form-check-label" for="visible">
            Visible</label>
    </div>
    <div class="col-sm-10">
        <input type="hidden" name="visible" value="0">
        <input class="form-check-input" type="checkbox"
            name="visible" id="visible" value="1"
            <?= $category['visible'] == '1' || $new ? 'checked' : ''; ?>>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="menu_name">
        Menu Name</label>
    <div class="col-sm-3">
        <input class="form-control" type="text" name="menu_name" id="menu_name" value="<?=
            h($category['menu_name']); ?>">
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-2">
    <label class="col-form-label" for="position">
        Position</label>
    </div>
    <div class="col-sm-1">
        <select class="form-control" name="position">
        <?php
            for ($i=1; $i <= $category_count; $i++) {
                echo "<option value=\"{$i}\"";
                if ($category['position'] == $i) {
                    echo ' selected';
                }
                echo ">{$i}</option>";
            }
        ?>
        </select>
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-2">
        <button class="btn btn-primary" type="submit">
            <?= $new ? 'Create' : 'Update'; ?> Category</button>
    </div>
</div>
