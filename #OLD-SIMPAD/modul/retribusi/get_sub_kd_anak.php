<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	die('Silahkan Login');
	exit;
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
//if(isset($_REQUEST['kd_opd']))
//{
	$kd_ret = pg_escape_string($_REQUEST['t']);
	$kd_opd = pg_escape_string($_REQUEST['s']);
	$kd_sub = pg_escape_string($_REQUEST['p']);
	$kd_rinci= pg_escape_string($_REQUEST['q']);
	$q_keg_usaha = "SELECT kd_detail_ret,nama_sub_detail,rek_apbd FROM retribusi.sub_detail_retribusi WHERE kd_ret='$kd_ret' AND kd_opd='$kd_opd' AND kd_sub_ret='$kd_sub' AND kd_rinci_ret='$kd_rinci'";
	$rs_keg_usaha =  pg_query($q_keg_usaha) or die('Query failed: ' . pg_last_error());
	while ($row_keg_usaha = pg_fetch_array($rs_keg_usaha))
		{
			echo "<option value=\"".$row_keg_usaha['kd_detail_ret']."\">".$row_keg_usaha['rek_apbd']."&nbsp;&nbsp;-&nbsp;&nbsp;".$row_keg_usaha['nama_sub_detail']."</option>";
		}
	pg_free_result($rs_keg_usaha);
	pg_close($dbconn);
//}
?>