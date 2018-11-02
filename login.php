<?php
/**
 * Created by PhpStorm.
 * User: Nathan Shava
 * Date: 01-Nov-18
 * Time: 14:15
 */
require 'connect.php';
session_start();
?>
<!--
Author: NATHAN SHAVA
Date created: 31-OCT-2018
Description: Library Management System (Technical Assessment)
-->
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Library System">
    <meta name="author" content="Nathan Shava">
    <!-- Bootstrap 3 -->
    <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <title>Members Login</title>

</head>

<body style="background-color:#f2f2f2">
<h3 style="text-align:center">
    Member Login
</h3>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Library System</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a href="index.php">Home</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <?php
                if (isset($_SESSION['logged_in']) AND $_SESSION['logged_in'] == true) {
                    ?>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Welcome <?php echo $_SESSION['member_name']; ?>
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                    <?php
                } else {
                ?>
            <li><a href="register.php">Sign Up</a></li>
            <li><a href="login.php">Login</a></li>
            <?php
            }
            ?>
        </ul>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-8" style="margin-top:5%;margin-left:5%;">
            <div class="panel panel-success">
                <div class="panel-heading" style="text-align:center;"><b>Login</b></div>
                <div class="panel-body">
                    <form method="post" id="loginForm" name="loginForm" target="_self" action="login.php">
                        <div class="form-group">
                            <label for="user">Username</label>
                            <input type="text" placeholder="e.g. nathants" class="form-control" id="user"
                                   name="user">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Password</label>
                            <input type="password" class="form-control" id="pwd" name="pwd">
                        </div>

                        <button type="button" onclick="validateLoginForm()" class="btn btn-success">Submit</button>
                        <button type="reset" class="btn btn-warning">Clear</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php

    if (isset($_POST['user']) && ($_POST['user'] != "") AND isset($_POST['pwd']) && ($_POST['pwd'] != "")) {

        $username = $connect->escape_string($_POST['user']);
        $password = $connect->escape_string($_POST['pwd']);

        $checkUser = $connect->query("CALL `memberLogin`('" . $username . "', '" . $password . "');");

        if ($checkUser->num_rows > 0) { //Member loggin in successfully

            $memberDetails = mysqli_fetch_array($checkUser);

            $_SESSION['member_id'] = $memberDetails['memberID'];
            $_SESSION['member_name'] = $memberDetails['memberName'];
            $_SESSION['logged_in'] = true;

            ?>
            <script>
                alert('Login successful');
                window.location.href = "index.php";
            </script>
        <?php
        }
        else {

        ?>
            <script>
                alert('Incorrect username and/or password');
                window.location.href = "login.php";
            </script>
            <?php
        }
    }
    ?>
</div>

<script language="JavaScript" type="text/javascript">

    function validateLoginForm() {

        var user = document.loginForm.user.value;
        var pwd = document.loginForm.pwd.value;

        if (user === "") {
            document.getElementById("name").style.borderColor = "";
            document.getElementById("user").style.borderColor = "#ff0000";
        }
        else if (pwd === "") {
            document.getElementById("user").style.borderColor = "";
            document.getElementById("pwd").style.borderColor = "#ff0000";
        }
        else {

            document.getElementById("user").style.borderColor = "";
            document.getElementById("pwd").style.borderColor = "";
            document.loginForm.submit();
            document.loginForm.reset();
        }
    }
</script>
</body>
</html>
