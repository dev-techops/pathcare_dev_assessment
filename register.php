<?php
/**
 * Created by PhpStorm.
 * User: Nathan Shava
 * Date: 01-Nov-18
 * Time: 14:15
 */

require 'connect.php';
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

    <title>Members</title>

</head>

<body style="background-color:#f2f2f2">
<h3 style="text-align:center">
    Members Module
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
                <div class="panel-heading" style="text-align:center;"><b>New Member Details</b></div>
                <div class="panel-body">
                    <form method="post" id="regForm" name="regForm" target="_self" action="register.php">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" placeholder="e.g. Nathan Shava" class="form-control" id="name"
                                   name="name">
                        </div>
                        <div class="form-group">
                            <label for="user">Username</label>
                            <input type="text" placeholder="e.g. nathants" class="form-control" id="user"
                                   name="user">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Password</label>
                            <input type="password" class="form-control" id="pwd" name="pwd">
                        </div>
                        <div class="form-group">
                            <label for="confPwd">Confirm Password</label>
                            <input type="password" class="form-control" id="confPwd" name="confPwd">
                        </div>

                        <button type="button" onclick="validateRegForm()" class="btn btn-success">Submit</button>
                        <button type="reset" class="btn btn-warning">Clear</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php

    if (isset($_POST['user']) && ($_POST['user'] != "")) {

        $name = $connect->escape_string($_POST['name']);
        $username = $connect->escape_string($_POST['user']);
        $password = $connect->escape_string($_POST['confPwd']);

        /**
         * First check if member username already exist
         */
        $checkUser = $connect->query("CALL `checkUserReg`('" . $username . "');");

        if ($checkUser->num_rows == 0) { //User not found so proceed

            mysqli_free_result($checkUser);
            mysqli_next_result($connect);

            $resultReg = $connect->query("CALL `newMember`('" . $name . "', '" . $username . "', '" . $password . "');");

            if ($resultReg == true) { //User registered

                ?>
                <script>
                    alert('Member registered successfully');
                    window.location.href = "login.php";
                </script>
            <?php
            }
            else {

            ?>
                <script>
                    alert('Unable to register member at this moment');
                    window.location.href = "register.php";
                </script>
                <?php
            }
        }
    }
    ?>

    <div class="col-sm-4" style="margin-top:5%;margin-left:5%;">
        <div class="panel panel-success">
            <div class="panel-heading" style="text-align:center;"><b>Registered Members</b></div>
            <div class="panel-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Member ID</th>
                        <th>Full Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $allMembers = "CALL readAllMembers();" or die($connect->error);
                    $members = $connect->query($allBooks);

                    if (mysqli_num_rows($members) > 0) {

                        while ($book = mysqli_fetch_array($members)) {
                            ?>
                            <tr>
                                <td><?php echo $book['memberID']; ?></td>
                                <td><?php echo $book['memberName']; ?></td>
                                <td>
                                    <a onclick="return confirm('You are about to remove the selected member. This operation cannot be undone! Continue?')"
                                       href="index.php?member_delID=<?php echo $book['memberID']; ?>"
                                       class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    <?php
                    if (isset($_GET['member_delID'])) {

                        $memberToDel = $connect->escape_string($_GET['member_delID']);
                        $deleteMember = "CALL delBook('" . $memberToDel . "')" or die($connect->error);

                        if ($connect->query($deleteMember)) {

                            ?>
                            <script>
                                alert('Member removed successfully!');
                                window.location.href = "index.php"; //Refresh
                            </script>
                        <?php
                        }
                        else {
                        ?>
                            <script>
                                alert('Unable to remove the selected member!');
                                window.location.href = "index.php";
                            </script>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<script language="JavaScript" type="text/javascript">

    function validateRegForm() {

        var name = document.regForm.name.value;
        var user = document.regForm.user.value;
        var pwd = document.regForm.pwd.value;
        var confPwd = document.regForm.confPwd.value;

        if (name === "") {

            document.getElementById("name").style.borderColor = "#ff0000";

        }
        else if (user === "") {
            document.getElementById("name").style.borderColor = "";
            document.getElementById("user").style.borderColor = "#ff0000";
        }
        else if (pwd === "") {
            document.getElementById("user").style.borderColor = "";
            document.getElementById("pwd").style.borderColor = "#ff0000";
        }
        else if (confPwd === "") {
            document.getElementById("pwd").style.borderColor = "";
            document.getElementById("confPwd").style.borderColor = "#ff0000";
        }
        else if (pwd !== confPwd) {

            document.getElementById("pwd").style.borderColor = "#ff0000";
            document.getElementById("confPwd").style.borderColor = "#ff0000";
            alert("Passwords do not match");
        }
        else {

            document.getElementById("name").style.borderColor = "";
            document.getElementById("user").style.borderColor = "";
            document.getElementById("pwd").style.borderColor = "";
            document.getElementById("confPwd").style.borderColor = "";

            document.regForm.submit();
            document.regForm.reset();

        }
    }

</script>
</body>
</html>