<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
	$search = strtoupper(pg_escape_string($_GET['search']));
	$schema = "hotel";
	list($kd_kecamatan,$kd_kelurahan,$kd_obj_pajak,$kd_keg_usaha,$no_reg) = explode(".",$search);
	$sql = "SELECT A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg_wp AS npwpd, A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.nm_usaha,A.alamat_usaha,A.ketetapan,A.id,A.ket,A.status_pajak,to_char(A.tgl_daftar, 'DD-MM-YYYY') AS tgl_daf,B.nama,B.alamat,B.telp,B.nik FROM $schema.dat_obj_pajak A
INNER JOIN public.wp B ON
A.kd_provinsi=B.kd_provinsi AND
A.kd_kota=B.kd_kota AND
A.kd_jns=B.kd_jns AND
A.no_reg_wp=B.no_reg
WHERE 
A.kd_kecamatan = '$kd_kecamatan' AND
A.kd_kelurahan = '$kd_kelurahan' AND
A.kd_obj_pajak = '$kd_obj_pajak' AND
A.kd_keg_usaha = '$kd_keg_usaha' AND
A.no_reg = '$no_reg'";
	$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
	$arr_det_wp = array();
	while ($row_wp = pg_fetch_array($tampil))
	{
		array_push($arr_det_wp, $row_wp);
	}
	echo json_encode($arr_det_wp);	
	pg_free_result($tampil);
	pg_close($dbconn);
?>