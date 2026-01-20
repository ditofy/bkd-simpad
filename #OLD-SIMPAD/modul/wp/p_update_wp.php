<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";

$data = $_REQUEST['postdata'];
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}

$nip = $_SESSION['nip'];
$npwpd = $arr_data['npwpd'];
list($kd_prov,$kd_kota,$kd_jns,$no_reg_wp) = explode(".",$npwpd);
$nm_wp = strtoupper($arr_data['nama-wp']);
$alamat_wp = strtoupper($arr_data['alamat']);
$telp_wp = $arr_data['no-telp'];
$nik = $arr_data['nik'];
$npwp = $arr_data['npwp'];
$status = $arr_data['status-wp'];
$kelurahan = strtoupper($arr_data['kelurahan']);
$kecamatan = strtoupper($arr_data['kecamatan']);
$kota = strtoupper($arr_data['kota']);

$query_wp = "UPDATE public.wp SET nik='$nik',nama='$nm_wp',alamat='$alamat_wp',kelurahan='$kelurahan',kecamatan='$kecamatan',kota='$kota',npwp='$npwp',telp='$telp_wp',tgl_daftar=TO_DATE('".$arr_data['tgl-daftar']."','DD-MM-YYYY'),nip_pendaftar='$nip',status='$status' WHERE kd_provinsi='$kd_prov' AND kd_kota='$kd_kota' AND kd_jns='$kd_jns' AND no_reg='$no_reg_wp'";
$result = pg_query($query_wp) or die('Query failed: ' . pg_last_error());
echo "WP dengan NPWPD : 13.76.".$kd_jns.".".$no_reg_wp." berhasil di update";
pg_free_result($result);
pg_close($dbconn);
?>
