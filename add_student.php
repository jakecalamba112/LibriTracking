<?php
// 1. Initialize environment and database connection
require_once "config.php";
$error_msg = "";
$success_msg = "";

// 2. Handle Form Submission (Registration)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_student'])) {
    // Using ternary operators to prevent "Undefined array key" warnings
    $lrn            = isset($_POST['lrn']) ? $mysqli->real_escape_string($_POST['lrn']) : '';
    $fname          = isset($_POST['first_name']) ? $mysqli->real_escape_string($_POST['first_name']) : '';
    $lname          = isset($_POST['last_name']) ? $mysqli->real_escape_string($_POST['last_name']) : '';
    $grade_level    = isset($_POST['grade_level']) ? $mysqli->real_escape_string($_POST['grade_level']) : '';
    $section        = isset($_POST['section']) ? $mysqli->real_escape_string($_POST['section']) : '';
    $contact        = isset($_POST['contact']) ? $mysqli->real_escape_string($_POST['contact']) : '';
    $email          = isset($_POST['email']) ? $mysqli->real_escape_string($_POST['email']) : '';
    $parent_contact = isset($_POST['parent_contact']) ? $mysqli->real_escape_string($_POST['parent_contact']) : '';

    // Validate LRN (Must be exactly 12 digits)
    if (strlen($lrn) != 12 || !ctype_digit($lrn)) {
        $error_msg = "⚠️ Error: LRN must be exactly 12 digits!";
    } else {
        // Check for duplicates in LRN, Email, or Contact Number
        $check_duplicate = $mysqli->query("SELECT * FROM students WHERE lrn = '$lrn' OR email = '$email' OR contact_number = '$contact'");

        if ($check_duplicate->num_rows > 0) {
            $row = $check_duplicate->fetch_assoc();
            if ($row['lrn'] == $lrn) {
                $error_msg = "⚠️ Error: LRN <b>$lrn</b> is already registered!";
            } elseif ($row['email'] == $email) {
                $error_msg = "⚠️ Error: The Gmail address <b>$email</b> is already registered!";
            } else {
                $error_msg = "⚠️ Error: The Contact Number <b>$contact</b> is already linked to another student!";
            }
        } else {
            // Insert into database
            $sql = "INSERT INTO students (lrn, first_name, last_name, grade_level, section, contact_number, email, parent_contact) 
                    VALUES ('$lrn', '$fname', '$lname', '$grade_level', '$section', '$contact', '$email', '$parent_contact')";

            if ($mysqli->query($sql)) {
                header("Location: add_student.php?success=1");
                exit;
            } else {
                $error_msg = "Database Error: " . $mysqli->error;
            }
        }
    }
}

include_once "header.php";

// 3. Handle Search Logic
$search = isset($_GET['student_q']) ? $mysqli->real_escape_string($_GET['student_q']) : '';
$query = "SELECT * FROM students";
if (!empty($search)) {
    $query .= " WHERE lrn LIKE '%$search%' OR first_name LIKE '%$search%' OR last_name LIKE '%$search%'";
}
$query .= " ORDER BY lrn ASC";
$student_list = $mysqli->query($query);
?>

