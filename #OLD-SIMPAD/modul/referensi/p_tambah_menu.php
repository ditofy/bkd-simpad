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
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}
$nm_menu = $arr_data['nama-menu'];
$link = $arr_data['link'];
$class = $arr_data['class'];
if(strtoupper($arr_data['tipe']) == "MENU") {
	$query = "SELECT MAX(menu_id) AS no_max FROM public.menu WHERE sub_id=0";
	$result = pg_query($query) or die('Query failed: ' . pg_last_error());
	$row = pg_fetch_array($result);
	$menu_id = $row['no_max']+1;
	$query = "INSERT INTO public.menu(menu_id,sub_id,nm_menu,link,aktif,class) VALUES($menu_id,0,'$nm_menu','$link','t','$class')";
	$result = pg_query($query) or die('Query failed: ' . pg_last_error());
	if($result){
		echo "Menu Berhasil Di Tambah";
	}
} else {
	$menu_induk = $arr_data['menu-induk'];
	list($menu_id,$nm_menu_induk) = explode(".",$menu_induk);
	$query = "SELECT MAX(sub_id) AS no_max FROM public.menu WHERE menu_id=$menu_id";
	$result = pg_query($query) or die('Query failed: ' . pg_last_error());
	$row = pg_fetch_array($result);
	$sub_id = $row['no_max']+1;
	$query = "INSERT INTO public.menu(menu_id,sub_id,nm_menu,link,aktif,class) VALUES($menu_id,$sub_id,'$nm_menu','$link','t','$class')";
	$result = pg_query($query) or die('Query failed: ' . pg_last_error());
	if($result){
		echo "Menu Berhasil Di Tambah";
	}
}
pg_free_result($result);
pg_close($dbconn);
?>