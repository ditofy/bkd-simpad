<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	die('Access Denied');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
$schema = "bill";
$nop = $_REQUEST['postdata'];
$error = false;
list($kd_kecamatan,$kd_kelurahan,$kd_obj_pajak,$kd_keg_usaha,$no_reg) = explode(".",$nop);
$query = "SELECT 1 FROM $schema.dat_bill WHERE kd_kecamatan='$kd_kecamatan' AND kd_kelurahan='$kd_kelurahan' AND kd_obj_pajak='$kd_obj_pajak' AND kd_keg_usaha='$kd_keg_usaha' AND no_reg='$no_reg' and status_pengembalian='0'";
$result = pg_query($query);
if($result) {
	$error = false;
} else {
	$error = true;
	goto error_handler;
}
$rows = pg_num_rows($result);
if($rows <= 0) {
	$status = array(
		"status" => "ok",
		"msg" => $data,
	);
} else {
	$status = array(
		"status" => "ada",
		"msg" => $data,
	);
}
pg_free_result($result);
pg_close($dbconn);
echo json_encode($status);

error_handler :
	if ($error == true) {
		$status = array(
			'status' => 'error',
			'error_msg' => pg_last_error(),
		);
		echo json_encode($status);
		pg_free_result($result);
		pg_close($dbconn);
	}
?>