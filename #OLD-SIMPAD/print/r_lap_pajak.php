<?php
if (!isset($_SESSION)) { session_start(); }
/*if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	die('Silahkan Login');
	exit;
}
if (!array_key_exists('data', $_SESSION)) {
   die("Buka Dari Tombol Download"); 
}*/
//function selisih($jam_keluar) {
//$jam_selesai=date("H:i:s",mktime(date("H",strtotime($jam_keluar))-0,date("i",strtotime($jam_keluar))-0,date("s",strtotime($jam_keluar)),0,0,0));
//return $jam_selesai;
//}
$bdir = pg_escape_string($_GET['bdir']);
include $bdir."inc/db.inc.php";
include $bdir."inc/db.orcl.inc.php";
include $bdir."phpqrcode/qrlib.php";
//include_once $_SESSION['base_dir']."inc/db.inc.php";
//include_once $_SESSION['base_dir']."inc/schema.php";
$tgl_awal = pg_escape_string($_GET['tgl-awal']);
$tgl_akhir = pg_escape_string($_GET['tgl-akhir']);
$ttd = pg_escape_string($_GET['ttd']);
$tahun_pajak = pg_escape_string($_GET['tahun']);
$currentDate = date('Y-m-d');

//$total_pokok = 0;
//$total_denda = 0;


?>
<style type="text/css">
table.noborder td, table.noborder th, table.noborder tr { border:none; font-family:"Arial Unicode MS"; font-size:14px; }

.skptable { border-collapse: collapse; }

.skptable td, .skptable th {
    border-style:solid;
    border-width:1px;
    border-color:#000;
}

.skptablesub { border-collapse: collapse; }

.skptablesub td, .skptablesub th {
    border-style:solid;
    border-width:1px;
    border-color:#000;
}

.skptablesub tr:first-child > * {
    border-top: 0;
}

.font10 {
	font-family:"Arial";
	font-size:10px;
}
.font11 {
	font-family:"Arial";
	font-size:11px;
}
.font12 {
	font-family:"Arial";
	font-size:12px;
}
.font14 {
	font-family:"Arial";
	font-size:14px;
}
.font16 {
	font-family:"Arial";
	font-size:16px;
}
.font18 {
	font-family:"Arial";
	font-size:18px;
}
.style1 {
	font-size: 18px;
	font-family:Arial;
	font-weight: bold;
}
.style2 {
	font-size: 20px;
	font-family:Arial;
	font-weight: bold;
	
}
.style3 {
	font-size: 13px;
	font-family:Arial;
	font-weight: bold;
}
</style>
<table width="100%" border="0" class="noborder">
  <tr>
  <td width="20"><img src="../../images/payakumbuh.png" height="60"></td>  <td height="50" align="center"><span class="style1">PEMERINTAH DAERAH KOTA PAYAKUMBUH</span><br/>
    <span class="style2">BADAN KEUANGAN DAERAH</span><br/>
    <span class="style3">Jalan. Veteran No. 70 Kel. Kapalo Koto Dibalai Payakumbuh</span><br/>
	 <span class="style3">Telepon / Fax  (0752) 92052 Payakumbuh - 25211</span><br/>
	 <span class="style3">Website : https://bkd.payakumbuhkota.go.id | Email : bkd@payakumbuhkota.go.id</span><br/></td></td>
  </tr>
	
</table><div align="center"><img width="100%" height="6" src="../../images/garis.gif"></div><br/>
<table class="noborder" align="center" cellpadding="2px" width="100%">
<tr class="headings">
	 <td colspan="5" align="center"><h3>LAPORAN REALISASI PER JENIS PAJAK DAERAH </h3></td>
