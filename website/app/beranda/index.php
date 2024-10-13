<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../../');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Dashboard</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>
        <div class="container">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <a href="../account">Edit Profile</a>
            <a href="../logout">Logout</a>
        </div>
    </body>
</html>
