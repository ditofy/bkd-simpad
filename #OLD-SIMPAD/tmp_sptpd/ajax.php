<?php
require 'config.php';
$conn = conn();

$response = [
    'success' => FALSE, 
    'message' => '', 
    'data' => [], 
];

if(isset($_GET['id_kecamatan'])) {
    $id_kecamatan = $_GET['id_kecamatan'];
    $stmt = $conn->prepare("SELECT KD_KELURAHAN, NM_KELURAHAN 
    FROM PBB.REF_KELURAHAN 
    WHERE KD_KECAMATAN = ?
    ORDER BY NM_KELURAHAN");
    $stmt->execute([$id_kecamatan]);
    $results = $stmt->fetchAll();
    
    foreach($results as $row) {
        $response['data'][] = [
            'kd_kelurahan' => $row['KD_KELURAHAN'],
            'nm_kelurahan' => $row['NM_KELURAHAN'],
        ];
    }

    $response['success'] = TRUE;
    $response['message'] = '';
}

echo json_encode($response);