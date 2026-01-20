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

$schema = "retribusi";

$nip = $_SESSION['nip'];
$kd_opd = $arr_data['opd-inner'];
$kd_ret = $arr_data['kd-ret'];
$kd_sub_ret = $arr_data['kd-sub-ret'];
$kd_sub_ret_rinci= $arr_data['kd-sub-ret-rinci'];
$kd_sub_ret_anak= $arr_data['kd-sub-ret-anak'];
$kd_sub_ret_detail= $arr_data['kd-sub-ret-detail'];
$jumlah = $arr_data['jumlah'];
$dasar_retribusi= str_replace(".", "", $jumlah);
$tgl_setor = $arr_data['tgl-setor'];
$pisah=explode("-",$tgl_setor);
$tanggal=$pisah[0];
$bulan=$pisah[1];
$tahun=$pisah[2];

//SELEKSI KODE PAD
if($kd_ret == '01' ){
$kd_pad='01';
}
//SELEKSI KODE PAD
if($kd_ret == '02' ){
$kd_pad='01';
}
//SELEKSI KODE PAD
if($kd_ret == '03' ){
$kd_pad='01';
}
//SELEKSI KODE PAD
if($kd_ret == '04' ){
$kd_pad='01';
}
//SELEKSI KODE PAD
if($kd_ret == '05' ){
$kd_pad='02';
}
//SELEKSI KODE PAD
if($kd_ret == '06' ){
$kd_pad='02';
}
//SELEKSI KODE PAD
if($kd_ret == '07' ){
$kd_pad='02';
}




$query_cek = "SELECT * FROM retribusi.retribusi WHERE kd_opd='$kd_opd' AND kd_ret='$kd_ret' AND kd_sub_ret='$kd_sub_ret' AND kd_rinci_ret='$kd_sub_ret_rinci' AND kd_detail_ret ='$kd_sub_ret_anak' AND kd_anak_detail='$kd_sub_ret_detail' AND kd_pad='$kd_pad' AND tgl_setor='$tgl_setor'";
		$exec_query_cek = pg_query($query_cek);
		if($exec_query_cek) {
			$ada_data=pg_num_rows($exec_query_cek);
			if($ada_data > 0) {
			$data_update = pg_fetch_array($exec_query_cek);
				$kd_opd_u = $data_update['kd_opd'];
				$kd_ret_u = $data_update['kd_ret'];
				$kd_sub_ret_u = $data_update['kd_sub_ret'];
				$kd_sub_ret_rinci_u = $data_update['kd_rinci_ret'];
				$kd_sub_ret_detail_u = $data_update['kd_detail_ret'];
				$kd_sub_ret_anak_u = $data_update['kd_anak_detail'];
				$tgl_setor_u = $data_update['tgl_setor']; 
				$query_update = "UPDATE $schema.retribusi SET kd_opd='$kd_opd',kd_ret='$kd_ret',kd_sub_ret='$kd_sub_ret',kd_rinci_ret='$kd_sub_ret_rinci',jumlah=$dasar_retribusi,penyimpan='$nip',tgl_setor=TO_DATE('$tgl_setor','DD-MM-YYYY'),kd_detail_ret='$kd_sub_ret_anak',kd_anak_detail='$kd_sub_ret_detail',kd_pad='$kd_pad'  WHERE kd_opd='$kd_opd_u' AND kd_ret='$kd_ret_u' AND kd_sub_ret='$kd_sub_ret_u' AND kd_rinci_ret='$kd_sub_ret_rinci_u' AND kd_detail_ret='$kd_sub_ret_detail_u' AND kd_anak_detail='$kd_sub_ret_anak_u' AND tgl_setor='$tgl_setor_u' ";
				$exec_update = pg_query($query_update);
						
				pg_free_result($exec_query_cek); 
				$status = array(
					"status" => "sudah",
					//"no_sptpd" => "02.".$thn_pajak_u.".".$bln_pajak_u.".".$kd_obj_pajak.".".$no_urut_surat_u,
					
				);	?>
				
		<?php	} else  {
			
					
				/*$query = "SELECT TO_NUMBER(MAX(no_urut_surat),'999999') AS no_max FROM $schema.sptpd WHERE thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak'
	AND kd_obj_pajak='$kd_obj_pajak'";
				$result = pg_query($query);
				if($result) {
					$error = false;
				} else {
					$error = true;
					goto error_handler;
				}
				$data = pg_fetch_array($result);
				$no_urut_sptpd = no_reg($data['no_max']+1);
				$qrcode=md5("02.".$thn_pajak.".".$bln_pajak.".".$kd_obj_pajak.".".$no_urut_sptpd);*/

				
/*$query = "SELECT TO_NUMBER(MAX(no_urut),'999999') AS no_max FROM retribusi.retribusi WHERE kd_opd='$kd_opd' AND kd_ret='$kd_ret' AND kd_sub_ret='$kd_sub_ret' AND kd_rinci_ret='$kd_sub_ret_rinci' AND kd_detail_ret='$kd_sub_ret_anak' AND kd_anak_detail ='$kd_sub_ret_detail' AND bln='$bulan' AND thn='$tahun'";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$data = pg_fetch_array($result);
$no_urut = no_reg($data['no_max']+1);
//$no_urut="0002";
pg_free_result($result);*/

$query_input = "INSERT INTO $schema.retribusi(kd_opd,kd_ret,kd_sub_ret,kd_rinci_ret,kd_detail_ret,kd_anak_detail,jumlah,tgl_setor,penyimpan,bln,thn,kd_pad) VALUES('$kd_opd','$kd_ret','$kd_sub_ret','$kd_sub_ret_rinci','$kd_sub_ret_anak','$kd_sub_ret_detail','$dasar_retribusi',TO_DATE('$tgl_setor','DD-MM-YYYY'),'$nip','$bulan','$tahun','$kd_pad')";

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
					//"no_sptpd" => "02.".$thn_pajak.".".$bln_pajak.".".$kd_obj_pajak.".".$no_urut_sptpd,
				);
			}
		} else {
			$error = true;
			goto error_handler;
		}
			

echo json_encode($status);
pg_free_result($result);
pg_close($dbconn);

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
