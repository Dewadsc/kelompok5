<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header('Location: ../../../../');
        exit;
    }
?>


<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chart Barang Keluar</title>
        <link rel="stylesheet" href="../../../../css/dashboard.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        <a href="../../">
                            <span class="icon">
                                <ion-icon name="home-outline"></ion-icon>
                            </span>
                            <span class="title">Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="../../../costumer">
                            <span class="icon">
                                <ion-icon name="accessibility-outline"></ion-icon>
                            </span>
                            <span class="title">Costumer</span>
                        </a>
                    </li>

                    <li>
                        <a href="../../../suplier">
                            <span class="icon">
                                <ion-icon name="cube-outline"></ion-icon>
                            </span>
                            <span class="title">Suplier</span>
                        </a>
                    </li>

                    <li>
                        <a href="../../../barangMasuk">
                            <span class="icon">
                                <ion-icon name="bag-add-outline"></ion-icon>
                            </span>
                            <span class="title">Barang Masuk</span>
                        </a>
                    </li>

                    <li>
                        <a href="../../../barangKeluar">
                            <span class="icon">
                                <ion-icon name="bag-remove-outline"></ion-icon>
                            </span>
                            <span class="title">Barang Keluar</span>
                        </a>
                    </li>

                    <li>
                        <a href="../../../stokBarang">
                            <span class="icon">
                                <ion-icon name="bag-handle-outline"></ion-icon>
                            </span>
                            <span class="title">Stok Barang</span>
                        </a>
                    </li>

                    <li>
                        <a href="../../../user">
                            <span class="icon">
                                <ion-icon name="people-outline"></ion-icon>
                            </span>
                            <span class="title">User</span>
                        </a>
                    </li>

                    <li>
                        <a href="../../../account">
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
                            <h2>Grafik Chart Barang Keluar</h2>
                            <a href="../../index.php" class="back-button">
                                <ion-icon style="font-size: 1.75rem;" name="arrow-undo-circle-outline"></ion-icon>
                            </a>
                        </div>

                        <canvas id="myChart"></canvas>
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

        <script src="../../../../js/script.js"></script>

        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

        <script>
            const ctx = document.getElementById('myChart').getContext('2d');

            fetch('get_data.php')
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => `Bulan ${item.bulan}`);
                    const datasets = [];

                    const groupedData = {};
                    data.forEach(item => {
                        if (!groupedData[item.nama_barang]) {
                            groupedData[item.nama_barang] = Array(12).fill(0);
                        }
                        groupedData[item.nama_barang][item.bulan - 1] = item.jumlah;
                    });

                    for (const [key, value] of Object.entries(groupedData)) {
                        datasets.push({
                            label: key,
                            data: value,
                            borderColor: getRandomColor(),
                            fill: false,
                            tension: 0.1,
                        });
                    }

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                },
                            },
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Jumlah'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Bulan'
                                    }
                                }
                            }
                        }
                    });
                });

            function getRandomColor() {
                const letters = '0123456789ABCDEF';
                let color = '#';
                for (let i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
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
                window.location.href = "../../../logout";
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