</tr> 
<tr class="headings font11">
<td colspan="5" align="left" class="font11"><b>PERIODE: <?php echo date('d',strtotime($tgl_awal))." ".$bulan[date('n',strtotime($tgl_awal))]." ".date('Y',strtotime($tgl_awal)); ?>&nbsp;s.d&nbsp;<?php echo date('d',strtotime($tgl_akhir))." ".$bulan[date('n',strtotime($tgl_akhir))]." ".date('Y',strtotime($tgl_akhir)); ?></b></td>
</tr>  </table>
<?php
/////PAJAK_AIR_TANAH //////////
$query_abt = "SELECT SUM(pokok_pajak) AS r_abt FROM air_tanah.pembayaran WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$result_abt = pg_query($query_abt) or die('Query failed: ' . pg_last_error());
$data_abt = pg_fetch_array($result_abt);
$pokok_abt = $data_abt['r_abt'];
$query_t_abt = "SELECT target FROM public.target WHERE thn_pajak='$tahun_pajak' AND kd_obj_pajak='01'";
$result_t_abt = pg_query($query_t_abt) or die('Query failed: ' . pg_last_error());
$data_t_abt = pg_fetch_array($result_t_abt);
$target_abt = $data_t_abt['target'];
$persen_abt = ($pokok_abt/$target_abt)*100;
pg_free_result($result_abt);
//pg_close($dbconn);
//////////////////////
//////////PAJAK REKLAME///////////
$q_reklame_papan = "SELECT SUM(A.pokok_pajak) AS r_reklame_p FROM reklame.pembayaran A
INNER JOIN reklame.skp B ON
A.jns_surat=B.jns_surat AND
A.thn_pajak=B.thn_pajak AND
A.bln_pajak=B.bln_pajak AND
A.kd_obj_pajak=B.kd_obj_pajak AND
A.no_urut_surat=B.no_urut_surat
WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' AND B.kd_keg_usaha = '01'";
$result_r_papan = pg_query($q_reklame_papan) or die('Query failed: ' . pg_last_error());
$data_r_papan = pg_fetch_array($result_r_papan);
$pokok_r_papan = $data_r_papan['r_reklame_p'];
$target_r_papan=267561079;
$persen_r_papan = ($pokok_r_papan/$target_r_papan)*100;
pg_free_result($result_r_papan);

$q_reklame_kain = "SELECT SUM(A.pokok_pajak) AS r_reklame_k FROM reklame.pembayaran A
INNER JOIN reklame.skp B ON
A.jns_surat=B.jns_surat AND
A.thn_pajak=B.thn_pajak AND
A.bln_pajak=B.bln_pajak AND
A.kd_obj_pajak=B.kd_obj_pajak AND
A.no_urut_surat=B.no_urut_surat
WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' AND B.kd_keg_usaha = '02'";
$result_r_kain = pg_query($q_reklame_kain) or die('Query failed: ' . pg_last_error());
$data_r_kain = pg_fetch_array($result_r_kain);
$pokok_r_kain = $data_r_kain['r_reklame_k'];
$target_r_kain=8300700;
$persen_r_kain = ($pokok_r_kain/$target_r_kain)*100;
pg_free_result($result_r_kain);

$q_reklame_stiker = "SELECT SUM(A.pokok_pajak) AS r_reklame_s FROM reklame.pembayaran A
INNER JOIN reklame.skp B ON
A.jns_surat=B.jns_surat AND
A.thn_pajak=B.thn_pajak AND
A.bln_pajak=B.bln_pajak AND
A.kd_obj_pajak=B.kd_obj_pajak AND
A.no_urut_surat=B.no_urut_surat
WHERE A.tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir' AND B.kd_keg_usaha = '03'";
$result_r_stiker = pg_query($q_reklame_stiker) or die('Query failed: ' . pg_last_error());
$data_r_stiker = pg_fetch_array($result_r_stiker);
$pokok_r_stiker = $data_r_stiker['r_reklame_s'];
$target_r_stiker=1650000;
$persen_r_stiker = ($pokok_r_stiker/$target_r_stiker)*100;
pg_free_result($result_r_stiker);
$target_reklame=$target_r_papan+$target_r_kain+$target_r_stiker;
$pokok_reklame=$pokok_r_papan+$pokok_r_kain+$pokok_r_stiker;
$persen_reklame= ($pokok_reklame/$target_reklame)*100;

