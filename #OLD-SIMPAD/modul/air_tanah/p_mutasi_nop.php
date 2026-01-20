<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/func_no_reg.inc.php";
$schema = "air_tanah";
$data = $_REQUEST['postdata'];
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}

$nip = $_SESSION['nip'];
$nop = $arr_data['nop'];
$kd_kecamatan_u = $arr_data['kd-kecamatan'];
$kd_kelurahan_u = $arr_data['kd-kelurahan'];
$kd_obj_pajak_u = substr($arr_data['kd-obj-pajak'],0,2);
$kd_keg_usaha_u = $arr_data['kd-keg-usaha'];
list($kd_kecamatan,$kd_kelurahan,$kd_obj_pajak,$kd_keg_usaha,$no_reg) = explode(".",$nop);

$query = "SELECT TO_NUMBER(MAX(no_reg),'999999') AS no_max FROM $schema.dat_obj_pajak WHERE kd_kecamatan='$kd_kecamatan_u' AND kd_kelurahan='$kd_kelurahan_u'
	AND kd_obj_pajak='$kd_obj_pajak_u' AND kd_keg_usaha='$kd_keg_usaha_u'";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$data = pg_fetch_array($result);
$no_reg_u = no_reg($data['no_max']+1);
pg_free_result($result);

$query_input = "UPDATE $schema.dat_obj_pajak SET kd_kecamatan='$kd_kecamatan_u',kd_kelurahan='$kd_kelurahan_u',kd_obj_pajak='$kd_obj_pajak_u',kd_keg_usaha='$kd_keg_usaha_u',no_reg='$no_reg_u' WHERE kd_kecamatan='$kd_kecamatan' AND kd_kelurahan='$kd_kelurahan' AND kd_obj_pajak='$kd_obj_pajak' AND kd_keg_usaha='$kd_keg_usaha' AND no_reg='$no_reg'";
$result = pg_query($query_input) or die('Query failed: ' . pg_last_error());
echo "Objek Pajak NOP : ".$nop." Berhasil Diganti Ke : ".$kd_kecamatan_u.".".$kd_kelurahan_u.".".$kd_obj_pajak_u.".".$kd_keg_usaha_u.".".$no_reg_u;
pg_free_result($result);
pg_close($dbconn);
?>
