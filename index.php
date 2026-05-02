<?php
require_once "config.php";
include_once "header.php";

// 1. Get stats from database
$total_books = $mysqli->query("SELECT COUNT(*) FROM books")->fetch_row()[0];
$student_count = $mysqli->query("SELECT COUNT(*) FROM students")->fetch_row()[0];
$borrowed_count = $mysqli->query("SELECT COUNT(*) FROM borrowers WHERE status = 'Borrowed'")->fetch_row()[0];

// 2. Fetch only active borrowers for the table
$recent_loans = $mysqli->query("SELECT * FROM borrowers WHERE status = 'Borrowed' ORDER BY date_borrowed DESC LIMIT 5");
?>

<!-- DIRIA ANG Search Section -->
<div class="search-container">
    <h2>Find a Resource</h2>
    <form method="GET" action="books.php">
        <input type="text" name="q" class="search-box" placeholder="Search by Title, Author, or ISBN...">
        <button type="submit" class="btn-search">Search Catalog</button>
    </form>
</div>

<!-- DIRIA ANG Dashboard Section -->
<div class="container">
    <div class="card">
        <h3>Library Overview</h3>
        <p><strong>Registered Students:</strong> <?php echo $student_count; ?></p>
        <p><strong>Total Books:</strong> <?php echo $total_books; ?></p>
        <p><strong>Currently Borrowed:</strong> <?php echo $borrowed_count; ?></p>
        <p><strong>System Status:</strong> Operational</p>
    </div>
</div>

<?php include_once "footer.php"; ?>