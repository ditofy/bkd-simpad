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
$data = $_REQUEST['postdata'];
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}
$uname = $arr_data['user-name'];
$pass = strtoupper(md5($arr_data['password']));
$level = substr($arr_data['level'],0,1);
$nip = $arr_data['nip'];
$nama = $arr_data['nama'];
$jabatan = strtoupper($arr_data['jabatan']);
$status = '1';
$sql = "INSERT INTO public.user(username,password,level,nip,nama,status,jabatan) VALUES('$uname','$pass','$level','$nip','$nama','$status','$jabatan')";
$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
echo "OK";
pg_free_result($result);
pg_close($dbconn);
?>
