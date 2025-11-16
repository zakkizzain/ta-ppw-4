<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

$index = $_GET['index'] ?? null;

if ($index === null || !isset($_SESSION['kontak'][$index])) {
    header("Location: kontak.php");
    exit();
}

$kontak_lama = $_SESSION['kontak'][$index];
$errors = [];
$data = $kontak_lama;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validasi Nama
    if (empty($_POST["nama"])) {
        $errors[] = "Nama harus diisi";
    } else {
        $data['nama'] = trim($_POST["nama"]);
        if (!preg_match("/^[a-zA-Z\s]+$/", $data['nama'])) {
            $errors[] = "Nama hanya boleh mengandung huruf dan spasi";
        }
    }
    
    // Validasi Umur
    if (empty($_POST["umur"])) {
        $errors[] = "Umur harus diisi";
    } else {
        $umur = (int)$_POST["umur"];
        if ($umur < 17 || $umur > 40) { 
            $errors[] = "Umur harus antara 17-40 tahun";
        } else {
            $data['umur'] = $umur;
        }
    }

    // Validasi No Telp
    if (empty($_POST["telp"])) {
        $errors[] = "No. Telepon harus diisi";
    } else {
        $data['telp'] = trim($_POST["telp"]);
        if (!preg_match("/^[0-9]{12}$/", $data['telp'])) {
            $errors[] = "No. Telepon harus berupa 12 angka";
        }
    }

    if (empty($errors)) {
        $_SESSION['kontak'][$index] = [
            'nama' => htmlspecialchars($data['nama']),
            'umur' => $data['umur'],
            'telp' => htmlspecialchars($data['telp'])
        ];
        header("Location: kontak.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit data pegawai</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
       
        :root {
            --primary-color: #0d47a1; 
            --accent-color: #00bcd4; 
            --danger-color: #e53935;
            --success-color: #43a047; 
        
        }

        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 0; 
            background: linear-gradient(135deg, #e0f7fa 0%, #cfd8dc 100%); 
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;

            background-image: url('background.jpg'); 
            background-size: cover; 
            background-repeat: no-repeat;
            background-attachment: fixed; 
            background-position: center center;
            background-color: var(--bg-corporate); 
            
            min-height: 100vh;
        }

        .container { 
            max-width: 600px; 
            width: 90%;
            padding: 30px; 
            background: white; 
            border-radius: 12px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }

        h2 { 
            color: var(--primary-color); 
            margin-bottom: 25px; 
            border-bottom: 3px solid var(--accent-color); 
            padding-bottom: 10px; 
            font-size: 24px;
        }

        .error { 
            color: white; 
            background: var(--danger-color); 
            padding: 15px; 
            border-radius: 8px; 
            margin-bottom: 25px; 
            font-weight: 600; 
        }

        .form-group { 
            margin-bottom: 20px; 
        }

        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: 600; 
            color: #424242; 
        }

        input[type="text"], input[type="number"] { 
            padding: 12px; 
            border: 1px solid #bdbdbd; 
            border-radius: 6px; 
            width: 100%; 
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus, input[type="number"]:focus { 
            border-color: var(--accent-color);
            box-shadow: 0 0 5px rgba(0, 188, 212, 0.5); 
            outline: none;
        }
        
        .btn { 
            padding: 10px 20px; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            text-decoration: none; 
            display: inline-block; 
            font-weight: 600; 
            transition: background-color 0.3s, transform 0.2s; 
            margin-right: 10px;
        }
        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-simpan { 
            background-color: var(--success-color); 
            color: white; 
            margin-top: 10px; 
        }
        .btn-simpan:hover { 
            background-color: #388e3c; 
        }

        .btn-batal { 
            background-color: #9e9e9e; 
            color: white; 
        }
        .btn-batal:hover { 
            background-color: #757575; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-edit"></i> Edit Pegawai: <?php echo htmlspecialchars($kontak_lama['nama']); ?></h2>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <p style="margin: 0; font-weight: 700;"><i class="fas fa-exclamation-triangle"></i> Terjadi Kesalahan:</p>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="nama">Nama Lengkap:</label>
                <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="umur">Umur (17-40 tahun):</label>
                <input type="number" id="umur" name="umur" min="17" max="40" value="<?php echo htmlspecialchars($data['umur']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="telp">No. Telepon (12 Angka):</label>
                <input type="text" id="telp" name="telp" pattern="[0-9]{12}" title="Harus 12 digit angka" value="<?php echo htmlspecialchars($data['telp']); ?>" required>
            </div>
            
            <input type="submit" value="Simpan Perubahan" class="btn btn-simpan">
            <a href="kontak.php" class="btn btn-batal">Batal</a>
        </form>
    </div>
</body>
</html>