<?php
    require '../../config.php';

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="data-barang-keluar.xls"');

    try {
        $stmt = $pdo->prepare("SELECT * FROM data_barang INNER JOIN barangkeluar ON data_barang.idBarang = barangkeluar.idBarang INNER JOIN costumer ON barangkeluar.idCostumer = costumer.idCostumer");
        $stmt->execute();

        $dataKeluars = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $no = 1;

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo "Terjadi kesalahan. Silakan coba lagi nanti.";
        exit();
    }

    echo "
            <table border='1'>
                <thead>
                    <tr>
                        <td>No</td>
                        <td>Nama Barang</td>
                        <td>Nama Costumer</td>
                        <td>Qty Keluar</td>
                        <td>Tgl Keluar</td>
                        <td>Jam Keluar</td>
                    </tr>
                </thead>
            <tbody>
        ";

    if ($dataKeluars):
        foreach ($dataKeluars as $keluar):
            echo "
                <tr>
                    <td>" . $no++ . "</td>
                    <td>" . htmlspecialchars($keluar['namaBarang']) . "</td>
                    <td>" . htmlspecialchars($keluar['namaCostumer']) . "</td>
                    <td>" . htmlspecialchars($keluar['qtyKeluar']) . "</td>
                    <td>" . htmlspecialchars(date('d-m-Y', strtotime($keluar['tglKeluar']))) . "</td>
                    <td>" . htmlspecialchars($keluar['jamKeluar']) . "</td>
                </tr>
            ";
        endforeach;
    else:
        echo "
            <tr>
                <td colspan='7'>Tidak ada data barang keluar.</td>
            </tr>
        ";
    endif;

    echo "</tbody></table>";
?>
