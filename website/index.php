<?php
    require 'config.php';
    require 'functions.php';

    $loginFailed = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = sanitizeInput($_POST['username']);
        $password = $_POST['password'];

        if (!preventBruteForce($username)) {
            die('Anda terlalu banyak mencoba Log In, silahkan anda coba lagi nanti.');
        }

        if (!validateCsrfToken($_POST['csrf_token'])) {
            die('Token CSRF tidak valid');
        }

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            unset($_SESSION['attempts'][$username]);
            header('Location: app/beranda/');
            exit;
        } else {
            recordFailedAttempt($username);
            $loginFailed = true;
        }
    }

    $csrf_token = generateCsrfToken();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Log In</title>
        <link rel="stylesheet" href="css/style.css">
        <style>
            body {
                font-family: Arial, sans-serif;
            }

            #showModal {
                display: none;
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
            <form method="POST" action="" class="formLogin" style="text-align: center;">
                <h2 class="judul-login">Log In</h2>
                <input type="text" name="username" required placeholder="Username">
                <input type="password" name="password" required placeholder="Password">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <button class="btn-login" type="submit">Login</button>
            </form>
        </div>

        <div id="alertModal" class="modal <?php echo $loginFailed ? 'show' : ''; ?>">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Pemberitahuan</h2>
                <p>Username atau Password salah.</p>
                <button id="closeModal">Tutup</button>
            </div>
        </div>

        <script>
            const modal = document.getElementById("alertModal");
            const span = document.getElementsByClassName("close")[0];
            const closeBtn = document.getElementById("closeModal");

            <?php if ($loginFailed): ?>
                modal.style.display = "block";
                setTimeout(() => {
                    modal.classList.add("show");
                }, 10);
            <?php endif; ?>

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
        </script>
    </body>
</html>
