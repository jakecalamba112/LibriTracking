<?php
require_once "config.php";
include_once "header.php";

$books_res = $mysqli->query("SELECT * FROM books ORDER BY title ASC");
?>



<div class="main-container" style="max-width: 1100px; margin: 20px auto; padding: 0 20px;">
    
    <form action="process_borrow.php" method="POST" id="borrowForm">
        
        <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); margin-bottom: 25px; border: 1px solid #eee;">
            
        
        
        
            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                <div style="width: 30px; height: 30px; background: var(--primary-blue); color: white; display: flex; align-items: center; justify-content: center; border-radius: 6px; font-weight: bold; margin-right: 10px; font-size: 0.9rem;">1</div>
                <h3 style="margin: 0; color: #333; font-size: 1.2rem;">Verify Student & Set Deadline</h3>
            </div>





            
            <div style="display: flex; gap: 20px; align-items: stretch;">




                <div style="width: 220px;">
                    <label style="font-size: 0.75rem; color: #666; font-weight: bold; display: block; margin-bottom: 5px;">Student ID</label>
                    <input type="number" id="sid_input" name="student_id" placeholder="Type Student ID..." required 
                           style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 1rem; outline: none; box-sizing: border-box;">
                </div>






                <div style="width: 220px;">
                    <label style="font-size: 0.75rem; color: #666; font-weight: bold; display: block; margin-bottom: 5px;">Return Due Date</label>
                    <input type="date" name="due_date" required 
                           min="<?php echo date('Y-m-d'); ?>" 
                           style="width: 100%; padding: 11px; border: 2px solid #ddd; border-radius: 8px; font-size: 1rem; outline: none; box-sizing: border-box;">
                </div>





                <div id="student_display" style="flex: 1; display: none; background: #fdfdfd; padding: 15px; border-radius: 8px; border: 1px dashed #cbd5e0;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 0.85rem;">
                        <div><small style="color:black;">Name:</small> <span id="s_name" style="font-weight: bold; color: var(--primary-blue);"></span></div>
                        <div><small style="color:black;">Contact Nmber:</small> <span id="s_contact" style="font-weight: bold;"></span></div>
                        <div><small style="color:black;">Course:</small> <span id="s_course" style="font-weight: bold;"></span></div>
                        <div><small style="color:black;">Status:</small> <span style="color: green; font-weight: bold;">● Verified</span></div>
                        <div><small style="color:black;">Gmail:</small> <span id="s_email" style="font-weight: bold;"></span></div>
                    </div>
                </div>




            </div>
        </div>





        <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 1px solid #eee;">
            



            <div style="display: flex; align-items: center; margin-bottom: 25px;">
                <div style="flex: 1; display: flex; align-items: center;">
                    <div style="width: 30px; height: 30px; background: var(--accent-gold); color: var(--primary-blue); display: flex; align-items: center; justify-content: center; border-radius: 6px; font-weight: bold; margin-right: 10px; font-size: 0.9rem;">2</div>
                    <h3 style="margin: 0; color: #333; font-size: 1.2rem; white-space: nowrap;">Select Books</h3>
                </div>
                
                <div style="flex: 2; display: flex; justify-content: center;">
                    <input type="text" id="book_search" onkeyup="filterBooks()" placeholder="Search title, author, or ISBN..." 
                           style="width: 100%; max-width: 400px; padding: 10px 20px; border-radius: 25px; background: #fcfcfc; font-size: 0.9rem; outline: none; border: 1px solid #ddd;">
                </div>

                <div style="flex: 1;"></div>
            </div>





            <div id="booksGrid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                <?php while($row = $books_res->fetch_assoc()): 
                    $is_out = ($row['stocks'] <= 0);
                    $status_color = (!$is_out) ? '#28a745' : '#dc3545';
                    $status_text = (!$is_out) ? 'Available ('.$row['stocks'].')' : 'Out of Stock';
                ?>
                    <div class="book-select-card <?php echo $is_out ? 'out' : ''; ?>" 
                         onclick="toggleBook(this, <?php echo $row['id']; ?>)"
                         data-title="<?php echo strtolower($row['title'] . ' ' . $row['author'] . ' ' . $row['isbn']); ?>"
                         style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border: 1px solid #eee; display: flex; flex-direction: column; transition: 0.3s; cursor: pointer; position: relative;">
                        




                        <div style="background: #bababa; height: 160px; display: flex; align-items: center; justify-content: center; position: relative;">
                            <div style="text-align: center; color: black;">
                                <i class="fa fa-book" style="font-size: 2.5rem; display: block; margin-bottom: 5px;"></i>
                                <span style="font-size: 0.7rem;">No Cover Image</span>
                            </div>
                            <div class="check-badge" style="display:none; position: absolute; top: 10px; right: 10px; background: #28a745; color: white; border-radius: 50%; width: 25px; height: 25px; align-items: center; justify-content: center; font-size: 0.8rem;">
                                ✓
                            </div>
                        </div>






                        <div style="padding: 15px; flex-grow: 1; text-align: center;">
                            <span style="font-family: monospace; font-size: 0.7rem; color: black;"><?php echo htmlspecialchars($row['isbn']); ?></span>
                            <h4 style="margin: 5px 0; color: var(--primary-blue); font-size: 0.95rem; line-height: 1.2; height: 35px; overflow: hidden;">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </h4>
                            <p style="margin: 0; font-size: 0.85rem; color: #555; font-weight: 500;">
                                <?php echo htmlspecialchars($row['author']); ?>
                            </p>
                            




                            <div style="margin: 10px 0; font-size: 0.7rem; color: #777;">
                                <span style="background: #eef2f7; padding: 2px 8px; border-radius: 10px;"><?php echo htmlspecialchars($row['genre']); ?></span>
                                <span style="background: #eef2f7; padding: 2px 8px; border-radius: 10px;"><?php echo htmlspecialchars($row['format']); ?></span>
                            </div>

                            <div style="font-size: 0.8rem; font-weight: bold; color: <?php echo $status_color; ?>;">
                                ● <?php echo $status_text; ?>
                            </div>




                        </div>
                        <input type="checkbox" name="book_ids[]" value="<?php echo $row['id']; ?>" style="display:none;">
                    </div>
                <?php endwhile; ?>
            </div>







            <div style="margin-top: 40px; text-align: center;">
                <button type="submit" class="btn-home" style="padding: 15px 60px; font-size: 1rem; border-radius: 8px; border: none; cursor: pointer; transition: 0.3s;">
                    Confirm Borrowing
                </button>
            </div>



        </div>
    </form>
