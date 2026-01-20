<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	die('Access Denied');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
$schema = "bill";
$error = false;
$data = $_REQUEST['postdata'];
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}
$nop = $arr_data['nop'];
$no_awal = (int)$arr_data['no-awal'];
$no_akhir = (int)$arr_data['no-akhir'];
$seri = $arr_data['seri-bill'];
$tgl_serah = $arr_data['tanggal-penyerahan'];
$nip = $_SESSION['nip'];
list($kd_kecamatan,$kd_kelurahan,$kd_obj_pajak,$kd_keg_usaha,$no_reg) = explode(".",$nop);
$ada_data = false;
for($i=$no_awal;$i<=$no_akhir;$i++) {
	$query = "SELECT 1 FROM $schema.dat_bill WHERE seri='$seri' and no_bill=$i";
	$result = pg_query($query);
	if($result) {
		$error = false;
	} else {
		$error = true;
		break 1;		
	}
	$rows = pg_num_rows($result);
	if($rows > 0) {
		pg_free_result($result);
		pg_close($dbconn);
		$status = array(
			"status" => "ada",
			"msg" => "Bill Seri ".$seri." No ".$i." Sudah Ada Dalam Data",
		);
		$ada_data = true;
		break 1;	
	}	
}
if($error) {
	goto error_handler;
}

if(!$ada_data) {
	for($i=$no_awal;$i<=$no_akhir;$i++) {
                if($i == $no_awal) {
                    $query_input = "INSERT INTO $schema.dat_bill(kd_kecamatan,kd_kelurahan,kd_obj_pajak,kd_keg_usaha,no_reg,seri,no_bill,status_pengembalian,tgl_penyerahan,nip_perekam,buku) 
		VALUES('$kd_kecamatan','$kd_kelurahan','$kd_obj_pajak','$kd_keg_usaha','$no_reg','$seri',$i,'0',TO_DATE('$tgl_serah','DD-MM-YYYY'),'$nip','AW')";
                } else if($i == $no_akhir) {
                    $query_input = "INSERT INTO $schema.dat_bill(kd_kecamatan,kd_kelurahan,kd_obj_pajak,kd_keg_usaha,no_reg,seri,no_bill,status_pengembalian,tgl_penyerahan,nip_perekam,buku) 
		VALUES('$kd_kecamatan','$kd_kelurahan','$kd_obj_pajak','$kd_keg_usaha','$no_reg','$seri',$i,'0',TO_DATE('$tgl_serah','DD-MM-YYYY'),'$nip','AK')";
                } else {
                    $query_input = "INSERT INTO $schema.dat_bill(kd_kecamatan,kd_kelurahan,kd_obj_pajak,kd_keg_usaha,no_reg,seri,no_bill,status_pengembalian,tgl_penyerahan,nip_perekam) 
		VALUES('$kd_kecamatan','$kd_kelurahan','$kd_obj_pajak','$kd_keg_usaha','$no_reg','$seri',$i,'0',TO_DATE('$tgl_serah','DD-MM-YYYY'),'$nip')";
                }		
		$result = pg_query($query_input);
		if($result) {
			$error = false;
		} else {
			$error = true;
			break 1;
		}
	}
} else {	
	echo json_encode($status);
}

if(!$error and !$ada_data) {
	$status = array(
	"status" => "ok",
	"msg" => "Bill Seri ".$seri." No ".$no_awal." - ".$no_akhir." Berhasil Disimpan",
	);
	echo json_encode($status);	
	pg_close($dbconn);
}

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