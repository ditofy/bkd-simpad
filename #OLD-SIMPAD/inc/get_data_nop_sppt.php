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

$stid = oci_parse($conn,"SELECT A.KD_PROPINSI||'.'||A.KD_DATI2||'.'||A.KD_KECAMATAN||'.'||A.KD_KELURAHAN||'.'||A.KD_BLOK||'-'||A.NO_URUT||'.'||A.KD_JNS_OP AS NOP,C.NM_WP,B.JALAN_OP,B.RT_OP,B.RW_OP,B.BLOK_KAV_NO_OP AS NOMOR,A.LUAS_BUMI_SPPT, A.LUAS_BNG_SPPT, D.NM_KECAMATAN, E.NM_KELURAHAN,A.NJOP_BUMI_SPPT,A.NJOP_BNG_SPPT,A.NJOP_RATIO,A.RATIO,C.SUBJEK_PAJAK_ID,C.JALAN_WP from PBB.SPPT A
INNER JOIN PBB.DAT_OBJEK_PAJAK B ON
A.KD_PROPINSI=B.KD_PROPINSI 
and A.KD_DATI2=B.KD_DATI2 
and A.KD_KECAMATAN=B.KD_KECAMATAN 
and A.KD_KELURAHAN=B.KD_KELURAHAN 
and A.kd_blok=B.kd_blok 
and A.no_urut=B.no_urut
and A.kd_jns_op=B.kd_jns_op 
INNER JOIN PBB.DAT_SUBJEK_PAJAK C ON
B.SUBJEK_PAJAK_ID=C.SUBJEK_PAJAK_ID
INNER JOIN PBB.REF_KECAMATAN D ON
A.KD_KECAMATAN=D.KD_KECAMATAN
INNER JOIN PBB.REF_KELURAHAN E ON
A.KD_KECAMATAN=E.KD_KECAMATAN AND
A.KD_KELURAHAN=E.KD_KELURAHAN
WHERE 
A.KD_KECAMATAN = '$kd_kecamatan' AND
A.KD_KELURAHAN = '$kd_kelurahan' AND
A.KD_blok = '$kd_blok' AND
A.NO_URUT='$no_urut' AND
A.KD_JNS_OP='$kd_jns_op' AND
A.THN_PAJAK_SPPT ='2025' AND 
A.RATIO IS NULL AND
A.NJOP_RATIO IS NULL
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
	"alamat_wp" => $data['JALAN_WP'],
	"nik" => $data['SUBJEK_PAJAK_ID'],
	"letak_op" => $data['JALAN_OP'],
	"nomor_op" => $data['NOMOR'],
	"rt_op" => $data['RT_OP'],
	"rw_op" => $data['RW_OP'],
	"nama_kel" => $data['NM_KELURAHAN'],
	"nama_kec" => $data['NM_KECAMATAN'],
	"njop_tanah" => $data['NJOP_BUMI_SPPT'],
	"njop_bng" => $data['NJOP_BNG_SPPT'],
	"luas_bumi" => $data['LUAS_BUMI_SPPT'],
	"luas_bng" => $data['LUAS_BNG_SPPT'],
	
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