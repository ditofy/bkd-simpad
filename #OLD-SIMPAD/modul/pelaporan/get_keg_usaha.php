<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	die('Silahkan Login');
	exit;
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
if(isset($_REQUEST['kd_obj']))
{
	$kd_obj_pjk = pg_escape_string($_REQUEST['kd_obj']);
	$q_keg_usaha = "SELECT kd_keg_usaha,nm_keg_usaha FROM public.keg_usaha WHERE kd_obj_pajak='$kd_obj_pjk'";
	$rs_keg_usaha =  pg_query($q_keg_usaha) or die('Query failed: ' . pg_last_error());
	while ($row_keg_usaha = pg_fetch_array($rs_keg_usaha))
		{
			echo "<option value=\"".$row_keg_usaha['kd_keg_usaha']."\">".$row_keg_usaha['nm_keg_usaha']."</option>";
		}
	pg_free_result($rs_keg_usaha);
	pg_close($dbconn);
}
?>