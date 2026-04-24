<?php
require_once "config.php";
include_once "header.php";

// Handle Search Logic
$search = isset($_GET['q']) ? $mysqli->real_escape_string($_GET['q']) : '';
$query = "SELECT * FROM books";
if (!empty($search)) {
    $query .= " WHERE title LIKE '%$search%' OR author LIKE '%$search%' OR isbn LIKE '%$search%'";
}
$query .= " ORDER BY title ASC";
$res = $mysqli->query($query);
?>

<div class="main-container" style="padding: 20px;">
    <div class="table-card" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h2 style="margin:0; color: var(--primary-blue);">Books Inventory</h2>
                <?php if(!empty($search)): ?>
                    <p style="margin:5px 0; color: #666;">Showing results for: "<?php echo htmlspecialchars($search); ?>"</p>
                <?php endif; ?>
            </div>
            <a href="add_book.php" class="btn-home" style="padding:10px 20px; text-decoration:none; border-radius:5px; font-weight:bold;">+ Add New Book</a>
        </div>

        <table style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding:12px; text-align:left;">ISBN</th>
                    <th style="padding:12px; text-align:left;">Book Title</th>
                    <th style="padding:12px; text-align:left;">Author</th>
                    <th style="padding:12px; text-align:left;">Genre</th>
                    <th style="padding:12px; text-align:left;">Format</th>
                    <th style="padding:12px; text-align:center;">Stock</th>
                    <th style="padding:12px; text-align:center;">Status</th>
                    <th style="padding:12px; text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $res->fetch_assoc()): 
                    $status_color = ($row['stocks'] > 0) ? '#28a745' : '#dc3545';
                    $status_bg = ($row['stocks'] > 0) ? '#d4edda' : '#f8d7da';
                ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding:12px; font-family: monospace; color: #555;"><?php echo htmlspecialchars($row['isbn']); ?></td>
                        <td style="padding:12px; font-weight:bold;"><?php echo htmlspecialchars($row['title']); ?></td>
                        <td style="padding:12px;"><?php echo htmlspecialchars($row['author']); ?></td>
                        <td style="padding:12px;"><span style="font-size:0.85rem; background:#e9ecef; padding:3px 8px; border-radius:12px;"><?php echo htmlspecialchars($row['genre']); ?></span></td>
                        <td style="padding:12px; color: #666;"><?php echo htmlspecialchars($row['format']); ?></td>
                        <td style="padding:12px; text-align:center;"><?php echo $row['stocks']; ?></td>
                        <td style="padding:12px; text-align:center;">
                            <span style="padding:5px 10px; border-radius:15px; font-size:0.8rem; font-weight:bold; background: <?php echo $status_bg; ?>; color: <?php echo $status_color; ?>;">
                                <?php echo ($row['stocks'] > 0) ? 'Available' : 'Out of Stock'; ?>
                            </span>
                        </td>
                        <td style="padding:12px; text-align:center;">
                            <a href="edit_book.php?id=<?php echo $row['id']; ?>" style="text-decoration:none; color: var(--primary-blue); font-weight:bold; border: 1px solid var(--primary-blue); padding: 5px 10px; border-radius: 4px; font-size:0.8rem;">Update Stock</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once "footer.php"; ?>