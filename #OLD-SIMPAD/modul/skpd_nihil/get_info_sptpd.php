<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/schema.php";
$error = false;
$no_surat = $_REQUEST['no_surat'];
list($kd_jns_surat,$thn_pajak,$bln_pajak,$kd_obj_pajak,$no_urut_surat) = explode(".",$no_surat);

if (!isset($list_schema[$kd_obj_pajak]) or !isset($jns_surat[$kd_jns_surat]) or ($kd_jns_surat != '02')) {
	$error = true;
	$status = array(
			'status' => 'error',
			'error_msg' => 'No Surat Tidak Ditemukan',
		);
	goto error_handler;
}

$schema = $list_schema[$kd_obj_pajak];
$table = $jns_surat[$kd_jns_surat];
switch ($table) {
	case "SPTPD":
		$query = "SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg_wp AS npwpd,A.nm_usaha AS nm_objek,A.status_pembayaran,0 AS denda,B.nama,C.nm_obj_pajak,D.pokok_pajak,to_char(D.tgl_bayar, 'DD-MM-YYYY') AS tgl_bayar,D.no_bukti FROM $schema.$table A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp INNER JOIN public.obj_pajak C ON c.kd_obj_pajak=A.kd_obj_pajak LEFT JOIN $schema.pembayaran D ON A.jns_surat=D.jns_surat AND A.thn_pajak=D.thn_pajak AND A.bln_pajak=D.bln_pajak AND A.kd_obj_pajak=D.kd_obj_pajak AND A.no_urut_surat=D.no_urut_surat WHERE A.jns_surat='$kd_jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_surat'";
		break;
}

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
if(empty($data['nop'])){
	$error = true;
	$status = array(
			'status' => 'error',
			'error_msg' => 'No Surat Tidak Ditemukan',
		);
	goto error_handler;
}

$status = array(
	"status" => "ok",
	"jns_surat" => $jns_surat[$kd_jns_surat],
	"jns_pajak" => $data['nm_obj_pajak'],
	"nop" => $data['nop'],
	"nm_objek" => $data['nm_objek'],
	"npwpd" => $data['npwpd'],
	"nm_wp" => $data['nama'],
	"pokok_pajak" => $data['pokok_pajak'],
	"denda" => $data['denda'],
	"status_pembayaran" => $data['status_pembayaran'],
	"jml_bayar" => number_format($data['pokok_pajak']+$data['denda']),
	"tgl_bayar" =>  $data['tgl_bayar'],
	"no_bukti" =>  $data['no_bukti'],
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
?>