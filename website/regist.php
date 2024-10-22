<?php
    require 'config.php';
    require 'functions.php';

    $modalMessage = '';
    $modalSuccess = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = rand(1, 9999);
        $username = sanitizeInput($_POST['username']);
        $password = sanitizeInput($_POST['password']);
        $nohp = sanitizeInput($_POST['nohp']);
        $alamat = sanitizeInput($_POST['alamat']);
        $role = sanitizeInput($_POST['role']);

        if (empty($username) || empty($password) || empty($nohp) || empty($alamat) || empty($role)) {
            die('Semua field harus diisi!');
        }

        if (!validateCsrfToken($_POST['csrf_token'])) {
            die('CSRF token validation failed');
        }

        $salt = bin2hex(random_bytes(16));
        $hashedPassword = hash('sha256', $salt . $password);

        $stmt1 = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt1->bindParam(':username', $username);
        $stmt1->execute();
        $usernameExists = $stmt1->fetchColumn();

        if ($usernameExists > 0) {
            $modalMessage = "Username sudah digunakan, silakan pilih username lain.";
        } else {
            $targetDir = "imgs/";
            $imageFileType = strtolower(pathinfo($_FILES["filefoto"]["name"], PATHINFO_EXTENSION));

            $newFileName = $username . '_' . time() . '.' . $imageFileType;
            $targetFile = $targetDir . $newFileName;

            $uploadOk = 1;

            if (isset($_POST["submit"])) {
                $check = getimagesize($_FILES["filefoto"]["tmp_name"]);
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    $modalMessage = "File bukan gambar.";
                    $uploadOk = 0;
                }
            }

            if ($_FILES["filefoto"]["size"] > 500000) { 
                $modalMessage = "Maaf, ukuran file terlalu besar.";
                $uploadOk = 0;
            }

            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                $modalMessage = "Maaf, hanya file JPG, JPEG & PNG yang diizinkan.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                $modalMessage = "Maaf, file tidak ter-upload.";
            } else {
                if (move_uploaded_file($_FILES["filefoto"]["tmp_name"], $targetFile)) {
                    $stmt = $pdo->prepare("INSERT INTO users (id, username, password, nohp, filefoto, alamat, role, salt) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$id, $username, $hashedPassword, $nohp, $newFileName, $alamat, $role, $salt]);
                    $modalSuccess = true;
                    $modalMessage = "Pengguna berhasil ditambahkan.";
                } else {
                    $modalMessage = "Maaf, terjadi kesalahan saat meng-upload file.";
                }
            }
        }
    }

    $csrf_token = generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Account</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            max-width: 500px; 
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transform: translateY(-50px);
            opacity: 0;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .modal.show .modal-content {
            transform: translateY(0);
            opacity: 1;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        button {
            padding: 10px 15px;
            font-size: 16px;
            background-color: lightskyblue;
            color: white; 
            border: none; 
            border-radius: 5px;
            cursor: pointer;
            transition: 0.25s;
        }

        button:hover {
            background-color: deepskyblue;
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="POST" action="" class="formLogin" style="text-align: center;" enctype="multipart/form-data">
            <h2 class="judul-login">Register Account</h2>
            <input type="text" name="username" required placeholder="Username">
            <input type="password" name="password" required placeholder="Password">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="text" name="nohp" inputmode="numeric" required placeholder="No HP">
            <input type="file" name="filefoto" accept="image/*" required>
            <textarea name="alamat" style="resize: none; height: 100px;" placeholder="Alamat"></textarea>
            <select name="role" required>
                <option value="" disabled selected>--Pilih Role--</option>
                <option value="Admin">Admin</option>
                <option value="Costumer">Costumer</option>
                <option value="Supplier">Supplier</option>
            </select>
            <button class="btn-login" type="submit">Register</button><br><br>
            <a href="index.php">Kembali</a>
        </form>
    </div>
    <div id="usernameModal" class="modal <?php echo ($modalMessage && !$modalSuccess) ? 'show' : ''; ?>">
        <div class="modal-content">
            <span class="close" onclick="closeModal('usernameModal')">&times;</span>
            <h2>Pemberitahuan</h2><br>
            <p><?php echo $modalMessage; ?></p><br>
            <button class="btn-modal" onclick="closeModal('usernameModal')">Tutup</button>
        </div>
    </div>

    <div id="successModal" class="modal <?php echo ($modalSuccess) ? 'show' : ''; ?>">
        <div class="modal-content">
            <span class="close" onclick="closeModal('successModal')">&times;</span>
            <h2>Pemberitahuan</h2><br>
            <p><?php echo $modalMessage; ?></p><br>
            <button class="btn-modal" onclick="closeModal('successModal')">Tutup</button>
        </div>
    </div>
</body>
<script>
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove("show");
        setTimeout(() => {
            modal.style.display = "none";
        }, 300);
    }

    window.onload = function() {
        if ('<?php echo $modalMessage; ?>' !== '') {
            const modalId = '<?php echo $modalSuccess ? 'successModal' : 'usernameModal'; ?>';
            const modal = document.getElementById(modalId);
            modal.style.display = "block";
            setTimeout(() => {
                modal.classList.add("show");
            }, 10);
        }
    };
</script>
</html>
