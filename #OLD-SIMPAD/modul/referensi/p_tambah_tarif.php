<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
if ($_SESSION['level'] != '0')
{
	die('Hak Akses Diberhentikan');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
$data = $_REQUEST['postdata'];
$kd_obj = pg_escape_string($_REQUEST['kd_obj']);
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}
$nm_tarif = $arr_data['nama-tarif'];
$tarif = $arr_data['tarif'];
$query = "SELECT MAX(kd_tarif) AS no_max FROM public.tarif WHERE kd_obj_pajak='$kd_obj'";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$row = pg_fetch_array($result);
$kd_tarif = $row['no_max']+1;
$query = "INSERT INTO public.tarif(kd_obj_pajak,kd_tarif,nm_tarif,tarif) VALUES('$kd_obj',$kd_tarif,'$nm_tarif',$tarif)";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
if($result){
	echo "Tarif Berhasil Di Tambah";
}
pg_free_result($result);
pg_close($dbconn);
?>