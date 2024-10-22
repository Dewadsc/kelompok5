<?php
    require '../../config.php';
    require '../../functions.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header('Location: ../../');
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    $updateSuccess = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = sanitizeInput($_POST['username']);
        $nohp = sanitizeInput($_POST['nohp']);
        $filefoto = $user['filefoto'];

        $salt = bin2hex(random_bytes(16));
        $password = !empty($_POST['password']) ? hash('sha256', $salt . sanitizeInput($_POST['password'])) : $user['password'];

        if (isset($_FILES['filefoto']) && $_FILES['filefoto']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['filefoto']['tmp_name'];
            $fileName = $_FILES['filefoto']['name'];
            $fileSize = $_FILES['filefoto']['size'];
            $fileType = $_FILES['filefoto']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedfileExtensions = ['jpg', 'jpeg', 'png'];
            $allowedMimeTypes = ['image/jpeg', 'image/png'];

            if (in_array($fileExtension, $allowedfileExtensions) && in_array($fileType, $allowedMimeTypes) && $fileSize < 5000000) {
                $newFileName = uniqid('user_', true) . '.' . $fileExtension;
                $uploadFileDir = '../../imgs/';
                $dest_path = $uploadFileDir . basename($newFileName);

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $filefoto = $newFileName;
                } else {
                    echo "Ada kesalahan saat mengunggah file.";
                }
            } else {
                echo "Format file tidak valid atau ukuran terlalu besar.";
            }
        }

        $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ?, salt = ?, nohp = ?, filefoto = ? WHERE id = ?");
        $stmt->execute([$username, $password, $salt, $nohp, $filefoto, $_SESSION['user_id']]);

        $updateSuccess = true;
    }
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Akun Saya</title>
        <link rel="stylesheet" href="../../css/dashboard.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
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
            input[type="file"] {
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
            input[type="file"]:focus {
                border-color: #1e1a6d;
                background-color: #e6f0ff;
            }
            .btn-edit-profile {
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
            .btn-edit-profile:hover {
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
            <div class="navigation">
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
                        <a href="../user">
                            <span class="icon">
                                <ion-icon name="people-outline"></ion-icon>
                            </span>
                            <span class="title">User</span>
                        </a>
                    </li>

                    <li>
                        <a href="index.php">
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
                    <div class="toggle"><ion-icon name="menu-outline"></ion-icon></div>
                </div>

                <div class="details">
                    <div class="recentOrders">
                        <div class="cardHeader">
                            <h2>Edit Akun Saya</h2>
                            <a href="index.php" class="back-button">
                                <ion-icon style="font-size: 1.75rem;" name="arrow-undo-circle-outline"></ion-icon>
                            </a>
                        </div>

                        <div class="layoutFormInput">
                            <form method="POST" action="" enctype="multipart/form-data">
                                <?php if ($user): ?>
                                    <center>
                                        <img src="<?php echo '../../imgs/' . $user['filefoto'] ?>" alt="Profile Image" class="profile-img">
                                    </center>
                                    <div class="info-container">
                                        <div class="info-item">
                                            <label class="label">Username</label>
                                            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                        </div>
                                        <div class="info-item">
                                            <label class="label">Password Baru</label>
                                            <input type="password" name="password" placeholder="Password Baru">
                                        </div>
                                        <div class="info-item">
                                            <label class="label">Foto</label>
                                            <input type="file" name="filefoto" accept="image/*">
                                        </div>
                                        <div class="info-item">
                                            <label class="label">No Telp</label>
                                            <input type="text" name="nohp" inputmode="numeric" value="<?php echo htmlspecialchars($user['nohp']); ?>" required>
                                        </div>
                                    </div>
                                    <center>
                                        <button type="submit" class="btn-edit-profile">Update</button>
                                    </center>
                                <?php else: ?>
                                    <p>Akun anda tidak ditemukan.</p>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="successModal" class="modal" style="display: <?php echo $updateSuccess ? 'block' : 'none'; ?>">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Pemberitahuan</h2><br>
                <p>Akun berhasil diperbarui.</p><br>
                <button id="btnClose" class="btn-modal">Tutup</button>
            </div>
        </div>

        <div id="alertModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Pemberitahuan</h2><br>
                <p>Apakah anda ingin Log Out dari Aplikasi ini?</p><br>
                <button id="btnYa" class="btn-modal">Ya</button>
                <button id="btnTidak" class="btn-modal">Tidak</button>
            </div>
        </div>

        <script src="../../js/script.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

        <script>
            const successModal = document.getElementById("successModal");
            const alertModal = document.getElementById("alertModal");
            const btn = document.getElementById("showModal");
            const span = document.getElementsByClassName("close");
            const btnClose = document.getElementById("btnClose");
            const btnTidak = document.getElementById("btnTidak");
            const btnYa = document.getElementById("btnYa");

            if (successModal.style.display === "block") {
                setTimeout(() => {
                    successModal.classList.add("show");
                }, 10);
            }

            btn.onclick = function() {
                alertModal.style.display = "block";
                setTimeout(() => {
                    alertModal.classList.add("show");
                }, 10);
            }

            for (let closeBtn of span) {
                closeBtn.onclick = function() {
                    closeModal(alertModal);
                }
            }

            btnClose.onclick = function() {
                window.location.href = "index.php";
            }

            btnTidak.onclick = function() {
                closeModal(alertModal);
            }

            btnYa.onclick = function() {
                window.location.href = "../logout";
            }

            window.onclick = function(event) {
                if (event.target == alertModal) {
                    closeModal(alertModal);
                } else if (event.target == successModal) {
                    closeModal(successModal);
                }
            }

            function closeModal(modal) {
                modal.classList.remove("show");
                setTimeout(() => {
                    modal.style.display = "none";
                }, 300);
            }
        </script>

    </body>

</html>