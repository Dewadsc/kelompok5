<?php
    require '../../../../config.php';

    $query = "
        SELECT 
            MONTH(tglKeluar) as bulan, 
            namaBarang, 
            SUM(qtyKeluar) as jumlah 
        FROM 
            data_barang 
        INNER JOIN 
            barangkeluar ON data_barang.idBarang = barangkeluar.idBarang 
        GROUP BY 
            MONTH(tglkeluar), namaBarang
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
