<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/db.orcl.inc.php";
include_once $_SESSION['base_dir']."inc/schema.php";
$error = false;
$no_pelayanan = $_REQUEST['nomor'];
$jenis_layanan = $_REQUEST['jns_layanan'];
if ($jenis_layanan == '01'){
list($thn_pelayanan,$bundel_pelayanan,$no_urut_pelayanan) = explode(".",$no_pelayanan);
$stid = oci_parse($conn,"SELECT B.KD_PROPINSI_PEMOHON||'.'||B.KD_DATI2_PEMOHON||'.'||B.KD_KECAMATAN_PEMOHON||'.'||B.KD_KELURAHAN_PEMOHON||'.'||B.KD_BLOK_PEMOHON||'-'||B.NO_URUT_PEMOHON||'.'||B.KD_JNS_OP_PEMOHON AS NOP,C.NM_WP,C.SUBJEK_PAJAK_ID,D.NM_JENIS_PELAYANAN,C.JALAN_WP||' '||C.BLOK_KAV_NO_WP||' '||C.KELURAHAN_WP||' '||C.KOTA_WP AS ALAMAT
FROM PBB.DAT_OBJEK_PAJAK A
INNER JOIN PBB.PST_DETAIL B ON
A.KD_PROPINSI=B.KD_PROPINSI_PEMOHON 
and A.KD_DATI2=B.KD_DATI2_PEMOHON 
and A.KD_KECAMATAN=B.KD_KECAMATAN_PEMOHON 
and A.KD_KELURAHAN=B.KD_KELURAHAN_PEMOHON 
and A.KD_BLOK=B.KD_BLOK_PEMOHON 
and A.NO_URUT=B.NO_URUT_PEMOHON
and A.KD_JNS_OP=B.KD_JNS_OP_PEMOHON
INNER JOIN PBB.DAT_SUBJEK_PAJAK C ON
A.SUBJEK_PAJAK_ID=C.SUBJEK_PAJAK_ID
INNER JOIN PBB.REF_JNS_PELAYANAN D ON
B.KD_JNS_PELAYANAN=D.KD_JNS_PELAYANAN
WHERE B.THN_PELAYANAN ='$thn_pelayanan' AND B.BUNDEL_PELAYANAN='$bundel_pelayanan' AND NO_URUT_PELAYANAN='$no_urut_pelayanan'");
oci_execute($stid);
$data = oci_fetch_array($stid, OCI_ASSOC);
$status = array(
	"status" => "ok",
	"nik" => $data['SUBJEK_PAJAK_ID'],
	"nama_wp" => $data['NM_WP'],
	"alamat_wp" => $data['ALAMAT'],
	"layanan" => $data['NM_JENIS_PELAYANAN'],
);

oci_free_statement($stid);
echo json_encode($status);
oci_close($conn);
}
else {
list($thn_pajak,$no_urut_surat) = explode(".",$no_pelayanan);
$schema = "bphtb";
$query= "SELECT A.NIK,B.NAMA,B.ALAMAT,A.TAHUN,D.nm_transaksi FROM bphtb.pelayanan A INNER JOIN public.wp B ON A.nik=B.nik INNER JOIN bphtb.sspd C on A.tahun=C.tahun_p AND A.NO_URUT_P=C.NO_URUT_P INNER JOIN public.transaksi D on A.id_transaksi=D.id WHERE A.tahun='$thn_pajak' and A.no_urut_p='$no_urut_surat'";
$result = pg_query($query);
if($result) {
	$error = false;
} else {
	$error = true;
	$status = array(
			'status' => 'error',
			'error_msg' => pg_last_error(),
		);
	goto error_handler;
}
$data = pg_fetch_array($result);
//pg_free_result($cek_npoptkp_result);
if(empty($data['tahun'])){
	$error = true;
	$status = array(
			'status' => 'error',
			'error_msg' => 'No Pelayanan Tidak Ditemukan',
		);
	goto error_handler;
}

$status = array(
	"status" => "ok",
	"nik" => $data['nik'],
	"nama_wp" => $data['nama'],
	"alamat_wp" => $data['alamat'],
	"layanan" => $data['nm_transaksi'],
);

pg_free_result($result);
echo json_encode($status);
pg_close($dbconn);

error_handler :
	if ($error == true) {		
		echo json_encode($status);
		pg_free_result($result);
		pg_close($dbconn);
	}

};



?>