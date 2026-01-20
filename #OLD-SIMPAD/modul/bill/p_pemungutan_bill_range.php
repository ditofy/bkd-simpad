<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
$schema = "bill";
$error = false;
$data = $_REQUEST['postdata'];
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = strtoupper(pg_escape_string($$value["name"]));
}
$nop = $arr_data['nop'];
list($kd_kecamatan,$kd_kelurahan,$kd_obj_pajak,$kd_keg_usaha,$no_reg) = explode(".",$nop);
$no_awal = (int)$arr_data['no-awal'];
$no_akhir = (int)$arr_data['no-akhir'];
$seri = $arr_data['seri-bill'];
$nip = $_SESSION['nip'];
$tahun_pajak = $arr_data['tahun-pajak'];
$bulan_pajak = $arr_data['bulan-pajak'];
$omset = (int)$arr_data['omset'];
$tarif = $arr_data['tarif'];
$pokok_pajak = $tarif*$omset;
$query = "INSERT INTO $schema.pengembalian_bill(kd_kecamatan,kd_kelurahan,kd_obj_pajak,kd_keg_usaha,no_reg,seri,no_awal,no_akhir,thn_pajak,bln_pajak,dasar_pengenaan_pajak,pokok_pajak,penyimpan,tarif) 
VALUES('$kd_kecamatan','$kd_kelurahan','$kd_obj_pajak','$kd_keg_usaha','$no_reg','$seri',$no_awal,$no_akhir,'$tahun_pajak','$bulan_pajak',$omset,$pokok_pajak,'$nip',$tarif)";
$result = pg_query($query);
if($result) {
	$error = false;
} else {
	$error = true;
	goto error_handler;		
}
$today = date("d-m-Y");
for($i=$no_awal;$i<=$no_akhir;$i++) {
	$query = "UPDATE $schema.dat_bill set tgl_pengembalian=TO_DATE('$today','DD-MM-YYYY'),status_pengembalian='1' 
	WHERE seri='$seri' and no_bill=$i";
	$result = pg_query($query);
	if($result) {
		$error = false;
	} else {
		$error = true;
		break 1;
	}
}
if($error) {
	goto error_handler;
} else {
	$status = array(
			'status' => 'ok',
			'msg' => 'Bill Seri '.$seri." No ".$no_awal." - ".$no_akhir." Berhasil Di Simpan",
		);
	pg_close($dbconn);
	echo json_encode($status);
}
error_handler :
	if ($error == true) {
		$status = array(
			'status' => 'error',
			'msg' => pg_last_error(),
		);
		echo json_encode($status);		
		pg_close($dbconn);
	}
?>