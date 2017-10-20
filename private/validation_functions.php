<?php

// is_blank('abcd')
// - validate data presence
// - uses trim() so empty spaces don't count
// - uses === to avoid false positives
// - better than empty() which considers "0" to be empty
// returns boolean
function is_blank($value) {
    return !isset($value) || trim($value) === '';
}

// has_presence('abcd')
// - validate data presence
// - reverse of is_blank()
// returns boolean
function has_presence($value) {
    return !is_blank($value);
}

// has_length_greater_than('abcd', 3)
// - validate string length
// - saces count toward length
// - use trim() if spaces should not count
// returns boolean
function has_length_greater_than($value, $min) {
    $length = strlen($value);
    return $length > $min;
}

// has_length_less_than('abcd', 5)
// - validate string length
// - spaces count towards length
// - use trim() if spaces should not count
// returns boolean
function has_length_less_than($value, $max) {
    $length = strlen($value);
    return $length < $max;
}

// has_length_exactly('abcd', 4)
// - validate string length
// - spaces count towards length
// - use trim() if spaces should not count
// returns boolean
function has_length_exactly($value, $exact) {
    $length = strlen($value);
    return $length == $exact;
}

// has_length('abcd', ['min' => 3, 'max' => 5])
// - validate string length
// - combines functions _greater_than, _less_than, _exactly
// - spaces count towards length
// - user trim() if spaces should not count
// returns boolean
function has_length($value, $options) {
    if(isset($options['min']) && !has_length_greater_than($value,
        $options['min'] - 1)) {
            return false;
    } elseif (isset($options['max']) && !has_length_less_than($value,
        $options['max'] + 1)) {
            return false;
    } elseif (isset($options['exact']) && !has_length_exactly($value,
        $options['exact'])) {
            return false;
    } else {
        return true;
    }
}

// has_inclusion_of( 5, [1,3,5,7,9])
// - validate inclusion in a set
// returns boolean
function has_inclusion_of($value, $set) {
    return in_array($value, $set);
}

// has_exclusion_of( 5, [1,3,5,7,9])
// - valudate exclusion from a set
// returns boolean
function has_exclusion_of($value, $set) {
    return !in_array($value, $set);
}

// has_string('nobody@nowhere.com', '.com')
// - validate inclusion of character(s)
// - strpos returns string start position or false
// - uses !== to prevent position 0 from being considered false
// - strpos is faster than preg_match()
// returns boolean
function has_string($value, $required_string) {
    return strpos($value, $required_string) !== false;
}

// matches('password', 'password')
// - validate strings match
// returns boolean
function matches($first_string, $second_string) {
    return ($first_string === $second_string);
}

// has_valid_email_format('nobody@nowhere.com')
// - validate correct format for email addresses
// - format: [chars]@[chars].[2+ letters]
// - preg_match is helpful, uses a regular expression
//   returns 1 for a match, 0 for no match
function has_valid_email_format($value) {
    $email_regex = '/\A[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\Z/i';
    return preg_match($email_regex, $value) === 1;
}

/*** Album specific ***/

// has_unique_album_menu_name('History')
// - validate uniqueness of album.menu_name
// - for new records, provide only the menu_name.
// - for existing records, provide current ID as second argument
//   has_unique_album_menu_name('History', 4)
// returns boolean
function has_unique_album_menu_name($menu_name, $current_id='0') {
    global $db;

    $sql = "SELECT * FROM albums ";
    $sql .= "WHERE menu_name='" . db_escape($db, $menu_name) . "' ";
    $sql .= "AND id != '" . db_escape($db, $current_id) . "'";

    $album_set = mysqli_query($db, $sql);
    $album_count = mysqli_num_rows($album_set);
    mysqli_free_result($album_set);
    return $album_count === 0;
}

// has_child_images(2)
// - validate album does not have images
function has_child_images($album_id) {
    global $db;

    $sql = "SELECT * FROM images ";
    $sql .= "WHERE album_id='" . db_escape($db, $album_id) . "'";

    $image_set = mysqli_query($db, $sql);
    $image_count = mysqli_num_rows($image_set);

    return $image_count !== 0;
}

/*** Image specific ***/

// if the file already exists in the database
// we don't want to overwrite the image
function has_unique_image_filename($filename, $current_id='0') {
    global $db;

    $sql = "SELECT * FROM images ";
    $sql .= "WHERE filename='" . db_escape($db, $filename) . "' ";
    $sql .= "AND id != '" . db_escape($db, $current_id) . "'";

    $image_set = mysqli_query($db, $sql);
    $image_count = mysqli_num_rows($image_set);
    mysqli_free_result($image_set);
    return $image_count === 0;
}

// make sure the file is a jpg/gif/png
function has_image_file_type($type) {
    switch ($type) {
        case 'image/jpeg':
        case 'image/gif':
        case 'image/png':
            return true;
            break;

        default:
            return false;
            break;
    }
}

/*** Category specific ***/

// has_unique_category_menu_name('History')
// - validate uniqueness of categories.menu_name
// - for new records, provide only the menu_name.
// - for existing records, provide current ID as second argument
//   has_unique_category_menu_name('History', 4)
// returns boolean
function has_unique_category_menu_name($menu_name, $current_id='0') {
    global $db;

    $sql = "SELECT * FROM categories ";
    $sql .= "WHERE menu_name='" . db_escape($db, $menu_name) . "' ";
    $sql .= "AND id != '" . db_escape($db, $current_id) . "'";

    $category_set = mysqli_query($db, $sql);
    $category_count = mysqli_num_rows($category_set);
    mysqli_free_result($category_set);
    return $category_count === 0;
}

// has_child_albums(2)
// - validate category does not have albums
function has_child_albums($category_id) {
    global $db;

    $sql = "SELECT * FROM albums ";
    $sql .= "WHERE category_id='" . db_escape($db, $category_id) . "'";

    $album_set = mysqli_query($db, $sql);
    $album_count = mysqli_num_rows($album_set);

    return $album_count !== 0;
}

/*** User specific ***/

// has_unique_user_username('johndoe')
// - validate uniqueness of users.username
// - for new records, provide only the username.
// - for existing records, provide current ID as second argument
//   has_unique_user_username('johndoe', 4)
// returns boolean
function has_unique_user_username($username, $current_id='0') {
    global $db;

    $sql = "SELECT * FROM users ";
    $sql .= "WHERE username='" . db_escape($db, $username) . "' ";
    $sql .= "AND id != '" . db_escape($db, $current_id) . "'";

    $user_set = mysqli_query($db, $sql);
    $user_count = mysqli_num_rows($user_set);
    mysqli_free_result($user_set);
    return $user_count === 0;
}
