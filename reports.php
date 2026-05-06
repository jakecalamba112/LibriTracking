<?php
require_once "config.php";

// Logic for saving a snapshot of the current month into history
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_report'])) {
    $current_month = date('m');
    $current_year = date('Y');

    // Count total transactions for the current month
    $res = $mysqli->query("SELECT COUNT(*) as total FROM borrowers WHERE MONTH(date_borrowed) = $current_month AND YEAR(date_borrowed) = $current_year");
    $total = $res->fetch_assoc()['total'];

    $title = "Monthly Summary - " . date('F Y');
    $summary = "Total transactions for " . date('F Y') . ": " . $total;

    // Sanitize title for security
    $title_esc = $mysqli->real_escape_string($title);
    $summary_esc = $mysqli->real_escape_string($summary);

    $mysqli->query("INSERT INTO reports (report_title, total_transactions, report_summary) 
                    VALUES ('$title_esc', $total, '$summary_esc')");

    header("Location: reports.php"); // Redirect to self to prevent form resubmission
    exit;
}

include_once "header.php";

// 1. Get the count for the header
$current_month_count = $mysqli->query("SELECT COUNT(*) FROM borrowers WHERE MONTH(date_borrowed) = MONTH(CURDATE()) AND YEAR(date_borrowed) = YEAR(CURDATE())")->fetch_row()[0];

// 2. Fetch the detailed list of current borrowers (Joining books and borrowers)
// Adjust 'lrn', 'due_date', and 'return_date' if your column names differ slightly
$details_query = "SELECT b.lrn, bk.title, b.date_borrowed, b.due_date, b.return_date, b.status 
                  FROM borrowers b 
                  JOIN books bk ON b.book_id = bk.id 
                  WHERE MONTH(b.date_borrowed) = MONTH(CURDATE()) 
                  ORDER BY b.date_borrowed DESC";
$details_res = $mysqli->query($details_query);

// 3. Fetch History
$history_res = $mysqli->query("SELECT * FROM reports ORDER BY generated_date DESC");
?>

<div class="container">
    <!-- Header Section -->
    <div class="card">
        <h2>Monthly Summary - <?php echo date('F Y'); ?></h2>
        <p><strong>Current Transactions:</strong> <?php echo $current_month_count; ?></p>

        <form method="POST" style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" name="save_report"
                style="padding:12px 25px; border:none; border-radius:5px; cursor:pointer; background-color: var(--primary-blue); color: white; font-weight: bold; transition: 0.3s;">
                Save Report to History
            </button>

            <button type="button" onclick="window.print()"
                style="padding:12px 25px; border:none; border-radius:5px; cursor:pointer; background-color: #6c757d; color: white; font-weight: bold; transition: 0.3s;">
                Print Page
            </button>
        </form>
    </div>

    <!-- NEW SECTION: Detailed Transactions List -->
    <div class="card">
        <h2>Current Transaction Details</h2>
        <table class="table-styled">
            <thead>
                <tr>
                    <th>Borrower LRN</th>
                    <th>Book Title</th>
                    <th>Borrowed Date</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($details_res && $details_res->num_rows > 0): ?>
                    <?php while ($row = $details_res->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['lrn']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo date("M d, Y", strtotime($row['date_borrowed'])); ?></td>
                            <td><?php echo date("M d, Y", strtotime($row['due_date'])); ?></td>
                            <td>
                                <?php echo ($row['return_date']) ? date("M d, Y", strtotime($row['return_date'])) : '<span style="color:#999 italic">Pending</span>'; ?>
                            </td>
                            <td>
                                <?php
                                $status = $row['status'];
                                $color = ($status == 'Returned') ? '#28a745' : '#dc3545';
                                // Use 'Not Available' logic if book is still out
                                $display_status = ($status == 'Borrowed') ? 'Not Available' : $status;
                                echo "<span style='color:$color; font-weight:bold;'>$display_status</span>";
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">No transactions found for this month.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Report History Section -->
    <div class="card">
        <h2>Report History</h2>
        <table>
            <thead>
                <tr>
                    <th>Report Title</th>
                    <th>Date Generated</th>
                    <th>Total Transactions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $history_res->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['report_title']); ?></td>
                        <td><?php echo date("M d, Y", strtotime($row['generated_date'])); ?></td>
                        <td><?php echo $row['total_transactions']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once "footer.php"; ?>