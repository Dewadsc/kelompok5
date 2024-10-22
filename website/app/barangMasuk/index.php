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
        <title>Data Barang Masuk</title>
        <link rel="stylesheet" href="../../css/dashboard.css">
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

            .download-button {
                cursor: pointer;
                background-color: #2a2185;
                color: white;
                border: none;
                width: 200px;
                height: min-content;
                border-radius: 5px;
                padding: 10px 20px;
                font-size: 16px;
                transition: background-color 0.3s, transform 0.3s;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }

            .download-button:hover {
                background-color: #1e1a6d;
                transform: translateY(-2px);
            }

            .download-button:focus {
                outline: none;
                box-shadow: 0 0 5px rgba(42, 33, 133, 0.5);
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
                        <a href="#">
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

                <div class="details" style="display: block;">
                    <div class="recentOrders">
                        <div class="cardHeader">
                            <h2>Data Barang Masuk</h2>
                            <a href="input.php">
                                <ion-icon style="font-size: 1.75rem;" name="add-circle-outline"></ion-icon>
                            </a>
                        </div>

                        <button type="submit" onclick="downloadfile()" class="download-button">Download to Excel</button>

                        <?php
                            require '../../config.php';
                            require '../../functions.php';

                            if (!isset($_SESSION['user_id'])) {
                                echo "Anda harus login untuk mengakses halaman ini.";
                                exit();
                            }

                            try {
                                $stmt = $pdo->prepare("SELECT * FROM data_barang INNER JOIN barangmasuk ON data_barang.idBarang = barangmasuk.idBarang INNER JOIN users ON barangmasuk.username = users.username");
                                $stmt->execute();

                                $dataMasuks = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                $no = 1;

                            } catch (PDOException $e) {
                                error_log("Database error: " . $e->getMessage());
                                echo "Terjadi kesalahan. Silakan coba lagi nanti.";
                                exit();
                            }
                        ?>

                        <table>
                            <thead>
                                <tr>
                                    <td>No</td>
                                    <td>Nama Barang</td>
                                    <td>Nama Suplier</td>
                                    <td>Qty Masuk</td>
                                    <td>Tgl Masuk</td>
                                    <td>Jam Masuk</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($dataMasuks): ?>
                                    <?php foreach ($dataMasuks as $masuk): ?>
                                        <tr>
                                            <td><?php echo $no++ ?></td>
                                            <td><?php echo htmlspecialchars($masuk['namaBarang']); ?></td>
                                            <td><?php echo htmlspecialchars($masuk['username']); ?></td>
                                            <td><?php echo htmlspecialchars($masuk['qtyMasuk']); ?></td>
                                            <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($masuk['tglMasuk']))); ?></td>
                                            <td><?php echo htmlspecialchars($masuk['jamMasuk']); ?></td>
                                            <td>
                                                <a style="color: #f58f7c;" href="#" class="showDeleteModal" data-id="<?php echo htmlspecialchars($masuk['idMasuk']); ?>" data-nama="<?php echo htmlspecialchars($masuk['namaBarang']); ?>">
                                                    <ion-icon name="trash-outline" style="font-size: 1.50rem;"></ion-icon>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7">Tidak ada data barang masuk.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div id="logoutModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeLogoutModal">&times;</span>
                <h2>Pemberitahuan</h2><br>
                <p>Apakah anda ingin Log Out dari Aplikasi ini?</p><br>
                <button id="btnYesLogout" class="btn-modal">Ya</button>
                <button id="btnNoLogout" class="btn-modal">Tidak</button>
            </div>
        </div>

        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeDeleteModal">&times;</span>
                <h2>Peringatan</h2><br>
                <p id="deleteMasuk">Apakah Anda yakin ingin menghapus barang masuk ini?</p><br>
                <button id="btnYesDelete" class="btn-modal">Ya</button>
                <button id="btnNoDelete" class="btn-modal">Tidak</button>
            </div>
        </div>

        <div id="alertModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Pemberitahuan</h2><br>
                <p id="alertMessage"><?php
                    if (isset($_SESSION['delete_success'])) {
                        echo htmlspecialchars($_SESSION['delete_success']);
                        unset($_SESSION['delete_success']);
                    } elseif (isset($_SESSION['delete_error'])) {
                        echo htmlspecialchars($_SESSION['delete_error']);
                        unset($_SESSION['delete_error']);
                    } else {
                        echo "Tidak ada pesan.";
                    }
                ?></p><br>
                <button id="closeModal" class="btn-modal">Tutup</button>
            </div>
        </div>

        <script src="../../js/script.js"></script>

        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

        <script>
            function downloadfile() {
                window.location.href = "export.php";
            }

            const modal = document.getElementById("alertModal");
            const span = document.getElementsByClassName("close")[0];
            const closeBtn = document.getElementById("closeModal");

            window.onload = function() {
                const message = document.getElementById("alertMessage").textContent;
                if (message !== "Tidak ada pesan.") {
                    modal.style.display = "block";
                    setTimeout(() => {
                        modal.classList.add("show");
                    }, 10);
                }
            };

            span.onclick = function() {
                closeModal();
            }

            closeBtn.onclick = function() {
                closeModal();
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    closeModal();
                }
            }

            function closeModal() {
                modal.classList.remove("show");
                setTimeout(() => {
                    modal.style.display = "none";
                }, 300);
            }

            const logoutModal = document.getElementById("logoutModal");
            const showLogoutModal = document.getElementById("showModal");
            const closeLogoutModal = document.getElementById("closeLogoutModal");
            const btnNoLogout = document.getElementById("btnNoLogout");
            const btnYesLogout = document.getElementById("btnYesLogout");

            showLogoutModal.onclick = function () {
                logoutModal.style.display = "block";
                setTimeout(() => {
                    logoutModal.classList.add("show");
                }, 10);
            };

            closeLogoutModal.onclick = function () {
                closeLogoutModalFunc();
            };

            btnNoLogout.onclick = function () {
                closeLogoutModalFunc();
            };

            btnYesLogout.onclick = function () {
                window.location.href = "../logout";
            };

            function closeLogoutModalFunc() {
                logoutModal.classList.remove("show");
                setTimeout(() => {
                    logoutModal.style.display = "none";
                }, 300);
            }

            const deleteModal = document.getElementById("deleteModal");
            const deleteLinks = document.querySelectorAll('.showDeleteModal');
            const closeDeleteModal = document.getElementById("closeDeleteModal");
            const btnYesDelete = document.getElementById("btnYesDelete");
            const btnNoDelete = document.getElementById("btnNoDelete");
            const deleteMasuk = document.getElementById("deleteMasuk");
            let deleteMasukId = null;

            deleteLinks.forEach(link => {
                link.onclick = function (e) {
                    e.preventDefault();
                    deleteMasukId = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    deleteMasuk.textContent = `Apakah Anda yakin ingin menghapus barang masuk ini "${nama}"?`;
                    deleteModal.style.display = "block";
                    setTimeout(() => {
                        deleteModal.classList.add("show");
                    }, 10);
                };
            });

            closeDeleteModal.onclick = function () {
                closeDeleteModalFunc();
            };

            btnNoDelete.onclick = function () {
                closeDeleteModalFunc();
            };

            btnYesDelete.onclick = function () {
                window.location.href = "delete.php?id=" + deleteMasukId;
            };

            function closeDeleteModalFunc() {
                deleteModal.classList.remove("show");
                setTimeout(() => {
                    deleteModal.style.display = "none";
                }, 300);
            }

            window.onclick = function (event) {
                if (event.target == logoutModal) {
                    closeLogoutModalFunc();
                }
                if (event.target == deleteModal) {
                    closeDeleteModalFunc();
                }
            };
        </script>

    </body>

</html>