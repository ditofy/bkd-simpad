<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
	$nik =  pg_escape_string($_REQUEST['nik']);
	$qwp = "SELECT kd_provinsi||'.'||kd_kota||'.'||kd_jns||'.'||no_reg AS npwpd,nama,alamat,telp,nik,email,tgl_daftar,kelurahan,kecamatan,kota,npwp FROM public.user_sptpd WHERE nik='$nik'";
	$tampil = pg_query($qwp) or die('Query failed: ' . pg_last_error());
	$arrwp = array();
	while ($row_wp = pg_fetch_array($tampil))
	{
		array_push($arrwp, $row_wp);
	}
	echo json_encode($arrwp);
	pg_free_result($tampil);
	pg_close($dbconn);
?>