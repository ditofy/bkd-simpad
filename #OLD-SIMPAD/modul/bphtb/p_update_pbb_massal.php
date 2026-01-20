<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}

//include_once $_SESSION['base_dir']."inc/func_no_reg.inc.php";
//include_once $_SESSION['base_dir']."inc/func_no_reg_wp.inc.php";
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
$nama_wp=strtoupper($arr_data['nm-wp']);


//$nop = strtoupper($arr_data['nop']);
//list($kd_prov,$kd_dati2,$kd_kec,$kd_kel,$kd_blok,$no_urut,$kd_jns_op) = explode(".",$nop);

/////// INSERT DATA PLN /////////////////////////
$cek_wp = oci_parse($conn,"SELECT  A.KD_PROPINSI||'.'||A.KD_DATI2||'.'||A.KD_KECAMATAN||'.'||A.KD_KELURAHAN||'.'||A.KD_BLOK||'.'||A.NO_URUT||'.'||A.KD_JNS_OP AS NOP FROM PBB.DAT_OBJEK_PAJAK A INNER JOIN PBB.DAT_SUBJEK_PAJAK B ON
A.SUBJEK_PAJAK_ID=B.SUBJEK_PAJAK_ID WHERE B.NM_WP ='$nama_wp'");
oci_execute($cek_wp);
//$hasil_cek_wp = oci_fetch_array($cek_wp, OCI_ASSOC);
$row = oci_fetch_array($cek_wp, OCI_ASSOC+OCI_RETURN_NULLS);
if($row == NULL) {
echo "Objek PBB dengan Nama WP  : ".$nama_wp." Tidak Ada !!";
}
else {
$r = oci_execute($cek_wp);
		while ($row_baru = oci_fetch_array($cek_wp, OCI_ASSOC+OCI_RETURN_NULLS))
			{
			$nop = $row_baru['NOP'];
			list($kd_prov,$kd_dati2,$kd_kec,$kd_kel,$kd_blok,$no_urut,$kd_jns_op) = explode(".",$nop);
			
			$queryUpdate = "UPDATE PBB.DAT_OBJEK_PAJAK SET SUBJEK_PAJAK_ID='$nik' WHERE KD_PROPINSI='$kd_prov' AND KD_DATI2='$kd_dati2' AND KD_KECAMATAN='$kd_kec' AND KD_KELURAHAN='$kd_kel' AND KD_BLOK='$kd_blok' AND NO_URUT='$no_urut' AND KD_JNS_OP='$kd_jns_op'";
$stidUpdate = oci_parse($conn, $queryUpdate);	
$update_wp=oci_execute($stidUpdate);
		
			
			}

}




echo "Objek PBB dengan Nama WP  : ".$nama_wp." Sebanyak ".$row." Objek Berhasil Di Update !!";

//}

oci_free_statement($stidUpdate);
oci_free_statement($stidInsert);
oci_close($conn);

?>