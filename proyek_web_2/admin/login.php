

<?php
session_start();

require_once '../config/database.php';
require_once '../includes/functions.php';

// Inisialisasi pesan error atau sukses
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['form_type']) && $_POST['form_type'] === 'login') {
        $username = sanitize_input($_POST['username']);
        $password = $_POST['password'];

        $sql = "SELECT id, password FROM admin WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $admin = $result->fetch_assoc();
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $username;
                header("Location: index.php");
                exit();
            } else {
                $error = "Password salah.";
            }
        } else {
            $error = "Username tidak ditemukan.";
        }
    } elseif (isset($_POST['form_type']) && $_POST['form_type'] === 'register') {
        $username = sanitize_input($_POST['username']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            $error = "Password dan konfirmasi password tidak cocok.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "SELECT id FROM admin WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "Username sudah terdaftar.";
            } else {
                $sql = "INSERT INTO admin (username, password) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $username, $hashed_password);

                if ($stmt->execute()) {
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Terjadi kesalahan saat mendaftar.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register - Admin</title>
    <link rel="stylesheet" href="../assets/styless.css">
</head>
<body>
<div class="login-page">
    <div class="form-container">
        <div class="form-toggle">
            <button onclick="showLoginForm()">Login</button>
            <button onclick="showRegisterForm()">Register</button>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form id="loginForm" method="POST" style="display: block;">
            <input type="hidden" name="form_type" value="login">
            <h2>Login</h2>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <form id="registerForm" method="POST" style="display: none;">
            <input type="hidden" name="form_type" value="register">
            <h2>Register</h2>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Register</button>
        </form>
    </div>
</div>


    

    <script>
        function showLoginForm() {
            document.getElementById('loginForm').style.display = 'block';
            document.getElementById('registerForm').style.display = 'none';
        }

        function showRegisterForm() {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('registerForm').style.display = 'block';
        }
    </script>
</body>
</html>