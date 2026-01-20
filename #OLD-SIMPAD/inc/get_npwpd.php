<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
	$filter = strtoupper(pg_escape_string($_GET['term']));
	$sql = "SELECT kd_provinsi||'.'||kd_kota||'.'||kd_jns||'.'||no_reg as value,nik FROM public.wp WHERE kd_provinsi||'.'||kd_kota||'.'||kd_jns||'.'||no_reg LIKE '%$filter%' AND status <> '0' LIMIT 10";
	$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
	$nrow = pg_num_rows($tampil);
	if ($nrow <= 0) {
		$no_data = array('value' => 'Data Tidak Ada', 'id' => 0);
		echo json_encode($no_data);
	} else {
		$arrkeg = array();
		while ($row_keg = pg_fetch_array($tampil))
		{
			array_push($arrkeg, $row_keg);
		}
		echo json_encode($arrkeg);
	}
	flush();
	pg_free_result($tampil);
	pg_close($dbconn);
?>