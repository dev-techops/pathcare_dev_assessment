<?php
/**
 * Created by PhpStorm.
 * User: Nathan Shava
 * Date: 02-Nov-18
 * Time: 07:05
 */

session_start();
session_unset(); //Unsets all existing sessions
session_destroy(); //Destroys all existing sessions
header('Location: http://' . $_SERVER['HTTP_HOST'] . '/library_system/login.php');
exit;

?>