<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header('Location: ../../');
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
            .details {
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 20px;
            }

            .recentOrders {
                width: 90%;
                max-width: 600px;
                background: #fff;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
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
            }

            .profile-img {
                border-radius: 50%;
                width: 120px;
                height: 120px;
                object-fit: cover;
                border: 3px solid #2a2185;
                margin-bottom: 20px;
                cursor: pointer;
            }

            .info-container {
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
            }

            .info-item {
                display: flex;
                justify-content: space-between;
                width: 100%;
                margin: 10px 0;
                padding: 10px;
                border-radius: 8px;
                background-color: #f9f9f9;
                transition: background-color 0.2s;
            }

            .info-item:hover {
                background-color: #eaeaea;
            }

            .label {
                font-weight: bold;
                color: #333;
                width: 40%;
            }

            .value {
                width: 60%;
                color: #666;
            }

            .btn-edit-profile {
                background-color: #2a2185;
                color: white;
                border: none;
                border-radius: 5px;
                padding: 10px 20px;
                margin-top: 20px;
                text-decoration: none;
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
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.8);
                justify-content: center;
                align-items: center;
            }

            .modal-content {
                margin: auto;
                display: block;
                width: 80%;
                max-width: 600px;
            }

            .close {
                position: absolute;
                top: 20px;
                right: 30px;
                color: #fff;
                font-size: 40px;
                font-weight: bold;
                cursor: pointer;
            }

            .modalLogout {
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

            .modal-contentLogout {
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

            .modalLogout.showLogout .modal-contentLogout {
                transform: translateY(0);
                opacity: 1;
            }

            .closeLogout {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }

            .closeLogout:hover,
            .closeLogout:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }

            .btn-modalLogout {
                padding: 10px 15px;
                font-size: 16px;
                background-color: #2a2185;
                color: white; 
                border: none; 
                border-radius: 5px;
                cursor: pointer;
                width: 100px;
            }

            .btn-modalLogout:hover {
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
                        <a href="../user">
                            <span class="icon">
                                <ion-icon name="people-outline"></ion-icon>
                            </span>
                            <span class="title">User</span>
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <span class="icon">
                                <ion-icon name="person-circle-outline"></ion-icon>
                            </span>
                            <span class="title">Akun Saya</span>
                        </a>
                    </li>

                    <li>
                        <a href="#" id="showModalLogout">
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
                            <h2>Akun Saya</h2>
                        </div>

                        <?php
                            require '../../config.php';
                            require '../../functions.php';

                            $user_id = $_SESSION['user_id'];

                            try {
                                $stmt = $pdo->prepare("SELECT id, username, nohp, filefoto FROM users WHERE id = ? LIMIT 1");
                                $stmt->execute([$user_id]);
                                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                            } catch (PDOException $e) {
                                error_log("Database error: " . $e->getMessage());
                                echo "Terjadi kesalahan. Silakan coba lagi nanti.";
                            }
                        ?>

                        <div class="layoutFormInput">
                            <?php if ($user): ?>
                                <img src="<?php echo '../../imgs/' . $user['filefoto'] ?>" alt="img" class="profile-img" id="profileImg">
                                <div class="info-container">
                                    <div class="info-item">
                                        <span class="label">Username</span>
                                        <span class="value"><?php echo htmlspecialchars($user['username']); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">Password</span>
                                        <span class="value">**********</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">No Telp</span>
                                        <span class="value"><?php echo htmlspecialchars($user['nohp']); ?></span>
                                    </div>
                                </div>
                                <a href="edit.php" class="btn-edit-profile">Edit Account</a>
                            <?php else: ?>
                                <p>Akun anda tidak ditemukan.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="myModal" class="modal">
            <span class="close" id="closeModal">&times;</span>
            <img class="modal-content" id="img01">
        </div>

        <div id="alertModalLogout" class="modalLogout">
            <div class="modal-contentLogout">
                <span class="closeLogout">&times;</span>
                <h2>Pemberitahuan</h2><br>
                <p>Apakah anda ingin Log Out dari Aplikasi ini?</p><br>
                <button id="btnYaLogout" class="btn-modalLogout">Ya</button>
                <button id="btnTidakLogout" class="btn-modalLogout">Tidak</button>
            </div>
        </div>

        <script src="../../js/script.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

        <script>
            const modalLogout = document.getElementById("alertModalLogout");
            const btnLogout = document.getElementById("showModalLogout");
            const spanLogout = document.getElementsByClassName("closeLogout")[0];
            const btnTidakLogout = document.getElementById("btnTidakLogout");
            const btnYaLogout = document.getElementById("btnYaLogout");

            btnLogout.onclick = function () {
                modalLogout.style.display = "block";
                setTimeout(() => {
                    modalLogout.classList.add("showLogout");
                }, 10);
            }

            spanLogout.onclick = closeModalLogout;
            btnTidakLogout.onclick = closeModalLogout;

            btnYaLogout.onclick = function () {
                window.location.href = "../logout";
            }

            function closeModalLogout() {
                modalLogout.classList.remove("showLogout");
                setTimeout(() => {
                    modalLogout.style.display = "none";
                }, 300);
            }

            var modal = document.getElementById("myModal");
            var img = document.getElementById("profileImg");
            var modalImg = document.getElementById("img01");
            var closeModal = document.getElementById("closeModal");

            img.onclick = function () {
                modal.style.display = "flex";
                modalImg.src = this.src;
            }

            closeModal.onclick = function () {
                modal.style.display = "none";
            }

            window.onclick = function (event) {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            }
        </script>
    </body>

</html>