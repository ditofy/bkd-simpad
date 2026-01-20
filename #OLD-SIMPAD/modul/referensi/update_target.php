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
$data_tarif = $_REQUEST['postdata'];
$thn_pajak = pg_escape_string($_REQUEST['tahun_pajak']);
$arr_data = array();
$query_cek = "SELECT kd_obj_pajak FROM public.target WHERE thn_pajak='$thn_pajak'";
$result = pg_query($query_cek) or die('Query failed: ' . pg_last_error());
$data = pg_fetch_array($result);
if($data['kd_obj_pajak'] == null) {
	$proses_dp_insert = true;
} else {
	$proses_dp_insert = false;
}
pg_free_result($result);
foreach ($data_tarif as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
	$kd_obj_pajak = $value["name"];
	if($arr_data[$value["name"]] == ""){
		$target = 0;
	} else {
		$target = $arr_data[$value["name"]];
	}
	if($proses_dp_insert == true){
		$query = "INSERT INTO public.target(thn_pajak,kd_obj_pajak,target) VALUES('$thn_pajak','$kd_obj_pajak',$target)";
	} else {
		$query = "UPDATE public.target SET target=$target WHERE thn_pajak='$thn_pajak' AND kd_obj_pajak='$kd_obj_pajak'";
	}
	$result = pg_query($query) or die('Query failed: ' . pg_last_error());
	pg_free_result($result);
}
pg_close($dbconn);
echo "Target Berhasi Di Simpan";
?>