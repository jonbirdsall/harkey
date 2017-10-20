<?php require_once('../../private/initialize.php'); ?>

<?php
$errors = [];
$username = '';
$password = '';

if (is_post_request()) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validations
    if (is_blank($username)) {
        $errors[] = "Username cannot be blank.";
    }
    if (is_blank($password)) {
        $errors[] = "Password cannot be blank.";
    }
    $login_failure_msg = "Log in was unsuccessful.";
    // if there were no errors, try to log in
    if (empty($errors)) {
        $user = find_user_by_username($username);
        if ($user) {

            if (password_verify($password, $user['hashed_password'])) {
                // password matches
                log_in_user($user);
                redirect_to(url_for('/admin/index.php'));
            } else {
                // username found, but password does not match
                $errors[] = $login_failure_msg;
            }
        } else {
            // no username found
            $errors[] = $login_failure_msg;
        }
    }

}

?>

<?php $page_title = "Log in"; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

<div id="content">
     <h1>Log in</h1>

     <?= display_errors($errors); ?>

     <form action="login.php" method="post">
         Username: <br>
         <input type="text" name="username" vlaue="<?= h($username); ?>"><br>
         Password: <br>
         <input type="password" name="password" value=""><br>
         <input type="submit" name="submit" value="Submit">
     </form>

</div>

<?php include(SHARED_PATH . '/admin_footer.php'); ?>
