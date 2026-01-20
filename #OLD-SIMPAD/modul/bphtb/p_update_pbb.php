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
$cek_wp = oci_parse($conn,"SELECT * FROM PBB.DAT_SUBJEK_PAJAK WHERE SUBJEK_PAJAK_ID='$nik'");
oci_execute($cek_wp);
$hasil_cek_wp = oci_fetch_array($cek_wp, OCI_ASSOC);
$jum_wp=oci_num_rows($cek_wp);
if($jum_wp > 0) {
$queryUpdate = "UPDATE PBB.DAT_OBJEK_PAJAK SET SUBJEK_PAJAK_ID='$nik' WHERE KD_PROPINSI='$kd_prov' AND KD_DATI2='$kd_dati2' AND KD_KECAMATAN='$kd_kec' AND KD_KELURAHAN='$kd_kel' AND KD_BLOK='$kd_blok' AND NO_URUT='$no_urut' AND KD_JNS_OP='$kd_jns_op'";
$stidUpdate = oci_parse($conn, $queryUpdate);	
$update_wp=oci_execute($stidUpdate);


}
else {
$queryInsert = "INSERT INTO PBB.DAT_SUBJEK_PAJAK
(
    SUBJEK_PAJAK_ID,
	NM_WP,
    JALAN_WP,
	BLOK_KAV_NO_WP,
	RW_WP,
	RT_WP,
	KELURAHAN_WP,
	KOTA_WP,
	KD_POS_WP,
	TELP_WP,
	NPWP,
	STATUS_PEKERJAAN_WP
) 
VALUES
(
    '$nik',
	'$nama_wp', 
    '$alamat_wp',
	'$blok_wp',
	'$rw_wp',
	'$rt_wp',
	'$kelurahan',
	'$kota',
	'-',
	'$telp',
	'-',
	'5' 
)";
$stidInsert = oci_parse($conn, $queryInsert);	
$insert=oci_execute($stidInsert);	
if (!$insert) {
    			$e = oci_error($stidInsert);
				$out = htmlentities($e['message'], ENT_QUOTES).PHP_EOL;
    			echo $out;
				oci_free_statement($stidInsert);
			} else {	
				$queryUpdate = "UPDATE PBB.DAT_OBJEK_PAJAK SET SUBJEK_PAJAK_ID='$nik' WHERE KD_PROPINSI='$kd_prov' AND KD_DATI2='$kd_dati2' AND KD_KECAMATAN='$kd_kec' AND KD_KELURAHAN='$kd_kel' AND KD_BLOK='$kd_blok' AND NO_URUT='$no_urut' AND KD_JNS_OP='$kd_jns_op'";
								$stidUpdate = oci_parse($conn, $queryUpdate);	
								$update_wp=oci_execute($stidUpdate);
			}
}

echo "Objek PBB dengan NOP : ".$kd_prov.".".$kd_dati2.".".$kd_kec.".".$kd_kel.".".$kd_blok.".".$no_urut.".".$kd_jns_op." Berhasil Di Update !!";

//}

oci_free_statement($stidUpdate);
oci_free_statement($stidInsert);
oci_close($conn);

?>
