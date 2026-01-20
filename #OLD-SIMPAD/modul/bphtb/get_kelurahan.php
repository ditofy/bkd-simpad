<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	die('Silahkan Login');
	exit;
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
if(isset($_REQUEST['kd_kec']))
{
	$kd_kec = pg_escape_string($_REQUEST['kd_kec']);
	$q_kel = "SELECT kd_kelurahan,kelurahan_op FROM bphtb.sspd WHERE kd_kecamatan='$kd_kec' group by kd_kelurahan,kelurahan_op";
	$rs_kel=  pg_query($q_kel) or die('Query failed: ' . pg_last_error());
	while ($row_kel = pg_fetch_array($rs_kel))
		{
			echo "<option value=\"".$row_kel['kd_kelurahan']."\">".$row_kel['kelurahan_op']."</option>";
		}
	pg_free_result($rs_kel);
	pg_close($dbconn);
}
?>