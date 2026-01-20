<?php

if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/schema.php";
$dokumen = pg_escape_string($_GET['file']);

list($jns_surat,$thn_pajak,$bln_pajak,$kd_obj_pajak,$no_urut_surat) = explode(".", $no_surat);

$schema = $list_schema[$kd_obj_pajak];
$query=pg_query("select dokumen from $schema.sptpd where dokumen = '$dokumen'") or die('Query failed: ' . pg_last_error());
$row=pg_fetch_array($query);
$ada=pg_num_rows($query);
pg_free_result($query);
		
$direktori = "$_SESSION[base_dir]/modul/$schema/file/"; // folder tempat penyimpanan file yang boleh didownload
$filename = $row['dokumen'];
//$filename ="13.76.02.000004-03-2019-sertifikat.pdf";
//if($ada > 0 ){
if(file_exists($direktori.$filename)){
	$file_extension = strtolower(substr(strrchr($filename,"."),1));

	switch($file_extension){
	  case "pdf": $ctype="application/pdf"; break;
	  case "exe": $ctype="application/octet-stream"; break;
	  case "zip": $ctype="application/zip"; break;
	  case "rar": $ctype="application/rar"; break;
	  case "doc": $ctype="application/msword"; break;
	  case "xls": $ctype="application/vnd.ms-excel"; break;
	  case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
	  case "gif": $ctype="image/gif"; break;
	  case "png": $ctype="image/png"; break;
	  case "jpeg":
	  case "jpg": $ctype="image/jpg"; break;
	  default: $ctype="application/proses";
	}

	if ($file_extension=='php'){
	  echo "<h1>Access forbidden!</h1>
			<p>Maaf, Sugeng</p>";
	  exit;
	}
	else{

	  header("Content-Type: octet/stream");
	  header("Pragma: private"); 
	  header("Expires: 0");
	  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	  header("Cache-Control: private",false); 
	  header("Content-Type: $ctype");
	  header("Content-Disposition: attachment; filename=\"".basename($filename)."\";" );
	  header("Content-Transfer-Encoding: binary");
	  header("Content-Length: ".filesize($direktori.$filename));
	  readfile("$direktori$filename");
	  exit();   
	}
}

else{
	  echo "<h1>Access forbidden!</h1>
			<p>Maaf, Sapi.</p>";
	  exit;
}

?>