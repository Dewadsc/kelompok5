<?php
    session_start();
    require '../../config.php';

    if (isset($_GET['id'])) {
        $masukId = $_GET['id'];
        try {
            $stmt = $pdo->prepare("DELETE FROM barangmasuk WHERE idMasuk = :id");
            $stmt->bindParam(':id', $masukId);
            $stmt->execute();

            $_SESSION['delete_success'] = "Barang masuk berhasil dihapus.";
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $_SESSION['delete_error'] = "Terjadi kesalahan saat menghapus data barang masuk.";
        }
    }

    header('Location: index.php');
    exit;
?>
