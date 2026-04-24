<?php
require_once "config.php";

$error_msg = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isbn      = $mysqli->real_escape_string($_POST['isbn']);
    $title     = $mysqli->real_escape_string($_POST['title']);
    $author    = $mysqli->real_escape_string($_POST['author']);
    $publisher = $mysqli->real_escape_string($_POST['publisher']);
    $pub_year  = intval($_POST['pub_year']);
    $genre     = $mysqli->real_escape_string($_POST['genre']);
    $stocks    = intval($_POST['stocks']);
    $format    = $mysqli->real_escape_string($_POST['format']);
    $location  = $mysqli->real_escape_string($_POST['location']);

    //Check if ISBN already exists ---
    $check_isbn = $mysqli->query("SELECT id FROM books WHERE isbn = '$isbn'");
    
    if ($check_isbn->num_rows > 0) {
        $error_msg = "⚠️ Error: A book with ISBN <b>$isbn</b> already exists in the system!";
    } else {
        // Proceed with insert if ISBN is unique
        $sql = "INSERT INTO books (isbn, title, author, publisher, pub_year, genre, stocks, format, location, status) 
                VALUES ('$isbn', '$title', '$author', '$publisher', $pub_year, '$genre', $stocks, '$format', '$location', 'Available')";

        if ($mysqli->query($sql)) {
            if (isset($_POST['save_new'])) {
                header("Location: add_book.php?success=1");
                exit;
            } else {
                header("Location: books.php");
                exit;
            }
        } else {
            $error_msg = "Database Error: " . $mysqli->error;
        }
    }
}

include_once "header.php";
?>

<div class="container">
    <?php if (!empty($error_msg)): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            <?php echo $error_msg; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            ✅ Book added successfully! You can add another one below.
        </div>
    <?php endif; ?>
    </div>


    
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px;">
            <h2 style="margin:0;">Add New Book</h2>
            <a href="books.php" style="text-decoration:none; color: #666;">&times; Cancel</a>
        </div>

        <form method="POST">
            <div style="display: flex; gap: 30px; flex-wrap: wrap;">
                
                <div style="flex: 1; min-width: 300px;">
                    <h4 style="color: var(--primary-blue); border-left: 4px solid var(--accent-gold); padding-left: 10px;">Core Information</h4>
                    
                    <div style="margin-bottom:15px;">
                        <label>ISBN / Unique Identifier</label>
                        <input type="text" name="isbn" placeholder="e.g. 978-3-16-148410-0" required style="width:100%; padding:10px; margin-top:5px; border: 1px solid #ccc; border-radius:4px;">
                    </div>

                    <div style="margin-bottom:15px;">
                        <label>Book Title</label>
                        <input type="text" name="title" required style="width:100%; padding:10px; margin-top:5px; border: 1px solid #ccc; border-radius:4px;">
                    </div>

                    <div style="margin-bottom:15px;">
                        <label>Author(s)</label>
                        <input type="text" name="author" required style="width:100%; padding:10px; margin-top:5px; border: 1px solid #ccc; border-radius:4px;">
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <div style="flex: 2;">
                            <label>Publisher</label>
                            <input type="text" name="publisher" style="width:100%; padding:10px; margin-top:5px; border: 1px solid #ccc; border-radius:4px;">
                        </div>
                        <div style="flex: 1;">
                            <label>Year</label>
                            <input type="number" name="pub_year" value="<?php echo date('Y'); ?>" style="width:100%; padding:10px; margin-top:5px; border: 1px solid #ccc; border-radius:4px;">
                        </div>
                    </div>
                </div>

                <div style="flex: 1; min-width: 300px;">
                    <h4 style="color: var(--primary-blue); border-left: 4px solid var(--accent-gold); padding-left: 10px;">Classification & Specs</h4>
                    
                    <div style="margin-bottom:15px;">
                        <label>Genre / Subject</label>
                        <select name="genre" style="width:100%; padding:10px; margin-top:5px; border: 1px solid #ccc; border-radius:4px;">
                            <option>General Fiction</option>
                            <option>Science & Technology</option>
                            <option>History</option>
                            <option>Philosophy</option>
                            <option>Reference / Dictionary</option>
                        </select>
                    </div>

                    <div style="margin-bottom:15px;">
                        <label>Format</label>
                        <select name="format" style="width:100%; padding:10px; margin-top:5px; border: 1px solid #ccc; border-radius:4px;">
                            <option value="Hardcover">Hardcover</option>
                            <option value="Paperback">Paperback</option>
                            <option value="eBook">eBook</option>
                        </select>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <div style="flex: 1;">
                            <label>Number of Copies</label>
                            <input type="number" name="stocks" value="1" min="1" style="width:100%; padding:10px; margin-top:5px; border: 1px solid #ccc; border-radius:4px;">
                        </div>
                        <div style="flex: 1;">
                            <label>Location (Shelf)</label>
                            <input type="text" name="location" placeholder="e.g. Shelf A-12" style="width:100%; padding:10px; margin-top:5px; border: 1px solid #ccc; border-radius:4px;">
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; display: flex; gap: 10px; justify-content: flex-end;">
                <button type="submit" name="save_new" style="padding: 12px 20px; background: #6c757d; color:white; border:none; border-radius:5px; cursor:pointer;">Save & Add Another</button>
                <button type="submit" name="save_close" class="btn-home" style="padding: 12px 25px; border:none; border-radius:5px; cursor:pointer; font-weight:bold;">Save & Close</button>
            </div>
        </form>
    </div>
</div>

<?php include_once "footer.php"; ?>