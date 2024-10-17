<?php
    session_start();
    require '../../config.php';

    if (isset($_GET['id'])) {
        $userId = $_GET['id'];
        try {
            $stmt = $pdo->prepare("DELETE FROM data_barang WHERE idBarang = :id");
            $stmt->bindParam(':id', $userId);
            $stmt->execute();

            $_SESSION['delete_success'] = "Barang berhasil dihapus.";
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $_SESSION['delete_error'] = "Terjadi kesalahan saat menghapus barang.";
        }
    }

    header('Location: index.php');
    exit;
?>
