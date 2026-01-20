
<style type="text/css">
table, tr, td, th, tbody, thead, tfoot {
    page-break-inside: avoid !important;
	font-family:"Arial Rounded MT Bold";
}
.font11 {
	font-family:"Verdana";
	font-size:11px;
	font:bold;
}
.style1 {font-size: 14px}
</style>
<?php
if (!isset($_SESSION)) { session_start(); }
//if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
//	header('Location: /login.php');
//}
function selisih($jam_keluar) {
$jam_selesai=date("H:i:s",mktime(date("H",strtotime($jam_keluar))-0,date("i",strtotime($jam_keluar))-0,date("s",strtotime($jam_keluar)),0,0,0));
return $jam_selesai;
}
$bdir = pg_escape_string($_GET['bdir']);
include $bdir."inc/db.inc.php";
//include_once $_SESSION['base_dir']."inc/db.inc.php";
//include_once $_SESSION['base_dir']."inc/schema.php";
$tgl_awal = pg_escape_string($_GET['tgl-awal']);
$tgl_akhir = pg_escape_string($_GET['tgl-akhir']);
$jns_objek = pg_escape_string($_GET['jns-objek']);
$jns_laporan = pg_escape_string($_GET['jns-laporan']);

$schema = $list_schema[$arr_data['jns-obj']];
//$total_pokok = 0;
//$total_denda = 0;

$query_obj= pg_query("select A.nama_pad,C.kd_pad,SUM(C.jumlah) as jj,A.rek_apbd from retribusi.jenis_pendapatan A LEFT JOIN retribusi.retribusi C ON A.kd_pad=C.kd_pad  where C.tgl_setor BETWEEN '$tgl_awal' AND '$tgl_akhir' GROUP BY A.nama_pad,C.kd_pad,A.rek_apbd   ")or die('Query failed: ' . pg_last_error()); 

?>

<table border="1" style="font-size:12px;border:1px solid black;border-collapse:collapse;" align="center" cellpadding="2px" width="100%">
<tr class="headings">
	 <td colspan="5" align="center"><h3>LAPORAN REALISASI PAD KOTA PAYAKUMBUH </h3></td>
</tr> 
<tr class="headings">
	 <td colspan="5" align="center"><h3>TANGGAL: <?php echo date('d',strtotime($tgl_awal))." ".$bulan[date('n',strtotime($tgl_awal))]." ".date('Y',strtotime($tgl_awal)); ?>&nbsp;s/d&nbsp;<?php echo date('d',strtotime($tgl_akhir))." ".$bulan[date('n',strtotime($tgl_akhir))]." ".date('Y',strtotime($tgl_akhir)); ?></h3></td>
</tr>                                       
<tr class="headings"><th width="1%">KODE REK</th><th>JENIS PAD</th><th>TARGET</th><th>REALISASI</th><th>PERSENTASE</th></tr>                                    
<tbody>                                                                                                                               								                                                                                                                      										
<?php
$no=0;