/////////////////////////////////
////////////PAJAK GALIAN C //////////////////
$query_galianc = "SELECT SUM(pokok_pajak) AS r_galianc FROM galianc.pembayaran WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$result_gal_c = pg_query($query_galianc) or die('Query failed: ' . pg_last_error());
$data_gal_c = pg_fetch_array($result_gal_c);
$pokok_gal_c = $data_gal_c['r_galianc'];
$query_c = "SELECT target FROM public.target WHERE thn_pajak='$tahun_pajak' AND kd_obj_pajak='05'";
$result_c = pg_query($query_c) or die('Query failed: ' . pg_last_error());
$data_c = pg_fetch_array($result_c);
$target_c = $data_c['target'];
$persen_c = ($pokok_gal_c/$target_c)*100;
pg_free_result($result_c);
pg_free_result($data_c);
//////////////////////////////////////
////////////////PBB//////////////////
$stid = oci_parse($conn, "SELECT TO_CHAR(SUM(B.PBB_YG_HARUS_DIBAYAR_SPPT), '999,999,999,999') AS POKOK,SUM(B.PBB_YG_HARUS_DIBAYAR_SPPT) AS POKOKPERSEN,TO_CHAR(SUM(A.DENDA_SPPT), '999,999,999,999') AS DENDA,TO_CHAR(SUM(A.JML_SPPT_YG_DIBAYAR), '999,999,999,999') AS TOTAL FROM PBB.PEMBAYARAN_SPPT A, PBB.SPPT B WHERE
A.TGL_PEMBAYARAN_SPPT BETWEEN TO_DATE('$tgl_awal','DD-MM-YYYY') AND TO_DATE('$tgl_akhir','DD-MM-YYYY')
and a.KD_PROPINSI=b.KD_PROPINSI 
and a.KD_DATI2=b.KD_DATI2 
and a.KD_KECAMATAN=b.KD_KECAMATAN 
and a.KD_KELURAHAN=b.KD_KELURAHAN 
and a.kd_blok=b.kd_blok 
and a.no_urut=b.no_urut
and a.kd_jns_op=b.kd_jns_op 
and a.THN_PAJAK_SPPT=b.THN_PAJAK_SPPT 
");
if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
// Perform the logic of the query
$r = oci_execute($stid);
$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
$pokok_pbb = $row['POKOKPERSEN'];
$ketetapanp='1939302672';
$persen_pbb = ($pokok_pbb/$ketetapanp)*100;
///////////////////////////////////
///////////////////BPHTB//////////
$query_bphtb = "SELECT SUM(pokok_pajak) AS r_bphtb FROM bphtb.pembayaran WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$result_bphtb = pg_query($query_bphtb) or die('Query failed: ' . pg_last_error());
$data_b = pg_fetch_array($result_bphtb);
$pokok_b = $data_b['r_bphtb'];
$query_b = "SELECT target FROM public.target WHERE thn_pajak='$tahun_pajak' AND kd_obj_pajak='09'";
$result_b = pg_query($query_b) or die('Query failed: ' . pg_last_error());
$data_bp = pg_fetch_array($result_b);
$target_bp = $data_bp['target'];
$persen_bp = ($pokok_b/$target_bp)*100;
pg_free_result($result_b);
pg_free_result($query_bphtb);
/////////////////////////////////
///////////////////PBJT//////////
$query_resto = "SELECT SUM(pokok_pajak) AS r_restoran FROM restoran.pembayaran WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$result_resto = pg_query($query_resto) or die('Query failed: ' . pg_last_error());
$data_resto = pg_fetch_array($result_resto);
$pokok_resto = $data_resto['r_restoran'];
$query_r = "SELECT target FROM public.target WHERE thn_pajak='$tahun_pajak' AND kd_obj_pajak='03'";
$result_r = pg_query($query_r) or die('Query failed: ' . pg_last_error());
$data_r = pg_fetch_array($result_r);
$target_r = $data_r['target'];
$persen_r = ($pokok_resto/$target_r)*100;
pg_free_result($result_resto);
pg_free_result($result_r);

$query_ppj = "SELECT SUM(pokok_pajak) AS r_ppj FROM ppj.pembayaran WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$result_ppj = pg_query($query_ppj) or die('Query failed: ' . pg_last_error());
$data_ppj = pg_fetch_array($result_ppj);
$pokok_ppj = $data_ppj['r_ppj'];
$query_p = "SELECT target FROM public.target WHERE thn_pajak='$tahun_pajak' AND kd_obj_pajak='04'";
$result_p = pg_query($query_p) or die('Query failed: ' . pg_last_error());
$data_p = pg_fetch_array($result_p);
$target_p = $data_p['target'];
$persen_p = ($pokok_ppj/$target_p)*100;
pg_free_result($result_ppj);
pg_free_result($result_p);

$query_hotel = "SELECT SUM(pokok_pajak) AS r_hotel FROM hotel.pembayaran WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$result_hotel = pg_query($query_hotel) or die('Query failed: ' . pg_last_error());
$data_hotel = pg_fetch_array($result_hotel);
$pokok_hotel = $data_hotel['r_hotel'];
$query_h = "SELECT target FROM public.target WHERE thn_pajak='$tahun_pajak' AND kd_obj_pajak='07'";
$result_h = pg_query($query_h) or die('Query failed: ' . pg_last_error());
$data_h = pg_fetch_array($result_h);
$target_h = $data_h['target'];
$persen_h = ($pokok_hotel/$target_h)*100;
pg_free_result($result_hotel);
pg_free_result($result_h);

$query_parkir = "SELECT SUM(pokok_pajak) AS r_parkir FROM parkir.pembayaran WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$result_parkir = pg_query($query_parkir) or die('Query failed: ' . pg_last_error());
$data_parkir = pg_fetch_array($result_parkir);
$pokok_parkir = $data_parkir['r_parkir'];
$query_pr = "SELECT target FROM public.target WHERE thn_pajak='$tahun_pajak' AND kd_obj_pajak='05'";
$result_pr = pg_query($query_pr) or die('Query failed: ' . pg_last_error());
$data_pr = pg_fetch_array($result_pr);
$target_pr = $data_pr['target'];
$persen_pr = ($pokok_parkir/$target_pr)*100;
pg_free_result($result_parkir);
pg_free_result($result_pr);

$query_hiburan = "SELECT SUM(pokok_pajak) AS r_hiburan FROM hiburan.pembayaran WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$result_hiburan = pg_query($query_hiburan) or die('Query failed: ' . pg_last_error());
$data_hiburan = pg_fetch_array($result_hiburan);
$pokok_hiburan = $data_hiburan['r_hiburan'];
$query_hb = "SELECT target FROM public.target WHERE thn_pajak='$tahun_pajak' AND kd_obj_pajak='06'";
$result_hb = pg_query($query_hb) or die('Query failed: ' . pg_last_error());
$data_hb = pg_fetch_array($result_hb);
$target_hb = $data_hb['target'];
$persen_hb = ($pokok_hiburan/$target_hb)*100;
pg_free_result($result_hiburan);
pg_free_result($result_hb);

$total_pbjt = $pokok_resto+$pokok_ppj+$pokok_hotel+$pokok_parkir+$pokok_hiburan;
$target_pbjt = $target_r+$target_p+$target_h+$target_pr+$target_hb;
$persen_pbjt = ($total_pbjt/$target_pbjt)*100;
/////////////////////////////////
//////////////OPSEN PKB /////////////////
$query_pkb = "SELECT SUM(pkb_pokok) AS r_pkb FROM pkb.pembayaran_opsen WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$result_pkb = pg_query($query_pkb) or die('Query failed: ' . pg_last_error());
$data_pkb = pg_fetch_array($result_pkb);
$pokok_pkb = $data_pkb['r_pkb'];
$query_pk = "SELECT target FROM public.target WHERE thn_pajak='$tahun_pajak' AND kd_obj_pajak='10'";
$result_pk = pg_query($query_pk) or die('Query failed: ' . pg_last_error());
$data_pk = pg_fetch_array($result_pk);
$target_pk = $data_pk['target'];
$persen_pk = ($pokok_pkb/$target_pk)*100;
pg_free_result($result_pkb);
pg_free_result($result_pk);
/////////////////////////////////////////
//////////////OPSEN BBNKB /////////////////
$query_bbnkb = "SELECT SUM(bbnkb_pokok) AS r_bbnkb FROM bbnkb.pembayaran_opsen WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$result_bbnkb = pg_query($query_bbnkb) or die('Query failed: ' . pg_last_error());
$data_bbnkb = pg_fetch_array($result_bbnkb);
$pokok_bbnkb = $data_bbnkb['r_bbnkb'];
$query_bb = "SELECT target FROM public.target WHERE thn_pajak='$tahun_pajak' AND kd_obj_pajak='11'";
$result_bb = pg_query($query_bb) or die('Query failed: ' . pg_last_error());
$data_bb = pg_fetch_array($result_bb);
$target_bb = $data_bb['target'];
$persen_bb = ($pokok_bbnkb/$target_bb)*100;
pg_free_result($result_bbnkb);
pg_free_result($result_bb);
/////////////////////////////////////////
///////TOTAL ////
$total_pajak = $pokok_abt+$pokok_reklame+$pokok_pbb+$pokok_gal_c+$total_pbjt+$pokok_b+$pokok_pkb+$pokok_bbnkb;
$target_pajak = $target_abt+$target_reklame+$ketetapanp+$target_c+$target_pbjt+$target_bp+$target_pk+$target_bb;
$persen_pajak = ($total_pajak/$target_pajak)*100;
//////
?>
<table class="skptable font12" align="center" cellpadding="2px" width="100%">                                 
<tr class="headings"><th width="1%">KODE </th><th>URAIAN</th><th>TARGET (Rp)</th><th>REALISASI (Rp)</th><th>PERSEN (%)</th></tr>                                    
<tbody>                                                                                                                               								 
<tr class="even pointer" valign="middle"><td><b>4.1.01</b></td><td align="left"><b>PAJAK DAERAH</b></td><td align="right"><b><?php echo number_format($target_pajak);?></b></td><td align="right"><b><?php echo number_format($total_pajak);?></b></td><td align="center"><b><?php echo round($persen_pajak,2);?>%</b></td></tr>
<tr class="even pointer" valign="middle"><td><b>4.1.01.09</b></td><td align="left"><b>Pajak Reklame</b></td><td align="right"><b><?php echo number_format($target_reklame);?></b></td><td align="right"><b><?php echo number_format($pokok_reklame);?></b></td><td align="center"><b><?php echo round($persen_reklame,2);?>%</b></td></tr> 
<tr class="even pointer" valign="middle"><td>4.1.01.09.01</td><td align="left">Pajak Reklame Papan/Billboard/Videotron/Megatron</td><td align="right"><?php echo number_format($target_r_papan);?></td><td align="right"><?php echo number_format($pokok_r_papan);?></td><td align="center"><?php echo round($persen_r_papan,2);?>%</td></tr>
<tr class="even pointer" valign="middle"><td>4.1.01.09.01.0001</td><td align="left">Pajak Reklame Papan/Billboard/Videotron/Megatron</td><td align="right"><?php echo number_format($target_r_papan);?></td><td align="right"><?php echo number_format($pokok_r_papan);?></td><td align="center"><b><?php echo round($persen_r_papan,2);?>%</b></td></tr>  
<tr class="even pointer" valign="middle"><td>4.1.01.09.02</td><td align="left">Pajak Reklame Kain</td><td align="right"><?php echo number_format($target_r_kain);?></td><td align="right"><?php echo number_format($pokok_r_kain);?></td><td align="center"><?php echo round($persen_r_kain,2);?>%</td></tr>
<tr class="even pointer" valign="middle"><td>4.1.01.09.02.0001</td><td align="left">Pajak Reklame Kain</td><td align="right"><?php echo number_format($target_r_kain);?></td><td align="right"><?php echo number_format($pokok_r_kain);?></td><td align="center"><?php echo round($persen_r_kain,2);?>%</td></tr>
<tr class="even pointer" valign="middle"><td>4.1.01.09.03</td><td align="left">Pajak Reklame Melekat/Stiker</td><td align="right"><?php echo number_format($target_r_stiker);?></td><td align="right"><?php echo number_format($pokok_r_stiker);?></td><td align="center"><?php echo round($persen_r_stiker,2);?>%</td></tr>
<tr class="even pointer" valign="middle"><td>4.1.01.09.03.0001</td><td align="left">Pajak Reklame Melekat/Stiker</td><td align="right"><?php echo number_format($target_r_stiker);?></td><td align="right"><?php echo number_format($pokok_r_stiker);?></td><td align="center"><?php echo round($persen_r_stiker,2);?>%</td></tr>   
                                                                                                                             									
<tr class="even pointer"><td><b>4.1.01.12</b></td><td class=" "><b>&nbsp;Pajak Air Tanah</b></td><td align="right"><b><?php echo number_format($target_abt);?></b></td><td align="right"><b><?php echo number_format($pokok_abt);?></b></td><td align="center"><b><?php echo round($persen_abt,2);?>%</b></td></tr>
<tr class="even pointer"><td>4.1.01.12.01</td><td class=" ">&nbsp;Pajak Air Tanah</td><td align="right"><?php echo number_format($target_abt);?></td><td align="right"><?php echo number_format($pokok_abt);?></td><td align="center"><?php echo round($persen_abt,2);?>%</td></tr>
<tr class="even pointer"><td>4.1.01.12.01.0001</td><td class=" ">&nbsp;Pajak Air Tanah</td><td align="right"><?php echo number_format($target_abt);?></td><td align="right"><?php echo number_format($pokok_abt);?></td><td align="center"><?php echo round($persen_abt,2);?>%</td></tr>

<tr class="even pointer"><td><b>4.1.01.14</b></td><td class=" "><b>&nbsp;Pajak Mineral Bukan Logam dan Batuan</b></td><td align="right"><b><?php echo number_format($target_c);?></b></td><td align="right"><b><?php echo number_format($pokok_gal_c);?></b></td><td align="center"><b><?php echo round($persen_c,2);?>%</b></td></tr>
<tr class="even pointer"><td>4.1.01.14.01</td><td class=" ">&nbsp;Pajak Mineral Bukan Logam dan Batuan</td><td align="right"><?php echo number_format($target_c);?></td><td align="right"><?php echo number_format($pokok_gal_c);?></td><td align="center"><?php echo round($persen_abt,2);?>%</td></tr>
<tr class="even pointer"><td>4.1.01.14.01.0001</td><td class=" ">&nbsp;Pajak Mineral Bukan Logam dan Batuan</td><td align="right"><?php echo number_format($target_c);?></td><td align="right"><?php echo number_format($pokok_gal_c);?></td><td align="center"><?php echo round($persen_c,2);?>%</td></tr>

<tr class="even pointer"><td><b>4.1.01.15</b></td><td class=" "><b>&nbsp;Pajak Bumi dan Bangunan Perdesaan dan Perkotaan (PBBP2)</b></td><td align="right"><b><?php echo number_format($ketetapanp);?></b></td><td align="right"><b><?php echo number_format($pokok_pbb);?></b></td><td align="center"><b><?php echo round($persen_pbb,2);?>%</b></td></tr>
<tr class="even pointer"><td>4.1.01.15.01</td><td class=" ">&nbsp;PBBP2</td><td align="right"><?php echo number_format($ketetapanp);?></td><td align="right"><?php echo number_format($pokok_pbb);?></td><td align="center"><?php echo round($persen_pbb,2);?>%</td></tr>
<tr class="even pointer"><td>4.1.01.15.01.0001</td><td class=" ">&nbsp;PBBP2</td><td align="right"><?php echo number_format($ketetapanp);?></td><td align="right"><?php echo number_format($pokok_pbb);?></td><td align="center"><?php echo round($persen_pbb,2);?>%</td></tr>

<tr class="even pointer"><td><b>4.1.01.16</b></td><td class=" "><b>&nbsp;Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB)</b></td><td align="right"><b><?php echo number_format($target_bp);?></b></td><td align="right"><b><?php echo number_format($pokok_b);?></b></td><td align="center"><b><?php echo round($persen_bp,2);?>%</b></td></tr>
<tr class="even pointer"><td>4.1.01.16.01</td><td class=" ">&nbsp;BPHTB-Pemindahan Hak</td><td align="right"><?php echo number_format($target_bp);?></td><td align="right"><?php echo number_format($pokok_b);?></td><td align="center"><?php echo round($persen_bp,2);?>%</td></tr>
<tr class="even pointer"><td>4.1.01.16.01.0001</td><td class=" ">&nbsp;BPHTB-Pemindahan Hak</td><td align="right"><?php echo number_format($target_bp);?></td><td align="right"><?php echo number_format($pokok_b);?></td><td align="center"><?php echo round($persen_bp,2);?>%</td></tr>

