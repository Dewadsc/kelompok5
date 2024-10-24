<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header('Location: ../../');
        exit;
    }

    require '../../config.php';
    require '../../functions.php';

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
            $targetDir = "../../imgs/";
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
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tambahkan Akun Baru</title>
        <link rel="stylesheet" href="../../css/dashboard.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }
            #navigation::-webkit-scrollbar {
                width: 8px;
            }

            #navigation::-webkit-scrollbar-thumb {
                background-color: #999;
                border-radius: 10px;
            }
            .details {
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 20px;
            }
            .cardHeader {
                text-align: center;
            }
            .recentOrders {
                width: 90%;
                max-width: 600px;
                background: #fff;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
                padding: 20px;
                margin: auto;
                transition: transform 0.3s;
            }
            .recentOrders:hover {
                transform: scale(1.02);
            }
            .layoutFormInput {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 20px;
            }
            .profile-img {
                border-radius: 50%;
                width: 120px;
                height: 120px;
                margin-top: 15%;
                object-fit: cover;
                border: 3px solid #2a2185;
                margin-bottom: 20px;
            }
            .info-container {
                width: 100%;
                display: flex;
                flex-direction: column;
            }
            .info-item {
                display: flex;
                flex-direction: column;
                margin: 10px 0;
                padding: 15px;
                border-radius: 10px;
                background-color: #f9f9f9;
                transition: background-color 0.2s;
            }
            .info-item:hover {
                background-color: #eaeaea;
            }
            .label {
                font-weight: bold;
                color: #333;
                margin-bottom: 5px;
            }
            input[type="text"],
            input[type="password"],
            input[type="file"],
            textarea,
            select {
                width: 100%;
                padding: 10px;
                border: 1px solid #2a2185;
                border-radius: 5px;
                outline: none;
                font-size: 14px;
                background-color: #f0f8ff;
                color: #333;
            }
            input[type="text"]:focus,
            input[type="password"]:focus,
            input[type="file"]:focus,
            textarea:focus,
            select:focus {
                border-color: #1e1a6d;
                background-color: #e6f0ff;
            }
            .btn-input-profile {
                background-color: #2a2185;
                color: white;
                border: none;
                border-radius: 5px;
                padding: 10px 20px;
                margin-top: 20px;
                align-self: center;
                cursor: pointer;
                transition: background-color 0.3s, transform 0.2s;
            }
            .btn-input-profile:hover {
                background-color: #1e1a6d;
                transform: translateY(-2px);
            }

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

            .btn-modal {
                padding: 10px 15px;
                font-size: 16px;
                background-color: #2a2185;
                color: white; 
                border: none; 
                border-radius: 5px;
                cursor: pointer;
                width: 100px;
            }

            .btn-modal:hover {
                background-color: #1c1a6a;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="navigation" style="overflow-y: auto;" id="navigation">
                <ul>
                    <li>
                        <a href="#">
                            <span class="title">Aplikasi CRUD Inventory Asset</span>
                        </a>
                    </li>

                    <li>
                        <a href="../beranda">
                            <span class="icon">
                                <ion-icon name="home-outline"></ion-icon>
                            </span>
                            <span class="title">Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="../costumer">
                            <span class="icon">
                                <ion-icon name="accessibility-outline"></ion-icon>
                            </span>
                            <span class="title">Costumer</span>
                        </a>
                    </li>

                    <li>
                        <a href="../suplier">
                            <span class="icon">
                                <ion-icon name="cube-outline"></ion-icon>
                            </span>
                            <span class="title">Suplier</span>
                        </a>
                    </li>

                    <li>
                        <a href="../barangMasuk">
                            <span class="icon">
                                <ion-icon name="bag-add-outline"></ion-icon>
                            </span>
                            <span class="title">Barang Masuk</span>
                        </a>
                    </li>

                    <li>
                        <a href="../barangKeluar">
                            <span class="icon">
                                <ion-icon name="bag-remove-outline"></ion-icon>
                            </span>
                            <span class="title">Barang Keluar</span>
                        </a>
                    </li>

                    <li>
                        <a href="../stokBarang">
                            <span class="icon">
                                <ion-icon name="bag-handle-outline"></ion-icon>
                            </span>
                            <span class="title">Stok Barang</span>
                        </a>
                    </li>

                    <li>
                        <a href="index.php">
                            <span class="icon">
                                <ion-icon name="people-outline"></ion-icon>
                            </span>
                            <span class="title">User</span>
                        </a>
                    </li>

                    <li>
                        <a href="../account">
                            <span class="icon">
                                <ion-icon name="person-circle-outline"></ion-icon>
                            </span>
                            <span class="title">Akun Saya</span>
                        </a>
                    </li>

                    <li>
                        <a href="#" id="showModal">
                            <span class="icon">
                                <ion-icon name="log-out-outline"></ion-icon>
                            </span>
                            <span class="title">Sign Out</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="main">
                <div class="topbar">
                    <div class="toggle">
                        <ion-icon name="menu-outline"></ion-icon>
                    </div>
                </div>

                <div class="details">
                    <div class="recentOrders">
                        <div class="cardHeader">
                            <h2>Tambahkan Akun Baru</h2>
                            <a href="index.php" class="back-button">
                                <ion-icon style="font-size: 1.75rem;" name="arrow-undo-circle-outline"></ion-icon>
                            </a>
                        </div>

                        <div class="layoutFormInput">
                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="info-container">
                                    <div class="info-item">
                                        <label class="label">Username</label>
                                        <input type="text" name="username" required placeholder="Username">
                                    </div>
                                    <div class="info-item">
                                        <label class="label">Password</label>
                                        <input type="password" name="password" required placeholder="Password">
                                    </div>
                                    <div class="info-item">
                                        <label class="label">Foto</label>
                                        <input type="file" name="filefoto" accept="image/*" required>
                                    </div>
                                    <div class="info-item">
                                        <label class="label">No Telp</label>
                                        <input type="text" name="nohp" inputmode="numeric" required placeholder="No HP">
                                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                    </div>
                                    <div class="info-item">
                                        <label class="label">Alamat</label>
                                        <textarea name="alamat" style="resize: none; height: 100px;" placeholder="Alamat"></textarea>
                                    </div>
                                    <div class="info-item">
                                        <label class="label">Role</label>
                                        <select name="role" required>
                                            <option value="" disabled selected>--Pilih Role--</option>
                                            <option value="Admin">Admin</option>
                                            <option value="Costumer">Costumer</option>
                                            <option value="Supplier">Supplier</option>
                                        </select>
                                    </div>
                                </div>
                                <center>
                                    <button type="submit" class="btn-input-profile">Input</button>
                                </center>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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

        <script src="../../js/script.js"></script>

        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

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
    </body>

</html>