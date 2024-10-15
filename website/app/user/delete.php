<?php
    session_start();
    require '../../config.php';

    $idGet = $_GET['id'];
    $idAkun = $_SESSION['user_id'];

    if($idGet==$idAkun) {
        $_SESSION['delete_error'] = "Maaf, anda tidak dapat menghapus akun anda sendiri";
    } else {
        if (isset($_GET['id'])) {
            $userId = $_GET['id'];
            try {
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
                $stmt->bindParam(':id', $userId);
                $stmt->execute();
    
                $_SESSION['delete_success'] = "Akun berhasil dihapus.";
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
                $_SESSION['delete_error'] = "Terjadi kesalahan saat menghapus akun.";
            }
        }
    }

    header('Location: index.php');
    exit;
?>
