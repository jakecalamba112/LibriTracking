<?php
require_once "config.php";
include_once "header.php";

// 1. Handle Search and Sort A-Z
$search = isset($_GET['q']) ? $mysqli->real_escape_string($_GET['q']) : '';
$query = "SELECT * FROM books";
if (!empty($search)) {
    $query .= " WHERE title LIKE '%$search%' OR author LIKE '%$search%' OR isbn LIKE '%$search%'";
}
$query .= " ORDER BY title ASC"; // Sorting A-Z as requested
$res = $mysqli->query($query);
?>








<div class="main-container" style="padding: 20px;">
    




    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h2 style="margin:0; color: var(--primary-blue);">Books Catalog</h2>
            <p style="margin:5px 0; color: #666;">Explore our collection alphabetically (A-Z)</p>
        </div>
        <a href="add_book.php" class="btn-home" style="padding:12px 20px; text-decoration:none; border-radius:5px; font-weight:bold;">+ Add New Book</a>
    </div>






    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
        
        <?php if ($res->num_rows > 0): ?>





            <?php while ($row = $res->fetch_assoc()): 
                $status_color = ($row['stocks'] > 0) ? '#28a745' : '#dc3545';
                $status_text = ($row['stocks'] > 0) ? 'Available ('.$row['stocks'].')' : 'Out of Stock';
            ?>
                <div class="book-card" style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); display: flex; flex-direction: column; transition: transform 0.3s;">
                    
                    
                
                
                    <div style="background: #bababa; height: 200px; display: flex; align-items: center; justify-content: center;">
                        <div style="text-align: center; color: black;">
                            <i class="fa fa-book" style="font-size: 3rem; display: block; margin-bottom: 10px;"></i>
                            <span style="font-size: 0.8rem;">No Cover Image</span>
                        </div>
                    </div>

                    
                    
                    
                    
                    <div style="padding: 15px; flex-grow: 1; text-align: center;">
                        <span style="font-family: monospace; font-size: 1rem; color: black;"><?php echo htmlspecialchars($row['isbn']); ?></span>
                        <h4 style="margin: 8px 0; color: var(--primary-blue); min-height: 40px; line-height: 1.2;">
                            <?php echo htmlspecialchars($row['title']); ?>
                        </h4>
                        <p style="margin: 0; font-size: 0.9rem; color: #555; font-weight: 500;">
                            <?php echo htmlspecialchars($row['author']); ?>
                        </p>
                        
                        <div style="margin: 10px 0; font-size: 0.8rem; color: #777;">
                            <span style="background: #eef2f7; padding: 2px 8px; border-radius: 10px;"><?php echo htmlspecialchars($row['genre']); ?></span>
                            <span style="background: #eef2f7; padding: 2px 8px; border-radius: 10px;"><?php echo htmlspecialchars($row['format']); ?></span>
                        </div>

                        <div style="font-size: 0.85rem; font-weight: bold; color: <?php echo $status_color; ?>; margin-bottom: 15px;">
                            ● <?php echo $status_text; ?>
                        </div>

                        <div style="border-top: 1px solid #eee; padding-top: 15px; display: flex; gap: 5px;">
                            <a href="edit_book.php?id=<?php echo $row['id']; ?>" style="flex: 1; text-decoration: none; font-size: 0.8rem; background: var(--primary-blue); color: white; padding: 8px; border-radius: 4px; font-weight: bold;">Update Stock</a>
                        </div>
                    </div>







                </div>
            <?php endwhile; ?>
        
        
        
            <?php else: ?>
            <div style="grid-column: span 4; text-align: center; padding: 50px; color: #999;">
                No books found in the catalog.
            </div>





        <?php endif; ?>
        
    </div>
</div>




<style>
/* Hover effect for the cards */
.book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
}
</style>

<?php include_once "footer.php"; ?>