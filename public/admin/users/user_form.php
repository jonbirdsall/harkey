<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="username">Username</label>
    <div class="col-sm-3">
        <input type="text" class="form-control" id="username" name="username"
            value="<?= h($user['username']); ?>" size="30">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="password">Password</label>
    <div class="col-sm-3">
        <input class="form-control" type="password" name="password" id="password"
            value="">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="confirm_password">
        Confirm Password</label>
    <div class="col-sm-3">
        <input class="form-control" type="password" name="confirm_password"
            id="confirm_password" value="">
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-10">
    <p>
        Passwords should be at least 12 characters and include at least one
        uppercase letter, lowercase letter, number, and symbol.
    </p>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="first_name">First Name</label>
    <div class="col-sm-3">
        <input class="form-control" type="text" name="first_name" id="first_name"
            value="<?= h($user['first_name']); ?>">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="last_name">Last Name</label>
    <div class="col-sm-3">
        <input class="form-control" type="text" name="last_name" id="last_name"
            value="<?= h($user['last_name']); ?>">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="email">Email</label>
    <div class="col-sm-3">
        <input class="form-control" type="email" name="email" id="email"
            value="<?= h($user['email']); ?>">
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-2">
        <button type="submit" class="btn btn-primary">
            <?= $new ? 'Create' : 'Update'; ?> User</button>
    </div>
</div>
