<?php
require_once "config.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $res = $mysqli->query("SELECT * FROM books WHERE id = $id");
    $book = $res->fetch_assoc();
} else {
    header("Location: books.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_inventory'])) {
    $new_stocks = intval($_POST['stocks']);
    $new_location = $mysqli->real_escape_string($_POST['location']);
    $id = intval($_POST['book_id']);

    $mysqli->query("UPDATE books SET stocks = $new_stocks, location = '$new_location' WHERE id = $id");
    header("Location: books.php?msg=updated");
    exit;
}

include_once "header.php";
?>

<div class="container" style="display:flex; justify-content:center; align-items:center; min-height: 70vh;">
    <div class="card" style="width:100%; max-width: 500px; border-top: 5px solid var(--accent-gold);">
        <h2 style="margin-top:0;">Inventory Management</h2>
        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <p style="margin:0; font-size:0.9rem; color:#666;">Editing details for:</p>
            <h3 style="margin:5px 0; color: var(--primary-blue);"><?php echo $book['title']; ?></h3>
            <span style="font-family:monospace; background:#eee; padding:2px 5px; border-radius:4px;">ISBN: <?php echo $book['isbn']; ?></span>
        </div>

        <form method="POST">
            <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
            
            <div style="margin-bottom:20px;">
                <label style="display:block; margin-bottom:8px; font-weight:bold;">Total Copies Available</label>
                <div style="display:flex; align-items:center; gap:10px;">
                    <input type="number" name="stocks" value="<?php echo $book['stocks']; ?>" min="0" 
                           style="flex:1; padding:12px; font-size:1.2rem; border:1px solid #ccc; border-radius:5px; text-align:center;">
                    <span style="color:#666;">Units</span>
                </div>
                <small style="color:#888;">Increase this number when new copies are purchased.</small>
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block; margin-bottom:8px; font-weight:bold;">Update Storage Location</label>
                <input type="text" name="location" value="<?php echo htmlspecialchars($book['location']); ?>" 
                       style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px; box-sizing:border-box;">
            </div>

            <div style="display:flex; gap:10px; margin-top:30px;">
                <a href="books.php" style="flex:1; text-align:center; padding:12px; background:#eee; color:#333; text-decoration:none; border-radius:5px;">Back to Inventory</a>
                <button type="submit" name="update_inventory" class="btn-home" 
                        style="flex:2; padding:12px; border:none; border-radius:5px; font-weight:bold; cursor:pointer;">Update Record</button>
            </div>
        </form>
    </div>
</div>

<?php include_once "footer.php"; ?>