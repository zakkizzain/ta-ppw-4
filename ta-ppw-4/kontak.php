<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['kontak'])) {
    $_SESSION['kontak'] = [];
}

$errors = [];
$data = ['nama' => '', 'umur' => '', 'telp' => ''];

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah'])) {
    
    // Validasi Nama (Harus huruf dan spasi)
    if (empty($_POST["nama"])) {
        $errors[] = "Nama harus diisi";
    } else {
        $data['nama'] = trim($_POST["nama"]);
        if (!preg_match("/^[a-zA-Z\s]+$/", $data['nama'])) {
            $errors[] = "Nama hanya boleh mengandung huruf dan spasi";
        }
    }
    
    // Validasi Umur (Harus 17-40 tahun)
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

    // Validasi No Telp (Harus 12 angka)
    if (empty($_POST["telp"])) {
        $errors[] = "No. Telepon harus diisi";
    } else {
        $data['telp'] = trim($_POST["telp"]);
        if (!preg_match("/^[0-9]{12}$/", $data['telp'])) {
            $errors[] = "No. Telepon harus berupa 12 angka";
        }
    }

    if (empty($errors)) {
        $_SESSION['kontak'][] = [
            'nama' => htmlspecialchars($data['nama']),
            'umur' => $data['umur'],
            'telp' => htmlspecialchars($data['telp'])
        ];
        $data = ['nama' => '', 'umur' => '', 'telp' => '']; 
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
    <title>Manajemen Pegawai</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        
        :root {
            --primary-color: #1a237e; 
            --accent-color: #00bcd4; 
            --success-color: #4caf50; 
            --danger-color: #e53935; 
            --bg-corporate: #e8eaf6; 
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
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

        .container { 
            max-width: 1200px; 
            margin: 40px auto; 
            padding: 20px; 
            background: rgba(255, 255, 255, 0.95); 
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15); 
        }

        header { 
            background: var(--primary-color); 
            color: white; 
            padding: 25px 30px; 
            margin: -20px -20px 30px -20px; 
            border-radius: 12px 12px 0 0; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }

        h1 { 
            margin: 0; 
            font-size: 28px; 
            font-weight: 700;
        }
        h1 i { margin-right: 10px; } 
        
        .content-area { 
            padding: 0px; 
        }

        .card { 
            background: #fdfdfd;
            border: 1px solid #e0e0e0; 
            border-radius: 10px; 
            padding: 30px; 
            margin-bottom: 30px; 
            box-shadow: var(--card-shadow); 
        }

        .card h3 { 
            color: var(--primary-color); 
            margin-top: 0; 
            border-bottom: 3px solid var(--accent-color); 
            padding-bottom: 10px; 
            font-size: 20px;
            display: flex;
            align-items: center;
        }
        .card h3 i { margin-right: 8px; font-size: 18px; }

        .error { 
            color: white; 
            background: var(--danger-color); 
            padding: 15px; 
            border-radius: 8px; 
            margin-bottom: 25px; 
            font-weight: 600; 
        }
        .error ul { margin: 5px 0 0 20px; }
        
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
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        input[type="text"]:focus, input[type="number"]:focus { 
            border-color: var(--accent-color);
            box-shadow: 0 0 5px rgba(0, 188, 212, 0.4); 
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
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .btn-tambah { 
            background-color: var(--success-color); 
            color: white; 
            margin-top: 10px; 
        }
        .btn-tambah:hover { 
            background-color: #388e3c; 
        }

        .btn-logout { 
            background-color: var(--danger-color); 
            color: white; 
            margin-left: 15px; 
            padding: 8px 15px;
        }
        .btn-logout:hover { 
            background-color: #c62828; 
        }
        
        .btn-edit { 
            background-color: var(--accent-color); 
            color: white; 
            font-size: 14px; 
            padding: 8px 12px; 
        }
        .btn-edit:hover { 
            background-color: #0097a7; 
        }

        .btn-hapus { 
            background-color: var(--danger-color); 
            color: white; 
            font-size: 14px; 
            padding: 8px 12px; 
        }
        .btn-hapus:hover { 
            background-color: #c62828; 
        }


        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 25px; 
        }

        th, td { 
            border: 1px solid #e0e0e0; 
            padding: 15px; 
            text-align: left; 
        }

        th { 
            background-color: #e3f2fd;
            color: var(--primary-color); 
            font-weight: 700; 
            text-transform: uppercase;
        }

        tr:nth-child(even) { 
            background-color: #f9f9ff;
        }
        tr:hover {
            background-color: #f0f0ff; 
        }

        td:last-child { 
            white-space: nowrap; 
        }
        
        .user-info { 
            display: flex; 
            align-items: center; 
        }
        .user-info p { 
            margin: 0; 
            margin-right: 15px; 
            font-weight: 500; 
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-users-cog"></i> Manajemen Data Pegawai</h1>
            <div class="user-info">
                <p>Halo <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                <form method="POST">
                    <input type="submit" name="logout" value="Logout" class="btn btn-logout">
                </form>
            </div>
        </header>
        
        <div class="content-area">
            
            <div class="card">
                <h3><i class="fas fa-user-plus"></i> Tambah Data Pegawai Baru</h3>
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
                    
                    <input type="submit" name="tambah" value="Tambah Pegawai" class="btn btn-tambah">
                </form>
            </div>

            <div class="card">
                <h3><i class="fas fa-list-alt"></i> Daftar Pegawai (Total: <?php echo count($_SESSION['kontak']); ?>)</h3>
                <?php if (empty($_SESSION['kontak'])): ?>
                    <p style="padding: 15px; border: 1px dashed #bdbdbd; border-radius: 5px; text-align: center;">Belum ada data Pegawai yang ditambahkan.</p>
                <?php else: ?>
                    <div style="overflow-x: auto;">
                        <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Umur</th>
                                <th>No. Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($_SESSION['kontak'] as $index => $kontak): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $kontak['nama']; ?></td>
                                    <td><?php echo $kontak['umur']; ?> tahun</td>
                                    <td><?php echo $kontak['telp']; ?></td>
                                    <td>
                                        <a href="edit.php?index=<?php echo $index; ?>" class="btn btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="hapus.php?index=<?php echo $index; ?>" class="btn btn-hapus" onclick="return confirm('Yakin ingin menghapus pegawai ini?');"><i class="fas fa-trash-alt"></i> Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>