while ($row = pg_fetch_array($query_obj))
	{
	$no++;
$kd_pad=$row['kd_pad'];
//HITUNG TOTAL  JENIS
$query_jenis_ret_sub_b= pg_query("select sum(b.target) as target_b from retribusi.jenis_pendapatan A LEFT JOIN retribusi.sub_anak_detail B ON A.kd_pad=B.kd_pad  where A.kd_pad='$kd_pad'") or die('Query failed: ' . pg_last_error());
$row_j_ret_sub_b = pg_fetch_array($query_jenis_ret_sub_b);
$persentasi_sub_b=round($row['jj']/$row_j_ret_sub_b['target_b'] * 100,2);
?>
<tr class="style1"><td><b><?php echo $row['rek_apbd']?></b></td><td ><b><?php echo $row['nama_pad']; ?></b></td><td align="right"><b><?php echo number_format($row_j_ret_sub_b['target_b']);?></b></td><td align="right"><b><?php echo number_format($row['jj']);?></b></td><td align="center"><b><?php echo $persentasi_sub_b;?></b></td></tr>
<?php
//HITUNG TARGET JENIS
$query_ret= pg_query("select A.nama_retribusi,A.rek_apbd,sum(b.jumlah) as jumlah,B.kd_ret from retribusi.jenis_retribusi A left join retribusi.retribusi B on A.kd_opd=B.kd_opd AND A.kd_ret=B.kd_ret and A.kd_pad=B.kd_pad where tgl_setor between '$tgl_awal' AND '$tgl_akhir' AND A.kd_pad='$kd_pad' GROUP BY A.nama_retribusi,A.rek_apbd,B.kd_ret ORDER BY B.kd_ret ASC") or die('Query failed: ' . pg_last_error());
while ($row_ret = pg_fetch_array($query_ret))
{
$kd_ret=$row_ret['kd_ret'];
//HITUNG TOTAL  JENIS
$query_jenis_ret_sub_a= pg_query("select sum(b.target) as target_a from retribusi.jenis_retribusi A LEFT JOIN retribusi.sub_anak_detail B ON A.kd_opd=B.kd_opd AND A.kd_ret=B.kd_ret  AND A.kd_opd=B.kd_opd where A.kd_ret='$kd_ret' AND A.kd_pad='$kd_pad'") or die('Query failed: ' . pg_last_error());
$row_j_ret_sub_a = pg_fetch_array($query_jenis_ret_sub_a);
$persentasi_sub_a=round($row_ret['jumlah']/$row_j_ret_sub_a['target_a'] * 100,2);
?>

<tr class="even pointer"><td><b><?php echo $row_ret['rek_apbd']; ?></b></td><td class=" ">&nbsp;<b><?php echo $row_ret['nama_retribusi']; ?></b></td><td align="right"><b><?php echo number_format($row_j_ret_sub_a['target_a']);?></b></td><td align="right"><b><?php echo number_format($row_ret['jumlah']);?></b></td><td align="center"><b><?php echo $persentasi_sub_a;?></b></td></tr>


<?php
//HITUNG TARGET RINCI
$query_rinci_ret= pg_query("select A.nama_sub_retribusi,A.rek_apbd,sum(b.jumlah) as jumlah,A.kd_sub_ret from retribusi.sub_jenis_retribusi A left join retribusi.retribusi B on A.kd_opd=B.kd_opd AND A.kd_ret=B.kd_ret AND A.kd_sub_ret=B.kd_sub_ret AND A.kd_pad=B.kd_pad where B.tgl_setor between '$tgl_awal' AND '$tgl_akhir' AND A.kd_ret='$kd_ret' AND A.kd_pad='$kd_pad' GROUP BY A.nama_sub_retribusi,A.rek_apbd,A.kd_sub_ret ORDER BY A.kd_sub_ret ASC") or die('Query failed: ' . pg_last_error());
while ($row_rinci_ret = pg_fetch_array($query_rinci_ret))
{
$kd_sub_ret=$row_rinci_ret['kd_sub_ret'];
//HITUNG TOTAL  JENIS
$query_rinci_ret_sub_a= pg_query("select sum(b.target) as target_a from retribusi.sub_jenis_retribusi A LEFT JOIN retribusi.sub_anak_detail B ON A.kd_opd=B.kd_opd AND A.kd_ret=B.kd_ret  AND A.kd_sub_ret=B.kd_sub_ret AND A.kd_pad=B.kd_pad where A.kd_sub_ret='$kd_sub_ret' AND  A.kd_ret='$kd_ret' AND A.kd_pad='$kd_pad'") or die('Query failed: ' . pg_last_error());
$row_r_ret_sub_a = pg_fetch_array($query_rinci_ret_sub_a);
$persentasi_rinci_a=round($row_rinci_ret['jumlah']/$row_r_ret_sub_a['target_a'] * 100,2);
?>

<tr class="even pointer"><td><b><?php echo $row_rinci_ret['rek_apbd']; ?></b></td><td class=" ">&nbsp;<b><?php echo $row_rinci_ret['nama_sub_retribusi']; ?></b></td><td align="right"><b><?php echo number_format($row_r_ret_sub_a['target_a']);?></b></td><td align="right"><b><?php echo number_format($row_rinci_ret['jumlah']);?></b></td><td align="center"><b><?php echo $persentasi_sub_a;?></b></td></tr>




<?php
//HITUNG TOTAL DETAIL
$query_rinci_retri= pg_query("select A.nama_sub_rinci,A.kd_rinci_ret,sum(B.jumlah) as jumjum,A.rek_apbd from retribusi.sub_rinci_retribusi A LEFT JOIN retribusi.retribusi B on A.kd_opd=B.kd_opd AND A.kd_ret=B.kd_ret AND A.kd_sub_ret=B.kd_sub_ret AND A.kd_rinci_ret=B.kd_rinci_ret AND A.kd_pad=B.kd_pad where tgl_setor between '$tgl_awal' AND '$tgl_akhir' AND A.kd_ret='$kd_ret' AND A.kd_sub_ret='$kd_sub_ret' and A.kd_pad='$kd_pad' GROUP BY A.nama_sub_rinci,A.kd_rinci_ret,A.rek_apbd ORDER BY A.kd_rinci_ret ASC") or die('Query failed: ' . pg_last_error());
while ($row_r_ret = pg_fetch_array($query_rinci_retri))
{
$kd_rinci_ret=$row_r_ret['kd_rinci_ret'];

//HITUNG TOTAL SUB RINCI
$query_jenis_ret_sub= pg_query("select sum(b.target) as jlh from retribusi.sub_rinci_retribusi A LEFT JOIN retribusi.sub_anak_detail B ON A.kd_sub_ret=B.kd_sub_ret AND A.kd_ret=B.kd_ret AND A.kd_opd=B.kd_opd AND A.kd_pad=B.kd_pad AND A.kd_rinci_ret=B.kd_rinci_ret where B.kd_ret='$kd_ret' and B.kd_sub_ret='$kd_sub_ret' and B.kd_rinci_ret='$kd_rinci_ret' AND B.kd_pad='$kd_pad' ") or die('Query failed: ' . pg_last_error());
$row_r_ret_sub = pg_fetch_array($query_jenis_ret_sub);
$persentasi_sub_r=round($row_r_ret['jumjum']/$row_r_ret_sub['jlh'] * 100,2);
?>
<tr class="even pointer"><td><?php echo $row_r_ret['rek_apbd']; ?></td><td class=" "><i><b><?php echo $row_r_ret['nama_sub_rinci']; ?></b></i></td><td align="right"><i><b><?php echo number_format($row_r_ret_sub['jlh']);?></b></i></td><td align="right"><i><b><?php echo number_format($row_r_ret['jumjum']);?></b></i></td><td align="center"><i><b><?php echo $persentasi_sub_r;?></b></i></td></tr>

<?php
//HITUNG TOTAL DETAIL
$query_detail_retri=pg_query("select A.nama_sub_detail,A.kd_detail_ret,sum(B.jumlah) as jumdet,A.rek_apbd from retribusi.sub_detail_retribusi A LEFT JOIN retribusi.retribusi B on A.kd_pad=B.kd_pad AND A.kd_ret=B.kd_ret AND A.kd_sub_ret=B.kd_sub_ret AND A.kd_rinci_ret=B.kd_rinci_ret AND A.kd_detail_ret=B.kd_detail_ret AND A.kd_opd=B.kd_opd where tgl_setor between '$tgl_awal' AND '$tgl_akhir' AND A.kd_ret='$kd_ret' AND A.kd_sub_ret='$kd_sub_ret' and A.kd_pad='$kd_pad' AND A.kd_rinci_ret='$kd_rinci_ret' GROUP BY A.nama_sub_detail,A.kd_detail_ret,A.rek_apbd,A.kd_rinci_ret ORDER BY A.kd_detail_ret ASC") or die('Query failed: ' . pg_last_error());
while ($row_d_ret = pg_fetch_array($query_detail_retri))
{
$kd_detail_ret=$row_d_ret['kd_detail_ret'];
//HITUNG TOTAL SUB RINCI
$query_detail_ret_sub= pg_query("select sum(b.target) as jlh from retribusi.sub_detail_retribusi A LEFT JOIN retribusi.sub_anak_detail B ON A.kd_sub_ret=B.kd_sub_ret AND A.kd_ret=B.kd_ret AND A.kd_detail_ret=B.kd_detail_ret AND A.kd_rinci_ret=B.kd_rinci_ret AND A.kd_pad=B.kd_pad AND A.kd_opd=B.kd_opd where A.kd_ret='$kd_ret' and A.kd_sub_ret='$kd_sub_ret' and A.kd_rinci_ret='$kd_rinci_ret' AND A.kd_pad='$kd_pad' AND A.kd_detail_ret='$kd_detail_ret' ") or die('Query failed: ' . pg_last_error());
$row_d_ret_sub = pg_fetch_array($query_detail_ret_sub);
$persentasi_sub_d=round($row_d_ret['jumdet']/$row_d_ret_sub['jlh'] * 100,2);
?>
<tr class="even pointer"><td><?php echo $row_d_ret['rek_apbd']; ?></td><td class=" "><i><?php echo $row_d_ret['nama_sub_detail']; ?></i></td><td align="right"><i><?php echo number_format($row_d_ret_sub['jlh']);?></i></td><td align="right"><i><?php echo number_format($row_d_ret['jumdet']);?></i></td><td align="center"><?php echo $persentasi_sub_d;?></td></tr>

<?php 
}
}
}
}
?>
<tr class="even pointer"><td>&nbsp;</td><td>&nbsp;</td></tr>
<?php

}
//TOTAL TARGET
$query_tot=pg_query("select SUM(target) as total from retribusi.sub_anak_detail where tahun='2021' and status='1'")or die('Query failed: ' . pg_last_error());
$row_t=pg_fetch_array($query_tot);
$total_pad=$row_t['total'];
//target dana perimbangan
/*$query_tot_s=pg_query("select SUM(target) as total from retribusi.sub_rinci_retribusi where kd_ret ='06' OR kd_ret='07' OR kd_ret='04'")or die('Query failed: ' . pg_last_error());
$row_s=pg_fetch_array($query_tot_s);
$total_dana=$row_s['total'];
//target pendapatan daerah lain
$query_tot_p=pg_query("select SUM(target) as total from retribusi.sub_rinci_retribusi where kd_ret ='08' OR kd_ret='09' OR kd_ret='12'")or die('Query failed: ' . pg_last_error());
$row_p=pg_fetch_array($query_tot_p);
$total_lain=$row_p['total'];*/
//TOTAL REALISASI

