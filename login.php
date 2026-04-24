<?php
session_start();
require_once "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    
    $stmt = $mysqli->prepare("SELECT UserID, Email, Password, Role FROM tb_Credentials WHERE Email = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
    if ($password == $row['Password']) { 
        $_SESSION["loggedin"] = true;
        $_SESSION["id"] = $row['UserID'];  
        $_SESSION["username"] = $row['Email']; 
        header("location: index.php"); 
        exit;
    } else {
        $error = "Invalid password.";
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Librarian Login</title>
    <style>
        :root {
            --primary-blue: #003366;
            --accent-gold: #FFD700;
            --bg-light: #f4f4f9;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--bg-light);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-card {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            border-top: 5px solid var(--primary-blue);
        }

        .login-card h2 {
            color: var(--primary-blue);
            margin-bottom: 10px;
        }

        .login-card p {
            color: #666;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }

        .input-group {
            text-align: left;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: var(--primary-blue);
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-blue);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-login:hover {
            background-color: #0055a4;
        }

        .alert-error {
            color: #721c24;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <h2>Librarian Login</h2>
        <p>Enter credentials to access the Management System.</p>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter username" required>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn-login">Access Dashboard</button>
        </form>
    </div>

</body>

</html>