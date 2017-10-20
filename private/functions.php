<?php
// /private/functions.php
// contains functions to use throughout app
// include in initialization file

// returns a site specific url for the given file path
function url_for($script_path) {
    // add the leading '/' if not present
    if ($script_path[0] != '/') {
       $script_path = '/' . $script_path;
    }

    return WWW_ROOT . $script_path;
}

// returns urlencoded $string
function u($string='') {
    return urlencode($string);
}

// returns rawurlencoded $string
function raw_u($string='') {
    return rawurlencode($string);
}

// returns htmlspecialchars $string
function h($string='') {
    return htmlspecialchars($string);
}

// sets html header and exits script
function error_404() {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit();
}

// sets html header and exits script
function error_500() {
    header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
    exit();
}

// redirect browser to $location using header
function redirect_to($location) {
    header("Location: " . $location);
    exit;
}

// checks if incoming request is POST method
// returns boolean
function is_post_request() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

// checks if incoming request is GET method
// returns boolean
function is_get_request() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}

// sets up html to display $errors array in bulleted list
// if no $errors, output empty string
function display_errors($errors = array()) {
    $output = '';
    if(!empty($errors)) {
        $output .= "<div class=\"errors\">";
        $output .= "Please fix the following errors:";
        $output .= "<ul>";
        foreach ($errors as $error) {
            $output .= "<li>" . h($error) ."</li>";
        }
        $output .= "</ul>";
        $output .= "</div>";
    }
    return $output;
}

// retrieves session variable 'message' and cleared the veriable
// returns string
function get_and_clear_session_message() {
    if(isset($_SESSION['message']) && $_SESSION['message'] != '') {
        $msg = $_SESSION['message'];
        unset($_SESSION['message']);
        return $msg;
    }
}

// sets up html to display session message
// returns string
function display_session_message() {
    $msg = get_and_clear_session_message();
    if(!is_blank($msg)) {
        return '<div id="message">' . h($msg) . '</div>';
    }
}

// make a thumbnail image from uploaded image
// $original = path/filename of saved image
// $max_width = maximum desired width of thumbnail image
// $max_height = maximum desired width of thumbnail image
// $extension = extension of uploaded image
function make_thumb($original, $max_width, $max_height, $extension) {
    list($w_orig, $h_orig) = getimagesize($original);
    $ratio = $w_orig / $h_orig;
    // determines dimensions of new thumbnail
    if (($max_width / $max_height) > $ratio) {
        $new_height = $max_height;
        $new_width = $max_height * $ratio;
    } else {
        $new_width = $max_width;
        $new_height = $max_width / $ratio;
    }

    $img = '';
    // make a new image resource to hold resampled thumbnail
    if ($extension == "gif" || $extension == "GIF") {
        $img = imagecreatefromgif($original);
    } elseif ($extension == "png" || $extension == "PNG") {
        $img = imagecreatefrompng($original);
    } else {
        $img = imagecreatefromjpeg($original);
    }
    // strip path from image file
    $filename = basename($original);
    $truecolorimage = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($truecolorimage, $img, 0, 0, 0, 0, $new_width, $new_height,
        $w_orig, $h_orig);
    return imagejpeg($truecolorimage, UPLOAD_PATH . "thumb_" . $filename, 80);
}

// return url for thumbnail image
function thumb_url($filename) {
    return url_for('/uploads/thumb_' . $filename);
}

function thumb_size($filename) {
    return getimagesize(UPLOAD_PATH . 'thumb_' . $filename)[3];
}
