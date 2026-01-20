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
$data = $_REQUEST['postdata'];
$user = pg_escape_string($_REQUEST['user']);
$arr_data = array();
if($data == "") {
	$arr_data['menuid'] = "";
} else {
foreach ($data as $value) {
    $$value["name"] = $value["value"].";";
	list($mid,$smid) = explode(".",$value["value"]);
	if($smid !== '0') {
		if (strpos($arr_data[$value["name"]], $mid.".0") === false) {
			$arr_data[$value["name"]] .= $mid.".0;";
		}
	}
    $arr_data[$value["name"]] .= pg_escape_string($$value["name"]);
}
}

include_once $_SESSION['base_dir']."inc/db.inc.php";
$query = "UPDATE public.user SET menu='".$arr_data['menuid']."' WHERE username='$user'";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
if($result) {
	echo "Menu User Berhasil Di Update";
}
pg_close($dbconn);
?>