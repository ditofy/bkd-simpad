<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/func_no_reg_wp.inc.php";
$data = $_REQUEST['postdata'];
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}

$nip = $_SESSION['nip'];
$nm_wp = strtoupper($arr_data['nama-wp']);
$alamat_wp = strtoupper($arr_data['alamat']);
$telp_wp = $arr_data['no-telp'];
$jns_wp = substr($arr_data['jenis-wp'],0,2);
$nik = $arr_data['nik'];
$kelurahan = strtoupper($arr_data['kelurahan']);
$kecamatan = strtoupper($arr_data['kecamatan']);
$kota = strtoupper($arr_data['kota']);
$npwp = strtoupper($arr_data['npwp']);
$query = "SELECT TO_NUMBER(MAX(no_reg),'999999') AS no_max FROM public.wp WHERE kd_jns = '$jns_wp'";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$data = pg_fetch_array($result);
$no_reg_wp = no_reg_wp($data['no_max']+1);
pg_free_result($result);
$query_wp = "INSERT INTO public.wp(kd_provinsi,kd_kota,kd_jns,no_reg,nik,nama,alamat,kelurahan,kecamatan,kota,npwp,telp,tgl_daftar,nip_pendaftar,status) VALUES('13','76','$jns_wp','$no_reg_wp','$nik','$nm_wp','$alamat_wp','$kelurahan','$kecamatan','$kota','$npwp','$telp_wp',TO_DATE('".$arr_data['tgl-daftar']."','DD-MM-YYYY'),'$nip','1')";
$result = pg_query($query_wp) or die('Query failed: ' . pg_last_error());
echo "WP berhasil ditambah dengan NPWPD : 13.76.".$jns_wp.".".$no_reg_wp;
pg_free_result($result);
pg_close($dbconn);
?>