<tr class="even pointer"><td><b>4.1.01.19</b></td><td class=" "><b>&nbsp;Pajak Barang dan Jasa Tertentu (PBJT)</b></td><td align="right"><b><?php echo number_format($target_pbjt);?></b></td><td align="right"><b><?php echo number_format($total_pbjt);?></b></td><td align="center"><b><?php echo round($persen_pbjt,2);?>%</b></td></tr>

<tr class="even pointer"><td>4.1.01.19.01</td><td class=" ">&nbsp;PBJT-Makanan dan/atau Minuman</td><td align="right"><?php echo number_format($target_r);?></td><td align="right"><?php echo number_format($pokok_resto);?></td><td align="center"><?php echo round($persen_r,2);?>%</td></tr>
<tr class="even pointer"><td>4.1.01.19.01.0001</td><td class=" ">&nbsp;PBJT-Restoran</td><td align="right"><?php echo number_format($target_r);?></td><td align="right"><?php echo number_format($pokok_resto);?></td><td align="center"><?php echo round($persen_r,2);?>%</td></tr>

<tr class="even pointer"><td>4.1.01.19.02</td><td class=" ">&nbsp;PBJT-Tenaga Listrik</td><td align="right"><?php echo number_format($target_p);?></td><td align="right"><?php echo number_format($pokok_ppj);?></td><td align="center"><?php echo round($persen_p,2);?>%</td></tr>
<tr class="even pointer"><td>4.1.01.19.02.0001</td><td class=" ">&nbsp;PBJT-Konsumsi Tenaga Listrik dari Sumber Lain</td><td align="right"><?php echo number_format($target_p);?></td><td align="right"><?php echo number_format($pokok_ppj);?></td><td align="center"><?php echo round($persen_p,2);?>%</td></tr>

