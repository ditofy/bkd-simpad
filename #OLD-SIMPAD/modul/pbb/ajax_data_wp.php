<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
?>
<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include $_SESSION['base_dir']."inc/db.orcl.inc.php";
$nop = pg_escape_string($_REQUEST['nop']);
list($kd_prop,$kd_dati2,$kd_kec,$kd_kel,$kd_blok,$no_urut,$kd_jns) = explode(".",$nop);
	//$filter = strtoupper(pg_escape_string($_GET['term']));
	
	$sql = oci_parse($conn,"SELECT KD_PROVINSI||'.'||KD_DATI2||'.'||KD_KECAMATAN||'.'||KD_KELURAHAN||'.'||KD_BLOK||'.'||NO_URUT||'.'||KD_JNS_OP as value,nik FROM PBB.SPPT WHERE KD_PROVINSI||'.'||KD_DATI2||'.'||KD_KECAMATAN||'.'||KD_KELURAHAN||'.'||KD_BLOK||'.'||NO_URUT||'.'||KD_JNS_OP LIKE '%$nop%'"); 

	//$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
	//$nrow = pg_num_rows($tampil);
	if (if (!$stid)  {
		$no_data = array('value' => 'Data Tidak Ada', 'id' => 0);
		
	echo json_encode($no_data);
	} else {
		$arrkeg = array();
		while ($row_keg = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS))
		{
			array_push($arrkeg, $row_keg);
		}
		echo json_encode($arrkeg);
	//}
	flush();
	pg_free_result($tampil);
	pg_close($dbconn);
?>


