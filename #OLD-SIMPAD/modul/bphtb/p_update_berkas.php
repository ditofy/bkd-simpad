<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/db.orcl.inc.php";
include_once $_SESSION['base_dir']."inc/schema.php";
$error = false;
$data = $_REQUEST['postdata'];
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}
$jenis_layanan = pg_escape_string($arr_data['jenis-layanan']);
$no_layanan = pg_escape_string($arr_data['no-pelayanan']);
$nama_ambil = pg_escape_string($arr_data['nama-ambil']);
$tgl_selesai = pg_escape_string($arr_data['tgl-selesai']);

if ($jenis_layanan == '01'){
list($thn_pelayanan,$bundel_pelayanan,$no_urut_pelayanan) = explode(".",$no_pelayanan);
$queryUpdate = "UPDATE PBB.PST_DETAIL SET TGL_PENYERAHAN='$tgl_selesai',CATATAN_PENYERAHAN='$nama_ambil' WHERE B.THN_PELAYANAN ='$thn_pelayanan' AND B.BUNDEL_PELAYANAN='$bundel_pelayanan' AND NO_URUT_PELAYANAN='$no_urut_pelayanan'");
$stidUpdate = oci_parse($conn, $queryUpdate);	
$update_berkas=oci_execute($stidUpdate);

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