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
$kecamatan = pg_escape_string($_GET['kecamatan']);
$kelurahan = pg_escape_string($_GET['kelurahan']);
$kelas = pg_escape_string($_GET['kelas']);

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
//include $_SESSION['base_dir']."inc/db.orcl.coba.php";
include $_SESSION['base_dir']."inc/db.orcl.inc.php";

$stid = oci_parse($conn, "
SELECT A.KD_PROPINSI||'.'||A.KD_DATI2||'.'||A.KD_KECAMATAN||'.'||A.KD_KELURAHAN||'.'||A.KD_BLOK||'.'||A.NO_URUT||'.'||A.KD_JNS_OP AS NOP,B.KD_KLS_TANAH,B.NILAI_PER_M2_TANAH,TO_NUMBER(B.KD_KLS_TANAH) AS KELAS, 
A.LUAS_BUMI_SPPT,A.NJOP_BUMI_SPPT,A.NJOP_BNG_SPPT,A.NJOP_SPPT,A.NJOPTKP_SPPT,A.PBB_YG_HARUS_DIBAYAR_SPPT,C.NM_KECAMATAN,D.NM_KELURAHAN FROM PBB.SPPT A
LEFT JOIN PBB.KELAS_TANAH B ON B.KD_KLS_TANAH=A.KD_KLS_TANAH
LEFT JOIN PBB.REF_KECAMATAN C ON C.KD_KECAMATAN=A.KD_KECAMATAN
LEFT JOIN PBB.REF_KELURAHAN D ON D.KD_KELURAHAN =A.KD_KELURAHAN AND D.KD_KECAMATAN=A.KD_KECAMATAN
WHERE A.THN_PAJAK_SPPT='2025' AND B.THN_AWAL_KLS_TANAH = '2011' AND B.THN_AKHIR_KLS_TANAH = '9999' AND A.KD_KECAMATAN='$kecamatan' AND A.KD_KELURAHAN='$kelurahan'
ORDER BY NOP ASC");
if (!$stid) {
    $b = oci_error($conn);
    //trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);	
}
$r = oci_execute($stid);
if (!$r) {
    $b = oci_error($stid);
   // trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	send_message('ERROR', pg_last_error());
}
$tampil=220;
//Mempersiapkan
	for($x = 10; $x >= 1; $x--) {
		send_message('INFO', 'Mempersiapkan Proses Penetapan Masal SKP '.$tampil.' Objek Pajak Tahun '.$kecamatan.' Bulan '.$kelurahan.' <br><strong>Jangan Menutup Browser Saat Proses Berjalan</strong><br>Proses Akan Dimulai Dalam Hitungan '.$x, $tampil); 
		sleep(1);
	} 
//LONG RUNNING TASK
	$i = 0;
	while ($row_pbb = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) 
	{
		$i++;		
		send_message($i, 'Memproses Update Kelas Tanah : '.$kecamatan.'.'.$kelurahan, $i);
		$kd=$row_pbb['KELAS'];
		$jadi_naik=$kd-$kelas;
		$jd_naik=sprintf("%03d", $jadi_naik);
		$nama_kecamatan=$row_pbb['NM_KECAMATAN'];	
		$nama_kelurahan=$row_pbb['NM_KELURAHAN'];
		$nop=$row_pbb['NOP'];
		list($kd_prop,$kd_dati2,$kd_kec,$kd_kel,$kd_blok,$no_urut,$kd_jns) = explode(".",$nop);
		
		$cari_kenaikan = oci_parse($conn, "SELECT (A.NILAI_PER_M2_TANAH) AS NILAI_BARU FROM PBB.KELAS_TANAH A WHERE A.THN_AWAL_KLS_TANAH = '2011' AND A.THN_AKHIR_KLS_TANAH = 	'9999' AND A.KD_KLS_TANAH='$jd_naik' ORDER BY A.NILAI_MIN_TANAH ASC");
if (!$cari_kenaikan ) {
    $e = oci_error($conn);
    //trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
	$u = oci_execute($cari_kenaikan );
	if (!$u) {
    $e = oci_error($cari_kenaikan );
    //trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
		while ($row_sim = oci_fetch_array($cari_kenaikan, OCI_ASSOC+OCI_RETURN_NULLS)) {
		$njop_sppt_lama=$row_pbb['NJOP_BUMI_SPPT']+$row_pbb['NJOP_BNG_SPPT']-$row_pbb['NJOPTKP_SPPT'];
		$kelas_bumi_baru=$row_sim['NILAI_BARU']*1000;
		$njop_bumi_baru=$row_pbb['LUAS_BUMI_SPPT']*$kelas_bumi_baru;
		$njop_sppt_baru=$njop_bumi_baru+$row_pbb['NJOP_BNG_SPPT']-$row_pbb['NJOPTKP_SPPT'];
		$per_ratio=$njop_sppt_baru*(0.1/100);
		$pbb_naik=$per_ratio*($ratio/100);
		$kenaikan=$njop_sppt_baru-$njop_sppt_lama;
		$persen=round($kenaikan/$njop_sppt_baru*100,0);
			if($persen == 0)
			{
			$per_rat=100;
			} else if ($persen > 0 and $persen <= 5)
			{
			$per_rat=95;
			} else if ($persen > 5 and $persen <= 10)
			{
			$per_rat=90;
			} else if ($persen > 10 and $persen <= 15)
			{
			$per_rat=85;
			} else if ($persen > 15 and $persen <= 20)
			{
			$per_rat=80;
			} else if ($persen > 20 and $persen <= 25)
			{
			$per_rat=75;
			} else if ($persen > 25 and $persen <= 30)
			{
			$per_rat=70;
			} else if ($persen > 30 and $persen <= 35)
			{
			$per_rat=65;
			} else if ($persen > 35 and $persen <= 40)
			{
			$per_rat=60;
			} else if ($persen > 40 and $persen <= 45)
			{
			$per_rat=55;
			} else if ($persen > 45 and $persen <= 50)
			{
			$per_rat=50;
			} else if ($persen > 50 and $persen <= 55)
			{
			$per_rat=45;
			} else if ($persen > 55 and $persen <= 60)
			{
			$per_rat=40;
			} else if ($persen > 60 and $persen <= 65)
			{
			$per_rat=35;
			} else if ($persen > 65 and $persen <= 70)
			{
			$per_rat=30;
			} else if ($persen > 70 and $persen <= 75)
			{
			$per_rat=25;
			}
			else if ($persen > 75 and $persen <= 100)
			{
			$per_rat=20;
			}
		$njop_baru=($per_rat/100)*$njop_sppt_baru;
		$pbb_baru=$njop_baru*(0.1/100);
			if($pbb_baru < 5000){
				$pbb_baru_kali=5000;
			}
			else{
				$pbb_baru_kali=$pbb_baru;
			}
		$kenaik=$pbb_baru_kali-$row_pbb['PBB_YG_HARUS_DIBAYAR_SPPT'];
		
///////////// UPDATE KELAS TANAH //////////////////////
			$qry_update = "UPDATE PBB.SPPT F SET F.KD_KLS_TANAH='$jd_naik',
												 F.NJOP_BUMI_SPPT=$njop_bumi_baru,
												 F.NJOP_SPPT=$njop_sppt_baru,
												 F.RATIO=$per_rat,
												 F.NJOP_RATIO=$njop_baru,
												 F.PBB_YG_HARUS_DIBAYAR_SPPT=$pbb_baru_kali
										    WHERE  
												F.KD_PROPINSI='$kd_prop' AND
												F.KD_DATI2='$kd_dati2' AND
												F.KD_KECAMATAN='$kd_kec' AND
												F.KD_KELURAHAN='$kd_kel' AND
												F.KD_BLOK ='$kd_blok' AND
												F.NO_URUT='$no_urut' AND
												F.KD_JNS_OP='$kd_jns' AND
												F.THN_PAJAK_SPPT='2025'";
			$stic = oci_parse($conn,$qry_update);
			$uw = oci_execute($stic);
////////// END UPDATE KELAS TANAH ////////////////////
?>                                             
<?php
}
} 
$nom='Kecamatan <b>'.$nama_kecamatan.'</b> Kelurahan <b>'.$nama_kelurahan.'</b> Reklas  <b>'.$kelas.'Kelas</b> Sebanyak <b>'.$i.'</b>';
usleep(1*50000);
send_message('SELESAI', 'Process complete Babi...', $nom);
oci_close($conn);
?>