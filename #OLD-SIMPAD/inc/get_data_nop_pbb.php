<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
?>

<?php
include $_SESSION['base_dir']."inc/db.orcl.inc.php";
include_once $_SESSION['base_dir']."inc/schema.php";
$error = false;
$nop = $_REQUEST['nop'];
list($kd_propinsi,$kd_dati2,$kd_kecamatan,$kd_kelurahan,$kd_blok,$no_urut,$kd_jns_op) = explode(".",$nop);



$schema = $list_schema[$kd_obj_pajak];
$table = $jns_surat[$kd_jns_surat];

	$stid = oci_parse($conn,"select A.KD_PROPINSI||'.'||A.KD_DATI2||'.'||A.KD_KECAMATAN||'.'||A.KD_KELURAHAN||'.'||A.KD_BLOK||'-'||A.NO_URUT||'.'||A.KD_JNS_OP AS NOP,B.NM_WP,A.JALAN_OP,A.RT_OP,A.RW_OP,A.BLOK_KAV_NO_OP AS NOMOR,A.TOTAL_LUAS_BUMI, A.TOTAL_LUAS_BNG, C.NM_KECAMATAN, D.NM_KELURAHAN,A.NJOP_BUMI,A.NJOP_BNG from PBB.DAT_OBJEK_PAJAK A
INNER JOIN PBB.DAT_SUBJEK_PAJAK B ON
A.SUBJEK_PAJAK_ID=B.SUBJEK_PAJAK_ID
INNER JOIN PBB.REF_KECAMATAN C ON
A.KD_KECAMATAN=C.KD_KECAMATAN
INNER JOIN PBB.REF_KELURAHAN D ON
A.KD_KECAMATAN=D.KD_KECAMATAN AND
A.KD_KELURAHAN=D.KD_KELURAHAN
WHERE 
A.KD_KECAMATAN = '$kd_kecamatan' AND
A.KD_KELURAHAN = '$kd_kelurahan' AND
A.KD_blok = '$kd_blok' AND
A.NO_URUT='$no_urut' AND
A.KD_JNS_OP='$kd_jns_op'
ORDER BY A.KD_PROPINSI,A.KD_DATI2,A.KD_KECAMATAN,A.KD_KELURAHAN,A.KD_BLOK,A.NO_URUT,A.KD_JNS_OP");
	oci_execute($stid);

//$result = pg_query($query);
//if($result) {
//	$error = false;
//} else {
	//$error = true;
	//$status = array(
		//	'status' => 'error',
		//	'error_msg' => pg_last_error(),
		//);
	//goto error_handler;
//}
$data = oci_fetch_array($stid, OCI_ASSOC);
//if(empty($data['nop'])){
////	$error = true;
//	$status = array(
//			'status' => 'error',
//			'error_msg' => 'No Surat Tidak Ditemukan',
	//	);
//	goto error_handler;
//}
$njop_tanah=$data['NILAI_PER_M2_TANAH']*1000;
$njop_bng=$data['NILAI_PER_M2_BNG']*1000;
$status = array(
	"status" => "ok",
	"nop" => $data['NOP'],
	"nama_sppt" => $data['NM_WP'],
	"letak_op" => $data['JALAN_OP'],
	"nomor_op" => $data['NOMOR'],
	"rt_op" => $data['RT_OP'],
	"rw_op" => $data['RW_OP'],
	"nama_kel" => $data['NM_KELURAHAN'],
	"nama_kec" => $data['NM_KECAMATAN'],
	"njop_tanah" => $data['NJOP_BUMI'],
	"njop_bng" => $data['NJOP_BNG'],
	"luas_bumi" => $data['TOTAL_LUAS_BUMI'],
	"luas_bng" => $data['TOTAL_LUAS_BNG'],
	
);

oci_free_statement($stid);
echo json_encode($status);
oci_close($conn);

error_handler :
	if ($error == true) {		
		echo json_encode($status);
		pg_free_result($result);
	//	pg_close($dbconn);
	}
?>