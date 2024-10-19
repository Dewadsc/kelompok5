<?php
    require '../../../../config.php';

    $query = "
        SELECT 
            MONTH(tglMasuk) as bulan, 
            namaBarang, 
            SUM(qtyMasuk) as jumlah 
        FROM 
            data_barang 
        INNER JOIN 
            barangmasuk ON data_barang.idBarang = barangmasuk.idBarang 
        GROUP BY 
            MONTH(tglMasuk), namaBarang
        ORDER BY 
            bulan
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }

    echo json_encode($data);
?>
