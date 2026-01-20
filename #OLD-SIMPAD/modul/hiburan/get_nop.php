<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
	$schema = "hiburan";
	$filter = strtoupper(pg_escape_string($_GET['term']));
	$sql = "SELECT kd_kecamatan||'.'||kd_kelurahan||'.'||kd_obj_pajak||'.'||kd_keg_usaha||'.'||no_reg as value,id FROM $schema.dat_obj_pajak WHERE kd_kecamatan||'.'||kd_kelurahan||'.'||kd_obj_pajak||'.'||kd_keg_usaha||'.'||no_reg LIKE '%$filter%' LIMIT 10";
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