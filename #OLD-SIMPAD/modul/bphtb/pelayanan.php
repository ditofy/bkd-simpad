<?php

/*if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/func_terbilang.inc.php";*/

$base_dir = $_GET['bdir'];

include_once $base_dir."inc/db.inc.php";
include_once $base_dir."inc/func_terbilang.inc.php";
include_once $base_dir."inc/schema.php";
include_once $base_dir."inc/fungsi_indotgl.php";
include_once $base_dir."phpqrcode/qrlib.php";

$no_pel = pg_escape_string($_GET['no_pel']);
list($thn,$no_urut) = explode(".", $no_pel);

$schema = "bphtb";
$sql = "SELECT A.*,B.*,C.* FROM $schema.pelayanan A INNER JOIN public.wp B ON
B.nik=A.nik 
INNER JOIN public.ppat C ON
A.id_ppat=C.id
WHERE A.tahun='$thn' AND A.no_urut_p='$no_urut'";
$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
$n_row = pg_num_rows($tampil);
if($n_row <= 0) {
	echo "NO.PELAYANAN BPHTB TIDAK DITEMUKAN, SILAHKAN PERIKSA KEMBALI NO. SSPD";
	pg_free_result($tampil);
	pg_close($dbconn);
	exit;
}
$x = 1;

?>
<style type="text/css">


.myTbl {
  border: 2px solid #000;
  border-collapse: collapse;
  border-style:inset;
  
}

