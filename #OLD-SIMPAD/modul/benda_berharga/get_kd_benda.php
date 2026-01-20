<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	die('Silahkan Login');
	exit;
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
if(isset($_REQUEST['kd_opd']))
{
	$kd_opd = pg_escape_string($_REQUEST['kd_opd']);
	$q_keg_usaha = "SELECT kd_benda,nama_benda,harga_lbr FROM benda_berharga.benda_berharga WHERE kd_opd='$kd_opd'";
	$rs_keg_usaha =  pg_query($q_keg_usaha) or die('Query failed: ' . pg_last_error());
	while ($row_keg_usaha = pg_fetch_array($rs_keg_usaha))
		{
			echo "<option value=\"".$row_keg_usaha['kd_benda']."\">".$row_keg_usaha['nama_benda']."---".$row_keg_usaha['harga_lbr']."</option>";
		}
	pg_free_result($rs_keg_usaha);
	pg_close($dbconn);
}
?>