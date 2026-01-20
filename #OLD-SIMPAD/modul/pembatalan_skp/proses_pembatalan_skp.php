<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/schema.php";
$error = false;
$no_surat = $_REQUEST['no_surat'];
$ket = strtoupper(pg_escape_string($_REQUEST['keterangan']));
list($kd_jns_surat,$thn_pajak,$bln_pajak,$kd_obj_pajak,$no_urut_surat) = explode(".",$no_surat);
$schema = $list_schema[$kd_obj_pajak];
$nip = $_SESSION['nip'];
switch ($schema) {
case "air_tanah":
$query_update = "UPDATE $schema.skp SET status_pembayaran='2',penyimpan='$nip',keterangan='$ket' WHERE jns_surat='$kd_jns_surat' AND thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak' AND kd_obj_pajak='$kd_obj_pajak' AND no_urut_surat='$no_urut_surat'";
break;
case "reklame":
$query_update = "UPDATE $schema.skp SET status_pembayaran='2',penyimpan='$nip',keterangan='$ket' WHERE jns_surat='$kd_jns_surat' AND thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak' AND kd_obj_pajak='$kd_obj_pajak' AND no_urut_surat='$no_urut_surat'";
break;
case "restoran":
$query_update = "UPDATE $schema.sptpd SET status_pembayaran='2',penyimpan='$nip',ket='$ket' WHERE jns_surat='$kd_jns_surat' AND thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak' AND kd_obj_pajak='$kd_obj_pajak' AND no_urut_surat='$no_urut_surat'";
break;
case "hotel":
$query_update = "UPDATE $schema.sptpd SET status_pembayaran='2',penyimpan='$nip',ket='$ket' WHERE jns_surat='$kd_jns_surat' AND thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak' AND kd_obj_pajak='$kd_obj_pajak' AND no_urut_surat='$no_urut_surat'";
break;
case"bphtb":
$query_update = "UPDATE $schema.sspd SET status_pembayaran='2',nip_perekam='$nip',keterangan='$ket' WHERE jns_surat='$kd_jns_surat' AND thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak' AND kd_obj_pajak='$kd_obj_pajak' AND no_urut_surat='$no_urut_surat'";
break;
}
$result = pg_query($query_update);
if($result) {
	$error = false;
} else {
	$error = true;
	goto error_handler;
}
$status = array(
	"status" => "ok",
	"msg" => "Pembatalan SKPD/SSPD No. ".$no_surat." Berhasil Di Proses",
);

echo json_encode($status);
pg_free_result($result);
pg_close($dbconn);

error_handler :
	if ($error == true) {
		$status = array(
			'status' => 'error',
			'error_msg' => pg_last_error(),
		);
		echo json_encode($status);
		pg_free_result($result);
		pg_close($dbconn);
	}
?>