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
        <title>Dashboard</title>
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
            #navigation::-webkit-scrollbar {
                width: 8px;
            }

            #navigation::-webkit-scrollbar-thumb {
                background-color: #999;
                border-radius: 10px;
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
                        <a href="#">
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

                <?php
                    require '../../config.php';

                    $cariQty = $pdo->prepare("SELECT idBarang, namaBarang, hargaBarang, satuanBarang, qtyBarang FROM data_barang");
                    $cariQty->execute();

                    $dataQtys = $cariQty->fetchAll(PDO::FETCH_ASSOC);
                    $totalQty = 0;

                    foreach ($dataQtys as $dataQty) {
                        $totalQty += $dataQty['qtyBarang'];
                    }
                ?>

                <div class="cardBox">
                    <div class="card" onclick="chartMasuk()">
                        <div>
                            <div class="cardName">Chart Barang Masuk</div>
                        </div>

                        <div class="iconBx">
                            <ion-icon name="bag-add-outline"></ion-icon>
                        </div>
                    </div>

                    <div class="card" onclick="chartKeluar()">
                        <div>
                            <div class="cardName">Chart Barang Keluar</div>
                        </div>

                        <div class="iconBx">
                            <ion-icon name="bag-remove-outline"></ion-icon>
                        </div>
                    </div>

                    <div class="card">
                        <div>
                            <div class="numbers"><?php echo htmlspecialchars($totalQty); ?></div>
                            <div class="cardName">Total Stok</div>
                        </div>

                        <div class="iconBx">
                            <ion-icon name="bag-handle-outline"></ion-icon>
                        </div>
                    </div>
                </div>

                <div class="details">
                    <div class="recentCustomers">
                        <div class="cardHeader">
                            <h2>Daftar Costumer</h2>
                        </div>

                        <?php
                            require '../../config.php';

                            try {
                                $perintahCostumer = $pdo->prepare("SELECT username, nohp FROM users WHERE role = ?");
                                $perintahCostumer->execute(['Costumer']);

                                $costumers = $perintahCostumer->fetchAll(PDO::FETCH_ASSOC);
                                $no = 1;

                            } catch (PDOException $e) {
                                error_log("Database error: " . $e->getMessage());
                                echo "Terjadi kesalahan. Silakan coba lagi nanti.";
                                exit();
                            }
                        ?>

                        <table>
                            <?php if ($costumers): ?>
                                <?php foreach ($costumers as $costumer): ?>
                                    <tr>
                                        <td>
                                            <h4><?php echo htmlspecialchars($costumer['username']); ?> <br> <span><?php echo htmlspecialchars($costumer['nohp']); ?></span></h4>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">Tidak ada history pengguna.</td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div id="alertModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Pemberitahuan</h2><br>
                <p>
                    Apakah anda ingin Log Out dari Aplikasi ini?
                </p><br>
                <button id="btnYa" class="btn-modal">Ya</button>
                <button id="btnTidak" class="btn-modal">Tidak</button>
            </div>
        </div>

        <script src="../../js/script.js"></script>

        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

        <script>
            function chartMasuk() {
                window.location.href = "komponen/chartMasuk";
            }

            function chartKeluar() {
                window.location.href = "komponen/chartKeluar";
            }

            const modal = document.getElementById("alertModal");
            const btn = document.getElementById("showModal");
            const span = document.getElementsByClassName("close")[0];
            const btnTidak = document.getElementById("btnTidak");
            const btnYa = document.getElementById("btnYa");

            btn.onclick = function() {
                modal.style.display = "block";
                setTimeout(() => {
                    modal.classList.add("show");
                }, 10);
            }

            span.onclick = function() {
                closeModal();
            }

            btnTidak.onclick = function() {
                closeModal();
            }

            btnYa.onclick = function() {
                window.location.href = "../logout";
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
        </script>

    </body>

</html>