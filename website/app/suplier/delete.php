<?php
    session_start();
    require '../../config.php';

    if (isset($_GET['id'])) {
        $userId = $_GET['id'];
        try {
            $stmt = $pdo->prepare("DELETE FROM suplier WHERE idSuplier = :id");
            $stmt->bindParam(':id', $userId);
            $stmt->execute();

            $_SESSION['delete_success'] = "Suplier berhasil dihapus.";
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $_SESSION['delete_error'] = "Terjadi kesalahan saat menghapus suplier.";
        }
    }

    header('Location: index.php');
    exit;
?>
