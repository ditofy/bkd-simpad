<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/func_no_reg.inc.php";
include_once $_SESSION['base_dir']."inc/schema.php";

$error = false;
$data = $_REQUEST['postdata'];
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}
$nomor = $arr_data['nomor'];
list($jns_surat,$thn_pajak,$bln_pajak,$kd_obj_pajak,$no_urut_surat) = explode(".",$nomor);
$nip = $_SESSION['nip'];
$dasar_pajak = $arr_data['dasar-pajak'];
$pajak_baru = $arr_data['pajak-baru'];
$pajak_setor = $arr_data['pajak-setor'];
$pokok_pajak = $pajak_baru-$pajak_setor;
$tgl_penetapan = $arr_data['tanggal-penetapan'];
$pejabat = $arr_data['tanda-tangan'];
$nama_wp = strtoupper($arr_data['nama-wp']);
$alamat_wp = strtoupper($arr_data['alamat-wp']);
$tgllama = "2019-02-01";
$tgl2 = date('d-m-Y', strtotime('+15days', strtotime($tg11))); //operasi penjumlahan tanggal sebanyak 6 hari
$originalDate = "2010-03-01";
$newDate = date("d-m-Y", strtotime('+15days', strtotime($tgl_penetapan)));
//$tgl2 = date('Y-m-d', strtotime('+15days', strtotime($originalDate))); //operasi penjumlahan tanggal sebanyak 6 hari
//echo $newDate;  


$today = date("d-m-Y");

$schema = $list_schema[$kd_obj_pajak];

$query_ada_data= pg_query("select * from $schema.skpdkb A where A.kd_obj_pajak='$kd_obj_pajak' and  A.thn_pajak='$thn_pajak' and A.bln_pajak='$bln_pajak' and A.no_urut_surat='$no_urut_surat'") or die('Query failed: ' . pg_last_error());
$cek_ada_data = pg_num_rows($query_ada_data);
if($cek_ada_data > 0) {
//	$data_update = pg_fetch_array($exec_query_cek);
	//$jns_surat_u = $data_update['jns_surat'];
//	$thn_pajak_u = $data_update['thn_pajak'];
//	$bln_pajak_u = $data_update['bln_pajak'];
//	$no_urut_surat_u = $data_update['no_urut_surat'];
	//$query_update = "UPDATE $schema.skp SET kd_provinsi='$kd_prov',kd_kota='$kd_kota',kd_jns='$kd_jns',no_reg_wp='$no_reg_wp',nm_usaha='$nm_usaha',alamat_usaha='$alamat_usaha',pokok_pajak=$pokok_pajak,penyimpan='$nip',tgl_simpan=TO_DATE('$today','DD-MM-YYYY'),tgl_penetapan=TO_DATE('$tgl_penetapan','DD-MM-YYYY'),tgl_jatuh_tempo=TO_DATE('$tgl_jatuh_tempo','DD-MM-YYYY'),nip_pejabat='$pejabat' WHERE jns_surat='$jns_surat_u' AND thn_pajak='$thn_pajak_u' AND bln_pajak='$bln_pajak_u' AND kd_obj_pajak='$kd_obj_pajak' AND no_urut_surat='$no_urut_surat_u'";
//	$exec_update = pg_query($query_update);
//	if($exec_update) {
//		$error = false;
//	} else {
//		$error = true;
//		goto error_handler;
//	}
//	$status = array(
//		"status" => "ok",
//		"no_skp" => "01.".$thn_pajak_u.".".$bln_pajak_u.".".$kd_obj_pajak.".".$no_urut_surat_u,
//	);
} else {
	
	$query_input = "INSERT INTO $schema.skpdkb(jns_surat,thn_pajak,bln_pajak,kd_obj_pajak,no_urut_surat,pokok_pajak,dasar_pajak,pokok_pajak_baru,pajak_disetor,tgl_jatuh_tempo,penyimpan,nip_p,tgl_surat,nama_wp,alamat_wp,status_pembayaran,kd_sh) VALUES('05','$thn_pajak','$bln_pajak','$kd_obj_pajak','$no_urut_surat',$pokok_pajak,$dasar_pajak,$pajak_baru,$pajak_setor,'$newDate','$nip','$pejabat',TO_DATE('$today','DD-MM-YYYY'),'$nama_wp','$alamat_wp','0','02')";
	$result = pg_query($query_input);
	if($result) {
		$error = false;
	} else {
		$error = true;
		goto error_handler;
	}
	pg_free_result($result);

	$status = array(
		"status" => "ok",
		"no_skpdkb" => "05.".$thn_pajak.".".$bln_pajak.".".$kd_obj_pajak.".".$no_urut_surat,
	);
}
echo json_encode($status);
pg_free_result($result);
pg_free_result($exec_query_cek);
pg_close($dbconn);

error_handler :
	if ($error == true) {
		$status = array(
			'status' => 'error',
			'error_msg' => pg_last_error(),
		);
		echo json_encode($status);
		pg_free_result($result);
		pg_free_result($exec_query_cek);
		pg_close($dbconn);
	}
?>
