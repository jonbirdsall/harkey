<?php
/** ALL **/

// Update positions when changes are made to table containing a position
// best used after successful update/insert/delete query
// $start_pos is 0 for new items
// $end_pos is 0 when deleting items
// $current_id necessary when changing item position
function shift_positions($table, $start_pos, $end_pos, $current_id=0){
    global $db;

    if ($start_pos == $end_pos) { return; }

    $sql = "UPDATE $table ";
    if ($start_pos == 0) {
        // new item, +1 to items greater than $end_pos
        $sql .= "SET position = position + 1 ";
        $sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
    } elseif ($end_pos == 0) {
        // delete item, -1 from items greater than $start_pos
        $sql .= "SET position = position - 1 ";
        $sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
    } elseif ($start_pos < $end_pos) {
        // move later, -1 from items between (including $end_pos)
        $sql .= "SET position = position - 1 ";
        $sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
        $sql .= "AND position <= '" . db_escape($db, $end_pos) . "' ";
    } elseif ($start_pos > $end_pos) {
        // move earlier, +1 to items between (including $end_pos)
        $sql .= "SET position = position + 1 ";
        $sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
        $sql .= "AND position < '" . db_escape($db, $start_pos) . "' ";
    }
    // Exclude the current_id in SQL WHERE clause
    $sql .= "AND id != '" . db_escape($db, $current_id) ."'";

    $result = mysqli_query($db, $sql);

    if ($result) {
        return true;
    } else {
        // UPDATE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

/** USERS **/

// returns mysqli result set of all users
function find_all_users() {
    global $db;

    $sql = "SELECT * FROM users ";
    $sql .= "ORDER BY last_name ASC, first_name ASC";
    $user_set = mysqli_query($db, $sql);
    confirm_result_set($user_set);
    return $user_set;
}

// returns assoc array of user with $id
function find_user_by_id($id) {
    global $db;

    $sql = "SELECT * FROM users ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $user = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $user;
}

// returns assoc array of user with $username
function find_user_by_username($username) {
    global $db;

    $sql = "SELECT * FROM users ";
    $sql .= "WHERE username='" . db_escape($db, $username) . "' ";
    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $user = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $user;
}

// validate user meets certain criteria
// returns an array containing strings of any errors encountered
// if all validation passes, $errors[] will be empty
function validate_user($user, $options=[]) {
    $errors = [];
    $password_required = $options['password_required'] ?? true;
    // username
    if (is_blank($user['username'])) {
        $errors[] = "Username cannot be blank.";
    } elseif (!has_length($user['username'], ['min' => 2, 'max' => 255])) {
        $errors[] = "Username must be between 2 and 255 characters.";
    }
    $current_id = $user['id'] ?? '0';
    if (!has_unique_user_username($user['username'], $current_id)) {
        $errors[] = "Username must be unique.";
    }

    // first_name
    if (is_blank($user['first_name'])) {
        $errors[] = "First Name cannot be blank.";
    } elseif (!has_length($user['first_name'], ['min' => 2, 'max' => 255])) {
        $errors[] = "First Name must be between 2 and 255 characters.";
    }

    // last_name
    if (is_blank($user['last_name'])) {
        $errors[] = "Last Name cannot be blank.";
    } elseif (!has_length($user['last_name'], ['min' => 2, 'max' => 255])) {
        $errors[] = "Last Name must be between 2 and 255 characters.";
    }

    // last_name
    if (is_blank($user['last_name'])) {
        $errors[] = "Last Name cannot be blank.";
    } elseif (!has_length($user['last_name'], ['min' => 2, 'max' => 255])) {
        $errors[] = "Last Name must be between 2 and 255 characters.";
    }

    // email
    if (is_blank($user['email'])) {
        $errors[] = "Email cannot be blank.";
    } elseif (!has_length($user['last_name'], ['min' => 2, 'max' => 255])) {
        $errors[] = "Email must be between 2 and 255 characters.";
    }
    if (!has_valid_email_format($user['email'])) {
        $errors[] = "Email must match the format 'user@domain.tld'";
    }

    // passwords
    if ($password_required) {
        if (is_blank($user['password'])) {
            $errors[] = "Password cannot be blank.";
        } else {
            if (!has_length($user['password'], array('min' => 12))) {
                $errors[] = "Password must contain 12 or more characters.";
            }
            if (!preg_match('/[A-Z]/', $user['password'])) {
                $errors[] = "Password must contain at least 1 uppercase letter.";
            }
            if (!preg_match('/[a-z]/', $user['password'])) {
                $errors[] = "Password must contain at least 1 lowercase letter.";
            }
            if (!preg_match('/[0-9]/', $user['password'])) {
                $errors[] = "Password must contain at least 1 number.";
            }
            if (!preg_match('/[^A-Za-z0-9\s]/', $user['password'])) {
                $errors[] = "Password must contain at least 1 symbol.";
            }
        }

        if (is_blank($user['confirm_password'])) {
            $errors[] = "Confirm password cannot be blank.";
        } elseif (!matches($user['password'], $user['confirm_password'])) {
            $errors[] = "Password and Confirm Password must match.";
        }
    }


    return $errors;
}

// INSERT new $user into users table
// returns true on success
// outputs errors on failure
function insert_user($user) {
    global $db;

    $errors = validate_user($user);
    if (!empty($errors)) {
        return $errors;
    }

    $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users ";
    $sql .= "(username, first_name, last_name, email, hashed_password) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $user['username']) . "',";
    $sql .= "'" . db_escape($db, $user['first_name']) . "',";
    $sql .= "'" . db_escape($db, $user['last_name']) . "',";
    $sql .= "'" . db_escape($db, $user['email']) . "',";
    $sql .= "'" . db_escape($db, $hashed_password) . "'";
    $sql .= ")";
    // var_dump($sql);
    $result = mysqli_query($db, $sql);

    if ($result) {
        return true;
    } else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

// Update $user
// returns true on success
// outputs error on failure
function update_user($user) {
    global $db;

    $password_sent = !is_blank($user['password']);

    $errors = validate_user($user, ['password_required' => $password_sent]);
    if (!empty($errors)) {
        return $errors;
    }

    $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);

    $sql = "UPDATE users SET ";
    $sql .= "username='" . db_escape($db, $user['username']) . "', ";
    if ($password_sent){
        $sql .= "hashed_password='" . db_escape($db, $hashed_password) . "', ";
    }
    $sql .= "first_name='" . db_escape($db, $user['first_name']) . "', ";
    $sql .= "last_name='" . db_escape($db, $user['last_name']) . "', ";
    $sql .= "email='" . db_escape($db, $user['email']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $user['id']) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);

    if ($result) {
        return true;
    } else {
        // UPDATE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

// DELETE $user
// returns true on success
// outputs errors on failure
function delete_user($user) {
    global $db;

    $sql = "DELETE FROM users ";
    $sql .= "WHERE id='" . db_escape($db, $user['id']) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);

    if ($result) {
        return true;
    } else {
        // DELETE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

/** CATEGORIES **/

// returns mysqli result set of all categories
// options:
//      bool visible
function find_all_categories($options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM categories ";
    if($visible) {
        $sql .= "WHERE visible = '1' ";
    }
    $sql .= "ORDER BY position ASC";
    $category_set = mysqli_query($db, $sql);
    // var_dump($category_set);
    confirm_result_set($category_set);
    return $category_set;
}

// returns assoc array of category with $id
// options:
//      bool visible
function find_category_by_id($id, $options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM categories ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    if ($visible) {
        $sql .= "AND visible = true";
    }
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);

    $category = mysqli_fetch_assoc($result);
    mysqli_free_result($result);

    return $category;
}


// validate category meets certain criteria
// returns an array containing strings of any errors encountered
// if all validation passes, $errors[] will be empty
function validate_category($category) {
    $errors = [];

    // menu_name
    if (is_blank($category['menu_name'])) {
        $errors[] = "Name cannot be blank.";
    } elseif (!has_length($category['menu_name'], ['min' => 2, 'max' => 255])) {
        $errors[] = "Name must be between 2 and 255 characters.";
    }
    $current_id = $category['id'] ?? '0';
    if (!has_unique_category_menu_name($category['menu_name'], $current_id)) {
        $errors[] = "Menu name must be unique.";
    }

    // visible
    // Make sure we are working with a string
    $visible_str = (string) $category['visible'];
    if (!has_inclusion_of($visible_str, ["0", "1"])) {
        $errors[] = "Visible must be true or false.";
    }

    return $errors;
}

// INSERT new category into categories table
// returns true on success
// outputs error on failure
function insert_category($category) {
    global $db;

    $errors = validate_category($category);
    if(!empty($errors)) {
        return $errors;
    }

    shift_positions('categories', 0, $category['position']);

    $sql = "INSERT INTO categories ";
    $sql .= "(menu_name, position, visible) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $category['menu_name']) . "',";
    $sql .= "'" . db_escape($db, $category['position']) . "',";
    $sql .= "'" . db_escape($db, $category['visible']) . "'";
    $sql .= ")";

    $result = mysqli_query($db, $sql);
    // $result will be boolean

    if ($result) {

        return true;
    } else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

// UPDATE $category
// returns true on success
// outputs error on failure
function update_category($category) {
    global $db;
    $start_pos = find_category_by_id($category['id'])['position'];

    $errors = validate_category($category);
    if(!empty($errors)) {
        return $errors;
    }

    $sql = "UPDATE categories SET ";
    $sql .= "menu_name='" . db_escape($db, $category['menu_name']) . "',";
    $sql .= "position='" . db_escape($db, $category['position']) . "',";
    $sql .= "visible='" . db_escape($db, $category['visible']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $category['id']) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);

    if ($result) {
        shift_positions('categories', $start_pos, $category['position'], $category['id']);
        return true;
    } else {
        // UPDATE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

// DELETE $category
// returns true on success
// outputs error on failure
function delete_category($category) {
    global $db;

    if (has_child_albums($category['id'])) {
        return array("Category still has child albums.
            Delete child albums before deleting category.");
    }
    $sql = "DELETE FROM categories ";
    $sql .= "WHERE id='" . db_escape($db, $category['id']) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);

    if ($result) {
        shift_positions('categories', $category['position'], 0, $category['id']);
        return true;
    } else {
        // DELETE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

/** ALBUMS **/

// returns mysqli result set of all albums
// options:
//      bool visible
function find_all_albums($options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT albums.*, categories.menu_name AS category_name ";
    $sql .= "FROM albums ";
    $sql .= "LEFT JOIN categories ON albums.category_id = categories.id ";
    if($visible) {
        $sql .= "WHERE visible = true ";
    }
    $sql .= "ORDER BY position ASC";
    $album_set = mysqli_query($db, $sql);
    confirm_result_set($album_set);
    return $album_set;
}

// returns assoc array of album with $id
// options:
//      bool visible
function find_album_by_id($id, $options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM albums ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    if ($visible) {
        $sql .= "AND visible = true";
    }
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);

    $album = mysqli_fetch_assoc($result);
    mysqli_free_result($result);

    return $album;
}

// returns result set of albums with $category_id
// options:
//      bool visible
function find_albums_by_category_id($category_id, $options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM albums ";
    $sql .= "WHERE category_id='" . db_escape($db, $category_id) . "' ";
    if ($visible) {
        $sql .= "AND visible=true ";
    }
    $sql .= "ORDER BY position ASC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
}

// returns count of albums with $category_id
// options:
//      bool visible
function count_albums_by_category_id($category_id, $options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT COUNT(id) FROM albums ";
    $sql .= "WHERE category_id='" . db_escape($db, $category_id) . "' ";
    if ($visible) {
        $sql .= "AND visible=true ";
    }
    $sql .= "ORDER BY position ASC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $row = mysqli_fetch_row($result);
    mysqli_free_result($result);
    $count = $row[0];
    return $count;
}

// validate album meets certain criteria
// returns an array containing strings of any errors encountered
// if all validation passes, $errors[] will be empty
function validate_album($album) {
    $errors = [];

    // menu_name
    if (is_blank($album['menu_name'])) {
        $errors[] = "Name cannot be blank.";
    } elseif (!has_length($album['menu_name'], ['min' => 2, 'max' => 255])) {
        $errors[] = "Name must be between 2 and 255 characters.";
    }
    $current_id = $album['id'] ?? '0';
    if (!has_unique_album_menu_name($album['menu_name'], $current_id)) {
        $errors[] = "Menu name must be unique.";
    }

    // visible
    // Make sure we are working with a string
    $visible_str = (string) $album['visible'];
    if (!has_inclusion_of($visible_str, ["0", "1"])) {
        $errors[] = "Visible must be true or false.";
    }

    return $errors;
}

// INSERT new album into albums table
// returns true on success
// outputs error on failure
function insert_album($album) {
    global $db;

    $errors = validate_album($album);
    if(!empty($errors)) {
        return $errors;
    }

    shift_positions('albums', 0, $ablum['position']);

    $sql = "INSERT INTO albums ";
    $sql .= "(menu_name, category_id, position, visible) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $album['menu_name']) . "',";
    $sql .= "'" . db_escape($db, $album['category_id']) . "',";
    $sql .= "'" . db_escape($db, $album['position']) . "',";
    $sql .= "'" . db_escape($db, $album['visible']) . "'";
    $sql .= ")";

    $result = mysqli_query($db, $sql);
    // $result will be boolean

    if ($result) {
        return true;
    } else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

// UPDATE $album
// returns true on success
// outputs error on failure
function update_album($album) {
    global $db;

    $start_pos = find_album_by_id($album['id'])['position'];

    $errors = validate_album($album);
    if(!empty($errors)) {
        return $errors;
    }

    $sql = "UPDATE albums SET ";
    $sql .= "menu_name='" . db_escape($db, $album['menu_name']) . "',";
    $sql .= "category_id='" . db_escape($db, $album['category_id']) . "',";
    $sql .= "position='" . db_escape($db, $album['position']) . "',";
    $sql .= "visible='" . db_escape($db, $album['visible']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $album['id']) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);

    if ($result) {
        shift_positions('albums', $start_pos, $album['position'], $album['id']);
        return true;
    } else {
        // UPDATE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

// DELETE $album
// returns true on success
// outputs error on failure
function delete_album($album) {
    global $db;

    if (has_child_images($album['id'])) {
        return array("Album still has child images.  Delete child images before deleting album.");
    }
    $sql = "DELETE FROM albums ";
    $sql .= "WHERE id='" . db_escape($db, $album['id']) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);

    if ($result) {
        shift_positions('albums', $album['position'], 0, $album['id']);
        return true;
    } else {
        // DELETE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

/** IMAGES **/

// returns all images
// includes album names as album_name
// includes category names as category_name
function image_list() {
    global $db;

    $sql = "SELECT images.*, albums.menu_name AS album_name, ";
    $sql .= "categories.menu_name AS category_name ";
    $sql .= "FROM images ";
    $sql .= "LEFT JOIN albums ON images.album_id = albums.id ";
    $sql .= "LEFT JOIN categories ON albums.category_id = categories.id";

    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;

}

// returns assoc array of image with $id
// options:
//      bool visible
function find_image_by_id($id, $options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM images ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    if ($visible) {
        $sql .= "AND visible=true";
    }
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $image = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $image;
}

// returns result set of images with $album_id
// options:
//      bool visible
function find_images_by_album_id($album_id, $options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM images ";
    $sql .= "WHERE album_id='" . db_escape($db, $album_id) . "' ";
    if ($visible) {
        $sql .= "AND visible=true ";
    }
    $sql .= "ORDER BY taken ASC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
}

// returns count of images with $album_id
// options:
//      bool visible
function count_images_by_album_id($album_id, $options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT COUNT(id) FROM images ";
    $sql .= "WHERE album_id='" . db_escape($db, $album_id) . "' ";
    if ($visible) {
        $sql .= "AND visible=true ";
    }
    $sql .= "ORDER BY taken DESC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $row = mysqli_fetch_row($result);
    mysqli_free_result($result);
    $count = $row[0];
    return $count;
}

// validate image meets certain criteria
// returns an array containing strings of any errors encountered
// if all validation passes, $errors[] will be empty
// TODO add image specific validations
function validate_image($image) {
    $errors = [];

    // filename
    if (!has_unique_image_filename($image['filename'],
        $image['id'] ?? '0')) {
        $errors[] = "Image exists on the server already.";
    }

    // visible
    // Make sure we are working with a string
    $visible_str = (string) $image['visible'];
    if (!has_inclusion_of($visible_str, ["0", "1"])) {
        $errors[] = "Visible must be true or false.";
    }

    // type
    // make sure file uploaded is a jpg/gif/png
    if (!has_image_file_type($image['type'])) {
        $errors[] = "File must be of type jpeg/gif/png.";
    }

    // alt_text
    if (is_blank($image['alt_text'])) {
        $errors[] = "Alt text is required.";
    }
    return $errors;
}

// INSERT new image into images table
// returns true on success
// outputs error on failure
function insert_image($image) {
    global $db;
    $errors = array();
    $errors = validate_image($image);
    if (!empty($errors)) {
        return $errors;
    }

    // TODO update sql to match images table
    $sql = "INSERT INTO images ";
    $sql .= "(album_id, filename, type, visible, taken, alt_text, caption) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $image['album_id']) . "',";
    $sql .= "'" . db_escape($db, $image['filename']) . "',";
    $sql .= "'" . db_escape($db, $image['type']) . "',";
    $sql .= "'" . db_escape($db, $image['visible']) . "',";
    $sql .= "'" . db_escape($db, $image['taken']) . "',";
    $sql .= "'" . db_escape($db, $image['alt_text']) . "',";
    $sql .= "'" . db_escape($db, $image['caption']) . "'";
    $sql .= ")";

    $result = mysqli_query($db, $sql);
    // $result will be boolean

    if ($result) {
        $saved_image = UPLOAD_PATH . $image['filename'];
        $moved = move_uploaded_file($image['tmp_name'], $saved_image);
        if ($moved) {
            $ext = explode('.', $image['name'])[1];
            $made_thumb = make_thumb($saved_image, 200, 150, $ext);
            if ($made_thumb) {
                return true;
            } else {
                msqli_rollback($db);
                $errors[] = "Thumbnail image could not be created.";
                return $errors;
            }
        } else {
            msqli_rollback($db);
            $errors[] = "Image could not be moved to storage.";
            return $errors;
        }
    } else {
        // INSERT failed
        $errors[] = mysqli_error($db);
        return $errors;
    }
}

// UPDATE $image
// returns true on success
// outputs error on failure
function update_image($image) {
    global $db;

    $errors = validate_image($image);
    if (!empty($errors)) {
        return $errors;
    }

    $sql = "UPDATE images SET ";
    $sql .= "album_id='" . db_escape($db, $image['album_id']) . "',";
    $sql .= "taken='" . db_escape($db, $image['taken']) . "',";
    $sql .= "visible='" . db_escape($db, $image['visible']) . "', ";
    $sql .= "alt_text='" . db_escape($db, $image['alt_text']) . "', ";
    $sql .= "caption='" . db_escape($db, $image['caption']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $image['id']) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);

    if ($result) {
        return true;
    } else {
        // UPDATE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

// DELETE $image from database and uploads folder
// also delete thumbnail image
function delete_image($image) {
    global $db;
    $errors = array();

    $sql = "DELETE FROM images ";
    $sql .= "WHERE id='" . db_escape($db, $image['id']) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);

    if ($result) {
        $deleted = unlink(UPLOAD_PATH . $image['filename']);
        if ($deleted) {
            $deleted = unlink(UPLOAD_PATH . "thumb_" . $image['filename']);
            if ($deleted) {
                return true;
            } else {
                $errors[] = "Could not delete thumbnail";
                return $errors;
            }
        } else {
            $errors[] = "Could not delete image";
            return $errors;
        }
    } else {
        // DELETE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}
