<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
?>

<?php
include $_SESSION['base_dir']."inc/db.orcl.inc.php";
include_once $_SESSION['base_dir']."inc/schema.php";
include_once $_SESSION['base_dir']."inc/db.inc.php";
$error = false;
$nik = $_REQUEST['nik'];
$stid = oci_parse($conn,"SELECT * FROM PBB.DAT_SUBJEK_PAJAK WHERE SUBJEK_PAJAK_ID = '$nik'");
oci_execute($stid);
$data = oci_fetch_array($stid, OCI_ASSOC);

///QUERY BPHTB ////
	

$status = array(
	"status" => "ok",
	"nik" => $data['SUBJEK_PAJAK_ID'],
	"nama_wp" => $data['NM_WP'],
	"jalan_wp" => $data['JALAN_WP'],
	"blok_wp" => $data['BLOK_KAV_NO_WP'],
	"rt_wp" => $data['RT_WP'],
	"rw_wp" => $data['RW_WP'],
	"kelurahan_wp" => $data['KELURAHAN_WP'],
	"kota_wp" => $data['KOTA_WP'],
	"telp_wp" => $data['TELP_WP'],
);

oci_free_statement($stid);
echo json_encode($status);
oci_close($conn);
pg_free_result($tampil);
pg_close($dbconn);


error_handler :
	if ($error == true) {		
		echo json_encode($status);
		pg_free_result($result);
	//	pg_close($dbconn);
	}
?>