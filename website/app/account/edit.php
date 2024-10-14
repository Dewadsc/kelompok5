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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = sanitizeInput($_POST['username']);
        $password = !empty($_POST['password']) ? password_hash(sanitizeInput($_POST['password']), PASSWORD_DEFAULT) : $user['password'];
        $nohp = sanitizeInput($_POST['nohp']);
        $filefoto = $user['filefoto'];

        if (isset($_FILES['filefoto']) && $_FILES['filefoto']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['filefoto']['tmp_name'];
            $fileName = $_FILES['filefoto']['name'];
            $fileSize = $_FILES['filefoto']['size'];
            $fileType = $_FILES['filefoto']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedfileExtensions = array('jpg', 'jpeg', 'png');
            $allowedMimeTypes = array('image/jpeg', 'image/png');

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

        $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ?, nohp= ?, filefoto = ? WHERE id = ?");
        $stmt->execute([$username, $password, $nohp, $filefoto, $_SESSION['user_id']]);

        echo "Akun berhasil diperbarui.";
        header('Location: index.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Akun Saya</title>
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
        </style>
    </head>

    <body>
        <div class="container">
            <div class="navigation">
                <ul>
                    <li>
                        <a href="#">
                            <span class="title">Aplikasi Kelompok 5</span>
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
                        <a href="#">
                            <span class="icon">
                                <ion-icon name="accessibility-outline"></ion-icon>
                            </span>
                            <span class="title">Costumer</span>
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <span class="icon">
                                <ion-icon name="bag-add-outline"></ion-icon>
                            </span>
                            <span class="title">Barang Masuk</span>
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <span class="icon">
                                <ion-icon name="bag-remove-outline"></ion-icon>
                            </span>
                            <span class="title">Barang Keluar</span>
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <span class="icon">
                                <ion-icon name="bag-handle-outline"></ion-icon>
                            </span>
                            <span class="title">Stok Barang</span>
                        </a>
                    </li>

                    <li>
                        <a href="#">
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
                        <a href="../logout">
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

        <script src="../../js/script.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    </body>

</html>