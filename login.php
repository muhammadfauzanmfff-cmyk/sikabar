<?php
session_start();

// Koneksi database
$db = new mysqli('localhost', 'root', '', 'sikabar');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi';
    } else {
        // Gunakan prepared statement untuk mencegah SQL injection
        $stmt = $db->prepare("SELECT id_admin, username, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verifikasi password
            if ($password === $user['password']) { // Dalam implementasi nyata, gunakan password_verify()
                $_SESSION['user_id'] = $user['id_admin'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['logged_in'] = true;
                
                header('Location: dasboard.php');
                exit;
            }
        }
        $error = 'Username atau password salah';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #387f39;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            display: flex;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            height: 100vh;
            border-radius: 0;
            overflow: hidden;
        }

        .login-image {
            background-image: url("assets/images/image1.jpg");
            background-size: cover;
            background-position: center;
            width: 75%;
            height: 100%;
        }

        .login-form input::placeholder {
            color: #fff;
            font-weight: lighter;
        }

        .login-form {
            padding: 50px;
            background-color: #387f39;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-form h1 {
            color: #fff;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: bold;
        }

        .login-form p {
            color: #fff;
            margin-bottom: 10px;
            font-size: 15px;
            font-weight: normal;
        }

        .form-control {
            background-color: rgba(249, 235, 255, 0.15);
            margin-bottom: 20px;
            border: none;
            outline: none;
            padding: 10px;
            height: 45px;
            color: #fff;
            border-radius: 15px;
        }

        .form-control:focus {
            background-color: rgba(249, 235, 255, 0.15);
            color: #fff;
            border: none;
            outline: none;
            box-shadow: none;
        }

        .btn-login {
            background-color: #ffff;
            border: none;
            padding: 10px;
            width: 100%;
            color: #387f39;
            border-radius: 15px;
            font-size: 18px;
            cursor: pointer;
            margin-top: 20px;
            font-weight: bold;
        }

        .btn-login:hover {
            opacity: 0.8;
        }

        .error {
            color: #ff3333;
            text-align: center;
            margin-top: 10px;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-image"></div>
        <div class="login-form">
            <h1>Welcome!</h1>
            <p>Masukkan Username dan Password</p>
            <form method="POST" action="login.php">
                <input type="text" 
                       class="form-control" 
                       name="username" 
                       placeholder="Username"
                       value="<?php echo htmlspecialchars($username ?? ''); ?>"
                       required>
                <input type="password" 
                       class="form-control" 
                       name="password" 
                       placeholder="Password" 
                       required>
                <button type="submit" class="btn-login">Login</button>
                <?php if ($error): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
