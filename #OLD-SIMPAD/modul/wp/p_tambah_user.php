<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/acak_pass.php";
$data = $_REQUEST['postdata'];
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}
$nik = $arr_data['nik'];
$sql = "SELECT * FROM public.user_sptpd WHERE nik = '$nik'";
	$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
	$nrow = pg_num_rows($tampil);
	if ($nrow > 0) {
		$no_data = array('value' => 'Data Sudah Ada');
		echo json_encode($no_data);
	} else {
$npwpd = $arr_data['npwpd'];
list($kd_provinsi,$kd_kota,$kd_jns,$no_reg) = explode(".", $npwpd);
$nip = $_SESSION['nip'];
$nm_wp = strtoupper($arr_data['nama-wp']);
$alamat_wp = strtoupper($arr_data['alamat']);
$telp_wp = $arr_data['no-telp'];
$kelurahan = strtoupper($arr_data['kelurahan']);
$kecamatan = strtoupper($arr_data['kecamatan']);
$kota = strtoupper($arr_data['kota']);
$npwp = strtoupper($arr_data['npwp']);
$email = $arr_data['email'];
//$pas = $ps;
$pass = strtoupper(md5($us));
$query_wp = "INSERT INTO public.user_sptpd(password,kd_provinsi,kd_kota,kd_jns,no_reg,nik,nama,alamat,kelurahan,kecamatan,kota,npwp,telp,tgl_daftar,email,status) VALUES('$pass','13','76','$kd_jns','$no_reg','$nik','$nm_wp','$alamat_wp','$kelurahan','$kecamatan','$kota','$npwp','$telp_wp',TO_DATE('".$arr_data['tgl-daftar']."','DD-MM-YYYY'),'$email','Y')";
$result = pg_query($query_wp) or die('Query failed: ' . pg_last_error());
//echo "WP Berhasil Ditambah dengan Password : $ps";
$status = array(
	"status" => "ok",
	"npwpd" => "$npwpd",
	"pass" => "$us",
);

echo json_encode($status);

pg_free_result($result);
pg_close($dbconn);
}
?>
