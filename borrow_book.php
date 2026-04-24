<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_borrow'])) {
    $fname = $mysqli->real_escape_string($_POST['first_name']);
    $lname = $mysqli->real_escape_string($_POST['last_name']);
    $book_id = intval($_POST['book_id']);


    $book_res = $mysqli->query("SELECT title FROM books WHERE id = $book_id");
    $book_title = $book_res->fetch_assoc()['title'];

    $mysqli->query("UPDATE books SET stocks = stocks - 1 WHERE id = $book_id");


    $mysqli->query("INSERT INTO borrowers (first_name, last_name, book_borrowed, book_id, date_borrowed, status) 
                    VALUES ('$fname', '$lname', '$book_title', $book_id, NOW(), 'Borrowed')");

    header("Location: borrowers.php");
    exit;
}

include_once "header.php";
?>

<div class="container">
    <div class="card">
        <h2>Borrow New Book</h2>
        <form method="POST">
            <input type="text" name="first_name" placeholder="First Name" required style="width:100%; padding:10px; margin-bottom:10px; box-sizing:border-box;">
            <input type="text" name="last_name" placeholder="Last Name" required style="width:100%; padding:10px; margin-bottom:10px; box-sizing:border-box;">

            <label>Select Book</label>
            <select name="book_id" required style="width:100%; padding:10px; margin-bottom:20px;">
                <?php
                $books = $mysqli->query("SELECT * FROM books WHERE stocks > 0");
                while ($row = $books->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['title']} ({$row['stocks']} left)</option>";
                }
                ?>
            </select>
            <button type="submit" name="submit_borrow" class="btn-home" style="padding:10px 20px;">Confirm Borrowing</button>
        </form>
    </div>
</div>
<?php include_once "footer.php"; ?>