.myTbl th, .myTbl td {
  white-space: nowrap;
  border-right: 1px solid #000;
  border-bottom: 1px solid #000;
  padding: 2px;
}
.nomyTbl {
border:1px solid #FFF;
	font-family:"Verdana";
	font-size:12px;
}
.nomyTbl th, .nomyTbl td {
border:1px solid #FFF;

}
.font8 {
	font-family:"Verdana";
	font-size:8px;
}
.font10 {
	font-family:"Verdana";
	font-size:10px;
}
.font11 {
	font-family:"Verdana";
	font-size:11px;
}
.font12 {
	font-family:"Verdana";
	font-size:12px;
}
.font14 {
	font-family:"Verdana";
	font-size:14px;
}
.font16 {
	font-family:"Verdana";
	font-size:16px;
}
.font18 {
	font-family:"Verdana";
	font-size:18px;
}
.font20 {
	font-family:"Verdana";
	font-size:20px;
}
.style4 {font-family: Geneva, Arial, Helvetica, sans-serif}
</style>
<?php
 while ($row = pg_fetch_array($tampil)) { 
 $luas_njop_bumi=$row['luas_bumi_trk'];
 $luas_njop_bng=$row['luas_bng_trk'];
 $njop_bumi=$row['njop_bumi'];
 $njop_bng=$row['njop_bng'];
 $hasil_bumi=$luas_njop_bumi*$njop_bumi;
 $hasil_bng=$luas_njop_bng*$njop_bng;
 $t_njop=$hasil_bumi+$hasil_bng;
 $persen_tarif = $row['t_pengurangan'] * 100;
 $id_trk=$row['id_transaksi'];
 $nip=$row['nip_p'];
 $qrcode=$row['qrcode_p'];
 
 
?>
<div style="width:215mm;height:400mm; margin-left:70px; border:#000000 solid outset">
  <table width="100%" border="0" class="">
  <tr>
    <td align="center" width="3%">
<img src="../../images/payakumbuh.png" width="60"></td>
    <td align="left">
	<span class="font14"><b>FORMULIR PELAYANAN WAJIB PAJAK BPHTB</b></span><br/>
	<span class="font14">BADAN KEUANGAN DAERAH KOTA PAYAKUMBUH</span><br/>
	<span class="font14">Jln.Veteran Komplek Balaikota Payakumbuh 
	</td>
  </tr></table>
  <table class="">
  <tr><td colspan="3">-----------------------------------------------------------------------------------------------------------------------------------</td></tr>
  <tr><td width="20%" align="right"><span class="font14">Nomor Pelayanan</span></td><td colspan="2"><b><span class="font14">:&nbsp;<?php echo $no_pel;?></span></b></td></tr>
  <tr><td align="right"><span class="font14">Tanggal Pelayanan</span></td><td><span class="font14">:&nbsp;<?php echo date('d',strtotime($row['tgl_verifikasi']))." ".$bulan[date('n',strtotime($row['tgl_verifikasi']))]." ".date('Y',strtotime($row['tgl_verifikasi'])); ?></span></td></tr>
   <tr><td align="right"><span class="font14">Tanggal Selesai (Perkiraan)</span></td><td width="1%"><span class="font14">:&nbsp;<?php echo date('d',strtotime($row['tgl_selesai']))." ".$bulan[date('n',strtotime($row['tgl_selesai']))]." ".date('Y',strtotime($row['tgl_selesai'])); ?></span></td></tr>
<tr height="25%"><td>&nbsp;</td></tr>
<tr>
<td colspan="4" ><span class="font12"><b>DATA WAJIB PAJAK DAN OBJEK PAJAK </b></span></td>
</tr>

<tr>
<td colspan="4">

<table class="nomyTbl" width="100%">
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">1. Nama Wajib Pajak</td><td>&nbsp;:</td><td><?php echo $row['nama'];?></td><td>7. ALAMAT OP</td><td>&nbsp;:</td><td align="left"><?php echo $row['alamat_op']; ?> &nbsp; <?php echo $row['nomor_op']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">2. NIK</td><td>&nbsp;:</td><td><?php echo $row['nik']; ?></td><td>8. KELURAHAN</td><td>&nbsp;:</td><td align="left"><?php echo $row['kelurahan_op']; ?>&nbsp;RT/RW&nbsp;:&nbsp;<?php echo $row['rt_op'];?>&nbsp;/&nbsp;<?php echo $row['rw_op'];?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">3. ALAMAT WP</td><td>&nbsp;:</td><td align="left"><?php echo $row['alamat']; ?></td><td>9. KECAMATAN</td><td>&nbsp;:</td><td align="left"><?php echo $row['kecamatan_op']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">4. NOP PBB</td><td>&nbsp;:</td><td align="left"><?php echo $row['kd_propinsi'].".".$row['kd_dati2'].".".$row['kd_kecamatan'].".".$row['kd_kelurahan'].".".$row['kd_blok'].".".$row['no_urut'].".".$row['kd_jns_op']; ?></td><td>10. JENIS TRANSAKSI</td><td>&nbsp;:</td><td align="left"><?php 
$trs="SELECT nm_transaksi FROM public.transaksi WHERE id='$id_trk'";
$trks = pg_query($trs) or die('Query failed: ' . pg_last_error());
$tr=pg_fetch_array($trks);

echo strtoupper($tr['nm_transaksi']);

//pg_free_result($tr); ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">5. NAMA SPPT</td><td>&nbsp;:</td><td align="left"><?php echo $row['nama_sppt']; ?></td><td width="20%">11. KETERANGAN</td><td>&nbsp;:</td><td align="left"><?php echo $row['ket']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">6. PPAT</td><td>&nbsp;:</td><td align="left"><?php echo $row['nama_ppat']; ?></td><td></td><td>&nbsp;</td><td align="left"></td></tr>

</table></td>
</tr>


<tr>
<td colspan="4" ><span class="font12"><b>DOKUMEN DILAMPIRKAN </b></span></td>
</tr>
<tr>
<td colspan="4">
<table class="nomyTbl" width="100%">
<?php 
$syarat=$row['syarat'];

$query_s = "SELECT * FROM bphtb.persyaratan ORDER BY id_p ASC";
$result_s = pg_query($query_s) or die('Query failed: ' . pg_last_error());
$no=1;

while($row_s= pg_fetch_array($result_s)){

$idc=$row_s['id_p'];
 ?>
<tr><td width><?php echo $no; ?></td><td><?php if (strpos($syarat,$idc) !== false) { echo "<input type=\"checkbox\" name=\"menuid\" value=\"".$ids."\" checked=\"checked\">"; } else { echo "<input type=\"checkbox\" name=\"menuid\" value=\"".$ids."\">"; } ?></td><td colspan="2"><?php echo $row_s['syarat_s'];?></td></tr>
<?php  $no++;} ?>
<tr><td colspan="4">Petugas Penerima Berkas</td><td>:</td><td><b>
<?php
$query_n = "SELECT nama FROM public.user where nip='$nip' ";
$result_n = pg_query($query_n) or die('Query failed: ' . pg_last_error());
$row_n=pg_fetch_array($result_n);
echo $row_n['nama'];

pg_free_result($result_n);
 ?>
</b></td></tr><tr> <td colspan="7">--------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>
	</table>
<table class="nomyTbl">
<tr><td width="35"></td>
</tr><td></td><td width="6"></td>
<td width="496"><b>Disposisi :</b></td>
</tr>
<tr><td><?php echo "<img src=\"../../images/box.png\">"; ?></td><td>&nbsp;</td><td>Kasubag TU UPTB Pajak Daerah, agar berkas permohonan ini :</td></tr>
<tr><td></td><td>&nbsp;</td><td>- Diteruskan Kepada Kabid Pendapatan </td><td width="8">:&nbsp;</td>
<td><?php echo "<img src=\"../../images/box.png\">"; ?></td>
</tr>
<tr><td></td><td>&nbsp;</td><td>- Dikembalikan kepada Petugas Pelayanan</td><td>:</td><td width="371"><?php echo "<img src=\"../../images/box.png\">"; ?></td>
</tr>
<tr><td></td><td>&nbsp;</td><td>- .....................................</td><td></td><td><?php echo "<img src=\"../../images/box.png\">"; ?></td></tr>
<tr height="5%"><td></td><td>&nbsp;</td><td></td><td></td><td></td></tr>
<tr><td><?php echo "<img src=\"../../images/box.png\">"; ?></td><td>&nbsp;</td><td>Yth Kabid Pendapatan :</td></tr>	
<tr><td></td><td>&nbsp;</td><td>Berkas permohonan sudah divalidasi, data sudah lengkap dan benar.</td><td>:</td><td></td></tr>

<tr><td colspan="6" align="center">Payakumbuh .......................</td></tr>
<tr><td colspan="6" align="center"><b>KEPALA UPTB PAJAK DAERAH</b></td></tr>
<tr height="50"><td colspan="6"></td></tr>
<tr><td align="center" colspan="5"><b>PUTRI JAMILAH, SE, Akt, M.Si.</b></td></tr>
<tr><td align="center" colspan="5"><b>Nip. 19830222 200901 2 002</b></td></tr>
<tr> <td colspan="7">--------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>
</table>

<table class="nomyTbl">
<tr><td></td></tr><td></td><td></td><td><b>Kepada Saudara :</b></td><td></td><td>&nbsp;</td><td colspan="2"><b>Disposisi :</b></td></tr>
<tr><td><?php echo "<img src=\"../../images/box.png\">"; ?></td><td>&nbsp;</td><td>KEPALA UPTB PAJAK DAERAH</td><td>&nbsp;</td><td><?php echo "<img src=\"../../images/box.png\">"; ?></td><td>&nbsp;</td><td>Hubungi WP untuk melengkapi Berkas Permohonan</td></tr>
<tr><td><?php echo "<img src=\"../../images/box.png\">"; ?></td><td>&nbsp;</td><td>KASUBBID PENDATAAN DAN PENETAPAN</td><td></td><td><?php echo "<img src=\"../../images/box.png\">"; ?></td><td>&nbsp;</td><td>Buatkan Konsep SK</td></tr>
<tr><td><?php echo "<img src=\"../../images/box.png\">"; ?></td><td>&nbsp;</td><td>KASUBBID PENAGIHAN DAN KEBERATAN</td><td></td><td><?php echo "<img src=\"../../images/box.png\">"; ?></td><td>&nbsp;</td><td>Proses sesuai aturan yang berlaku</td></tr>
<tr><td><?php echo "<img src=\"../../images/box.png\">"; ?></td><td>&nbsp;</td><td>KASUBBID KOORDINASI PENERIMAAN LAINNYA</td><td></td><td><?php echo "<img src=\"../../images/box.png\">"; ?></td><td>&nbsp;</td><td>Bicarakan dengan saya</td></tr>
<tr><td></td><td>&nbsp;</td><td></td><td>&nbsp;</td><td><?php echo "<img src=\"../../images/box.png\">"; ?></td><td>&nbsp;</td><td>Teruskan Kepada :</td></tr>
<tr></tr>
<tr><td></td><td>&nbsp;</td><td></td><td>&nbsp;</td><td><?php echo "<img src=\"../../images/box.png\">"; ?></td><td>&nbsp;</td><td>...........................</td></tr>
<tr><td colspan="7" align="center">Payakumbuh .......................</td></tr>
<tr><td width="20"></td><td width="20"></td><td colspan="5" align="center"><b>KEPALA BIDANG PENDAPATAN</b></td></tr>
<tr height="50"><td colspan="7"></td></tr>
<tr><td width="20"></td><td width="20"><td align="center" colspan="7"><b>NOVALIZA, SE, M.Si</b></td></tr>
<tr><td width="20"></td><td width="20"><td align="center" colspan="7"><b>Nip. 19781119 200701 2 002</b></td></tr>
<tr> <td colspan="7">--------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>
</table>	
	</td></tr>
<tr height="40%"><td>&nbsp;</td></tr>

 </table>
 <br/><br/><br/><br/>
 <table>

 <tr height="20"><td>&nbsp;</td></tr>
 <tr><td align="center" width="3%">
<img src="../../images/payakumbuh.png" width="30"></td>
    <td align="left">
	<span class="font14"><b>TANDA TERIMA BERKAS PELAYANAN BPHTB</b></span><br/>
	<span class="font12">BADAN KEUANGAN DAERAH KOTA PAYAKUMBUH</span><br/>
	<span class="font12">Jln.Veteran Komplek Balaikota Payakumbuh 
	
	</td>
  </tr><tr> <td colspan="7">---------------------------------------------------------------------------------------------------------------------------------</td></tr></table>
  
  <table class="">
  <tr><td width="20%" align="right"><span class="font14">Nomor Pelayanan</span></td><td colspan="2"><b><span class="font14">:&nbsp;<?php echo $no_pel;?></span></b></td></tr>
  <tr><td align="right"><span class="font14">Tanggal Pelayanan</span></td><td><span class="font14">:&nbsp;<?php echo date('d',strtotime($row['tgl_verifikasi']))." ".$bulan[date('n',strtotime($row['tgl_verifikasi']))]." ".date('Y',strtotime($row['tgl_verifikasi'])); ?></span></td></tr>
   <tr><td align="right"><span class="font14">Tanggal Selesai (Perkiraan)</span></td><td width="1%"><span class="font14">:&nbsp;<?php echo date('d',strtotime($row['tgl_selesai']))." ".$bulan[date('n',strtotime($row['tgl_selesai']))]." ".date('Y',strtotime($row['tgl_selesai'])); ?></span></td></tr>
<tr height="25%"><td>&nbsp;</td></tr>
<tr>
<td colspan="4" ><span class="font12">DATA WAJIB PAJAK DAN OBJEK PAJAK </span></td>
</tr>
<tr>
<td colspan="4">
<table class="nomyTbl" width="100%">

<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">1. Nama Wajib Pajak</td><td>&nbsp;:</td><td><?php echo $row['nama'];?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">2. NIK</td><td>&nbsp;:</td><td align="left"><?php echo $row['nik']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">3. ALAMAT WP</td><td>&nbsp;:</td><td align="left"><?php echo $row['alamat']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">4. NOP PBB</td><td>&nbsp;:</td><td align="left"><?php echo $row['kd_propinsi'].".".$row['kd_dati2'].".".$row['kd_kecamatan'].".".$row['kd_kelurahan'].".".$row['kd_blok'].".".$row['no_urut'].".".$row['kd_jns_op']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">5. NAMA SPPT</td><td>&nbsp;:</td><td align="left"><?php echo $row['nama_sppt']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">6. ALAMAT OP</td><td>&nbsp;:</td><td align="left"><?php echo $row['alamat_op']; ?> &nbsp; <?php echo $row['nomor_op']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">7. KELURAHAN</td><td>&nbsp;:</td><td align="left"><?php echo $row['kelurahan_op']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RT/RW&nbsp;:&nbsp;<?php echo $row['rt_op'];?>&nbsp;/&nbsp;<?php echo $row['rw_op'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">8. KECAMATAN</td><td>&nbsp;:</td><td align="left"><?php echo $row['kecamatan_op']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;KOTA&nbsp;PAYAKUMBUH</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">9. KETERANGAN</td><td>&nbsp;:</td><td align="left"><?php echo $row['ket']; ?></td></tr>

<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">10. JENIS TRANSAKSI</td><td>&nbsp;:</td><td align="left"><?php 
$trs="SELECT nm_transaksi FROM public.transaksi WHERE id='$id_trk'";
$trks = pg_query($trs) or die('Query failed: ' . pg_last_error());
$tr=pg_fetch_array($trks);

echo strtoupper($tr['nm_transaksi']);

//pg_free_result($tr); ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">11. NAMA PPAT</td><td>&nbsp;:</td><td align="left"><?php echo $row['nama_ppat']; ?></td></tr>
</table></td>
</tr>
<tr height="20%"><td></td></tr>


	</table></td></tr>


</tr>
 </table><br/>
<div style="margin-right:156px;"> <table class="font14" border="0" align="right" ><tr><td></td><td></td><td></td><td></td><td align="center">Petugas Penerima Berkas</td></tr>
 <tr height="35"><td><?php //QRcode::png("$qrcode","image.png","L",2,2);echo "<img src='image.png'/>";?></td><td width="60">&nbsp;</td><td align="center">&nbsp;</td></tr>
 <?php 
$query_p = "SELECT B.nip,B.nama FROM bphtb.pelayanan A inner join public.user B on A.nip_p=B.nip where A.tahun='$thn' and a.no_urut_p='$no_urut'";
$result_p = pg_query($query_p) or die('Query failed: ' . pg_last_error());
$nmp=pg_fetch_array($result_p);
$ni_p=$nmp['nip'];
$nm_p=$nmp['nama'];

?>
 <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td >&nbsp;</td><td align="center"><b><?php echo $nm_p; ?></b></td></tr>
 <tr><td></td><td></td><td></td><td></td><td align="center"><b>NIP.&nbsp;<?php echo $ni_p; ?></b></td></tr></table>
 <table class="font14" >
 <tr style="margin-top:-100px;"><td><img src="../../images/skm.jpeg" /><br/>
<b>SILAHKAN ISI SURVEY KEPUASAN MASYARAKAT (SKM) BADAN KEUANGAN DAERAH DENGAN SCAN QR DIATAS..!!</b></td></tr>
</table>
 
 </div>
  
<?php

if($x != $n_row) {
	echo "<div style=\"page-break-before:always;\"></div>";
	$x++;
}

}
//pg_free_result($tampil);
pg_close($dbconn);


?>