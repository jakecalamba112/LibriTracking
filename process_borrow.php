<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure 'lrn' exists in the POST data to avoid the Undefined Key warning
    $lrn = isset($_POST['lrn']) ? $mysqli->real_escape_string($_POST['lrn']) : '';
    $due_date = isset($_POST['due_date']) ? $mysqli->real_escape_string($_POST['due_date']) : '';
    $book_ids = isset($_POST['book_ids']) ? $_POST['book_ids'] : [];

    if (empty($lrn)) {
        header("Location: borrow_book.php?error=no_student");
        exit;
    }

    // Check if the LRN actually exists in the students table first
    $check_student = $mysqli->query("SELECT lrn FROM students WHERE lrn = '$lrn'");
    if ($check_student->num_rows == 0) {
        header("Location: borrow_book.php?error=invalid_lrn");
        exit;
    }

    if (!empty($book_ids)) {
        foreach ($book_ids as $book_id) {
            $book_id = intval($book_id);

            // 1. Get the book title
            $book_res = $mysqli->query("SELECT title FROM books WHERE id = $book_id");
            if ($book_data = $book_res->fetch_assoc()) {
                $book_title = $mysqli->real_escape_string($book_data['title']);

                // 2. Reduce stock by 1
                $mysqli->query("UPDATE books SET stocks = stocks - 1 WHERE id = $book_id");

                // 3. Insert into borrowers table
                $query = "INSERT INTO borrowers (lrn, book_borrowed, book_id, date_borrowed, due_date, status) 
                          VALUES ('$lrn', '$book_title', $book_id, NOW(), '$due_date', 'Borrowed')";

                $mysqli->query($query);
            }
        }
        header("Location: borrowers.php?msg=success");
    } else {
        header("Location: borrow_book.php?error=no_books");
    }
    exit;
}
