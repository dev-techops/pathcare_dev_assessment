<?php
/**
 * Created by PhpStorm.
 * User: Nathan Shava
 * Date: 31-Oct-18
 * Time: 20:46
 */
require 'connect.php';

if (isset($_POST['title']) && ($_POST['title'] != "") AND isset($_POST['author']) && ($_POST['author'] != "")) {

    $title = $connect->escape_string($_POST['title']);
    $author = $connect->escape_string($_POST['author']);
    $year = $connect->escape_string($_POST['year']);
    $synopsis = $connect->escape_string($_POST['synopsis']);

    $newBook = "CALL newBook('" . $title . "', '" . $author . "', '" . $year . "', '" . $synopsis . "');" or die($connect->error);
    $resultBook = $connect->query($newBook);


    if ($resultBook == true) {

        ?>
        <script>
            alert('New book record added successfully');
            window.location.href = "index.php";
        </script>
        <?php

    } else {

        ?>
        <script>
            alert('Unable to add the book record!');
            window.location.href = "index.php";
        </script>
        <?php
    }
}

?>