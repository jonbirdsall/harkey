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

function image_url($filename) {
    return IMAGE_URL . $filename;
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
    $thumb = make_reasonable_size($original, $max_width, $max_height, $extension);
    return imagejpeg($thumb, UPLOAD_PATH . "thumb_" . basename($original), 80);
}

function resize_and_save($original, $save_location){
    $ext_array = explode(".", $original);
    $extension = end($ext_array);
    switch ($extension) {
        case 'jpg':
            return imagejpeg(make_reasonable_size($original, 980, 860, $extension),
                $save_location);
        case 'gif':
            return imagegif(make_reasonable_size($original, 980, 860, $extension),
                $save_location);
        case 'png':
            return imagepng(make_reasonable_size($original, 980, 860, $extension),
                $save_location);
        default:
            return false;
            break;
    }
}

// returns image link resource
// use imagejpeg/imagegif/imagepng to save
function make_reasonable_size($original, $max_width = 980, $max_height = 860, $extension = 'jpg') {
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
    var_dump($truecolorimage);
    return $truecolorimage;
}

// return url for thumbnail image
function thumb_url($filename) {
    return url_for('/uploads/thumb_' . $filename);
}

// return the html formatted size attributes of the given
// image thumbnail stored on the server
function thumb_size($filename) {
    return getimagesize(UPLOAD_PATH . 'thumb_' . $filename)[3];
}

function rotate_image($filename, $degrees) {
    // Load
    $source = imagecreatefromjpeg($filename);

    // Rotate
    $rotate = imagerotate($source, $degrees, 0);

    // Output
    if (imagejpeg($rotate, $filename)) {
        imagedestroy($source);
        imagedestroy($rotate);
        return true;
    } else {
        return false;
    }
}