<tr class="even pointer"><td>4.1.01.19.03</td><td class=" ">&nbsp;PBJT-Jasa Perhotelan</td><td align="right"><?php echo number_format($target_h);?></td><td align="right"><?php echo number_format($pokok_hotel);?></td><td align="center"><?php echo round($persen_h,2);?>%</td></tr>
<tr class="even pointer"><td>4.1.01.19.03.0001</td><td class=" ">&nbsp;PBJT-Hotel</td><td align="right"><?php echo number_format($target_h);?></td><td align="right"><?php echo number_format($pokok_hotel);?></td><td align="center"><?php echo round($persen_h,2);?>%</td></tr>

<tr class="even pointer"><td>4.1.01.19.04</td><td class=" ">&nbsp;PBJT-Jasa Parkir</td><td align="right"><?php echo number_format($target_pr);?></td><td align="right"><?php echo number_format($pokok_parkir);?></td><td align="center"><?php echo round($persen_pr,2);?>%</td></tr>
<tr class="even pointer"><td>4.1.01.19.04.0001</td><td class=" ">&nbsp;PBJT-Penyediaan atau Penyelenggaraan Tempat Parkir</td><td align="right"><?php echo number_format($target_pr);?></td><td align="right"><?php echo number_format($pokok_parkir);?></td><td align="center"><?php echo round($persen_pr,2);?>%</td></tr>

