<?php
    // /private/initialize.php
    // include with all files to configure App

    ob_start(); // turn on output buffering
    session_start(); // turn on sessions

    /* Assign file paths to PHP constants
     * __FILE__ returns the current path to this file
     * dirname() returns the path to the parent directory
     */
    // /home/harkey/theharkeyfamily.com/private
    define('PRIVATE_PATH', dirname(__FILE__));
    // /home/harkey
    define('PROJECT_PATH', dirname(PRIVATE_PATH));
    // /var/www/html/public
    define('PUBLIC_PATH', PROJECT_PATH . '/public');
    // /home/harkey/theharkeyfamily.com/private/shared
    define('SHARED_PATH', PRIVATE_PATH . '/shared');
    // /var/www/html/public/uploads
    define('UPLOAD_PATH', PUBLIC_PATH . '/uploads/');

    /* Assign the root URL to a PHP constant
     * * Do not need to include the domain
     * * Use same document root as webserver
     * * Can set to hardcoded value:
     * define("WWW_ROOT", '/public');
     * define("WWW_ROOT", '');
     * * Can dynamically find everything in URL up to "/public"
     */
    $public_end = strpos($_SERVER['SCRIPT_NAME'], '/public') + 7;
    $doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $public_end);
    define("WWW_ROOT", '/public');

    define('IMAGE_URL', WWW_ROOT . '/uploads/');

    define("VISIBLE", "'visible' => true");

    // functions go here
    require_once('functions.php');

    // database connection functions
    require_once('database.php');

    // query functions
    require_once('query_functions.php');

    // validation functions
    require_once('validation_functions.php');

    // authentication functions
    require_once('auth_functions.php');

    // establish database connection for all files
    $db = db_connect();
    // require_once('shared/fake_db.php');
    $errors = [];
