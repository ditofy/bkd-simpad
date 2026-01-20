<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include $_SESSION['base_dir']."inc/db.orcl.inc.php";
if(isset($_GET['kd']))
{
$kd_kec = pg_escape_string($_GET['kd']);
list($kode_kecamatan,$nm_kecamatan) = explode(".",$kd_kec);
	$kd_kec = $_GET['kd'];
	$qkel = oci_parse($conn,"SELECT KD_KELURAHAN,NM_KELURAHAN FROM PBB.REF_KELURAHAN WHERE KD_KECAMATAN='$kode_kecamatan' ORDER BY KD_KELURAHAN");
	oci_execute($qkel);
	//$nrow = pg_num_rows($tampil);
	//if ($nrow > 0) {
		while ($data = oci_fetch_array($qkel, OCI_ASSOC))
		{
			echo "<option value=\"".$data['KD_KELURAHAN'].".".$data['NM_KELURAHAN']."\">".$data['KD_KELURAHAN']." - ".$data['NM_KELURAHAN']."</option>";
		}
	//}
	
	oci_free_statement($stid);
	oci_close($conn);
}
?>