<tr class="even pointer"><td>4.1.01.19.05</td><td class=" ">&nbsp;PBJT-Jasa Kesenian dan Hiburan</td><td align="right"><?php echo number_format($target_hb);?></td><td align="right"><?php echo number_format($pokok_hiburan);?></td><td align="center"><?php echo round($persen_hb,2);?>%</td></tr>
<tr class="even pointer"><td>4.1.01.19.05.0002</td><td class=" ">&nbsp;PBJT-Pergelaran Kesenian, Musik, Tari dan/atau Busana</td><td align="right"><?php echo number_format($target_hb);?></td><td align="right"><?php echo number_format($pokok_hiburan);?></td><td align="center"><?php echo round($persen_hb,2);?>%</td></tr>

<tr class="even pointer"><td><b>4.1.01.20</b></td><td class=" "><b>&nbsp;Opsen Pajak Kenderaan Bermotor (PKB)</b></td><td align="right"><b><?php echo number_format($target_pk);?></b></td><td align="right"><b><?php echo number_format($pokok_pkb);?></b></td><td align="center"><b><?php echo round($persen_pk,2);?>%</b></td></tr>
<tr class="even pointer"><td>4.1.01.20.01</td><td class=" ">&nbsp;Opsen PKB</td><td align="right"><?php echo number_format($target_pk);?></td><td align="right"><?php echo number_format($pokok_pkb);?></td><td align="center"><?php echo round($persen_pk,2);?>%</td></tr>
<tr class="even pointer"><td>4.1.01.20.0.0001</td><td class=" ">&nbsp;Opsen PKB</td><td align="right"><?php echo number_format($target_pk);?></td><td align="right"><?php echo number_format($pokok_pkb);?></td><td align="center"><?php echo round($persen_pk,2);?>%</td></tr>

