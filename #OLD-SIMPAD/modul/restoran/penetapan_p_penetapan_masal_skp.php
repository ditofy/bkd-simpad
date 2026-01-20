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
	$tgl_jatuh_tempo = "01-".$bln_jth_tempo."-".$thn_pajak;
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
include_once $_SESSION['base_dir']."inc/db.mssql.inc.php";
$sql = "SELECT A.*,B.nama,B.alamat FROM restoran.dat_obj_pajak A
INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp WHERE A.jenis_penetapan='01' AND A.status_pajak='1' ORDER BY A.kd_kecamatan,A.kd_kelurahan,A.kd_obj_pajak,A.kd_keg_usaha,A.no_reg";
$tampil = pg_query($sql);
if($tampil) {
//Mempersiapkan
	for($x = 5; $x >= 1; $x--) {
		send_message('INFO', 'Mempersiapkan Proses Penetapan Masal SKP '.pg_num_rows($tampil).' Objek Pajak Tahun '.$thn_pajak.' Bulan '.$bulan_s[$bln_pajak].' <br><strong>Jangan Menutup Browser Saat Proses Berjalan</strong><br>Proses Akan Dimulai Dalam Hitungan '.$x, pg_num_rows($tampil)); 
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
		$nm_usaha = $row['nm_usaha'];
		$alamat_usaha = $row['alamat_usaha'];
		$pokok_pajak = $row['ketetapan'];
		$query_cek = "SELECT * FROM restoran.skp WHERE kd_kecamatan='$kd_kecamatan' AND kd_kelurahan='$kd_kelurahan' AND kd_obj_pajak='$kd_obj_pajak' AND kd_keg_usaha='$kd_keg_usaha' AND no_reg='$no_reg' AND thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak'";
		$exec_query_cek = pg_query($query_cek);
		if($exec_query_cek) {
			$ada_data=pg_num_rows($exec_query_cek);
			if($ada_data > 0) {
				$data_update = pg_fetch_array($exec_query_cek);
				$jns_surat_u = $data_update['jns_surat'];
				$thn_pajak_u = $data_update['thn_pajak'];
				$bln_pajak_u = $data_update['bln_pajak'];
				$no_urut_surat_u = $data_update['no_urut_surat'];
				$query_update = "UPDATE restoran.skp SET kd_provinsi='$kd_prov',kd_kota='$kd_kota',kd_jns='$kd_jns',no_reg_wp='$no_reg_wp',nm_usaha='$nm_usaha',alamat_usaha='$alamat_usaha',pokok_pajak=$pokok_pajak,penyimpan='$nip',tgl_simpan=TO_DATE('$today','DD-MM-YYYY'),tgl_penetapan=TO_DATE('$tgl_penetapan','DD-MM-YYYY'),tgl_jatuh_tempo=TO_DATE('$tgl_jatuh_tempo','DD-MM-YYYY'),nip_pejabat='$pejabat' WHERE jns_surat='$jns_surat_u' AND thn_pajak='$thn_pajak_u' AND bln_pajak='$bln_pajak_u' AND kd_obj_pajak='$kd_obj_pajak' AND no_urut_surat='$no_urut_surat_u'";
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
			} else {
				$query = "SELECT TO_NUMBER(MAX(no_urut_surat),'999999') AS no_max FROM restoran.skp WHERE thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak'
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
				pg_free_result($result);
				$query_input = "INSERT INTO restoran.skp(thn_pajak,bln_pajak,no_urut_surat,kd_kecamatan,kd_kelurahan,kd_obj_pajak,kd_keg_usaha,no_reg,kd_provinsi,kd_kota,kd_jns,no_reg_wp,nm_usaha,alamat_usaha,pokok_pajak,penyimpan,tgl_simpan,status_pembayaran,tgl_penetapan,nip_pejabat,tgl_jatuh_tempo) VALUES('$thn_pajak','$bln_pajak','$no_urut_skp','$kd_kecamatan','$kd_kelurahan','$kd_obj_pajak','$kd_keg_usaha','$no_reg','13','76','$kd_jns','$no_reg_wp','$nm_usaha','$alamat_usaha',$pokok_pajak,'$nip',TO_DATE('$today','DD-MM-YYYY'),'0',TO_DATE('$tgl_penetapan','DD-MM-YYYY'),'$pejabat',TO_DATE('$tgl_jatuh_tempo','DD-MM-YYYY'))";
				$excec_input = pg_query($query_input);
				if($excec_input) {
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
					$nm_wp = $row['nama'];
					$alamat_wp = $row['alamat'];
					$npwpd_sub = $kd_jns.".".$no_reg_wp;					
					$q_sync_sipkd = mssql_query("INSERT INTO SKP_SIMPAD(NOSKP,NPWPD,TGLSKP,PENYETOR,ALAMAT,URAISKP,TGLTEMPO,TGLVALID,MTGKEY,KDPER,NILAI) VALUES('$nosp','$npwpd_sub',CAST('$tglskp' AS DATETIME),'$nm_wp','$alamat_wp','$uraian',CAST('$tgltempo' AS DATETIME),CAST('$tglskp' AS DATETIME),'$mtgkey','$kdper',$pokok_pajak)");
					pg_free_result($result);
					}
					
				} else {
					send_message('ERROR', pg_last_error());
					pg_free_result($result);
					pg_free_result($tampil);
					pg_close($dbconn);
					exit;
				}				
			}	
			usleep(1*50000);
		} else {
			send_message('ERROR', pg_last_error());
			pg_free_result($exec_query_cek);
			pg_free_result($result);
			pg_free_result($tampil);
			pg_close($dbconn);
			mssql_close($link);
			exit;
		}
	}
//pg_close($dbconn);  
	pg_free_result($tampil);
	pg_close($dbconn);
	mssql_close($link);
	send_message('SELESAI', 'Process complete', $i);
} else {
	send_message('ERROR', pg_last_error());
	//send_message('SELESAI', pg_last_error());
	pg_free_result($tampil);
	pg_close($dbconn);
	mssql_close($link);
}

?>