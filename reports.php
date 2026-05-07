<?php
require_once "config.php";

// 1. Handle Saving Report (Archive Logic)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_report'])) {
    $current_month = date('m');
    $current_year = date('Y');

    // Count transactions for this month
    $count_res = $mysqli->query("SELECT COUNT(*) as total FROM borrowers WHERE MONTH(date_borrowed) = $current_month AND YEAR(date_borrowed) = $current_year");
    $total = $count_res->fetch_assoc()['total'];

    $title = $mysqli->real_escape_string("Monthly Summary - " . date('F Y'));
    $summary = $mysqli->real_escape_string("Total transactions: " . $total);

    $mysqli->query("INSERT INTO reports (report_title, total_transactions, report_summary) VALUES ('$title', $total, '$summary')");

    header("Location: reports.php?archived=1");
    exit;
}

include_once "header.php";

// 2. Fetch Dashboard Stats
$today = date('Y-m-d');
$month_count = $mysqli->query("SELECT COUNT(*) FROM borrowers WHERE MONTH(date_borrowed) = MONTH(CURDATE())")->fetch_row()[0];

// 3. Fetch Detailed List with Overdue Logic
$details_query = "SELECT b.lrn, bk.title, b.date_borrowed, b.due_date, b.return_date, b.status 
                  FROM borrowers b 
                  JOIN books bk ON b.book_id = bk.id 
                  WHERE MONTH(b.date_borrowed) = MONTH(CURDATE()) 
                  ORDER BY b.date_borrowed DESC";
$details_res = $mysqli->query($details_query);

// 4. Fetch History
$history_res = $mysqli->query("SELECT * FROM reports ORDER BY generated_date DESC");
?>

<div class="container" style="max-width: 1200px; margin: auto; padding: 20px; font-family: sans-serif;">

    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="margin: 0; color: #2c3e50;">Monthly Report: <?php echo date('F Y'); ?></h2>
            <p style="margin: 5px 0 0; color: #7f8c8d;">Total Monthly Transactions: <strong><?php echo $month_count; ?></strong></p>
        </div>
        <div style="display: flex; gap: 10px;">
            <form method="POST">
                <button type="submit" name="save_report" style="background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold;">Save Archive</button>
            </form>
            <button onclick="window.print()" style="background: #34495e; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold;">Print PDF</button>
        </div>
    </div>

    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px;">Current Monthly Details</h3>
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr style="background: #f8f9fa; text-align: left;">
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">LRN</th>
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Book Title</th>
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Borrowed</th>
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Due Date</th>
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $details_res->fetch_assoc()):
                    // LOGIC: Determine Status & Color
                    $is_returned = ($row['status'] == 'Returned');
                    $is_overdue = (!$is_returned && $today > $row['due_date']);

                    if ($is_returned) {
                        $badge_text = "Returned";
                        $badge_color = "#27ae60"; // Green
                    } elseif ($is_overdue) {
                        $badge_text = "OVERDUE";
                        $badge_color = "#e74c3c"; // Red
                    } else {
                        $badge_text = "Out (Pending)";
                        $badge_color = "#f39c12"; // Orange
                    }
                ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px; font-weight: bold;"><?php echo $row['lrn']; ?></td>
                        <td style="padding: 12px;"><?php echo htmlspecialchars($row['title']); ?></td>
                        <td style="padding: 12px;"><?php echo date("M d", strtotime($row['date_borrowed'])); ?></td>
                        <td style="padding: 12px; <?php echo $is_overdue ? 'color: red; font-weight: bold;' : ''; ?>">
                            <?php echo date("M d, Y", strtotime($row['due_date'])); ?>
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: <?php echo $badge_color; ?>; color: white; padding: 4px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: bold;">
                                <?php echo $badge_text; ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div style="background: #ecf0f1; padding: 20px; border-radius: 10px; border: 1px solid #bdc3c7;">
        <h3 style="margin-top: 0;">Archive History</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="text-align: left; color: #7f8c8d; font-size: 0.9rem;">
                <th style="padding: 10px;">Title</th>
                <th style="padding: 10px;">Date Saved</th>
                <th style="padding: 10px;">Total</th>
            </tr>
            <?php while ($h = $history_res->fetch_assoc()): ?>
                <tr style="border-top: 1px solid #dcdde1;">
                    <td style="padding: 10px;"><?php echo htmlspecialchars($h['report_title']); ?></td>
                    <td style="padding: 10px;"><?php echo date("M d, Y", strtotime($h['generated_date'])); ?></td>
                    <td style="padding: 10px; font-weight: bold;"><?php echo $h['total_transactions']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

<?php include_once "footer.php"; ?>