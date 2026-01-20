<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	die("Silahkan Login Atau Refresh Browser");
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
$tgl_penyampaian = pg_escape_string($_GET['tanggal_tetap']);
$today = date("d-m-Y");
function send_message($id, $message, $progress) {
    $d = array('message' => $message , 'progress' => $progress);      
    echo "id: $id" . PHP_EOL;
    echo "data: " . json_encode($d) . PHP_EOL;
    echo PHP_EOL;	  
    //ob_flush();
	//ob_start();
	ob_flush();
    flush();
	ob_clean();
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/func_no_reg.inc.php";
$sql = "select A.kd_kecamatan,A.kd_kelurahan,A.kd_obj_pajak,A.kd_keg_usaha,A.no_reg,B.kd_provinsi,B.kd_kota,B.kd_jns,B.no_reg_wp,B.nm_usaha,B.alamat_usaha,A.tarif,sum(dasar_pengenaan_pajak) AS dasar_pajak,sum(pokok_pajak) AS pokok_pajak from bill.pengembalian_bill A
INNER JOIN
restoran.dat_obj_pajak B ON
A.kd_kecamatan=B.kd_kecamatan AND
A.kd_kelurahan=B.kd_kelurahan AND
A.kd_obj_pajak=B.kd_obj_pajak AND
A.kd_keg_usaha=B.kd_keg_usaha AND
A.no_reg=B.no_reg
where thn_pajak='$thn_pajak' and bln_pajak='$bln_pajak'
group by A.kd_kecamatan,A.kd_kelurahan,A.kd_obj_pajak,A.kd_keg_usaha,A.no_reg,B.kd_provinsi,B.kd_kota,B.kd_jns,B.no_reg_wp,B.nm_usaha,B.alamat_usaha,A.tarif";
$tampil = pg_query($sql);
if($tampil) {
//Mempersiapkan
	for($x = 5; $x >= 1; $x--) {
		send_message('INFO', 'Mempersiapkan Proses Penyampaian Masal SPTPD (Restoran - Bill) '.pg_num_rows($tampil).' Objek Pajak Tahun '.$thn_pajak.' Bulan '.$bulan_s[$bln_pajak].' <br><strong>Jangan Menutup Browser Saat Proses Berjalan</strong><br>Proses Akan Dimulai Dalam Hitungan '.$x, pg_num_rows($tampil)); 
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
		$pokok_pajak = $row['pokok_pajak'];
		$dasar_pengenaan_pajak = $row['dasar_pajak'];
		$tarif = $row['tarif'];
		$query_cek = "SELECT * FROM restoran.sptpd WHERE kd_kecamatan='$kd_kecamatan' AND kd_kelurahan='$kd_kelurahan' AND kd_obj_pajak='$kd_obj_pajak' AND kd_keg_usaha='$kd_keg_usaha' AND no_reg='$no_reg' AND thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak'";
		$exec_query_cek = pg_query($query_cek);
		if($exec_query_cek) {
			$ada_data=pg_num_rows($exec_query_cek);
			if($ada_data > 0) {
				$data_update = pg_fetch_array($exec_query_cek);
				$jns_surat_u = $data_update['jns_surat'];
				$thn_pajak_u = $data_update['thn_pajak'];
				$bln_pajak_u = $data_update['bln_pajak'];
				$no_urut_surat_u = $data_update['no_urut_surat'];
				$query_update = "UPDATE restoran.sptpd SET kd_provinsi='$kd_prov',kd_kota='$kd_kota',kd_jns='$kd_jns',no_reg_wp='$no_reg_wp',nm_usaha='$nm_usaha',alamat_usaha='$alamat_usaha',pokok_pajak=$pokok_pajak,dasar_pengenaan_pajak=$dasar_pengenaan_pajak,penyimpan='$nip',tgl_penyampaian=TO_DATE('$tgl_penyampaian','DD-MM-YYYY'),dat_pendukung='BILL',tarif=$tarif WHERE jns_surat='$jns_surat_u' AND thn_pajak='$thn_pajak_u' AND bln_pajak='$bln_pajak_u' AND kd_obj_pajak='$kd_obj_pajak' AND no_urut_surat='$no_urut_surat_u'";
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
				$query = "SELECT TO_NUMBER(MAX(no_urut_surat),'999999') AS no_max FROM restoran.sptpd WHERE thn_pajak='$thn_pajak' AND bln_pajak='$bln_pajak'
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
				$no_urut_sptpd = no_reg($data['no_max']+1);
				pg_free_result($result);
				$query_input = "INSERT INTO restoran.sptpd(thn_pajak,bln_pajak,no_urut_surat,kd_kecamatan,kd_kelurahan,kd_obj_pajak,kd_keg_usaha,no_reg,kd_provinsi,kd_kota,kd_jns,no_reg_wp,nm_usaha,alamat_usaha,dasar_pengenaan_pajak,tarif,pokok_pajak,penyimpan,tgl_penyampaian,status_pembayaran,dat_pendukung) VALUES('$thn_pajak','$bln_pajak','$no_urut_sptpd','$kd_kecamatan','$kd_kelurahan','$kd_obj_pajak','$kd_keg_usaha','$no_reg','13','76','$kd_jns','$no_reg_wp','$nm_usaha','$alamat_usaha',$dasar_pengenaan_pajak,$tarif,$pokok_pajak,'$nip',TO_DATE('$tgl_penyampaian','DD-MM-YYYY'),'0','BILL')";
				$excec_input = pg_query($query_input);
				if($excec_input) {
			
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