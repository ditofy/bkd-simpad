<?php

function getQris($request_id = null, $outlet_id = null, $billing_number = null, $amount = null, $val_time_out = 0, $date_time_start = null, $date_time_end = null) {
    /*
    Format ID :
    ======================================
    QR01 List Outlet Terdaftar
    QR02 Get Data Outlet By Id
    QR03 Generate QRIS
    QR04 Check Status of QRIS Transaction
    */

    $data = [];
	if ($outlet_id !== null) {
		$data["outlet_id"] = $outlet_id;
	}
	if ($billing_number !== null) {
		$data["billing_number"] = $billing_number;
	}
	if ($amount !== null) {
		$data["amount"] = $amount;
	}
	if ($val_time_out !== 0) {
		$data["val_time_out"] = $val_time_out;
	}
	if ($date_time_start !== null) {
		$data["date_time_start"] = $date_time_start;
	}
	if ($date_time_end !== null) {
		$data["date_time_end"] = $date_time_end;
	}
	
	$url = "http://api.banknagari.co.id:6969/APIAgregatorService_Public/Services/PublicRequest";
	
    $serverKey = "BKD*!!@PYKHJK#BN==";
	$institutionID = "PYK";
	
	$institutionID64 = base64_encode($institutionID);
	$timestamp = date("Y-m-d H:i:s");
	$data = json_encode($data);
	$signature = preg_replace("/[^0-9a-zA-Z{}:,.]/", "", $data);
	$signature_a = "$signature:$timestamp";
	$signature_b = strtoupper($signature_a);
	$signature_c = hash_hmac('sha256', $signature_b, $serverKey, true);
	$signature_d = base64_encode($signature_c);

	// File cookie di direktori yang sama
    $cookieFile = __DIR__ . '/request.io';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile); // Baca cookie dari file
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);  // Simpan cookie ke file
	$headers = [
	   "Content-Type: application/json",
	   "Accept: application/json",
	   "Authorization: BN $institutionID64",
	   "Timestamp: $timestamp",
	   "Signature: $signature_d",
	   "RequestId: $request_id",
	];
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	$res = curl_exec($ch);
	curl_close($ch);

	if ($res) {
		return json_decode($res, true);
	} else {
		return false;
	}
}

function get($url, $params = []) {
    // File cookie di direktori yang sama
    $cookieFile = __DIR__ . '/request.io';

    // Tambahkan query string jika ada parameter
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile); // Baca cookie dari file
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);  // Simpan cookie ke file

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);
    return $response;
}

function post($url, $data = null) {
    // File cookie di direktori yang sama
    $cookieFile = __DIR__ . '/request.io';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile); // Baca cookie dari file
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);  // Simpan cookie ke file

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);
    return $response;
}

function NopPbb($billing_number)
{
	$kd_kecamatan = substr($billing_number, 0, 1);
	$kd_kelurahan = substr($billing_number, 1, 2);
	$kd_blok = substr($billing_number, 3, 2);
	$no_urut = substr($billing_number, 5, 3);
	$thn_pajak_sppt = substr($billing_number, 8, 2);
	
	$kd_kecamatan = '0' . $kd_kecamatan . '0';
	$kd_kelurahan = '0'. $kd_kelurahan;
	$kd_blok = '0'. $kd_blok;
	$no_urut = '0'. $no_urut;
	$kd_jns_op = '0';

	if ($thn_pajak_sppt > 96) {
		$thn_pajak_sppt = '19' . $thn_pajak_sppt;
	} else {
		$thn_pajak_sppt = '20' . $thn_pajak_sppt;
	}

	return '13.76.' . $kd_kecamatan . '.' . $kd_kelurahan . '.' . $kd_blok . '.' . $no_urut . '.' . $kd_jns_op . '.' . $thn_pajak_sppt;
}

function NopPjd($billing_number)
{
	$jns_surat = substr($billing_number, 0, 1);
	$thn_pajak = substr($billing_number, 1, 2);
	$bln_pajak = substr($billing_number, 3, 2);
	$kd_obj_pajak = substr($billing_number, 5, 2);
	$no_urut_surat = substr($billing_number, 7, 3);

	$jns_surat = '0' . $jns_surat;
	$thn_pajak = '20' . $thn_pajak;
	$no_urut_surat = '0' . $no_urut_surat;

	return $jns_surat . '.' . $thn_pajak . '.' . $bln_pajak . '.' . $kd_obj_pajak . '.' . $no_urut_surat;
}

function NopRet($billing_number)
{
	$tahun = substr($billing_number, 0, 4);
	$bulan = substr($billing_number, 4, 2);
	$id_retribusi_detail = substr($billing_number, 6, 2);
	$no_urut_retribusi = substr($billing_number, 8, 4);
	
	return $tahun . $bulan . $id_retribusi_detail . $no_urut_retribusi;
}

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function dateFormat($date, $format = 'd-m-Y')
{
    return DateTime::createFromFormat('Y-m-d', $date)->format($format);
}
