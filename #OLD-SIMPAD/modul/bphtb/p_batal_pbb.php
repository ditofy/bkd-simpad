
<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}

include_once $_SESSION['base_dir']."inc/func_no_reg.inc.php";
include_once $_SESSION['base_dir']."inc/func_no_reg_wp.inc.php";
include_once $_SESSION['base_dir']."inc/db.orcl.inc.php";
$schema = "bphtb";
$data = $_REQUEST['postdata'];
$arr_data = array();

foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}


$nip = $_SESSION['nip'];

//// INSERT WAJIB PAJAK /////////////

$nik=$arr_data['nik-wp'];
$nama_wp=strtoupper($arr_data['nama-wp']);
$alamat_wp=strtoupper($arr_data['alamat-wp']);
$rt_wp=strtoupper($arr_data['rt-wp']);
$rw_wp=strtoupper($arr_data['rw-wp']);
$blok_wp=strtoupper($arr_data['blok-wp']);
$telp=$arr_data['no-telp-wp'];
$kelurahan=strtoupper($arr_data['kelurahan-wp']);
$kecamatan=strtoupper($arr_data['kecamatan-wp']);
$kota=strtoupper($arr_data['kota-wp']);
$npwp=strtoupper($arr_data['npwp']);

$nop = strtoupper($arr_data['nop']);
list($kd_prov,$kd_dati2,$kd_kec,$kd_kel,$kd_blok,$no_urut,$kd_jns_op) = explode(".",$nop);

/////// INSERT DATA PLN /////////////////////////
$cek_bayar = oci_parse($conn,"SELECT * FROM PBB.SPPT WHERE KD_PROPINSI='$kd_prov' AND KD_DATI2='$kd_dati2' AND KD_KECAMATAN='$kd_kec' AND KD_KELURAHAN='$kd_kel' AND KD_BLOK='$kd_blok' AND NO_URUT='$no_urut' AND KD_JNS_OP='$kd_jns_op' AND THN_PAJAK_SPPT = '2025'");
oci_execute($cek_bayar);
$hasil_cek_bayar = oci_fetch_array($cek_bayar, OCI_ASSOC);
//$jum_bayar=oci_num_rows($cek_wp);
$status_bayar = $hasil_cek_bayar['STATUS_PEMBAYARAN_SPPT'];
if($status_bayar == '0') {
$queryUpdate = "UPDATE PBB.SPPT SET STATUS_PEMBAYARAN_SPPT ='2' WHERE KD_PROPINSI='$kd_prov' AND KD_DATI2='$kd_dati2' AND KD_KECAMATAN='$kd_kec' AND KD_KELURAHAN='$kd_kel' AND KD_BLOK='$kd_blok' AND NO_URUT='$no_urut' AND KD_JNS_OP='$kd_jns_op' AND THN_PAJAK_SPPT='2025'";
$stidUpdate = oci_parse($conn, $queryUpdate);	
$update_bayar=oci_execute($stidUpdate);
		if($update_bayar){
		$queryUpdateOP = "UPDATE PBB.DAT_OP_BUMI SET JNS_BUMI='4' WHERE KD_PROPINSI='$kd_prov' AND KD_DATI2='$kd_dati2' AND KD_KECAMATAN='$kd_kec' AND KD_KELURAHAN='$kd_kel' AND KD_BLOK='$kd_blok' AND NO_URUT='$no_urut' AND KD_JNS_OP='$kd_jns_op'";
		$stidUpdateOP = oci_parse($conn, $queryUpdateOP);	
		$update_bayar=oci_execute($stidUpdateOP);
		echo "Objek PBB dengan NOP : ".$kd_prov.".".$kd_dati2.".".$kd_kec.".".$kd_kel.".".$kd_blok.".".$no_urut.".".$kd_jns_op." Berhasil di Batalkan !!";
				}
		
				}
else {
		echo "Objek PBB dengan NOP : ".$kd_prov.".".$kd_dati2.".".$kd_kec.".".$kd_kel.".".$kd_blok.".".$no_urut.".".$kd_jns_op." Sudah Dibayar !!";

}

//}

oci_free_statement($cek_bayar);
oci_free_statement($stidUpdate);
oci_free_statement($stidUpdateOP);
oci_close($conn);

?>
