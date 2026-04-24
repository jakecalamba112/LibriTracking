<?php
require_once "config.php";
include_once "header.php";

// Fetch borrower data along with their count of currently borrowed books
$borrower_sql = "
    SELECT 
        br.student_id, 
        br.full_name, 
        br.grade_section, 
        br.status,
        (SELECT COUNT(l.id) FROM loans l WHERE l.borrower_id = br.id AND l.return_date IS NULL) as books_borrowed
    FROM borrowers br
    ORDER BY br.full_name ASC
";
$borrower_result = $mysqli->query($borrower_sql);
?>
<title>TNTS | Borrower Records</title>
<style>
    .main-container {
        max-width: 1100px;
        margin: 30px auto;
        padding: 20px;
    }

    .table-card {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: var(--primary-blue);
        border-bottom: 3px solid var(--accent-gold);
        padding-bottom: 10px;
        display: inline-block;
    }

    .badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
    }

    .active {
        background: #d4edda;
        color: #155724;
    }

    /* Clear */
    .blocked {
        background: #f8d7da;
        color: #721c24;
    }

    /* Overdue */
</style>

<div class="main-container">
    <div class="table-card">
        <h2>Borrower Registry</h2>
        <p>List of students currently registered in the system.</p>

        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Full Name</th>
                    <th>Grade/Section</th>
                    <th>Books Borrowed</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($borrower_result->num_rows > 0): ?>
                    <?php while ($row = $borrower_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['grade_section']); ?></td>
                            <td><?php echo $row['books_borrowed']; ?></td>
                            <td>
                                <?php if ($row['status'] == 'Clear'): ?>
                                    <span class="badge active">Clear</span>
                                <?php else: ?>
                                    <span class="badge blocked">Overdue</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No borrowers found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once "footer.php"; ?>