<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/func_no_reg.inc.php";
include_once $_SESSION['base_dir']."inc/schema.php";
$error = false;
/*$data = $_REQUEST['postdata'];
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}*/
//$no_surat = $arr_data['no_surat'];
$no_surat = $_REQUEST['no_surat'];
list($jns_surat_s,$thn_pajak_s,$bln_pajak_s,$kd_obj_pajak_s,$no_urut_surat_s) = explode(".",$no_surat);
$schema = $list_schema[$kd_obj_pajak_s];
$nip = $_SESSION['nip'];
$nik = pg_escape_string($_REQUEST['nik']);
$nop = $_REQUEST['nop'];
$dasar_skpdkb = pg_escape_string($_REQUEST['dasar_skpdkb']);
$dasar_pengenaan_skpdkb= str_replace(".", "", $dasar_skpdkb);

$pajak_bayar  = pg_escape_string($_REQUEST['pajak_bayar']);
$pajak_bayar_skpdkb= str_replace(".", "", $pajak_bayar);


$pajak_kurang = pg_escape_string($_REQUEST['pajak_kurang']);
$pajak_kurang_skpdkb= str_replace(".", "", $pajak_kurang);

$pajak_seharusnya = pg_escape_string($_REQUEST['pajak_seharusnya']);
$pajak_seharusnya_skpdkb= str_replace(".", "", $pajak_seharusnya);

$today = date("d-m-Y");
list($tgl_pajak_skpdkb,$bln_pajak_skpdkb,$thn_pajak_skpdkb) = explode("-",$today);
$newDate = date("d-m-Y", strtotime('+30days', strtotime($today)));
$nama_wp =  pg_escape_string($_REQUEST['namawp']);
$kd_kecamatan  = pg_escape_string($_REQUEST['kd_kecamatan']);
$kd_kelurahan  = pg_escape_string($_REQUEST['kd_kelurahan']);
$kd_keg_usaha  = pg_escape_string($_REQUEST['kd_keg_usaha']);
$pejabat = pg_escape_string($_REQUEST['tanda_tangan']);
////////QUERY CEK //////////
$query_cek = "SELECT * FROM $schema.skpdkb WHERE jns_surat_parent='$jns_surat_s' AND thn_pajak_parent='$thn_pajak_s' AND bln_pajak_parent='$bln_pajak_s' AND kd_obj_pajak_parent='$kd_obj_pajak_s' AND no_urut_surat_parent='$no_urut_surat_s'";
$exec_query_cek = pg_query($query_cek);
$ada_data = pg_num_rows($exec_query_cek);
$row_cek = pg_fetch_array ($exec_query_cek);
if($ada_data > 0 ) {
	if($row_cek['status_pembayaran'] > 0){
				    $status = array(
					"status" => "bayar",
					"msg" => "SKPDKB Ini Sudah Terbayar Tidak Bisa Di Update Lagi !!",
					);
	}
	else{
$query_update = "UPDATE $schema.skpdkb A SET dasar_pajak=$dasar_pengenaan_skpdkb,pajak_seharusnya=$pajak_seharusnya_skpdkb,pokok_pajak=$pajak_kurang_skpdkb WHERE A.jns_surat_parent='$jns_surat_s' AND A.thn_pajak_parent='$thn_pajak_s' AND A.bln_pajak_parent='$bln_pajak_s' AND A.kd_obj_pajak_parent='$kd_obj_pajak_s' AND A.no_urut_surat_parent='$no_urut_surat_s' AND A.bln_pajak='$bln_pajak_skpdkb' AND A.thn_pajak='$thn_pajak_skpdkb'";
$result = pg_query($query_update);
			if($result) {
					$error = false;
				} else {
					$error = true;
					goto error_handler;
				}
					$status = array(
					"status" => "update",
					"msg" => "Data Berhasil Di Update",
					);	?>
					<?php	
	     }	
	  }	
else  {				
$query = "SELECT TO_NUMBER(MAX(no_urut_surat),'999999') AS no_max FROM $schema.skpdkb WHERE thn_pajak='$thn_pajak_skpdkb' AND bln_pajak='$bln_pajak_skpdkb' AND kd_obj_pajak='$kd_obj_pajak_s'";
				$result_q = pg_query($query);
				if($result_q) {
					$error = false;
				} else {
					$error = true;
					goto error_handler;
				}
				$data = pg_fetch_array($result_q);
				$no_urut_skpdkb = no_reg($data['no_max']+1);
				pg_free_result($result_q);
				
$query_input = "INSERT INTO $schema.skpdkb
(jns_surat,
thn_pajak,
bln_pajak,
kd_obj_pajak,
no_urut_surat,
tgl_surat,
penyimpan,
nip_p,
dasar_pajak,
pokok_pajak,
pajak_disetor,
pajak_seharusnya,
tgl_jatuh_tempo,
nama_wp,
status_pembayaran,
nop,
nik,
va,
jns_surat_parent,
thn_pajak_parent,
bln_pajak_parent,
kd_obj_pajak_parent,
no_urut_surat_parent,
kd_kecamatan,
kd_kelurahan,
kd_keg_usaha) 
VALUES
('05',
'$thn_pajak_skpdkb',
'$bln_pajak_skpdkb',
'$kd_obj_pajak_s',
'$no_urut_skpdkb',
TO_DATE('$today','DD-MM-YYYY'),
'$nip',
'$pejabat',
$dasar_pengenaan_skpdkb,
$pajak_kurang_skpdkb,
$pajak_bayar_skpdkb,
$pajak_seharusnya_skpdkb,
'$newDate',
'$nama_wp',
'0',
'$nop',
'$nik',
'0',
'$jns_surat_s',
'$thn_pajak_s',
'$bln_pajak_s',
'$kd_obj_pajak_s',
'$no_urut_surat_s',
'$kd_kecamatan',
'$kd_kelurahan',
'$kd_keg_usaha')";

				$result = pg_query($query_input);
				if($result) {
					$error = false;
				} else {
					$error = true;
					goto error_handler;
				}
				pg_free_result($result);
				$status = array(
					"status" => "ok",
					"msg" => "No. 05.".$thn_pajak_skpdkb.".".$bln_pajak_skpdkb.".".$kd_obj_pajak_s.".".$no_urut_skpdkb." Berhasil Di Simpan",
				);
			}
		//} else {
		//	$error = true;
		//	goto error_handler;
		//}
			

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
