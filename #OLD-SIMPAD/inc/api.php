<?php 
/* Fungsi API ke Server Bank Nagari */
function api($id = "QR01", $outlet_id = "", $billing_number = "", $amount = "", $val_time_out = 0, $date_time_start = "", $date_time_end = "")
{ 
	/*
	Format ID :
	======================================
	QR01 List Outlet Terdaftar
	QR02 Get Data Outlet By Id
	QR03 Generete QRIS
	QR04 Cek Status Transaksi QRIS
	*/
	
	$data = [];
	if($outlet_id != "") {
		$data["outlet_id"] = $outlet_id;
	}
	if($billing_number != "") {
		$data["billing_number"] = $billing_number;
	}
	if($amount != "") {
		$data["amount"] = $amount;
	}
	if($val_time_out !== 0) {
		$data["val_time_out"] = $val_time_out;
	}
	if($date_time_start != "") {
		$data["date_time_start"] = $date_time_start;
	}
	if($date_time_end != "") {
		$data["date_time_end"] = $date_time_end;
	}
	
	//$url = "http://demo.banknagari.co.id:9002/APIAgregatorService_Public/Services/PublicRequest";
	//$url = "http://123.231.205.203:6969/APIAgregatorService_Public/Services/PublicRequest";
	$url = "http://api.banknagari.co.id:6969/APIAgregatorService_Public/Services/PublicRequest";
	
    //$serverKey = "DPKD!!@PYKHJK#BN==";
	$serverKey = "BKD*!!@PYKHJK#BN==";
	$institutionID = "PYK";
	$requestid = $id; 
	
	$institutionID64 = base64_encode($institutionID);
	$timestamp = date("Y-m-d H:i:s");
	$data = json_encode($data);
	$signature = preg_replace("/[^0-9a-zA-Z{}:,.]/", "", $data);
	$signature_a = "$signature:$timestamp";
	$signature_b = strtoupper($signature_a);
	$signature_c = hash_hmac('sha256', $signature_b, $serverKey, true);
	$signature_d = base64_encode($signature_c);
	//var_dump($data); exit;

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$headers = [
	   "Content-Type: application/json",
	   "Accept: application/json",
	   "Authorization: BN $institutionID64",
	   "Timestamp: $timestamp",
	   "Signature: $signature_d",
	   "RequestId: $requestid",
	];
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

	$res = curl_exec($curl);
	curl_close($curl);

	if($res) {
		return json_decode($res);
	} else {
		return json_decode([
			"rc" => "XX",
			"message" => "PHP Error"
		]);
	}
}

/* Fungsi buat file gambar QR code format .PNG */
function qrImg($str64, $billing_number)
{
	$data = base64_decode($str64);
	$file = "qrimage/$billing_number.png";
	file_put_contents($file, $data);
}