<?php
    session_start();
    require '../../config.php';

    if (isset($_GET['id'])) {
        $userId = $_GET['id'];
        try {
            $stmt = $pdo->prepare("DELETE FROM costumer WHERE idCostumer = :id");
            $stmt->bindParam(':id', $userId);
            $stmt->execute();

            $_SESSION['delete_success'] = "Akun berhasil dihapus.";
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $_SESSION['delete_error'] = "Terjadi kesalahan saat menghapus akun.";
        }
    }

    header('Location: index.php');
    exit;
?>
