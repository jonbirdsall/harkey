<?php
// /private/database.php
// functions for connecting to datbase and verifying connections
// include in initialization file

require_once('db_credentials.php');

// connect to database listed in db_credentials.php
// returns handle to db connection
function db_connect() {
    $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    confirm_db_connect();
    return $connection;
}

// closes connection to database
function db_disconnect($connection) {
    if (isset($connection)) {
        mysqli_close($connection);
    }
}

// db_escape($db, 'string to escape')
// alias for mysqli_real_escape_string to prevent SQL injection
function db_escape($connection, $string) {
    return mysqli_real_escape_string($connection, $string);
}

// used by db_connect() to verify connection succeeded
// nothing happens if connection was successful
// output error message on failure
function confirm_db_connect(){
    if (mysqli_connect_errno()) {
        $msg = "Database connection failed: ";
        $msg .= mysqli_connect_error();
        $msg .= " (". mysqli_connect_errno() . ")";
        exit($msg);
    }
}

function confirm_result_set($result_set){
    if (!$result_set) {
        exit("Database query failed.");
    }
}
 ?>
