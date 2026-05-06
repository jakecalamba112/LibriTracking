<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Check if LRN is present
    if (!isset($_POST['lrn']) || empty(trim($_POST['lrn']))) {
        header("Location: borrow_book.php?error=missing_lrn");
        exit;
    }

    $lrn = $mysqli->real_escape_string($_POST['lrn']);
    $due_date = $mysqli->real_escape_string($_POST['due_date']);
    $book_ids = $_POST['book_ids'] ?? [];

    if (!empty($book_ids)) {
        // 2. Double-check that this LRN exists in the students table
        $check_student = $mysqli->query("SELECT lrn FROM students WHERE lrn = '$lrn'");
        if ($check_student->num_rows === 0) {
            header("Location: borrow_book.php?error=student_not_found");
            exit;
        }

        // 3. Process each book
        foreach ($book_ids as $book_id) {
            $book_id = intval($book_id);

            // Get book details
            $book_res = $mysqli->query("SELECT title, stocks FROM books WHERE id = $book_id");
            $book_data = $book_res->fetch_assoc();

            if ($book_data && $book_data['stocks'] > 0) {
                $book_title = $mysqli->real_escape_string($book_data['title']);

                // Decrease stock
                $mysqli->query("UPDATE books SET stocks = stocks - 1 WHERE id = $book_id");

                // Record the borrow
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
