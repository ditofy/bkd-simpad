<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
	$search = strtoupper(pg_escape_string($_GET['search']));
	list($kd_prov,$kd_kota,$kd_jns,$no_reg) = explode(".",$search);
	$sql = "SELECT to_char(tgl_daftar, 'DD-MM-YYYY') AS tgl_daf,kd_provinsi||'.'||kd_kota||'.'||kd_jns||'.'||no_reg AS npwpd, * FROM public.wp WHERE nik ='$search' AND status != '0'";
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