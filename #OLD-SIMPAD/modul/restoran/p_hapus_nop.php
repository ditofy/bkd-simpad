<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
$schema = "restoran";
$data = $_REQUEST['postdata'];
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}
$nop = $arr_data['nop'];
list($kd_kecamatan,$kd_kelurahan,$kd_obj_pajak,$kd_keg_usaha,$no_reg) = explode(".",$nop);
$cek_skp = "SELECT 1 FROM $schema.skp WHERE kd_kecamatan='$kd_kecamatan' AND kd_kelurahan='$kd_kelurahan' AND kd_obj_pajak='$kd_obj_pajak' AND kd_keg_usaha='$kd_keg_usaha' AND no_reg='$no_reg'";
$exec_cek = pg_query($cek_skp) or die('Query failed: ' . pg_last_error());
$skp = pg_num_rows($exec_cek);
pg_free_result($exec_cek);
$cek_sptpd = "SELECT 1 FROM $schema.sptpd WHERE kd_kecamatan='$kd_kecamatan' AND kd_kelurahan='$kd_kelurahan' AND kd_obj_pajak='$kd_obj_pajak' AND kd_keg_usaha='$kd_keg_usaha' AND no_reg='$no_reg'";
$exec_cek = pg_query($cek_sptpd) or die('Query failed: ' . pg_last_error());
$sptpd = pg_num_rows($exec_cek);
pg_free_result($exec_cek);

if( ($skp > 0) or ($sptpd > 0) ) {
	echo "ERROR, Objek Pajak dengan NOP : ".$kd_kecamatan.".".$kd_kelurahan.".".$kd_obj_pajak.".".$kd_keg_usaha.".".$no_reg." Sedah Pernah Diterbitkan SKP atau SPTPD, Silahkan Non Aktifkan Status Pajak";
} else {
$query = "DELETE FROM $schema.dat_obj_pajak WHERE kd_kecamatan='$kd_kecamatan' AND kd_kelurahan='$kd_kelurahan' AND kd_obj_pajak='$kd_obj_pajak' AND kd_keg_usaha='$kd_keg_usaha' AND no_reg='$no_reg'";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
echo "Objek Pajak dengan NOP : ".$kd_kecamatan.".".$kd_kelurahan.".".$kd_obj_pajak.".".$kd_keg_usaha.".".$no_reg." berhasil di hapus";
}


pg_close($dbconn);
?>