</div>













<style>
.book-select-card:hover:not(.out) {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
    border-color: #28a745 !important;
}
.book-select-card.selected {
    border: 2px solid #28a745 !important;
    background: #f0fff4 !important;
}
.book-select-card.selected .check-badge {
    display: flex !important;
}
.book-select-card.out {
    background: #f5f5f5;
    opacity: 0.6;
    cursor: not-allowed;
}
.book-select-card.out:hover {
    border-color: #dc3545 !important;
}
</style>

<script>
document.getElementById('sid_input').addEventListener('input', function() {
    let sid = this.value;
    if(sid) {
        fetch('get_student.php?id=' + sid)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.getElementById('student_display').style.display = 'block';
                document.getElementById('s_name').innerText = data.first_name + " " + data.last_name;
                document.getElementById('s_course').innerText = data.course;
                document.getElementById('s_email').innerText = data.email;
                document.getElementById('s_contact').innerText = data.contact_number;
            } else {
                document.getElementById('student_display').style.display = 'none';
            }
        });
    } else {
        document.getElementById('student_display').style.display = 'none';
    }
});

function toggleBook(card, id) {
    if(card.classList.contains('out')) return;
    card.classList.toggle('selected');
    let checkbox = card.querySelector('input[type="checkbox"]');
    checkbox.checked = !checkbox.checked;
}

function filterBooks() {
    let input = document.getElementById('book_search').value.toLowerCase();
    let cards = document.querySelectorAll('.book-select-card');
    cards.forEach(card => {
        let text = card.getAttribute('data-title');
        card.style.display = text.includes(input) ? "block" : "none";
    });
}
</script>