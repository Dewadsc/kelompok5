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

        $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
        $stmt->execute([$username, $password, $_SESSION['user_id']]);
        
        echo "Update profile ";
        header('Location: ../beranda');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit Profile</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>
        <div class="container">
            <form method="POST" action="">
                <h2>Edit Profile</h2>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                <input type="password" name="password" placeholder="Password Baru">
                <button type="submit">Update</button>
                <p><a href="../beranda">Back to Dashboard</a></p>
            </form>
        </div>
    </body>
</html>
