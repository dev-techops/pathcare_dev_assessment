<?php
/**
 * Created by PhpStorm.
 * User: Nathan Shava
 * Date: 31-Oct-18
 * Time: 20:21
 */

/**
 * Defined as constants so that they can't be changed
 */
DEFINE('DB_USER', 'Admin');
DEFINE('DB_PASSWORD', '');
DEFINE('DB_HOST', 'localhost');
DEFINE('DB_NAME', 'library_system');

/**
 * Connection to library_system
 */
$connect = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
OR die('Could not connect to MySQL: ' . mysqli_connect_error());

?>