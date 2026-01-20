<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";

include_once $_SESSION['base_dir']."inc/func_no_reg.inc.php";
//include_once $_SESSION['base_dir']."inc/va.inc.php";
$schema = "reklame";
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
$nm_reklame = pg_escape_string(strtoupper($arr_data['nama-usaha']));
$alamat_reklame = strtoupper($arr_data['alamat-usaha']);

$q_tarif = "SELECT B.nm_tarif,B.tarif FROM reklame.dat_obj_pajak A INNER JOIN public.tarif B ON B.kd_obj_pajak=A.kd_obj_pajak AND B.kd_tarif=A.kd_tarif WHERE A.kd_kecamatan='$kd_kecamatan' AND A.kd_kelurahan='$kd_kelurahan' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.kd_keg_usaha='$kd_keg_usaha' AND A.no_reg='$no_reg'";
$rs_tarif = pg_query($q_tarif);
if($rs_tarif) {
	$error = false;
} else {
	$error = true;
	goto error_handler;
}
$data_tarif = pg_fetch_array($rs_tarif);
$nm_tarif = $data_tarif['nm_tarif'];
$tarif = $data_tarif['tarif'];
//pg_free_result($rs_tarif);

$p = $arr_data['panjang'];
$l = $arr_data['lebar'];
$s = $arr_data['sisi'];
$jlh = $arr_data['jumlah'];
$lama_pasang = $arr_data['lama-pasang'];
$thn_pajak = $arr_data['tahun-pajak'];
$bln_pajak = date("m",strtotime($arr_data['tmt-awal']));
$lama_pasang = $arr_data['lama-pasang'];
$tgl_penetapan = $arr_data['tgl-penetapan'];
if(strpos($nm_tarif, 'LEMBAR') !== false) {
	$jlh_skp = $jlh*$lama_pasang*$tarif;
} else {
	$jlh_skp = $p*$l*$s*$jlh*$lama_pasang*$tarif;
}
$today = date("d-m-Y");
$nip_pejabat = $arr_data['tanda-tangan'];
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
$pokok = 100000.00;
//$nops = "01.".$thn_pajak.".".$bln_pajak.".".$kd_obj_pajak.".".$no_urut_skp;
//deleteVaPjd($nops);
//$va = getVaPjd($nops, $nm_reklame, $jlh_skp);
//$va = 2222222;
//$va = getVirtualAccountNo($nops, "$nm_reklame", '20000.00');
//'01.2017.03.01.0008', 'Budi Rudi', '20000.00
//pg_free_result($result);
$surat="01";
$qrcode=md5($surat.".".$thn_pajak.".".$bln_pajak.".".$kd_obj_pajak.".".$no_urut_skp);
$query_input = "INSERT INTO $schema.skp(thn_pajak,bln_pajak,no_urut_surat,kd_kecamatan,kd_kelurahan,kd_obj_pajak,kd_keg_usaha,no_reg,kd_provinsi,kd_kota,kd_jns,no_reg_wp,nm_reklame,alamat_reklame,p,l,s,jlh,nm_tarif,tarif,tmt_awal,tmt_akhir,penyimpan,tgl_simpan,status_pembayaran,pokok_pajak,lama_pasang,nip_pejabat,tgl_penetapan,status_tagihan,qrcode,va) VALUES('$thn_pajak','$bln_pajak','$no_urut_skp','$kd_kecamatan','$kd_kelurahan','$kd_obj_pajak','$kd_keg_usaha','$no_reg','13','76','$kd_jns','$no_reg_wp','$nm_reklame','$alamat_reklame',$p,$l,$s,$jlh,'$nm_tarif',$tarif,TO_DATE('".$arr_data['tmt-awal']."','DD-MM-YYYY'),TO_DATE('".$arr_data['tmt-akhir']."','DD-MM-YYYY'),'$nip',TO_DATE('$today','DD-MM-YYYY'),'0',$jlh_skp,$lama_pasang,'$nip_pejabat',TO_DATE('$tgl_penetapan','DD-MM-YYYY'),'0','$qrcode','$va')";
$result = pg_query($query_input);
if($result) {
	$error = false;
} else {
	$error = true;
	goto error_handler;
}
//pg_free_result($result);

$status = array(
	"status" => "ok",
	"no_skp" => "01.".$thn_pajak.".".$bln_pajak.".".$kd_obj_pajak.".".$no_urut_skp,
	
);

echo json_encode($status);
//pg_free_result($result);
//pg_close($dbconn);

/*$query_update_tmt = "UPDATE reklame.dat_obj_pajak SET tmt_awal=TO_DATE('".$tmt_aw."','DD-MM-YYYY'), tmt_akhir=TO_DATE('".$tmt_ak."','DD-MM-YYYY') WHERE kd_kecamatan='$kd_kecamatan' AND kd_kelurahan='$kd_kelurahan' AND kd_obj_pajak='$kd_obj_pajak' AND kd_keg_usaha='$kd_keg_usaha' AND no_reg='$no_reg'";
				$excec_upd_tmt = pg_query($query_update_tmt);
				if($excec_upd_tmt) {
			
				} else {
					send_message('ERROR', pg_last_error());
					pg_free_result($result);
					pg_free_result($tampil);
					pg_close($dbconn);
					exit;
				} */
error_handler :
	if ($error == true) {
		$status = array(
			'status' => 'error',
			'error_msg' => pg_last_error(),
		);
		echo json_encode($status);
		//pg_free_result($result);
		pg_close($dbconn);
	} 
?>
