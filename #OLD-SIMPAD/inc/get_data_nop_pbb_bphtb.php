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
$nop = $_REQUEST['nop'];
list($kd_propinsi,$kd_dati2,$kd_kecamatan,$kd_kelurahan,$kd_blok,$no_urut,$kd_jns_op) = explode(".",$nop);

$schema = $list_schema[$kd_obj_pajak];
$table = $jns_surat[$kd_jns_surat];

$stid = oci_parse($conn,"select A.KD_PROPINSI||'.'||A.KD_DATI2||'.'||A.KD_KECAMATAN||'.'||A.KD_KELURAHAN||'.'||A.KD_BLOK||'-'||A.NO_URUT||'.'||A.KD_JNS_OP AS NOP,B.NM_WP,B.JALAN_WP,A.JALAN_OP,A.RT_OP,A.RW_OP,A.BLOK_KAV_NO_OP AS NOMOR,A.TOTAL_LUAS_BUMI, A.TOTAL_LUAS_BNG, C.NM_KECAMATAN, D.NM_KELURAHAN,A.NJOP_BUMI,A.NJOP_BNG,B.SUBJEK_PAJAK_ID FROM PBB.DAT_OBJEK_PAJAK A
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
$data = oci_fetch_array($stid, OCI_ASSOC);
$njop_tanah=$data['NILAI_PER_M2_TANAH']*1000;
$njop_bng=$data['NILAI_PER_M2_BNG']*1000;

///QUERY BPHTB ////

	//list($kd_propinsi,$kd_dati2,$kd_kecamatan,$kd_kelurahan,$kd_blok,$no_urut,$kd_jns_op) = explode(".",$nop);
	$sql = "SELECT A.NIK,B.nama,B.alamat,B.telp,B.kelurahan,B.kecamatan,B.kota FROM BPHTB.SSPD A 
	INNER JOIN PUBLIC.WP B ON
	A.NIK=B.NIK
	WHERE kd_propinsi='$kd_propinsi' AND kd_dati2='$kd_dati2' AND kd_kecamatan='$kd_kecamatan' AND kd_kelurahan='$kd_kelurahan' AND kd_blok ='$kd_blok' AND no_urut='$no_urut' AND kd_jns_op='$kd_jns_op'";
	$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
	//$arr_det_wp = array();
    $row_wp = pg_fetch_array($tampil);
	

$status = array(
	"status" => "ok",
	"nop" => $data['NOP'],
	"nik" => $data['SUBJEK_PAJAK_ID'],
	"nama_sppt" => $data['NM_WP'],
	"alamat_wp_sppt" => $data['JALAN_WP'],
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
	"nik_wp" => $row_wp['nik'],
	"nama_wp" => $row_wp['nama'],
	"alamat_wp" => $row_wp['alamat'],
	"kelurahan_wp" => $row_wp['kelurahan'],
	"kecamatan_wp" => $row_wp['kecamatan'],
	"kota_wp" => $row_wp['kota'],
	"telp_wp" => $row_wp['telp'],
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