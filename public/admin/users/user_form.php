<dl>
    <dt>Username</dt>
    <dd><input type="text" name="username" value="<?=
    h($user['username']); ?>"></dd>
</dl>
<dl>
    <dt>Password</dt>
    <dd><input type="password" name="password" value=""></dd>
</dl>
<dl>
    <dt>Confirm Password</dt>
    <dd><input type="password" name="confirm_password" value=""></dd>
</dl>
<p>
    Passwords should be at least 12 characters and include at least one
    uppercase letter, lowercase letter, number, and symbol.
</p>
<dl>
    <dt>First Name</dt>
    <dd><input type="text" name="first_name" value="<?=
    h($user['first_name']); ?>"></dd>
</dl>
<dl>
    <dt>Last Name</dt>
    <dd><input type="text" name="last_name" value="<?=
    h($user['last_name']); ?>"></dd>
</dl>
<dl>
    <dt>Email</dt>
    <dd><input type="text" name="email" value="<?=
    h($user['email']); ?>"></dd>
</dl>
<div id="operations">
    <input type="submit" value="Submit User">
</div>
