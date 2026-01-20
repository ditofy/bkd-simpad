<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
if ($_SESSION['level'] != '0')
{
	die('Hak Akses Diberhentikan');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
$user_key = $_GET['key'];
	switch ($_GET['aksi']) {
    case "nonaktif":
			$sql = "UPDATE public.user SET status = '0' WHERE username = '$user_key'";    
			$hasil = "User Berhasil Di Non Aktifkan";    	
        break;
    case "aktif":
        	$sql = "UPDATE public.user SET status = '1' WHERE username = '$user_key'";        	
			$hasil = "User Berhasil Di Aktifkan";
        break;
    case "hapus":
        	$sql = "DELETE FROM public.user WHERE username = '$user_key'";
			$hasil = "User Berhasil Di Hapus";
        break;
	}
$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
echo $hasil;
pg_free_result($result);
pg_close($dbconn);
?>