<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/func_no_reg.inc.php";
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
pg_free_result($rs_tarif);

$p = $arr_data['panjang'];
$l = $arr_data['lebar'];
$s = $arr_data['sisi'];
$jlh = $arr_data['jumlah'];
$lama_pasang = $arr_data['lama-pasang'];
$thn_pajak = date("Y",strtotime($arr_data['tmt-awal']));
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
//denda
$timeStart=strtotime($arr_data['tmt-awal']);
//$timeStart = strtotime("Y-m-d");
$timeEnd = strtotime("$tgl_penetapan");
// Menambah bulan ini + semua bulan pada tahun sebelumnya
$numBulan = 0 + (date("Y",$timeEnd)-date("Y",$timeStart))*12;
// menghitung selisih bulan
$numBulan += date("m",$timeEnd)-date("m",$timeStart);
//echo $numBulan;
$aw_denda=$numBulan*2/100;
//end denda
$pokok_pajak=$arr_data['pokok-pajak'];
$denda=$aw_denda*$pokok_pajak;
$query = "SELECT TO_NUMBER(MAX(no_urut_surat),'999999') AS no_max FROM $schema.stpd WHERE thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak'
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
//if($numBulan >0)
//{
$sapi="select * from $schema.stpd where thn_pajak='$thn_pajak' and bln_pajak='$bln_pajak' and kd_kecamatan='$kd_kecamatan' and kd_kelurahan='$kd_kelurahan' and kd_obj_pajak='$kd_obj_pajak' and kd_keg_usaha='$kd_keg_usaha' and no_reg='$no_reg'";
$sasa=pg_query($sapi);
$sapis=pg_num_rows($sasa);
if($sapis  > 0){

$kambing="update $schema.stpd set nip_pejabat='$nip_pejabat',tgl_penetapan=TO_DATE('$tgl_penetapan','DD-MM-YYYY'),denda=$denda where thn_pajak='$thn_pajak' and bln_pajak='$bln_pajak' and kd_kecamatan='$kd_kecamatan' and kd_kelurahan='$kd_kelurahan' and kd_obj_pajak='$kd_obj_pajak' and kd_keg_usaha='$kd_keg_usaha' and no_reg='$no_reg' ";
$result = pg_query($kambing);
if($result) {
	$error = false;
} else {
	$error = true;
	goto error_handler;
}
pg_free_result($result);

$status = array(
	"status" => "ok",
	"no_skp" => "03.".$thn_pajak.".".$bln_pajak.".".$kd_obj_pajak.".".$no_urut_skp,
);

echo json_encode($status);

}
else{
$query_input = "INSERT INTO 
$schema.stpd(thn_pajak,bln_pajak,no_urut_surat,kd_kecamatan,kd_kelurahan,kd_obj_pajak,kd_keg_usaha,no_reg,kd_provinsi,kd_kota,kd_jns,no_reg_wp,nm_reklame,alamat_reklame,p,l,s,jlh,nm_tarif,tarif,tmt_awal,tmt_akhir,penyimpan,tgl_simpan,denda,status_pembayaran,pokok_pajak,lama_pasang,nip_pejabat,tgl_penetapan) VALUES('$thn_pajak','$bln_pajak','$no_urut_skp','$kd_kecamatan','$kd_kelurahan','$kd_obj_pajak','$kd_keg_usaha','$no_reg','13','76','$kd_jns','$no_reg_wp','$nm_reklame','$alamat_reklame',$p,$l,$s,$jlh,'$nm_tarif',$tarif,TO_DATE('".$arr_data['tmt-awal']."','DD-MM-YYYY'),TO_DATE('".$arr_data['tmt-akhir']."','DD-MM-YYYY'),'$nip',TO_DATE('$today','DD-MM-YYYY'),$denda,'0',$jlh_skp,$lama_pasang,'$nip_pejabat',TO_DATE('$tgl_penetapan','DD-MM-YYYY'))";
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
	"no_skp" => "03.".$thn_pajak.".".$bln_pajak.".".$kd_obj_pajak.".".$no_urut_skp,
);

echo json_encode($status);
pg_free_result($result);
pg_close($dbconn);
}
//else{
//$error = true;
//	$status = array(
//			'status' => 'error',
//			'error_msg' => 'Bulan Jatuh Tempo Belum Lewat',
//		);
	//goto error_handler;
//}
error_handler :
	if ($error == true) {
		$status = array(
			'status' => 'error',
			'error_msg' => pg_last_error(),
		);
		echo json_encode($status);
		pg_free_result($result);
		pg_close($dbconn);
	}
?>
