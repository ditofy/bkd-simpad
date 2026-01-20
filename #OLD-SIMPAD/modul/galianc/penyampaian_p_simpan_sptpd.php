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
$nop = $arr_data['nop'];
list($kd_kecamatan,$kd_kelurahan,$kd_obj_pajak,$kd_keg_usaha,$no_reg) = explode(".",$nop);
$schema = "galianc";
$npwpd = strtoupper($arr_data['npwpd']);
list($kd_prov,$kd_kota,$kd_jns,$no_reg_wp) = explode(".",$npwpd);
$nip = $_SESSION['nip'];
$nm_usaha = strtoupper($arr_data['nama-usaha']);
$alamat_usaha = strtoupper($arr_data['alamat-usaha']);
$thn_pajak = $arr_data['tahun-pajak'];
$bln_pajak = $arr_data['bulan-pajak'];
$tgl_penyampaian = $arr_data['tanggal-penyampaian'];
$today = date("d-m-Y");
$nip_p=$arr_data['tanda-tangan'];

//perhitungan pajak
$kerikil=$arr_data['kerikil'];
$pasir_pasang=$arr_data['pasir-pasang'];
$pasir_urug=$arr_data['pasir-urug'];
$sirtu=$arr_data['sirtu'];

if($kerikil){ $kd_op="01"; }
if($pasir_pasang){ $kd_op="02"; }
if($pasir_urug){ $kd_op="03"; }
if($sirtu){ $kd_op="04"; }

$v_kerikil=$arr_data['v-kerikil'];
$t_kerikil=$v_kerikil*5000;
$v_pasir_pasang=$arr_data['v-pasir-pasang'];
$t_pasir_pasang=$v_pasir_pasang*5000;
$v_pasir_urug=$arr_data['v-pasir-urug'];
$t_pasir_urug=$v_pasir_urug*750;
$v_sirtu=$arr_data['v-sirtu'];
$t_sirtu=$v_sirtu*5000;

$pokok_pajak = $t_kerikil+$t_pasir_pasang+$t_pasir_urug+$t_sirtu;
$pokok_p=round($pokok_pajak);

/*$query_cek = "SELECT * FROM $schema.sptpd WHERE kd_kecamatan='$kd_kecamatan' AND kd_kelurahan='$kd_kelurahan' AND kd_obj_pajak='$kd_obj_pajak' AND kd_keg_usaha='$kd_keg_usaha' AND no_reg='$no_reg' AND thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak'";
		$exec_query_cek = pg_query($query_cek);
		if($exec_query_cek) {
			$ada_data=pg_num_rows($exec_query_cek);
			if($ada_data > 0) {
				$data_update = pg_fetch_array($exec_query_cek);
				$jns_surat_u = $data_update['jns_surat'];
				$thn_pajak_u = $data_update['thn_pajak'];
				$bln_pajak_u = $data_update['bln_pajak'];
				$no_urut_surat_u = $data_update['no_urut_surat'];
				$query_update = "UPDATE $schema.sptpd SET kd_provinsi='$kd_prov',kd_kota='$kd_kota',kd_jns='$kd_jns',no_reg_wp='$no_reg_wp',nm_usaha='$nm_usaha',alamat_usaha='$alamat_usaha',pokok_pajak=$pokok_pajak,kerikil=$kerikil,t_kerikil=t_kerikil,pasir_pasang=$pasir_pasang,t_pasir_pasang=$t_pasir_pasang,pasir_urug=$pasir_urug,t_pasir_urug=$t_pasir_urug,sirtu=$sirtu,t_sirtu=$t_sirtu,nip_p='$nip_p',penyimpan='$nip',tgl_penyampaian=TO_DATE('$tgl_penyampaian','DD-MM-YYYY'),tarif=$tarif WHERE jns_surat='$jns_surat_u' AND thn_pajak='$thn_pajak_u' AND bln_pajak='$bln_pajak_u' AND kd_obj_pajak='$kd_obj_pajak' AND no_urut_surat='$no_urut_surat_u'";
				$exec_update = pg_query($query_update);
				if($exec_update) {
			
				} else {
					send_message('ERROR', pg_last_error());
					pg_free_result($result);
					pg_free_result($tampil);
					pg_free_result($exec_query_cek);
					pg_close($dbconn);
					exit;
				}				
				pg_free_result($exec_query_cek);
				$status = array(
					"status" => "ok",
					"no_sptpd" => "02.".$thn_pajak_u.".".$bln_pajak_u.".".$kd_obj_pajak.".".$no_urut_surat_u,
				);
			} else { */
				$query = "SELECT TO_NUMBER(MAX(no_urut_surat),'999999') AS no_max FROM $schema.sptpd WHERE thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak'
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
				pg_free_result($result);
				$query_input = "INSERT INTO $schema.sptpd(thn_pajak,bln_pajak,no_urut_surat,kd_kecamatan,kd_kelurahan,kd_obj_pajak,kd_keg_usaha,no_reg,kd_provinsi,kd_kota,kd_jns,no_reg_wp,nm_usaha,alamat_usaha,pokok_pajak,penyimpan,tgl_penyampaian,status_pembayaran,nip_p) VALUES('$thn_pajak','$bln_pajak','$no_urut_sptpd','$kd_kecamatan','$kd_kelurahan','$kd_obj_pajak','$kd_keg_usaha','$no_reg','13','76','$kd_jns','$no_reg_wp','$nm_usaha','$alamat_usaha',$pokok_p,'$nip',TO_DATE('$tgl_penyampaian','DD-MM-YYYY'),'0','$nip_p')";
			
	
					
