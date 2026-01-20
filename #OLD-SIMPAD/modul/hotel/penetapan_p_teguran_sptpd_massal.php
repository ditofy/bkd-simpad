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
$teguran_ke = pg_escape_string($_GET['teguran_ke']);
//$bln_jth_tempo = (int)$bln_pajak;
//if($bln_jth_tempo > 12) {
//	$bln_jatuh_tempo = $thn_pajak+1;
//	$tgl_jatuh_tempo = "15-01-".$bln_jatuh_tempo;
//} else {
//	$tgl_jatuh_tempo = "15-".$bln_jth_tempo."-".$thn_pajak;
//}
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
$sql = "select * from hotel.dat_obj_pajak A where ( A.kd_kecamatan,A.kd_kelurahan,A.kd_obj_pajak,A.kd_keg_usaha,A.no_reg) NOT IN (SELECT  B.kd_kecamatan,B.kd_kelurahan,B.kd_obj_pajak,B.kd_keg_usaha,B.no_reg FROM hotel.sptpd B where B.thn_pajak='$thn_pajak' and B.bln_pajak='$bln_pajak') ";
$tampil = pg_query($sql);
if($tampil) {
//Mempersiapkan
	for($x = 10; $x >= 1; $x--) {
		send_message('INFO', 'Mempersiapkan Proses Penetapan Masal Surat Teguran Penyampaian SPTPD '.pg_num_rows($tampil).' Objek Pajak Tahun '.$thn_pajak.' Bulan '.$bulan_s[$bln_pajak].' <br><strong>Jangan Menutup Browser Saat Proses Berjalan</strong><br>Proses Akan Dimulai Dalam Hitungan '.$x, pg_num_rows($tampil)); 
		sleep(1);
	} 
//LONG RUNNING TASK
	$i = 0;
	while ($row = pg_fetch_array($tampil))
	{
		$i++;		
		send_message($i, 'Memproses NOP : '.$row['kd_kecamatan'].'.'.$row['kd_kelurahan'].'.'.$row['kd_obj_pajak'].'.'.$row['kd_keg_usaha'].'.'.$row['no_reg'], $i);
		$jns_surat=$row['jns_surat'];
	    $tahun_pajak=$row['thn_pajak'];
		$bulan_pajak=$row['bln_pajak'];
		$kd_obj_pajak=$row['kd_obj_pajak'];
		$no_urut_surat=$row['no_urut_surat'];
		$nip = $_SESSION['nip'];
		$ins="BKD-PYK";
		$qrcode=md5($ins.".".$row['kd_kecamatan'].".".$row['kd_kelurahan'].".".$row['kd_obj_pajak'].".".$row['kd_keg_usaha'].".".$row['no_reg']);
	
		
		/*$q_tarif = "SELECT B.nm_tarif,B.tarif FROM reklame.dat_obj_pajak A INNER JOIN public.tarif B ON B.kd_obj_pajak=A.kd_obj_pajak AND B.kd_tarif=A.kd_tarif WHERE A.kd_kecamatan='$kd_kecamatan' AND A.kd_kelurahan='$kd_kelurahan' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.kd_keg_usaha='$kd_keg_usaha' AND A.no_reg='$no_reg'";
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

		}		*/
		//denda
/*$timeStart=strtotime($row['tmt_awal']);
//$timeStart = strtotime("Y-m-d");
$today = date("d-m-Y");
$timeEnd = strtotime("$tgl_penetapan");
// Menambah bulan ini + semua bulan pada tahun sebelumnya
$numBulan = 0 + (date("Y",$timeEnd)-date("Y",$timeStart))*12;
// menghitung selisih bulan
$numBulan += date("m",$timeEnd)-date("m",$timeStart);
//echo $numBulan;
$aw_denda=$numBulan*2/100;
$pokok_pajak=$row['pokok_pajak'];
$denda=$aw_denda*$pokok_pajak;
//end denda */
		$query_cek = "SELECT * FROM hotel.sptpd WHERE kd_kecamatan='$row[kd_kecamatan]' and kd_kelurahan='$row[kd_kelurahan]' AND kd_obj_pajak='$row[kd_obj_pajak]' AND kd_keg_usaha='$row[kd_keg_usaha]' AND no_reg='$row[no_reg]' and thn_pajak='$thn_pajak' and bln_pajak='$bln_pajak'";
		$exec_query_cek = pg_query($query_cek);
		if($exec_query_cek) {
			$ada_data=pg_num_rows($exec_query_cek);
			if($ada_data > 0) {
				/*$data_update = pg_fetch_array($exec_query_cek);
				//$jns_surat_u = $data_update['jns_surat'];
				$thn_pajak_u = $data_update['thn_pajak'];
				$bln_pajak_u = $data_update['bln_pajak'];
				$no_urut_surat_u = $data_update['no_urut_surat'];	
				$teguran_ke_u = $data_update['teguran_ke'];							
				
				$query_update = "UPDATE reklame.teguran SET penyimpan='$nip',tgl_surat=TO_DATE('$today','DD-MM-YYYY'),nip_pejabat='$pejabat' WHERE thn_pajak='$thn_pajak_u' AND bln_pajak='$bln_pajak_u' AND kd_obj_pajak='$kd_obj_pajak' AND no_urut_surat='$no_urut_surat_u' AND teguran_ke='$teguran_ke_u'";
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
		*/	} else {
				/*$query = "SELECT TO_NUMBER(MAX(no_urut_surat),'999999') AS no_max FROM reklame.teguran WHERE thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak'
	AND kd_obj_pajak='$kd_obj_pajak'";
				$result = pg_query($query);
				if($result) {
			
				} else {
					send_message('ERROR', pg_last_error());
					pg_free_result($result);
					pg_free_result($tampil);
					pg_close($dbconn);
					exit;
				}
				$data = pg_fetch_array($result);
				$no_urut_skp = no_reg($data['no_max']+1);
				pg_free_result($result); */
				$query_input = "INSERT INTO hotel.teguran_sptpd(thn_pajak,bln_pajak,kd_kecamatan,kd_kelurahan,kd_obj_pajak,kd_keg_usaha,no_reg,tgl_surat,nip_p,penyimpan,status,qrcode) VALUES('$thn_pajak','$bln_pajak','$row[kd_kecamatan]','$row[kd_kelurahan]','$row[kd_obj_pajak]','$row[kd_keg_usaha]','$row[no_reg]',TO_DATE('$tgl_penetapan','DD-MM-YYYY'),'$pejabat','$nip','0','$qrcode')";
				$excec_input = pg_query($query_input);
				if($excec_input) {
			
				} else {
					send_message('ERROR', pg_last_error());
					pg_free_result($result);
					pg_free_result($tampil);
					pg_close($dbconn);
					exit;
				}		
				
								
			//	$query_update_tmt = "UPDATE reklame.dat_obj_pajak SET tmt_awal=TO_DATE('".$tmt_aw."','DD-MM-YYYY'), tmt_akhir=TO_DATE('".$tmt_ak."','DD-MM-YYYY') WHERE kd_kecamatan='$kd_kecamatan' AND kd_kelurahan='$kd_kelurahan' AND kd_obj_pajak='$kd_obj_pajak' AND kd_keg_usaha='$kd_keg_usaha' AND no_reg='$no_reg'";
			//	$update_status_tagihan="UPDATE reklame.skp SET status_tagihan='1' where kd_kecamatan='$kd_kecamatan' AND kd_kelurahan='$kd_kelurahan' AND kd_obj_pajak='$kd_obj_pajak' AND kd_keg_usaha='$kd_keg_usaha' AND no_reg='$no_reg' AND bln_pajak='$bln_pajak' AND thn_pajak='$thn_pajak' ";
			//	$excec_upd_tmt = pg_query($update_status_tagihan);
			//	if($excec_upd_tmt) {
			
				//} else {
				//	send_message('ERROR', pg_last_error());
				//	pg_free_result($result);
				//	pg_free_result($tampil);
				//	pg_close($dbconn);
				//	exit;
				//} 
			}	
			usleep(1*50000);
		}
		
		 else {
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
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