<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
set_time_limit(0);
header('Content-Type: text/event-stream;');
//header('Content-Encoding: none; ');
// recommended to prevent caching of event data.
header('Cache-Control: no-cache'); 
//$_SESSION['base_dira'] = "D:/wamp/www/htdocs/simpad_dev/";
//include_once $_SESSION['base_dira']."inc/db.inc.php";
$thn_pajak = pg_escape_string($_GET['tahun']);
$bln_pajak = pg_escape_string($_GET['bulan']);
$pejabat = pg_escape_string($_GET['pejabat']);
$tgl_penetapan = pg_escape_string($_GET['tanggal_tetap']);
$bln_jth_tempo = (int)$bln_pajak;
if($bln_jth_tempo > 12) {
	$bln_jatuh_tempo = $thn_pajak+1;
	$tgl_jatuh_tempo = "15-01-".$bln_jatuh_tempo;
} else {
	$tgl_jatuh_tempo = "15-".$bln_jth_tempo."-".$thn_pajak;
}
$today = date("d-m-Y");
function send_message($id, $message, $progress) {
    $d = array('message' => $message , 'progress' => $progress);      
    echo "id: $id" . PHP_EOL;
    echo "data: " . json_encode($d) . PHP_EOL;
    echo PHP_EOL;	  
    //ob_flush();
	//ob_start();
	ob_flush();
    //flush();
	ob_clean();
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/func_no_reg.inc.php";
$sql = "SELECT * FROM reklame.dat_obj_pajak WHERE status_pemasangan='PERMANEN' AND status_pajak='1' AND to_char(tmt_akhir,'MM')='$bln_pajak' ORDER BY kd_kecamatan,kd_kelurahan,kd_obj_pajak,kd_keg_usaha,no_reg";
$tampil = pg_query($sql);
if($tampil) {
//Mempersiapkan
	for($x = 10; $x >= 1; $x--) {
		send_message('INFO', 'Mempersiapkan Proses Penetapan Surat Pemberitahuan '.pg_num_rows($tampil).' Objek Pajak Tahun '.$thn_pajak.' Bulan '.$bulan_s[$bln_pajak].' <br><strong>Jangan Menutup Browser Saat Proses Berjalan</strong><br>Proses Akan Dimulai Dalam Hitungan '.$x, pg_num_rows($tampil)); 
		sleep(1);
	} 
//LONG RUNNING TASK
	$i = 0;
	while ($row = pg_fetch_array($tampil))
	{
		$i++;		
		send_message($i, 'Memproses NOP : '.$row['kd_kecamatan'].'.'.$row['kd_kelurahan'].'.'.$row['kd_obj_pajak'].'.'.$row['kd_keg_usaha'].'.'.$row['no_reg'], $i);
		$kd_kecamatan=$row['kd_kecamatan'];
		$kd_kelurahan=$row['kd_kelurahan'];
		$kd_obj_pajak=$row['kd_obj_pajak'];
		$kd_keg_usaha=$row['kd_keg_usaha'];
		$no_reg=$row['no_reg'];
		$kd_prov=$row['kd_provinsi'];
		$kd_kota=$row['kd_kota'];
		$kd_jns=$row['kd_jns'];
		$no_reg_wp=$row['no_reg_wp'];
		$nip = $_SESSION['nip'];
		$nm_reklame = pg_escape_string($row['nm_reklame']);
		$alamat_reklame = $row['alamat_reklame'];
		$p = $row['p'];
		$l = $row['l'];
		$s = $row['s'];
		$jlh =$row['jlh'];
		$lama_pasang = $row['lama_pasang'];		
		
		$q_tarif = "SELECT B.nm_tarif,B.tarif FROM reklame.dat_obj_pajak A INNER JOIN public.tarif B ON B.kd_obj_pajak=A.kd_obj_pajak AND B.kd_tarif=A.kd_tarif WHERE A.kd_kecamatan='$kd_kecamatan' AND A.kd_kelurahan='$kd_kelurahan' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.kd_keg_usaha='$kd_keg_usaha' AND A.no_reg='$no_reg'";
				$rs_tarif = pg_query($q_tarif);
				if($rs_tarif) {
					
				} else {
					send_message('ERROR', pg_last_error());
					pg_free_result($rs_tarif);
					pg_free_result($result);
					pg_free_result($tampil);
					pg_free_result($exec_query_cek);
					pg_close($dbconn);
					exit;
				}
		$data_tarif = pg_fetch_array($rs_tarif);
		$nm_tarif = $data_tarif['nm_tarif'];
		$tarif = $data_tarif['tarif'];
		pg_free_result($rs_tarif);
		if(strpos($nm_tarif, 'LEMBAR') !== false) {
			$jlh_skp = $jlh*$lama_pasang*$tarif;
		} else {
			$jlh_skp = $p*$l*$s*$jlh*$lama_pasang*$tarif;
		}
		if(strpos($nm_tarif, 'BULAN') !== false) {
						//bulanan			
		} else {
			$tmt_aw = date('d',strtotime($row['tmt_akhir']))."-".date('m',strtotime($row['tmt_akhir']))."-".date('Y',strtotime($row['tmt_akhir']));
			$tmt_ak	= date('d',strtotime($row['tmt_akhir']))."-".date('m',strtotime($row['tmt_akhir']))."-".(date('Y',strtotime($row['tmt_akhir']))+1);

		}		
		
		$query_cek = "SELECT * FROM reklame.pemberitahuan WHERE kd_kecamatan='$kd_kecamatan' AND kd_kelurahan='$kd_kelurahan' AND kd_obj_pajak='$kd_obj_pajak' AND kd_keg_usaha='$kd_keg_usaha' AND no_reg='$no_reg' AND thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak'";
		$exec_query_cek = pg_query($query_cek);
		if($exec_query_cek) {
			$ada_data=pg_num_rows($exec_query_cek);
			if($ada_data > 0) {
			/*	$data_update = pg_fetch_array($exec_query_cek);
				$jns_surat_u = $data_update['jns_surat'];
				$thn_pajak_u = $data_update['thn_pajak'];
				$bln_pajak_u = $data_update['bln_pajak'];
				$no_urut_surat_u = $data_update['no_urut_surat'];												
				
				$query_update = "UPDATE reklame.skp SET kd_provinsi='$kd_prov',kd_kota='$kd_kota',kd_jns='$kd_jns',no_reg_wp='$no_reg_wp',nm_usaha='$nm_usaha',alamat_usaha='$alamat_usaha',pokok_pajak=$pokok_pajak,penyimpan='$nip',tgl_simpan=TO_DATE('$today','DD-MM-YYYY'),tgl_penetapan=TO_DATE('$tgl_penetapan','DD-MM-YYYY'),nip_pejabat='$pejabat' WHERE jns_surat='$jns_surat_u' AND thn_pajak='$thn_pajak_u' AND bln_pajak='$bln_pajak_u' AND kd_obj_pajak='$kd_obj_pajak' AND no_urut_surat='$no_urut_surat_u'";
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
				pg_free_result($exec_query_cek);  */
			} else {
				
				$idn="BKD-PYK";
                $qrcode=md5($idn.".".$kd_kecamatan.".".$kd_kelurahan.".".$kd_obj_pajak.".".$kd_keg_usaha.".".$no_reg);
				$query_input = "INSERT INTO reklame.pemberitahuan(kd_kecamatan,kd_kelurahan,kd_obj_pajak,kd_keg_usaha,no_reg,kd_provinsi,kd_kota,kd_jns,no_reg_wp,tmt_awal,tmt_akhir,tgl_simpan,penyimpan,nip_pejabat,bln_pajak,thn_pajak,qrcode,p,l,s,jlh,tarif,lama_pasang,pokok_pajak,nm_reklame,alamat_reklame) VALUES('$kd_kecamatan','$kd_kelurahan','$kd_obj_pajak','$kd_keg_usaha','$no_reg','$kd_prov','$kd_kota','$kd_jns','$no_reg_wp',TO_DATE('".$tmt_aw."','DD-MM-YYYY'),TO_DATE('".$tmt_ak."','DD-MM-YYYY'),TO_DATE('$today','DD-MM-YYYY'),'$nip','$pejabat','$bln_pajak','$thn_pajak','$qrcode',$p,$l,$s,$jlh,$tarif,$lama_pasang,$jlh_skp,'$nm_reklame','$alamat_reklame')";
				$excec_input = pg_query($query_input);
				if($excec_input) {
			//'$thn_pajak','$bln_pajak','$no_urut_skp','$kd_kecamatan','$kd_kelurahan','$kd_obj_pajak','$kd_keg_usaha','$no_reg','13','76','$kd_jns','$no_reg_wp','$nm_reklame','$alamat_reklame',$p,$l,$s,$jlh,'$nm_tarif',$tarif,TO_DATE('".$tmt_aw."','DD-MM-YYYY'),TO_DATE('".$tmt_ak."','DD-MM-YYYY'),'$nip',TO_DATE('$today','DD-MM-YYYY'),'0',$jlh_skp,$lama_pasang,'$pejabat',TO_DATE('$tgl_penetapan','DD-MM-YYYY'),'0','$qrcode'
				} else {
					send_message('ERROR', pg_last_error());
					pg_free_result($result);
					pg_free_result($tampil);
					pg_close($dbconn);
					exit;
				}		
				
								
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
			}	
			usleep(1*50000);
		} else {
			send_message('ERROR', pg_last_error());
			pg_free_result($exec_query_cek);
			pg_free_result($result);
			pg_free_result($tampil);
			pg_close($dbconn);
			exit;
		}
	}
//pg_close($dbconn);  
	pg_free_result($tampil);
	pg_close($dbconn);
	send_message('SELESAI', 'Process complete', $i);
} else {
	send_message('ERROR', pg_last_error());
	//send_message('SELESAI', pg_last_error());
	pg_free_result($tampil);
	pg_close($dbconn);
}

?>