//$query_input = "INSERT INTO $schema.sptpd SET thn_pajak='$thn_pajak',bln_pajak='$bln_pajak',no_urut_surat='$no_urut_skp',kd_kecamatan='$kd_kecamatan',kd_kelurahan='$kd_kelurahan',kd_obj_pajak='$kd_obj_pajak',kd_keg_usaha='$kd_keg_usaha',no_reg='$no_reg',kd_provinsi='13',kd_kota='76',kd_jns='$kd_jns',no_reg_wp='$no_reg_wp',nm_usaha='$nm_usaha',alamat_usaha='$alamat_usaha',dasar_pengenaan_pajak=$dasar_pengenaan_pajak,tarif=$tarif,pokok_pajak=$pokok_pajak,penyimpan='$nip',tgl_simpan=TO_DATE('$today','DD-MM-YYYY'),status_pembayaran='0'";
				$result = pg_query($query_input);
				if($result) {
					$error = false;
					if($v_kerikil > 0){
					$query_kali = "INSERT INTO $schema.perkalian(thn_pajak,bln_pajak,no_urut_surat,kd_obj_pajak,jns_op,v_op,t_op,penyimpan,tgl_surat) VALUES ('$thn_pajak','$bln_pajak','$no_urut_sptpd','$kd_obj_pajak','Batu Kerikil',$v_kerikil,$t_kerikil,'$nip',TO_DATE('$tgl_penyampaian','DD-MM-YYYY'))";
					$result_k = pg_query($query_kali);
					//pg_free_result($result_k);
					}
					if($v_pasir_pasang != 0){
					$query_kali = "INSERT INTO $schema.perkalian(thn_pajak,bln_pajak,no_urut_surat,kd_obj_pajak,jns_op,v_op,t_op,penyimpan,tgl_surat) VALUES ('$thn_pajak','$bln_pajak','$no_urut_sptpd','$kd_obj_pajak','Pasir Pasang',$v_pasir_pasang,$t_pasir_pasang,'$nip',TO_DATE('$tgl_penyampaian','DD-MM-YYYY'))";
					$result_k = pg_query($query_kali);
					pg_free_result($result_k);
					}
					if($v_pasir_urug > 0){
					$query_kali = "INSERT INTO $schema.perkalian(thn_pajak,bln_pajak,no_urut_surat,kd_obj_pajak,jns_op,v_op,t_op,penyimpan,tgl_surat) VALUES ('$thn_pajak','$bln_pajak','$no_urut_sptpd','$kd_obj_pajak','Pasir Urug / Tanah Urug',$v_pasir_urug,$t_pasir_urug,'$nip',TO_DATE('$tgl_penyampaian','DD-MM-YYYY'))";
					$result_k = pg_query($query_kali);
					pg_free_result($result_k);
					}
					if($v_sirtu > 0){
					$query_kali = "INSERT INTO $schema.perkalian(thn_pajak,bln_pajak,no_urut_surat,kd_obj_pajak,jns_op,v_op,t_op,penyimpan,tgl_surat) VALUES ('$thn_pajak','$bln_pajak','$no_urut_sptpd','$kd_obj_pajak','Sirtu',$v_sirtu,$t_sirtu,'$nip',TO_DATE('$tgl_penyampaian','DD-MM-YYYY'))";
					$result_k = pg_query($query_kali);
					pg_free_result($result_k);
					}
					
				} else {
					$error = true;
					goto error_handler;
				}
				pg_free_result($result);
				$status = array(
					"status" => "ok",
					"no_sptpd" => "02.".$thn_pajak.".".$bln_pajak.".".$kd_obj_pajak.".".$no_urut_sptpd,
				);
		//	}
		//}
		// else {
			//$error = true;
			//goto error_handler;
		//}
			

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
