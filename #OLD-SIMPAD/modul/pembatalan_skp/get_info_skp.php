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

if (!isset($list_schema[$kd_obj_pajak]) or !isset($jns_surat[$kd_jns_surat])) {
	$error = true;
	$status = array(
			'status' => 'error',
			'error_msg' => 'No SKPD Tidak Ditemukan',
		);
	goto error_handler;
}

$schema = $list_schema[$kd_obj_pajak];
$table = $jns_surat[$kd_jns_surat];
switch ($table) {
	case "SKP":
		if($schema == 'reklame') {
			$query = "SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg_wp AS npwpd,A.nm_reklame AS nm_objek,A.pokok_pajak,A.status_pembayaran,0 AS denda,B.nama,C.nm_obj_pajak  FROM $schema.$table A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp INNER JOIN public.obj_pajak C ON c.kd_obj_pajak=A.kd_obj_pajak WHERE A.jns_surat='$kd_jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_surat'";
		} else {
			$query = "SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg_wp AS npwpd,A.nm_usaha AS nm_objek,A.pokok_pajak,A.status_pembayaran,0 AS denda,B.nama,C.nm_obj_pajak  FROM $schema.$table A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp INNER JOIN public.obj_pajak C ON c.kd_obj_pajak=A.kd_obj_pajak WHERE A.jns_surat='$kd_jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_surat'";
		}
		break;
	case "SPTPD":
	$query = "SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg_wp AS npwpd,A.nm_usaha AS nm_objek,A.pokok_pajak,A.status_pembayaran,0 AS denda,B.nama,C.nm_obj_pajak  FROM $schema.$table A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp INNER JOIN public.obj_pajak C ON c.kd_obj_pajak=A.kd_obj_pajak WHERE A.jns_surat='$kd_jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_surat'";
	
	
		/*$status = array(
			'status' => 'error',
			'error_msg' => 'No SKPD Tidak Ditemukan',
		);
		$error = true;
		goto error_handler;*/
		break;
		case "SSPD":
		$query = "SELECT A.kd_propinsi||'.'||A.kd_dati2||'.'||A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_blok||'.'||A.no_urut||'.'||A.kd_jns_op AS nop,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg AS npwpd,A.nama_sppt AS nm_objek,A.pokok_pajak_real as pokok_pajak,A.status_pembayaran,0 AS denda,B.nama,C.nm_obj_pajak  FROM $schema.$table A INNER JOIN public.wp B ON B.nik=A.nik INNER JOIN public.obj_pajak C ON c.kd_obj_pajak=A.kd_obj_pajak WHERE A.jns_surat='$kd_jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_surat'";
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
	"pokok_pajak" => number_format($data['pokok_pajak']),
	"denda" => $data['denda'],
	"status_pembayaran" => $data['status_pembayaran'],
	"jml_bayar" => number_format($data['pokok_pajak']+$data['denda']),
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