<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
$schema = "bill";
$error = false;
$no_bill = (int)pg_escape_string($_REQUEST['no_bill']);
$seri = pg_escape_string($_REQUEST['seri']);
$ket = strtoupper(pg_escape_string($_REQUEST['ket']));
$today = date("d-m-Y");
$nip = $_SESSION['nip'];
	$query = "UPDATE $schema.dat_bill set tgl_pengembalian=TO_DATE('$today','DD-MM-YYYY'),status_pengembalian='2',keterangan='$ket' 
	WHERE seri='$seri' and no_bill=$no_bill";
	$result = pg_query($query);
	if($result) {
		$error = false;
	} else {
		$error = true;
	}

if($error) {
	goto error_handler;
} else {
	$status = array(
			'status' => 'ok',
			'msg' => 'Bill Seri '.$seri." No ".$no_bill." Berhasil Di Batalkan",
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