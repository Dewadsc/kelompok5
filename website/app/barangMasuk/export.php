<?php
    require '../../config.php';

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="data-barang-masuk.xls"');

    try {
        $stmt = $pdo->prepare("SELECT * FROM data_barang INNER JOIN barangmasuk ON data_barang.idBarang = barangmasuk.idBarang INNER JOIN suplier ON barangmasuk.idSuplier = suplier.idSuplier");
        $stmt->execute();

        $dataMasuks = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                        <td>Nama Suplier</td>
                        <td>Qty Masuk</td>
                        <td>Tgl Masuk</td>
                        <td>Jam Masuk</td>
                    </tr>
                </thead>
            <tbody>
        ";

    if ($dataMasuks):
        foreach ($dataMasuks as $masuk):
            echo "
                <tr>
                    <td>" . $no++ . "</td>
                    <td>" . htmlspecialchars($masuk['namaBarang']) . "</td>
                    <td>" . htmlspecialchars($masuk['namaSuplier']) . "</td>
                    <td>" . htmlspecialchars($masuk['qtyMasuk']) . "</td>
                    <td>" . htmlspecialchars(date('d-m-Y', strtotime($masuk['tglMasuk']))) . "</td>
                    <td>" . htmlspecialchars($masuk['jamMasuk']) . "</td>
                </tr>
            ";
        endforeach;
    else:
        echo "
            <tr>
                <td colspan='7'>Tidak ada data barang masuk.</td>
            </tr>
        ";
    endif;

    echo "</tbody></table>";
?>
