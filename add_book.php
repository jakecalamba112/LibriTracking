<?php
require_once "config.php";

$error_msg = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_book'])) {
    $isbn      = $mysqli->real_escape_string($_POST['isbn']);
    $title     = $mysqli->real_escape_string($_POST['title']);
    $author    = $mysqli->real_escape_string($_POST['author']);
    $publisher = $mysqli->real_escape_string($_POST['publisher']);
    $pub_year  = intval($_POST['pub_year']);
    $genre     = $mysqli->real_escape_string($_POST['genre']);
    $stocks    = intval($_POST['stocks']);
    $format    = $mysqli->real_escape_string($_POST['format']);
    $location  = $mysqli->real_escape_string($_POST['location']);






    // Check if ISBN already exists
    $check_isbn = $mysqli->query("SELECT id FROM books WHERE isbn = '$isbn'");
    
    if ($check_isbn->num_rows > 0) {
        $error_msg = "⚠️ Error: A book with ISBN <b>$isbn</b> already exists!";
    } else {
        // SQL Command (Photo is handled as a 'placeholder' for now)
        $sql = "INSERT INTO books (isbn, title, author, publisher, pub_year, genre, stocks, format, location, status) 
                VALUES ('$isbn', '$title', '$author', '$publisher', $pub_year, '$genre', $stocks, '$format', '$location', 'Available')";

        if ($mysqli->query($sql)) {
            header("Location: books.php?success=1");
            exit;
        } else {
            $error_msg = "Database Error: " . $mysqli->error;
        }
    }
}

include_once "header.php";
?>







<div class="container">
    <div class="card" style="max-width: 900px; margin: auto;">
        <h2 style="color: var(--primary-blue); border-bottom: 2px solid var(--accent-gold); padding-bottom: 10px;">Catalog New Book</h2>




        <?php if(!empty($error_msg)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                <?php echo $error_msg; ?>
            </div>
        <?php endif; ?>





        <form method="POST" enctype="multipart/form-data">
            <div style="display: flex; gap: 30px;">
                





                <div style="flex: 1; text-align: center; border-right: 1px solid #eee; padding-right: 30px;">
                    <label style="font-weight: bold; display: block; margin-bottom: 10px;">Book Cover Photo</label>
                    <div id="photo-preview" style="width: 200px; height: 280px; background: #f0f0f0; border: 2px dashed #ccc; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; color: #999; border-radius: 8px;">
                        <span>No Image Selected</span>
                    </div>
                    <input type="file" name="book_image" accept="image/*" style="font-size: 0.8rem;">
                    <p style="font-size: 0.75rem; color: #888; margin-top: 10px;">(Supported: JPG, PNG. Max 2MB)</p>
                </div>






                <div style="flex: 2;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div style="grid-column: span 2;">
                            <label>Book Title</label>
                            <input type="text" name="title" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <div>
                            <label>ISBN</label>
                            <input type="text" name="isbn" required placeholder="000-000-000" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <div>
                            <label>Author</label>
                            <input type="text" name="author" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <div>
                            <label>Genre</label>
                            <select name="genre" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                                <option>Technology</option>
                                <option>Science</option>
                                <option>History</option>
                                <option>Fiction</option>
                                <option>General</option>
                            </select>
                        </div>
                        <div>
                            <label>Stocks</label>
                            <input type="number" name="stocks" value="1" min="1" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <div>
                            <label>Format</label>
                            <select name="format" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                                <option>Hardcover</option>
                                <option>Paperback</option>
                                <option>eBook</option>
                            </select>
                        </div>
                        <div>
                            <label>Shelf Location</label>
                            <input type="text" name="location" placeholder="e.g. Shelf A-1" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <input type="hidden" name="publisher" value="N/A">
                        <input type="hidden" name="pub_year" value="2024">
                    </div>
                </div>
            </div>







            

            <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
                <a href="books.php" style="padding: 12px 25px; text-decoration: none; color: #666; background: #eee; border-radius: 4px;">Cancel</a>
                <button type="submit" name="add_book" class="btn-home" style="padding: 12px 40px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">Save Book to Inventory</button>
            </div>
        </form>
    </div>
</div>

<?php include_once "footer.php"; ?>