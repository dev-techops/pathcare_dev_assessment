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

    <title>Library Management System (Update Book)</title>

    <script type="text/javascript" language="JavaScript">
        $(window).on('load',function(){
            $('#editBook').modal('show');
        });
    </script>
</head>

<body style="background-color:#f2f2f2" onload="">
<h3 style="text-align:center">
    Update the Book record
</h3>

<?php
/**
 * Created by PhpStorm.
 * User: Nathan Shava
 * Date: 01-Nov-18
 * Time: 13:32
 */

require 'connect.php';

if (isset($_GET['book_EditID'])) {

    $idToEdit = $_GET['book_EditID'];

    $selectedBook = "CALL `readSelectedBook`('" . $idToEdit . "');" or die($connect->error);
    $bookEdit = $connect->query($selectedBook);

    if (mysqli_num_rows($bookEdit) > 0) {

        while ($editBook = mysqli_fetch_array($bookEdit)) {
            ?>
            <div class="modal fade" id="editBook" role="dialog"
                 aria-labelledby="editBook" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"
                                style="text-align:center; font-size:25px"><?php
                                echo $editBook['title'];
                                ?></h4>
                            <button class="close" type="button" data-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="center-block">
                                            <div class="col-md-10">
                                                <form method="post" id="bookForm"
                                                      name="bookForm" target="_self"
                                                      action="add_book.php">
                                                    <div class="form-group">
                                                        <label for="title">Book
                                                            Title</label>
                                                        <input type="text"
                                                               placeholder="<?php echo $editBook['title']; ?>"
                                                               class="form-control"
                                                               id="title" name="title">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="author">Book
                                                            Author</label>
                                                        <input type="text"
                                                               placeholder="<?php echo $editBook['author']; ?>"
                                                               class="form-control"
                                                               id="author"
                                                               name="author">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="year">Year
                                                            Published</label>
                                                        <input type="number"
                                                               placeholder="<?php echo $editBook['yearPublished']; ?>"
                                                               min="1800" max="2018"
                                                               class="form-control"
                                                               id="year" name="year">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="synopsis">Synopsis</label>
                                                        <textarea
                                                                placeholder="<?php echo $editBook['synopsis']; ?>"
                                                                class="form-control"
                                                                rows="5"
                                                                id="synopsis"
                                                                name="synopsis"></textarea>
                                                    </div>

                                                    <button type="button"
                                                            onclick="validateEditForm()"
                                                            class="btn btn-success">Update
                                                    </button>
                                                    <a href="index.php" target="_self" class="btn btn-default">Close</a>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
?>

</body>
</html>