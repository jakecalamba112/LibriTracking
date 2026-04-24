<?php
require_once "config.php";

// Handle Return Logic
if (isset($_GET['return_id'])) {
    $borrower_id = intval($_GET['return_id']);
    $book_id = intval($_GET['book_id']);

    // 1. Return the stock to the books table
    $mysqli->query("UPDATE books SET stocks = stocks + 1 WHERE id = $book_id");

    // 2. Remove from active borrowers (or update status to 'Returned')
    $mysqli->query("DELETE FROM borrowers WHERE id = $borrower_id");

    header("Location: borrowers.php?msg=returned");
    exit;
}

include_once "header.php";

// Fetch all active transactions
$res = $mysqli->query("SELECT * FROM borrowers ORDER BY date_borrowed DESC");
?>

<div class="main-container" style="padding: 20px;">
    <div class="table-card" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h2 style="margin:0; color: var(--primary-blue);">Active Borrowers</h2>
                <p style="margin:5px 0; color: #666; font-size: 0.9rem;">Registry of books currently outside the library.</p>
            </div>
            <a href="borrow_book.php" class="btn-home" style="padding:10px 20px; text-decoration:none; border-radius:5px; font-weight:bold;">+ New Transaction</a>
        </div>

        <?php if(isset($_GET['msg'])): ?>
            <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 0.9rem; border: 1px solid #c3e6cb;">
                ✅ Book has been successfully returned to inventory.
            </div>
        <?php endif; ?>

        <table style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding:15px; text-align:left;">Borrower Name</th>
                    <th style="padding:15px; text-align:left;">Book Title</th>
                    <th style="padding:15px; text-align:center;">Date Borrowed</th>
                    <th style="padding:15px; text-align:center;">Status</th>
                    <th style="padding:15px; text-align:center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($res->num_rows > 0): ?>
                    <?php while ($row = $res->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding:15px;">
                                <div style="font-weight:bold; color: #333;">
                                    <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                                </div>
                                <small style="color:#888;">ID: #TRX-<?php echo $row['id']; ?></small>
                            </td>
                            <td style="padding:15px; color: var(--primary-blue); font-weight: 500;">
                                <?php echo htmlspecialchars($row['book_borrowed']); ?>
                            </td>
                            <td style="padding:15px; text-align:center; color: #666;">
                                <?php echo date("M d, Y", strtotime($row['date_borrowed'])); ?>
                            </td>
                            <td style="padding:15px; text-align:center;">
                                <span style="background: #fff3cd; color: #856404; padding: 5px 12px; border-radius: 15px; font-size: 0.75rem; font-weight: bold; border: 1px solid #ffeeba;">
                                    BORROWED
                                </span>
                            </td>
                            <td style="padding:15px; text-align:center;">
                                <a href="borrowers.php?return_id=<?php echo $row['id']; ?>&book_id=<?php echo $row['book_id']; ?>" 
                                   onclick="return confirm('Confirm book return? Stock will be updated.');"
                                   style="text-decoration:none; background: #dc3545; color: white; padding: 6px 12px; border-radius: 4px; font-size: 0.8rem; font-weight: bold;">
                                   Return Book
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="padding:40px; text-align:center; color: #999;">
                            <img src="https://via.placeholder.com/50" style="opacity: 0.3; margin-bottom: 10px;"><br>
                            No active borrow transactions found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once "footer.php"; ?>