<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/func_no_reg.inc.php";
include_once $_SESSION['base_dir']."inc/db.mssql.inc.php";
$schema = "restoran";
$error = false;
$data = $_REQUEST['postdata'];
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}
$nop = $arr_data['nop'];
list($kd_kecamatan,$kd_kelurahan,$kd_obj_pajak,$kd_keg_usaha,$no_reg) = explode(".",$nop);
$npwpd = strtoupper($arr_data['npwpd']);
list($kd_prov,$kd_kota,$kd_jns,$no_reg_wp) = explode(".",$npwpd);
$nip = $_SESSION['nip'];
$nm_usaha = strtoupper($arr_data['nama-usaha']);
$alamat_usaha = strtoupper($arr_data['alamat-usaha']);
$nm_wp = strtoupper($arr_data['nama-wp']);
$alamat_wp = strtoupper($arr_data['alamat']);
$thn_pajak = $arr_data['tahun-pajak'];
$bln_pajak = $arr_data['bulan-pajak'];
$pokok_pajak = $arr_data['ketetapan'];
$tgl_penetapan = $arr_data['tanggal-penetapan'];
$pejabat = $arr_data['tanda-tangan'];
$bln_jth_tempo = (int)$bln_pajak;
if($bln_jth_tempo > 12) {
	$bln_jatuh_tempo = $thn_pajak+1;
	$tgl_jatuh_tempo = "15-01-".$bln_jatuh_tempo;
} else {
	$tgl_jatuh_tempo = "01-".$bln_jth_tempo."-".$thn_pajak;
}
$today = date("d-m-Y");
$query_cek = "SELECT * FROM $schema.skp WHERE kd_kecamatan='$kd_kecamatan' AND kd_kelurahan='$kd_kelurahan' AND kd_obj_pajak='$kd_obj_pajak' AND kd_keg_usaha='$kd_keg_usaha' AND no_reg='$no_reg' AND thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak'";
$exec_query_cek = pg_query($query_cek);
if($exec_query_cek) {
	$error = false;
} else {
	$error = true;
	goto error_handler;
}
$ada_data=pg_num_rows($exec_query_cek);
if($ada_data > 0) {
	$data_update = pg_fetch_array($exec_query_cek);
	$jns_surat_u = $data_update['jns_surat'];
	$thn_pajak_u = $data_update['thn_pajak'];
	$bln_pajak_u = $data_update['bln_pajak'];
	$no_urut_surat_u = $data_update['no_urut_surat'];
	$query_update = "UPDATE $schema.skp SET kd_provinsi='$kd_prov',kd_kota='$kd_kota',kd_jns='$kd_jns',no_reg_wp='$no_reg_wp',nm_usaha='$nm_usaha',alamat_usaha='$alamat_usaha',pokok_pajak=$pokok_pajak,penyimpan='$nip',tgl_simpan=TO_DATE('$today','DD-MM-YYYY'),tgl_penetapan=TO_DATE('$tgl_penetapan','DD-MM-YYYY'),tgl_jatuh_tempo=TO_DATE('$tgl_jatuh_tempo','DD-MM-YYYY'),nip_pejabat='$pejabat' WHERE jns_surat='$jns_surat_u' AND thn_pajak='$thn_pajak_u' AND bln_pajak='$bln_pajak_u' AND kd_obj_pajak='$kd_obj_pajak' AND no_urut_surat='$no_urut_surat_u'";
	$exec_update = pg_query($query_update);
	if($exec_update) {
		$error = false;
	} else {
		$error = true;
		goto error_handler;
	}
	$status = array(
		"status" => "ok",
		"no_skp" => "01.".$thn_pajak_u.".".$bln_pajak_u.".".$kd_obj_pajak.".".$no_urut_surat_u,
	);
} else {
	$query = "SELECT TO_NUMBER(MAX(no_urut_surat),'999999') AS no_max FROM $schema.skp WHERE thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak'
	AND kd_obj_pajak='$kd_obj_pajak'";
	$result = pg_query($query);
	if($result) {
		$error = false;
	} else {
		$error = true;
		goto error_handler;
	}
	$data = pg_fetch_array($result);
	$no_urut_skp = no_reg($data['no_max']+1);
	pg_free_result($result);
	$query_input = "INSERT INTO 	$schema.skp(thn_pajak,bln_pajak,no_urut_surat,kd_kecamatan,kd_kelurahan,kd_obj_pajak,kd_keg_usaha,no_reg,kd_provinsi,kd_kota,kd_jns,no_reg_wp,nm_usaha,alamat_usaha,pokok_pajak,penyimpan,tgl_simpan,status_pembayaran,tgl_penetapan,nip_pejabat,tgl_jatuh_tempo) VALUES('$thn_pajak','$bln_pajak','$no_urut_skp','$kd_kecamatan','$kd_kelurahan','$kd_obj_pajak','$kd_keg_usaha','$no_reg','13','76','$kd_jns','$no_reg_wp','$nm_usaha','$alamat_usaha',$pokok_pajak,'$nip',TO_DATE('$today','DD-MM-YYYY'),'0',TO_DATE('$tgl_penetapan','DD-MM-YYYY'),'$pejabat',TO_DATE('$tgl_jatuh_tempo','DD-MM-YYYY'))";
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
		"no_skp" => "01.".$thn_pajak.".".$bln_pajak.".".$kd_obj_pajak.".".$no_urut_skp,
	);
	
	
	////sync SIPKD
	
	if($link and $seldb and $stat_sync_sipkd) {
	
		$q_kd_rek = "SELECT rek_apbd,mtgkey FROM public.keg_usaha WHERE kd_obj_pajak='$kd_obj_pajak' AND kd_keg_usaha='$kd_keg_usaha'";
		$result = pg_query($q_kd_rek);
		$data_rek = pg_fetch_array($result);
		$mtgkey = $data_rek['mtgkey'];
		$kdper = $data_rek['rek_apbd'];
		$nosp = "01.".$thn_pajak.".".$bln_pajak.".".$kd_obj_pajak.".".$no_urut_skp;
		if($bln_pajak == '01') { 
			  	$uraian = "PAJAK RESTORAN ".$nm_usaha." DI ".$alamat_usaha." MASA PAJAK BULAN ".strtoupper($bulan[$bln_pajak-1])." ".($thn_pajak-1);
			  } else {
			  	$uraian = "PAJAK RESTORAN ".$nm_usaha." DI ".$alamat_usaha." MASA PAJAK BULAN ".strtoupper($bulan[$bln_pajak-1])." ".($thn_pajak);
			  }				
		$tglskp = date('n',strtotime($tgl_penetapan))."-".date('d',strtotime($tgl_penetapan))."-".date('Y',strtotime($tgl_penetapan));	
		$tgltempo = date('n',strtotime($tgl_jatuh_tempo))."-".date('d',strtotime($tgl_jatuh_tempo))."-".date('Y',strtotime($tgl_jatuh_tempo));	
		$npwpd_sub= substr($npwpd, -9);
		$q_sync_sipkd = mssql_query("INSERT INTO SKP_SIMPAD(NOSKP,NPWPD,TGLSKP,PENYETOR,ALAMAT,URAISKP,TGLTEMPO,TGLVALID,MTGKEY,KDPER,NILAI) VALUES('$nosp','$npwpd_sub',CAST('$tglskp' AS DATETIME),'$nm_wp','$alamat_wp','$uraian',CAST('$tgltempo' AS DATETIME),CAST('$tglskp' AS DATETIME),'$mtgkey','$kdper',$pokok_pajak)");
	}
	
}
echo json_encode($status);
pg_free_result($result);
pg_free_result($exec_query_cek);
pg_close($dbconn);
mssql_close($link);

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
