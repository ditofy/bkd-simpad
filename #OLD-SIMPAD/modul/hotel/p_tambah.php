<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/func_no_reg.inc.php";
$schema = "hotel";
$data = $_REQUEST['postdata'];
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}

$nip = $_SESSION['nip'];
$kd_kecamatan = substr($arr_data['kd-kecamatan'],0,2);
$kd_kelurahan = substr($arr_data['kd-kelurahan'],0,2);
$kd_obj_pajak = substr($arr_data['kd-obj-pajak'],0,2);
$kd_keg_usaha = substr($arr_data['kd-keg-usaha'],0,2);
$ketetapan = $arr_data['ketetapan'];
if($ketetapan == ''){
			$ketetapan = 0;
}
$nm_usaha = strtoupper($arr_data['nama-usaha']);
$alamat_usaha = strtoupper($arr_data['alamat-usaha']);
$npwpd = strtoupper($arr_data['npwpd']);

$izin = $arr_data['izin-usaha'];
$masa = strtoupper($arr_data['masa-berlaku']);
$pembukuan = strtoupper($arr_data['metode-pembukuan']);
$sumber_air = strtoupper($arr_data['sumber-air']);
$bumi = $arr_data['bumi'];
$bangunan = $arr_data['bangunan'];
$karyawan = $arr_data['karyawan'];
$occupancy = $arr_data['occupancy'];
$parkir = strtoupper($arr_data['parkir']);

list($kd_prov,$kd_kota,$kd_jns,$no_reg_wp) = explode(".",$npwpd);

$query = "SELECT TO_NUMBER(MAX(no_reg),'999999') AS no_max FROM $schema.dat_obj_pajak WHERE kd_kecamatan='$kd_kecamatan' AND kd_kelurahan='$kd_kelurahan'
	AND kd_obj_pajak='$kd_obj_pajak' AND kd_keg_usaha='$kd_keg_usaha'";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$data = pg_fetch_array($result);
$no_reg = no_reg($data['no_max']+1);
pg_free_result($result);
$query_input = "INSERT INTO $schema.dat_obj_pajak(kd_provinsi,kd_kota,kd_jns,no_reg_wp,kd_kecamatan,kd_kelurahan,kd_obj_pajak,kd_keg_usaha,no_reg,nm_usaha,alamat_usaha,penyimpan,ketetapan,status_pajak,tgl_daftar,jenis_penetapan,izin,berlaku_izin,pembukuan,sumber_air,bumi,bangunan,karyawan,occupancy,parkir) VALUES('13','76','$kd_jns','$no_reg_wp','$kd_kecamatan','$kd_kelurahan','$kd_obj_pajak','$kd_keg_usaha','$no_reg','$nm_usaha','$alamat_usaha','$nip',$ketetapan,'1',TO_DATE('".$arr_data['tgl-daftar']."','DD-MM-YYYY'),'02','$izin','$masa','$pembukuan','$sumber_air','$bumi','$bangunan','$karyawan','$occupancy','$parkir')";
$result = pg_query($query_input) or die('Query failed: ' . pg_last_error());
echo "Objek Pajak berhasil simpan dengan NOP : ".$kd_kecamatan.".".$kd_kelurahan.".".$kd_obj_pajak.".".$kd_keg_usaha.".".$no_reg;
pg_free_result($result);
pg_close($dbconn);
?>
