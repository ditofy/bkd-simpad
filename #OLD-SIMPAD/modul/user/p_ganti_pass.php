<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
$data = $_REQUEST['postdata'];
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}
$pass_lama = strtoupper(md5($arr_data['pwd-lama']));
$pass_baru = strtoupper(md5($arr_data['pwd-baru']));
$user = $_SESSION['user'];
$sql = "SELECT * FROM public.user WHERE username = '$user' AND password = '$pass_lama'";
$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
if(pg_num_rows($result) == '0')
	{ 
		echo "Password lama salah!!!";
	} else {
		$sql = "UPDATE public.user SET password = '$pass_baru' WHERE username = '$user'";
		$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
		echo "OK";
	}
pg_free_result($result);
pg_close($dbconn);
?>