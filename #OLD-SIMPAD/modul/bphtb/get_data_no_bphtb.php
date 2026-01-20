<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/schema.php";
$error = false;
$no_pelayanan = $_REQUEST['nomor'];

list($thn_pajak,$no_urut_surat) = explode(".",$no_pelayanan);


$schema = "bphtb";

$query= "SELECT A.*,B.*,C.nama_ppat FROM $schema.pelayanan A INNER JOIN public.wp B ON A.nik=B.nik LEFT JOIN public.ppat C on A.id_ppat=C.id WHERE A.tahun='$thn_pajak' and A.no_urut_p='$no_urut_surat'";


$result = pg_query($query);
if($result) {
	$error = false;
} else {
	$error = true;
	$status = array(
			'status' => 'error',
			'error_msg' => pg_last_error(),
		);
	goto error_handler;
}
$data = pg_fetch_array($result);

//CEK NPOPTKP
//$thn_pelayanan=date('Y');
$nik=$data['nik'];
$id_transaksi=$data['id_transaksi'];
$cek_npoptkp ="SELECT * FROM bphtb.sspd where nik='$nik' and thn_pajak >='2024' and status_pembayaran < '2'";
$cek_npoptkp_result=pg_query($cek_npoptkp); //or die ('Query failed: ' . pg_last_error());
$sum_npoptkp=pg_num_rows($cek_npoptkp_result);
$data_npoptkp= pg_fetch_array($cek_npoptkp_result);


if ($sum_npoptkp > 0){
 $npoptkp= 0;
}

else if($id_transaksi == '04' or $id_transaksi == '05'){
$npoptkp=300000000;
}
else {
$npoptkp=80000000;
}

//pg_free_result($cek_npoptkp_result);
if(empty($data['tahun'])){
	$error = true;
	$status = array(
			'status' => 'error',
			'error_msg' => 'No Pelayanan Tidak Ditemukan',
		);
	goto error_handler;
}

$status = array(
	"status" => "ok",
	"nik" => $nik,
	"nama_wp" => $data['nama'],
	"alamat_wp" => $data['alamat'],
	"no_telp" => $data['telp'],
	"kelurahan" => $data['kelurahan'],
	"kecamatan" => $data['kecamatan'],
	"kota" => $data['kota'],
	"luas_bumi_trk" => $data['luas_bumi_trk'],
	"luas_bng_trk" => $data['luas_bng_trk'],
	"nomor_sertifikat" => $data['sertifikat'],
	"harga_trk" => number_format($data['harga_trk']),
	"akumulasi" => number_format($data['akumulasi']),
	"pengurangan" => number_format($data['pengurangan']),
	"tgl_verifikasi" => $data['tgl_verifikasi'],
	"nop" => $data['kd_propinsi'].".".$data['kd_dati2'].".".$data['kd_kecamatan'].".".$data['kd_kelurahan'].".".$data['kd_blok'].".".$data['no_urut'].".".$data['kd_jns_op'],
	"npwpd" => $data['kd_provinsi'].".".$data['kd_kota'].".".$data['kd_jns'].".".$data['no_reg'],
	"nama_sppt" => $data['nama_sppt'],
	"alamat_op" => $data['alamat_op'],
	"nomor_op" => $data['nomor_op'],
	"rt_op" => $data['rt_op'],
	"rw_op" => $data['rw_op'],
	"kelurahan_op" => $data['kelurahan_op'],
	"kecamatan_op" => $data['kecamatan_op'],
	"njop_bumi" => $data['njop_bumi'],
	"njop_bng" => $data['njop_bng'],
	"luas_bumi" => $data['luas_bumi'],
	"luas_bng" => $data['luas_bng'],
	"id_pln" => $data['id_pln'],
	"nama_ppat" => $data['nama_ppat'],
	"npoptkp" => number_format($npoptkp),
	"sum_npoptkp" => $sum_npoptkp,
);

pg_free_result($result);
echo json_encode($status);
pg_close($dbconn);

error_handler :
	if ($error == true) {		
		echo json_encode($status);
		pg_free_result($result);
		pg_close($dbconn);
	}
?>