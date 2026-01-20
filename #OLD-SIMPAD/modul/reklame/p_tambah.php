<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/func_no_reg.inc.php";
$schema = "reklame";
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
$nm_reklame = strtoupper($arr_data['nama-usaha']);
$alamat_reklame = strtoupper($arr_data['alamat-usaha']);
$npwpd = strtoupper($arr_data['npwpd']);
list($kd_prov,$kd_kota,$kd_jns,$no_reg_wp) = explode(".",$npwpd);
$jenis_penetapan = substr($arr_data['jenis-penetapan'],0,2);
$p = $arr_data['panjang'];
$l = $arr_data['lebar'];
$s = $arr_data['sisi'];
$jlh = $arr_data['jumlah'];
$lama_pasang = $arr_data['lama-pasang'];
$kd_tarif = $arr_data['tarif'];
$tmt_awal = $arr_data['tmt-awal'];
$tmt_akhir = $arr_data['tmt-akhir'];
$status_pemasangan = $arr_data['status-pemasangan'];
$query = "SELECT TO_NUMBER(MAX(no_reg),'999999') AS no_max FROM $schema.dat_obj_pajak WHERE kd_kecamatan='$kd_kecamatan' AND kd_kelurahan='$kd_kelurahan'
	AND kd_obj_pajak='$kd_obj_pajak' AND kd_keg_usaha='$kd_keg_usaha'";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$data = pg_fetch_array($result);
$no_reg = no_reg($data['no_max']+1);
pg_free_result($result);
$query_input = "INSERT INTO $schema.dat_obj_pajak(kd_provinsi,kd_kota,kd_jns,no_reg_wp,kd_kecamatan,kd_kelurahan,kd_obj_pajak,kd_keg_usaha,no_reg,nm_reklame,alamat_reklame,penyimpan,p,l,s,jlh,status_pajak,tgl_daftar,jenis_penetapan,tmt_awal,tmt_akhir,kd_tarif,lama_pasang,status_pemasangan) VALUES('13','76','$kd_jns','$no_reg_wp','$kd_kecamatan','$kd_kelurahan','$kd_obj_pajak','$kd_keg_usaha','$no_reg','$nm_reklame','$alamat_reklame','$nip',$p,$l,$s,$jlh,'1',TO_DATE('".$arr_data['tgl-daftar']."','DD-MM-YYYY'),'$jenis_penetapan',TO_DATE('".$arr_data['tmt-awal']."','DD-MM-YYYY'),TO_DATE('".$arr_data['tmt-akhir']."','DD-MM-YYYY'),$kd_tarif,$lama_pasang,'$status_pemasangan')";
$result = pg_query($query_input) or die('Query failed: ' . pg_last_error());
echo "Objek Pajak berhasil simpan dengan NOP : ".$kd_kecamatan.".".$kd_kelurahan.".".$kd_obj_pajak.".".$kd_keg_usaha.".".$no_reg;
//pg_free_result($result);
pg_close($dbconn);
?>
