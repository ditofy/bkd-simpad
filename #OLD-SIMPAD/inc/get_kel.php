<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
if(isset($_GET['kd']))
{
	$kd_kec =  substr(pg_escape_string($_GET['kd']),0,2);
	$qkel = "SELECT kd_kelurahan,nm_kelurahan FROM public.kelurahan WHERE kd_kecamatan='$kd_kec' order by kd_kelurahan";
	$tampil = pg_query($qkel) or die('Query failed: ' . pg_last_error());
	$nrow = pg_num_rows($tampil);
	if ($nrow > 0) {
		while ($row_kel = pg_fetch_array($tampil))
		{
			echo "<option value=\"".$row_kel['kd_kelurahan']."\">".$row_kel['kd_kelurahan']." - ".$row_kel['nm_kelurahan']."</option>";
		}
	}
	
	pg_free_result($tampil);
	pg_close($dbconn);
}
?>