<div class="container">

    <!-- Notifications -->
    <?php if (!empty($error_msg)): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            <?php echo $error_msg; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            ✅ Student registered successfully!
        </div>
    <?php endif; ?>

    <!-- Registration Form -->
    <div class="card" style="max-width: 800px; margin: auto; margin-bottom: 30px;">
        <h2 style="color: var(--primary-blue); border-bottom: 2px solid var(--accent-gold); padding-bottom: 10px;">
            High School Student Registration
        </h2>

        <form method="POST" action="add_student.php">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px;">

                <div style="grid-column: span 2;">
                    <label>LRN (Learner Reference Number)</label>
                    <input type="text" name="lrn" required maxlength="12"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12);"
                        placeholder="Enter 12-digit LRN"
                        style="width:98%; padding:12px; border:1px solid #ccc; border-radius:5px; font-size: 1.1em; letter-spacing: 2px;">
                </div>

                <div>
                    <label>First Name</label>
                    <input type="text" name="first_name" required style="width:98%; padding:12px; border:1px solid #ccc; border-radius:5px;">
                </div>

                <div>
                    <label>Last Name</label>
                    <input type="text" name="last_name" required style="width:98%; padding:12px; border:1px solid #ccc; border-radius:5px;">
                </div>

                <div>
                    <label>Grade Level</label>
                    <select name="grade_level" required style="width:98%; padding:12px; border:1px solid #ccc; border-radius:5px;">
                        <option value="">Select Grade Level</option>
                        <option value="Grade 7">Grade 7</option>
                        <option value="Grade 8">Grade 8</option>
                        <option value="Grade 9">Grade 9</option>
                        <option value="Grade 10">Grade 10</option>
                        <option value="Grade 11">Grade 11</option>
                        <option value="Grade 12">Grade 12</option>
                    </select>
                </div>

                <div>
                    <label>Section / Strand</label>
                    <input type="text" name="section" required placeholder="e.g., STEM A, HUMSS B" style="width:98%; padding:12px; border:1px solid #ccc; border-radius:5px;">
                </div>

                <div>
                    <label>Contact Number (Student)</label>
                    <input type="text" name="contact" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                        placeholder="09123456789"
                        style="width:98%; padding:12px; border:1px solid #ccc; border-radius:5px;">
                </div>

                <div>
                    <label>Gmail Address</label>
                    <input type="email" name="email" required placeholder="student@gmail.com" style="width:98%; padding:12px; border:1px solid #ccc; border-radius:5px;">
                </div>

                <div style="grid-column: span 2;">
                    <label>Parent/Guardian Contact Number</label>
                    <input type="text" name="parent_contact" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                        placeholder="09123456789"
                        style="width:98%; padding:12px; border:1px solid #ccc; border-radius:5px;">
                </div>

            </div>
            <button type="submit" name="register_student" class="btn-home" style="width:100%; margin-top:25px; border:none; padding:15px; cursor:pointer; font-weight:bold; background-color: var(--primary-blue); color: white; border-radius: 5px;">
                Register Student
            </button>
        </form>
    </div>

    <!-- Search Section -->
    <div class="card" style="max-width: 1000px; margin: auto; margin-bottom: 20px; background: #eef2f7;">
        <form method="GET" action="add_student.php" style="display: flex; gap: 10px;">
            <input type="text" name="student_q" value="<?php echo htmlspecialchars($search); ?>"
                placeholder="Search by LRN or Name..."
                style="flex: 1; padding: 12px; border: 1px solid #ccc; border-radius: 5px;">
            <button type="submit" class="btn-home" style="padding: 0 25px; border: none; cursor: pointer; font-weight: bold; background-color: var(--primary-blue); color: white; border-radius: 5px;">Search</button>
            <?php if (!empty($search)): ?>
                <a href="add_student.php" style="padding: 12px 20px; color: #666; text-decoration: none; background: #fff; border: 1px solid #ccc; border-radius: 5px;">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Registry Table -->
    <div class="card" style="max-width: 1000px; margin: auto;">
        <h3 style="color: var(--primary-blue); margin-top: 0;">Registered Students Registry</h3>
        <table style="width:100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding:12px; text-align:left;">LRN</th>
                    <th style="padding:12px; text-align:left;">Full Name</th>
                    <th style="padding:12px; text-align:left;">Grade & Section</th>
                    <th style="padding:12px; text-align:left;">Email</th>
                    <th style="padding:12px; text-align:left;">Contacts</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($student_list->num_rows > 0): ?>
                    <?php while ($s = $student_list->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding:12px; font-weight:bold; color: var(--primary-blue); letter-spacing: 1px;">
                                <?php echo htmlspecialchars($s['lrn']); ?>
                            </td>
                            <td style="padding:12px;">
                                <?php echo htmlspecialchars($s['first_name'] . ' ' . $s['last_name']); ?>
                            </td>
                            <td style="padding:12px;">
                                <?php echo htmlspecialchars($s['grade_level'] . ' - ' . $s['section']); ?>
                            </td>
                            <td style="padding:12px; color: #666;">
                                <?php echo htmlspecialchars($s['email']); ?>
                            </td>
                            <td style="padding:12px;">
                                <div style="font-size: 0.8rem;">S: <?php echo htmlspecialchars($s['contact_number']); ?></div>
                                <div style="font-size: 0.8rem; color: #888;">P: <?php echo htmlspecialchars($s['parent_contact']); ?></div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="padding:30px; text-align:center; color:#999;">No matching students found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once "footer.php"; ?>