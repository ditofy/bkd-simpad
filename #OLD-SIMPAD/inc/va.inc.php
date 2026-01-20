<?php

// echo 'VA Create : ' . getVaPjd('02.2025.10.06.0002', 'SUSRI NALIASTUTI', '400000'); // 2251006002
// echo '<br>';
// echo 'VA Create : ' . getVaPbb('13.76.010.010.003.0345.0', '2025', 'AYUN MULYADI 2025', '88956'); // 1100334525

function servicesApiHeaders($data)
{
    $serverKey = 'PYK@BKD#UEVOREFQQVRBTg=='; 
    $institutionID = 'PDT';

    $institutionID64 = base64_encode($institutionID);
    $timestamp = (new DateTime())->format('Y-m-d H:i:s');

    if (is_array($data) || is_object($data)) {
        $data_string = json_encode($data);
    } else {
        $data_string = (string) $data;
    }

    $signature_a = preg_replace('/[^0-9a-zA-Z{}:,.]/', '', $data_string);
    $signature_b = $signature_a . ':' . $timestamp;
    $signature_c = strtoupper($signature_b);
    $signature_d = hash_hmac('sha256', $signature_c, $serverKey, true);
    $signature = base64_encode($signature_d);

    $rawHeaders = [
        'Content-Type'  => 'application/json',
        'Accept'        => 'application/json',
        'Authorization' => 'BKD ' . $institutionID64,
        'Timestamp'     => $timestamp,
        'Signature'     => $signature,
    ];

    $headers = [];
    foreach ($rawHeaders as $key => $value) {
        $headers[] = $key . ': ' . $value;
    }

    return $headers;
}

function getVaPbb($nop, $year, $virtualAccountName, $amount)
{
    $url = 'http://192.168.10.20/api/v1/nagari/va/create';

    $nop = preg_replace('/[^0-9]/', '', $nop);
    $nop_kd_kecamatan = substr($nop, 5, 1);
    $nop_kd_kelurahan = substr($nop, 8, 2);
    $nop_kd_blok = substr($nop, 11, 2);
    $nop_no_urut = substr($nop, 14, 3);
    $nop_thn_pjk = substr($year, 2, 2);

    $customerNo = $nop_kd_kecamatan . $nop_kd_kelurahan . $nop_kd_blok . $nop_no_urut . $nop_thn_pjk; 

    $data = [
        'customerNo' => '1' . $customerNo,
        'virtualAccountName' => $virtualAccountName,
        'amount' => $amount,
    ];

    $headers = servicesApiHeaders($data);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $curl_page = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception(curl_error($ch));
    }

    curl_close($ch);

    $result = json_decode($curl_page, true);

    $virtualAccountNo = $result['data']['va'] ?? 'VA Tidak Ditemukan';

    return trim($virtualAccountNo);
}

function getVaPjd($nop, $virtualAccountName, $amount)
{
    $url = 'http://192.168.10.20/api/v1/nagari/va/create';

    $nop = preg_replace('/[^0-9]/', '', $nop);
    $nop_jns_surat = substr($nop, 1, 1);
    $nop_thn_pajak = substr($nop, 4, 2);
    $nop_bln_pajak = substr($nop, 6, 2);
    $nop_kd_obj_pajak = substr($nop, 8, 2);
    $nop_no_urut_surat = substr($nop, 11, 3);

    $customerNo = $nop_jns_surat . $nop_thn_pajak . $nop_bln_pajak . $nop_kd_obj_pajak . $nop_no_urut_surat;

    $data = [
        'customerNo' => '2' . $customerNo,
        'virtualAccountName' => $virtualAccountName,
        'amount' => $amount,
    ];

    $headers = servicesApiHeaders($data);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $curl_page = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception(curl_error($ch));
    }

    curl_close($ch);

    $result = json_decode($curl_page, true);

    $virtualAccountNo = $result['data']['va'] ?? 'VA Tidak Ditemukan';

    return trim($virtualAccountNo);
}

function deleteVaPbb($nop, $year)
{
    return false;
}

function deleteVaPjd($nop)
{
    return false;
}
