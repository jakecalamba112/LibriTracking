<?php
require_once "config.php";

if (isset($_GET['return_id'])) {
    $borrower_id = intval($_GET['return_id']);
    $book_id = intval($_GET['book_id']);

    // Return the stock
    $mysqli->query("UPDATE books SET stocks = stocks + 1 WHERE id = $book_id");

    // UPDATE the status and set the current time as return_date
    $mysqli->query("UPDATE borrowers SET status = 'Returned', return_date = NOW() WHERE id = $borrower_id");

    header("Location: borrowers.php?msg=returned");
    exit;
}

include_once "header.php";

// Query for Active Borrowers (Status = Borrowed)
// CHANGED 's.course' to 's.grade_level'
$active_query = "SELECT b.*, s.first_name, s.last_name, s.grade_level, s.email, s.contact_number 
                 FROM borrowers b
                 JOIN students s ON b.lrn = s.lrn
                 WHERE b.status = 'Borrowed'
                 ORDER BY b.date_borrowed DESC";
$active_res = $mysqli->query($active_query);

// Query for Return History (Status = Returned)
// CHANGED 's.course' to 's.grade_level'
$history_query = "SELECT b.*, s.first_name, s.last_name, s.grade_level
                  FROM borrowers b
                  JOIN students s ON b.lrn = s.lrn
                  WHERE b.status = 'Returned'
                  ORDER BY b.return_date DESC";
$history_res = $mysqli->query($history_query);
?>

<div class="main-container" style="padding: 20px; max-width: 1200px; margin: 0 auto;">

    <div class="table-card" style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); margin-bottom: 40px; border: 1px solid #eee;">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h2 style="margin:0; color: var(--primary-blue); font-size: 1.5rem;">Active Borrowers</h2>
                <p style="margin:5px 0; color: #666; font-size: 0.85rem;">Currently unreturned books from the Student System</p>
            </div>
            <a href="borrow_book.php" class="btn-home" style="padding:10px 20px; text-decoration:none; border-radius:6px; font-weight:bold; font-size: 0.9rem;">+ Add Borrow Book</a>
        </div>

        <table style="width:100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead>
                <tr style="background: #fcfcfc; border-bottom: 2px solid #eee;">
                    <th style="padding:12px; text-align:left; color: black;">LRN</th>
                    <th style="padding:12px; text-align:left; color: black;">Name & Grade</th>
                    <th style="padding:12px; text-align:left; color: black;">Book Info</th>
                    <th style="padding:12px; text-align:left; color: black;">Contact/Gmail</th>
                    <th style="padding:12px; text-align:left; color: black;">Schedule</th>
                    <th style="padding:12px; text-align:center; color: black;">Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($active_res && $active_res->num_rows > 0): ?>
                    <?php while ($row = $active_res->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid #fafafa;">
                            <td style="padding:15px; font-family: monospace; font-weight: bold; color: #333;">
                                <?php echo htmlspecialchars($row['lrn']); ?>
                            </td>
                            <td style="padding:15px;">
                                <div style="font-weight: 600;"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></div>
                                <!-- CHANGED to grade_level -->
                                <div style="font-size: 0.75rem; color: #999;"><?php echo htmlspecialchars($row['grade_level']); ?></div>
                            </td>
                            <td style="padding:15px;">
                                <div style="color: var(--primary-blue); font-weight: 500;"><?php echo htmlspecialchars($row['book_borrowed']); ?></div>
                            </td>
                            <td style="padding:15px;">
                                <div style="font-size: 0.8rem;"><?php echo htmlspecialchars($row['email']); ?></div>
                                <div style="font-size: 0.8rem; color: #777;"><?php echo htmlspecialchars($row['contact_number']); ?></div>
                            </td>
                            <td style="padding:15px;">
                                <div style="margin-bottom: 5px;">
                                    <span style="font-size: 0.7rem; color: #3498db; background: #ebf5fb; padding: 3px 8px; border-radius: 12px; font-weight: bold;">
                                        BORROWED: <?php echo date('M d, Y', strtotime($row['date_borrowed'])); ?>
                                    </span>
                                </div>
                                <div>
                                    <?php if (!empty($row['due_date']) && $row['due_date'] != '0000-00-00'): ?>
                                        <span style="font-size: 0.7rem; color: #e67e22; background: #fef5ec; padding: 3px 8px; border-radius: 12px; font-weight: bold;">
                                            DUE: <?php echo date('M d, Y', strtotime($row['due_date'])); ?>
                                        </span>
                                    <?php else: ?>
                                        <span style="font-size: 0.7rem; color: #999; background: #f5f5f5; padding: 3px 8px; border-radius: 12px; font-weight: bold;">
                                            DUE: NOT SET
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td style="padding:15px; text-align:center;">
                                <a href="borrowers.php?return_id=<?php echo $row['id']; ?>&book_id=<?php echo $row['book_id']; ?>"
                                    onclick="return confirm('Confirm book return?');"
                                    style="text-decoration:none; background: #a72828; color: white; padding: 8px 15px; border-radius: 6px; font-size: 0.75rem; font-weight: bold; transition: 0.3s;">
                                    Return Book
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding:40px; text-align:center; color:#ccc;">No active borrowers found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="table-card" style="background: #fdfdfd; padding: 25px; border-radius: 12px; border: 1px solid #eee;">
        <h3 style="margin:0; color: var(--primary-blue); font-size: 1.5rem;">
            <i class="fa fa-history" style="margin-right: 10px;"></i> Return Books History
        </h3>

        <table style="width:100%; border-collapse: collapse; font-size: 0.85rem;">
            <thead>
                <tr style="border-bottom: 1px solid #ddd;">
                    <th style="padding:10px; text-align:left; color: black;">Student</th>
                    <th style="padding:10px; text-align:left; color: black;">Book Title</th>
                    <th style="padding:10px; text-align:left; color: black;">Borrowed On</th>
                    <th style="padding:10px; text-align:left; color: black;">Returned On</th>
                    <th style="padding:10px; text-align:center; color: black;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($history_res && $history_res->num_rows > 0): ?>
                    <?php while ($h_row = $history_res->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid #eee; background: white;">
                            <td style="padding:12px;">
                                <?php echo htmlspecialchars($h_row['first_name'] . ' ' . $h_row['last_name']); ?>
                                <!-- Added grade_level here as well for consistency -->
                                <div style="font-size: 0.7rem; color: #999;"><?php echo htmlspecialchars($h_row['grade_level']); ?></div>
                            </td>
                            <td style="padding:12px; font-style: italic;"><?php echo htmlspecialchars($h_row['book_borrowed']); ?></td>
                            <td style="padding:12px; color: #888;"><?php echo date('M d, Y', strtotime($h_row['date_borrowed'])); ?></td>
                            <td style="padding:12px; font-weight: bold; color: #28a745;">
                                <?php echo date('M d, Y | h:i A', strtotime($h_row['return_date'])); ?>
                            </td>
                            <td style="padding:12px; text-align:center;">
                                <span style="background: #e8f5e9; color: #2e7d32; padding: 4px 10px; border-radius: 4px; font-size: 0.7rem; font-weight: bold;">COMPLETE</span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="padding:20px; text-align:center; color:#bbb;">No history records available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once "footer.php"; ?>