$query_real=pg_query("select SUM(jumlah) as realisasi from retribusi.retribusi where tgl_setor between '$tgl_awal' AND '$tgl_akhir'")or die('Query failed: ' . pg_last_error());
$row_r=pg_fetch_array($query_real);
$realisasi=$row_r['realisasi'];
$persen_pad=round($realisasi/$total_pad * 100,2);
//$total=$total_pad+$total_dana+$total_lain;*/
?>
<tr class="even pointer">
<td class="  style1">&nbsp;</td><td><b>TOTAL PENDAPATAN DAERAH</b></td>
<td class=" " align="center" ><strong><?php echo number_format($total_pad); ?></strong></td><td class=" " align="center" ><strong><?php echo number_format($realisasi); ?></strong></td><td align="center"><b><?php echo $persen_pad;?></b></td>
</tr>

</tbody>

</table>
                                <table class="style1">
<tr><td>&nbsp;</td></tr>
<tr><td><span class="style3">Dicetak Oleh:</span></td>
</tr>

<tr><td><?php

$nama = pg_escape_string($_GET['nama']);
 echo $nama; ?></td></tr>
 <tr><td><?php
$nip = pg_escape_string($_GET['nip']);

 echo "Nip. &nbsp;$nip "; ?></td></tr>
 <tr><td><?php
$tg = date("d-m-Y h:i:s");

 echo "$tg"; ?></td></tr></table>   
 <?php // } ?>                    