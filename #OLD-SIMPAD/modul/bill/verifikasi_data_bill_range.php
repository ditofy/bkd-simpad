<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
$no_awal = (int)$_REQUEST['awal'];
$no_akhir = (int)$_REQUEST['akhir'];
$seri = $_REQUEST['seri'];
$schema = "bill";
$error = false;
$tidak_ada_data = false;
$beda_nop = false;
$dikembalikan = false;
$nop = "";
for($i=$no_awal;$i<=$no_akhir;$i++) {
	$query = "SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.status_pengembalian FROM $schema.dat_bill A WHERE A.seri='$seri' and A.no_bill=$i";
	$result = pg_query($query);
	if($result) {
		$error = false;
	} else {
		$error = true;
		break 1;		
	}
	$rows = pg_num_rows($result);
	if($rows <= 0) {
		pg_free_result($result);
		pg_close($dbconn);
		$status = array(
			"status" => "tab",
			"msg" => "Bill Seri ".$seri." No ".$i." Tidak Ada Dalam Data",
		);
		$tidak_ada_data = true;
		break 1;	
	} else {
		$row = pg_fetch_array($result);
		if($nop == "") {
			$nop = $row['nop'];
		}
		if($nop != $row['nop']) {
			pg_free_result($result);
			pg_close($dbconn);
			$status = array(
				"status" => "bnop",
				"msg" => "Bill Seri ".$seri." No ".$i." Bukan Terdaftar Untuk NOP ".$nop,
			);
			$beda_nop = true;
			break 1;
		} else {
			$nop = $row['nop'];
		}
		if($row['status_pengembalian'] == '1') {
			pg_free_result($result);
			pg_close($dbconn);
			$status = array(
				"status" => "sudahsetor",
				"msg" => "Bill Seri ".$seri." No ".$i." Sudah Disetor",
			);
			$dikembalikan = true;
			break 1;
		}
	}		
}

if($error) {
	goto error_handler;
}

if($tidak_ada_data or $beda_nop or $dikembalikan) {
	echo json_encode($status);
} else {
	pg_free_result($result);
	$query = "SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp AS npwpd,C.nama,C.alamat,B.nm_usaha,B.alamat_usaha FROM $schema.dat_bill A 
	INNER JOIN restoran.dat_obj_pajak B ON
	B.kd_kecamatan = A.kd_kecamatan AND
	B.kd_kelurahan = A.kd_kelurahan AND
	B.kd_obj_pajak = A.kd_obj_pajak AND
	B.kd_keg_usaha = A.kd_keg_usaha AND
	B.no_reg = A.no_reg
	INNER JOIN public.wp C ON
	B.kd_provinsi=C.kd_provinsi AND
	B.kd_kota=C.kd_kota AND
	B.kd_jns=C.kd_jns AND
	B.no_reg_wp=C.no_reg
	WHERE A.seri='$seri' and A.no_bill=$no_awal";
	$result = pg_query($query);
	if($result) {
		$error = false;
	} else {
		$error = true;
		goto error_handler;		
	}
	$row = pg_fetch_array($result);
	$status = array(
			'status' => 'ok',
			'nop' => $row['nop'],
			'npwpd' => $row['npwpd'],
			'nm_wp' => $row['nama'],
			'alamat' => $row['alamat'],
			'nm_usaha' => $row['nm_usaha'],
			'alamat_usaha' => $row['alamat_usaha'],
		);
	pg_free_result($result);
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
		pg_free_result($result);
		pg_close($dbconn);
	}
?>