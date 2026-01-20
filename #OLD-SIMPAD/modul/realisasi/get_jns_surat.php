<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	die('Silahkan Login');
	exit;
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
if(isset($_REQUEST['kd_obj']))
{
	$kd_obj = pg_escape_string($_REQUEST['kd_obj']);
	$q_keg_usaha = "SELECT jns_surat,nm_surat FROM public.jenis_surat WHERE kd_obj='$kd_obj' order by jns_surat ASC";
	$rs_keg_usaha =  pg_query($q_keg_usaha) or die('Query failed: ' . pg_last_error());
	while ($row_keg_usaha = pg_fetch_array($rs_keg_usaha))
		{
			echo "<option value=\"".$row_keg_usaha['jns_surat']."\">".$row_keg_usaha['nm_surat']."</option>";
		}
	pg_free_result($rs_keg_usaha);
	pg_close($dbconn);
}
?>