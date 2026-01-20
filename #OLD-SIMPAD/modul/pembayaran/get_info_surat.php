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
			'error_msg' => 'No Surat Tidak Ditemukan',
		);
	goto error_handler;
}

$schema = $list_schema[$kd_obj_pajak];
$table = $jns_surat[$kd_jns_surat];
switch ($table) {
	case "SKP":
		if($schema == 'reklame') {
			$query = "SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg_wp AS npwpd,A.nm_reklame AS nm_objek,A.pokok_pajak,A.status_pembayaran,0 AS denda,B.nama,B.alamat,C.nm_obj_pajak,D.mtgkey   FROM $schema.$table A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp INNER JOIN public.obj_pajak C ON c.kd_obj_pajak=A.kd_obj_pajak 
INNER JOIN public.keg_usaha D ON D.kd_obj_pajak=A.kd_obj_pajak and D.kd_keg_usaha=A.kd_keg_usaha			
			WHERE A.jns_surat='$kd_jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_surat'";
		} else {
			$query = "SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg_wp AS npwpd,A.nm_usaha AS nm_objek,A.pokok_pajak,A.status_pembayaran,0 AS denda,B.nama,B.alamat,C.nm_obj_pajak,D.mtgkey   FROM $schema.$table A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp INNER JOIN public.obj_pajak C ON c.kd_obj_pajak=A.kd_obj_pajak INNER JOIN public.keg_usaha D ON D.kd_obj_pajak=A.kd_obj_pajak and D.kd_keg_usaha=A.kd_keg_usaha WHERE A.jns_surat='$kd_jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_surat'";
		}
		break;
	case "SPTPD":
		$query = "SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg_wp AS npwpd,A.nm_usaha AS nm_objek,A.pokok_pajak,A.status_pembayaran,0 AS denda,B.nama,B.alamat,C.nm_obj_pajak,D.mtgkey  FROM $schema.$table A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp INNER JOIN public.obj_pajak C ON c.kd_obj_pajak=A.kd_obj_pajak
INNER JOIN public.keg_usaha D ON D.kd_obj_pajak=A.kd_obj_pajak and D.kd_keg_usaha=A.kd_keg_usaha		
		 WHERE A.jns_surat='$kd_jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_surat'";
		break;
		case "SSPD":
		$query = "SELECT A.kd_propinsi||'.'||A.kd_dati2||'.'||A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_blok||'.'||A.no_urut||'.'||A.kd_jns_op AS nop,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg AS npwpd,A.nama_sppt AS nm_objek,C.pokok_pajak_real as pokok_pajak,C.status_pembayaran,0 AS denda,B.nama,D.nm_obj_pajak,A.pengurangan,E.mtgkey   FROM bphtb.pelayanan A INNER JOIN public.wp B ON B.nik=A.nik INNER JOIN bphtb.sspd C ON C.tahun_p=A.tahun AND C.no_urut_p=A.no_urut_p  INNER JOIN public.obj_pajak D ON D.kd_obj_pajak=C.kd_obj_pajak
	INNER JOIN public.keg_usaha E ON E.kd_obj_pajak=C.kd_obj_pajak and E.kd_keg_usaha=C.kd_sh	
		 WHERE C.jns_surat='$kd_jns_surat' AND C.thn_pajak='$thn_pajak' AND C.bln_pajak='$bln_pajak' AND C.kd_obj_pajak='$kd_obj_pajak' AND C.no_urut_surat='$no_urut_surat'";
		break;
		case "SKPDKB":
		$query="select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,C.kd_provinsi||'.'||C.kd_kota||'.'||C.kd_jns||'.'||C.no_reg as npwpd,C.nama,C.alamat,b.nm_usaha as nm_objek,C.alamat as alamat_wp,b.alamat_usaha as alamat_objek,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,A.thn_pajak,a.bln_pajak,D.nm_obj_pajak,D.perda,A.tgl_jatuh_tempo,A.dasar_pajak,A.pokok_pajak,A.pajak_disetor,A.pokok_pajak_baru,A.tgl_surat,A.nip_p,A.ket,A.status_pembayaran,0 as denda from $schema.$table A LEFT JOIN 
$schema.dat_obj_pajak B on A.kd_kecamatan=B.kd_kecamatan and A.kd_kelurahan=B.kd_kelurahan and A.kd_keg_usaha=b.kd_keg_usaha and A.no_reg=B.no_reg
LEFT JOIN public.wp C on C.kd_provinsi=b.kd_provinsi and C.kd_kota=B.kd_kota and C.kd_jns=B.kd_jns and C.no_reg=B.no_reg_wp 
LEFT JOIN public.obj_pajak D on D.kd_obj_pajak=A.kd_obj_pajak
WHERE A.jns_surat='05' and A.thn_pajak='$thn_pajak' and A.bln_pajak='$bln_pajak' and A.kd_obj_pajak='$kd_obj_pajak' and A.no_urut_surat='$no_urut_surat'";
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
	"alamat_wp" => $data['alamat'],
	"pokok_pajak" => $data['pokok_pajak'],
	"denda" => $data['denda'],
	"mtgkey" => $data['mtgkey'],
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