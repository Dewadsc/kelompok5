<?php
    session_start();
    require '../../config.php';

    if (isset($_GET['id'])) {
        $keluarId = $_GET['id'];
        try {
            $stmt = $pdo->prepare("DELETE FROM barangkeluar WHERE idKeluar = :id");
            $stmt->bindParam(':id', $keluarId);
            $stmt->execute();

            $_SESSION['delete_success'] = "Barang keluar berhasil dihapus.";
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $_SESSION['delete_error'] = "Terjadi kesalahan saat menghapus data barang keluar.";
        }
    }

    header('Location: index.php');
    exit;
?>
