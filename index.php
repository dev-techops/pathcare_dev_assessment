<?php
/**
 * Created by PhpStorm.
 * User: Nathan Shava
 * Date: 31-Oct-18
 * Time: 09:39
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

    <title>Library Management System</title>

</head>

<body style="background-color:#f2f2f2">
<h3 style="text-align:center">
    Library Management System
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
                    <a class="dropdown-toggle" data-toggle="dropdown"
                       href="#">Welcome <?php echo $_SESSION['member_name']; ?>
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
                <div class="panel-heading" style="text-align:center;"><b>New Book Form</b></div>
                <div class="panel-body">
                    <form method="post" id="bookForm" name="bookForm" target="_self" action="add_book.php">
                        <div class="form-group">
                            <label for="title">Book Title</label>
                            <input type="text" placeholder="e.g. Introduction to Java Programming" class="form-control"
                                   id="title" name="title">
                        </div>
                        <div class="form-group">
                            <label for="author">Book Author</label>
                            <input type="text" placeholder="e.g. Daniel Liang" class="form-control" id="author"
                                   name="author">
                        </div>
                        <div class="form-group">
                            <label for="year">Year Published</label>
                            <input type="number" placeholder="e.g. 2015" min="1800" max="2018" class="form-control"
                                   id="year" name="year">
                        </div>
                        <div class="form-group">
                            <label for="synopsis">Synopsis</label>
                            <textarea
                                    placeholder="e.g. Java is a high level, object-oriented, platform independent language. Java, unlike some languages before it allows for the use of words and commands instead of just symbols and numbers. Java also allows for the creation of advanced data types called objects which represent real world things like a chair or a computer where you can set the attributes of these objects and things they do."
                                    class="form-control" rows="5" id="synopsis" name="synopsis"></textarea>
                        </div>

                        <button type="button" onclick="validateBookForm()" class="btn btn-success">Add Book</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-8" style="margin-top:5%;margin-left:5%;">
            <div class="panel panel-info">
                <div class="panel-heading" style="text-align:center;"><b>Available Books</b></div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Book ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Year Published</th>
                            <th>Synopsis</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $allBooks = "CALL readAllBooks();" or die($connect->error);
                        $books = $connect->query($allBooks);

                        if (mysqli_num_rows($books) > 0) {

                            while ($book = mysqli_fetch_array($books)) {
                                ?>
                                <tr>
                                    <td><?php echo $book['bookID']; ?></td>
                                    <td><?php echo $book['title']; ?></td>
                                    <td><?php echo $book['author']; ?></td>
                                    <td><?php echo $book['yearPublished']; ?></td>
                                    <td><?php echo $book['synopsis']; ?></td>
                                    <td>
                                        <a onclick="return confirm('You are about to remove the selected book. This operation cannot be undone! Continue?')"
                                           href="index.php?book_delID=<?php echo $book['bookID']; ?>"
                                           class="btn btn-danger">Delete</a>
                                    </td>
                                    <td>
                                        <a href="update_book.php?book_EditID=<?php echo $book['bookID']; ?>"
                                           class="btn btn-warning">
                                            Edit
                                        </a>
                                    </td>
                                    <td>
                                        <a onclick="return confirm('You are about to check out this book! Continue?')"
                                           href="index.php?book_loanID=<?php echo $book['bookID']; ?>"
                                           class="btn btn-success">Check Out</a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        <?php
                        if (isset($_GET['book_delID'])) {

                            mysqli_free_result($books);
                            mysqli_next_result($connect);

                            $bookToDel = $_GET['book_delID'];
                            $deleteBook = "CALL delBook('" . $bookToDel . "')" or die($connect->error);

                            if ($connect->query($deleteBook)) {

                                ?>
                                <script>
                                    alert('Book record deleted successfully!');
                                    window.location.href = "index.php"; //Refresh
                                </script>
                            <?php
                            }
                            else {
                            ?>
                                <script>
                                    alert('Unable to delete the selected book!');
                                    window.location.href = "index.php";
                                </script>
                                <?php
                            }
                        }
                        ?>
                        <?php
                        if ($_SESSION['logged_in'] == true) {

                        if (isset($_GET['book_loanID'])) {

                            mysqli_free_result($bookToDel);
                            mysqli_next_result($connect);

                            $bookToLoan = $_GET['book_loanID'];
                            $memberLoan = $_SESSION['member_id'];

                            $checkOut = "CALL `newBookLoan`('" . $bookToLoan . "', '" . $memberLoan . "');" or die($connect->error);
                            $resultCheckOut = $connect->query($checkOut);

                        if ($resultCheckOut == true) {

                            ?>
                            <script>
                                alert('The book has been checked out successfully!');
                                window.location.href = "index.php"; //Refresh
                            </script>
                        <?php
                        }
                        else {
                        ?>
                            <script>
                                alert('Unable to check out book!');
                                window.location.href = "index.php";
                            </script>
                        <?php
                        }
                        }
                        } else{
                        ?>
                            <script>
                                alert('Please ensure you are logged in to check out a book');
                                window.location.href = "login.php";
                            </script>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-sm-8" style="margin-top:5%;margin-left:5%;">
            <div class="panel panel-info">
                <div class="panel-heading" style="text-align:center;"><b>Book Loans</b></div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Loan ID</th>
                            <th>Book ID</th>
                            <th>Title</th>
                            <th>Checkout By</th>
                            <th>Checkout Timestamp</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        mysqli_free_result($books);
                        mysqli_next_result($connect);

                        $loanedBooks = "CALL readAllBookLoans();" or die($connect->error);
                        $loanBook = $connect->query($loanedBooks);

                        if (mysqli_num_rows($loanBook) > 0) {

                            while ($booksLoaned = mysqli_fetch_array($loanBook)) {
                                ?>
                                <tr>
                                    <td><?php echo $booksLoaned['loanID']; ?></td>
                                    <td><?php echo $booksLoaned['bookID']; ?></td>
                                    <td><?php echo $booksLoaned['title']; ?></td>
                                    <td><?php echo $booksLoaned['memberName']; ?></td>
                                    <td><?php echo $booksLoaned['dateLoaned']; ?></td>
                                    <td>
                                        <a onclick="return confirm('Are you sure you would like to return the selected book?')"
                                           href="index.php?returnID=<?php echo $booksLoaned['loanID']; ?>"
                                           class="btn btn-danger">Return Book</a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        <?php
                        if (isset($_GET['returnID'])) {

                            mysqli_free_result($loanBook);
                            mysqli_next_result($connect);

                            $bookToReturn = $_GET['returnID'];
                            $returnBook = "CALL delLoan('" . $bookToReturn . "')" or die($connect->error);
                            $resultReturn = $connect->query($returnBook);

                            if ($resultReturn == true) {

                                ?>
                                <script>
                                    alert('Book returned successfully!');
                                    window.location.href = "index.php"; //Refresh
                                </script>
                            <?php
                            }
                            else {
                            ?>
                                <script>
                                    alert('Unable to return the book at this time!');
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

    <div class="row">
        <div class="col-sm-8" style="margin-top:5%;margin-left:5%;">
            <div class="panel panel-success">
                <div class="panel-heading" style="text-align:center;"><b>Search</b></div>
                <div class="panel-body">
                    <form method="post" name="searchForm" id="searchForm" target="_self" action="index.php">
                        <div class="form-group">
                            <input type="text" placeholder="Enter the book's title/author" class="form-control"
                                   id="searchValue"
                                   name="searchValue">
                        </div>
                        <button type="button" onclick="validateSearch();" class="btn btn-success">Go</button>
                    </form>
                    <hr>
                    <?php
                    if (isset($_POST['searchValue']) && ($_POST['searchValue'] != "")) {

                        ?>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Book ID</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Year Published</th>
                                <th>Synopsis</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            mysqli_free_result($resultReturn);
                            mysqli_next_result($connect);

                            $searchValue = $connect->escape_string($_POST['searchValue']);

                            $searchBooks = "CALL searchBooks('" . $searchValue . "');" or die($connect->error);
                            $resultBooks = $connect->query($searchBooks);

                            if (mysqli_num_rows($resultBooks) > 0) {

                                while ($foundBooks = mysqli_fetch_array($resultBooks)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $foundBooks['bookID']; ?></td>
                                        <td><?php echo $foundBooks['title']; ?></td>
                                        <td><?php echo $foundBooks['author']; ?></td>
                                        <td><?php echo $foundBooks['yearPublished']; ?></td>
                                        <td><?php echo $foundBooks['synopsis']; ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript" type="text/javascript">

    function validateSearch() {

        var searchVal = document.searchForm.searchValue.value;

        if (searchVal !== "") {

            document.searchForm.submit();
            document.searchForm.reset();
        }
        else {
            document.getElementById("searchValue").style.borderColor = "#ff0000";
        }
    }

    function validateBookForm() {

        var bookTitle = document.bookForm.title.value;
        var bookAuthor = document.bookForm.author.value;
        var yearPublished = document.bookForm.year.value;
        var bookSynopsis = document.bookForm.synopsis.value;

        if (bookTitle === "") {

            document.getElementById("title").style.borderColor = "#ff0000";

        }
        else if (bookAuthor === "") {
            document.getElementById("title").style.borderColor = "";
            document.getElementById("author").style.borderColor = "#ff0000";
        }
        else if (yearPublished === "") {
            document.getElementById("author").style.borderColor = "";
            document.getElementById("year").style.borderColor = "#ff0000";
        }
        else if (bookSynopsis === "") {
            document.getElementById("year").style.borderColor = "";
            document.getElementById("synopsis").style.borderColor = "#ff0000";
        }
        else {

            document.getElementById("title").style.borderColor = "";
            document.getElementById("author").style.borderColor = "";
            document.getElementById("year").style.borderColor = "";
            document.getElementById("synopsis").style.borderColor = "";

            document.bookForm.submit();
            document.bookForm.reset();

        }
    }

</script>
</body>
</html>