<?php
// /admin/logout.php
require_once('../../private/initialize.php');


log_out_user();

redirect_to(url_for('/admin/login.php'));