<tr class="even pointer"><td><b>4.1.01.21</b></td><td class=" "><b>&nbsp;Opsen Bea Balik Nama Kenderaan Bermotor (BBNKB)</b></td><td align="right"><b><?php echo number_format($target_bb);?></b></td><td align="right"><b><?php echo number_format($pokok_bbnkb);?></b></td><td align="center"><b><?php echo round($persen_bb,2);?>%</b></td></tr>
<tr class="even pointer"><td>4.1.01.21.01</td><td class=" ">&nbsp;Opsen BBNKB</td><td align="right"><?php echo number_format($target_bb);?></td><td align="right"><?php echo number_format($pokok_bbnkb);?></td><td align="center"><?php echo round($persen_bb,2);?>%</td></tr>
<tr class="even pointer"><td>4.1.01.21.0.0001</td><td class=" ">&nbsp;Opsen BBNKB</td><td align="right"><?php echo number_format($target_bb);?></td><td align="right"><?php echo number_format($pokok_bbnkb);?></td><td align="center"><?php echo round($persen_bb,2);?>%</td></tr>




</tbody>

</table>
                                <table width="100%" border="0" class="noborder">
  <tr>
    <td width="35%" rowspan="5" valign="top"><?php 
  $qr=md5("BKD-PYK");
  $tempdir = "../../images/";
  //ambil logo
  $logopath="../../images/bkd.png";

 //isi qrcode jika di scan
 $codeContents = "$qr"; 

 //simpan file qrcode
 QRcode::png($codeContents, $tempdir.'image.png', QR_ECLEVEL_H, 1,1);


 // ambil file qrcode
 $QR = imagecreatefrompng($tempdir.'image.png');

 // memulai menggambar logo dalam file qrcode
 $logo = imagecreatefromstring(file_get_contents($logopath));
 
 imagecolortransparent($logo , imagecolorallocatealpha($logo , 0, 0, 0, 300));
 imagealphablending($logo , false);
 imagesavealpha($logo , true);

 $QR_width = imagesx($QR);
 $QR_height = imagesy($QR);

 $logo_width = imagesx($logo);
 $logo_height = imagesy($logo);

 // Scale logo to fit in the QR Code
 $logo_qr_width = $QR_width/2;
 $scale = $logo_width/$logo_qr_width;
 $logo_qr_height = $logo_height/$scale;

 imagecopyresampled($QR, $logo, $QR_width/2.5, $QR_height/2.5, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);

 // Simpan kode QR lagi, dengan logo di atasnya
 imagepng($QR,$tempdir.'image.png');

  
  
//  QRcode::png("$nik","image.png","L",4,4);
echo '<img src="'.$tempdir.'image.png'.'" />';?></td>
    <td width="30%">&nbsp;</td>
    <td width="35%">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><div align="center"><?php echo "Payakumbuh, ".date('d',strtotime($currentDate))." ".$bulan[date('n',strtotime($currentDate))]." ".date('Y',strtotime($currentDate)); ?></div></td>
    </tr>
  <tr>
    <td colspan="2"><div align="center">
     <b> <?php
	$nip = $row['nip_pejabat'];
	$sql = "SELECT * FROM public.user WHERE nip='$ttd'";
	$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
	$row_user = pg_fetch_array($result);
	echo $row_user['jabatan'];
	?></b>
    </div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">		</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;
      <div align="center">
    
        
        
    &nbsp;</div></td>
    </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><div align="center"><b><?php echo "<u>".$row_user['nama']."</u>"; ?></b></div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><div align="center"><b><?php echo "NIP. ".$row_user['nip']; ?></b></div></td>
    </tr>
</table>
                 