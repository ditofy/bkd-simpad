<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
$schema = "hiburan";
$data = $_REQUEST['postdata'];
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}

$nip = $_SESSION['nip'];
$nop = $arr_data['nop'];
list($kd_kecamatan,$kd_kelurahan,$kd_obj_pajak,$kd_keg_usaha,$no_reg) = explode(".",$nop);
$npwpd = $arr_data['npwpd'];
list($kd_prov,$kd_kota,$kd_jns,$no_reg_wp) = explode(".",$npwpd);
$ketetapan = $arr_data['ketetapan'];
if($ketetapan == ''){
			$ketetapan = 0;
}
$nm_usaha = strtoupper($arr_data['nama-usaha']);
$alamat_usaha = strtoupper($arr_data['alamat-usaha']);
$jenis_penetapan = substr($arr_data['jenis-penetapan'],0,2);
$keterangan = strtoupper($arr_data['keterangan']);
$status_pajak = $arr_data['status-pajak'];
$query = "UPDATE $schema.dat_obj_pajak SET kd_provinsi='$kd_prov',kd_kota='$kd_kota',kd_jns='$kd_jns',no_reg_wp='$no_reg_wp',nm_usaha='$nm_usaha',alamat_usaha='$alamat_usaha',ketetapan=$ketetapan,status_pajak='$status_pajak',ket='$keterangan',penyimpan='$nip',tgl_daftar=TO_DATE('".$arr_data['tgl-daftar']."','DD-MM-YYYY'),jenis_penetapan='$jenis_penetapan' WHERE kd_kecamatan='$kd_kecamatan' AND kd_kelurahan='$kd_kelurahan' AND kd_obj_pajak='$kd_obj_pajak' AND kd_keg_usaha='$kd_keg_usaha' AND no_reg='$no_reg'";

//$query_input = "INSERT INTO $schema.dat_obj_pajak(kd_provinsi,kd_kota,kd_jns,no_reg_wp,kd_kecamatan,kd_kelurahan,kd_obj_pajak,kd_keg_usaha,no_reg,nm_usaha,alamat_usaha,penyimpan,ketetapan,status_pajak,jenis_penetapan,tgl_daftar) VALUES('13','76','$kd_jns','$no_reg_wp','$kd_kecamatan','$kd_kelurahan','$kd_obj_pajak','$kd_keg_usaha','$no_reg','$nm_usaha','$alamat_usaha','$nip',$ketetapan,'1','$jenis_penetapan',TO_DATE('".$arr_data['tgl-daftar']."','DD-MM-YYYY'))";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
echo "Objek Pajak dengan NOP : ".$kd_kecamatan.".".$kd_kelurahan.".".$kd_obj_pajak.".".$kd_keg_usaha.".".$no_reg." berhasil di update";
pg_free_result($result);
pg_close($dbconn);
?>
