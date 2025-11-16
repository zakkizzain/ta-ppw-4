<?php
session_start();
$valid_username = "zakizain";
$valid_password = "jaki123";

$login_error = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header("Location: kontak.php");
        exit();
    } else {
        $login_error = "Username atau password salah!";
    }
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    header("Location: kontak.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Manajemen Pegawai</title>
    <style>
        
        :root {
            --primary-color: #1d4ed8; 
            --secondary-color: #3b82f6; 
            --background-color: #eef2ff;
            --error-color: #ef4444;
            --shadow-color: rgba(29, 78, 216, 0.2);
        }

                body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 0; 
            

            background-image: url('background.jpg'); 
            background-size: cover; 
            background-repeat: no-repeat;
            background-attachment: fixed; 
            background-position: center center;
            background-color: var(--bg-corporate); 
            
            min-height: 100vh;
        }

        body { 
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0; 
            background-color: var(--background-color); 
        }

        .login-form { 
            background: white; 
            padding: 40px; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px var(--shadow-color); 
            max-width: 400px; 
            width: 90%; 
            text-align: center; 
            border: 1px solid #e0e7ff; 
        }

        h2 { 
            color: var(--primary-color); 
            margin-bottom: 30px; 
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }


        h2::before {
            content: "🔑";
            margin-right: 10px;
            font-size: 28px;
        }

        .error { 
            color: white; 
            background: var(--error-color); 
            padding: 12px; 
            border-radius: 8px; 
            margin-bottom: 25px; 
            font-weight: 600; 
        }

        .form-group { 
            text-align: left; 
            margin-bottom: 20px; 
        }

        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: 600; 
            color: #4b5563; 
        }

        input[type="text"], input[type="password"] { 
            width: 100%; 
            padding: 14px; 
            border: 1px solid #d1d5db; 
            border-radius: 8px; 
            box-sizing: border-box; 
            transition: border-color 0.3s, box-shadow 0.3s; 
        }

        input[type="text"]:focus, input[type="password"]:focus { 
            border-color: var(--secondary-color); 
            box-shadow: 0 0 0 3px #bfdbfe; 
            outline: none; 
        }

        input[type="submit"] { 
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color)); 
            color: white; 
            padding: 14px; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            width: 100%; 
            font-size: 17px; 
            font-weight: 700; 
            margin-top: 15px; 
            transition: transform 0.2s, box-shadow 0.2s; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        input[type="submit"]:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        small { 
            color: #9ca3af; 
            display: block; 
            margin-top: 25px; 
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>Login Management Pegawai</h2>
        <?php if ($login_error): ?>
            <p class="error"><?php echo $login_error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" placeholder="zakizain" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" placeholder="jaki123" name="password" required>
            </div>
                
            <input type="submit" name="login" value="Masuk">
        </form>
        <small>Hint: zakizain / jaki123</small>
    </div>
</body>
</html>