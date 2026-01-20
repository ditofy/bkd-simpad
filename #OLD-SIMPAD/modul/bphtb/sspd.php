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
include_once $base_dir."inc/va.inc.php";

$no_sspd = pg_escape_string($_GET['no_sspd']);
list($jns_surat,$thn_pajak,$bln_pajak,$kd_obj_pajak,$no_urut_sspd) = explode(".", $no_sspd);
$schema = "bphtb";
$sql = "SELECT A.*,B.*,C.*,D.* FROM $schema.pelayanan A INNER JOIN public.wp B ON
B.nik=A.nik 
INNER JOIN $schema.sspd C ON
C.tahun_p=A.tahun AND
C.no_urut_p=A.no_urut_p
INNER JOIN public.ppat D ON
A.id_ppat=D.id
WHERE C.jns_surat='$jns_surat' AND C.thn_pajak='$thn_pajak' AND C.bln_pajak='$bln_pajak' AND C.kd_obj_pajak='$kd_obj_pajak' AND C.no_urut_surat='$no_urut_sspd'";
$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
$n_row = pg_num_rows($tampil);
if($n_row <= 0) {
	echo "NO. SSPD BPHTB TIDAK DITEMUKAN, SILAHKAN PERIKSA KEMBALI NO. SSPD";
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
.style2 {font-size: 7px}
.style3 {color: #FFFFFF}
</style>
<?php
 while ($row = pg_fetch_array($tampil)) { 
 $luas_njop_bumi=$row['luas_bumi_trk'];
 $luas_njop_bng=$row['luas_bng_trk'];
 $njop_bumi=$row['njop_bumi'];
 $njop_bng=$row['njop_bng'];
 $luas_bumi=$row['luas_bumi'];
 $nj_op_bumi=$njop_bumi/$luas_bumi;
 $luas_bng=$row['luas_bng'];
 $nj_op_bng=$njop_bng/$luas_bng;
 $hasil_bumi=$luas_njop_bumi*$nj_op_bumi;
 $hasil_bng=$luas_njop_bng*$nj_op_bng;
 $tot_njop = $hasil_bumi + $hasil_bng;
 $persen_tarif = $row['t_pengurangan'] * 100;
 $qrcode=$row['qrcode'];
?>
<div style="width:215mm;height:300mm;">
  <table width="100%" border="0" class="myTbl">
  <tr>
    <td rowspan="2" align="center">
<img src="../../images/payakumbuh.png" width="60">
<p><pre class="font10" style="line-height: 1.6em;">KOTA PAYAKUMBUH</pre></p></td>
    <td align="center">
<pre>
<strong class="font16">SURAT SETORAN PAJAK DAERAH</strong>
<strong class="font18">BEA PEROLEHAN HAK ATAS TANAH DAN BANGUNAN</strong>
<strong class="font20">(SSPD - BPHTB)</strong></pre>	
  </td>
<td rowspan="2" align="center">
<pre class="font14">
<strong>No. SSPD</strong>
<?php echo $row['jns_surat'].".".$row['thn_pajak'].".".$row['bln_pajak'].".".$row['kd_obj_pajak'].".".$row['no_urut_surat']; ?></pre>
<pre>
</pre>	</td>
  </tr>
  <tr>
    <td align="center">&nbsp;<span class="font14">BERFUNGSI SEBAGAI SURAT PEMBERITAHUAN OBJEK PAJAK<br/> </span>
	<span class="font14">PAJAK BUMI DAN BANGUNAN (SPOP PBB)</strong>
   </td>
  </tr>
  <tr>
    <td colspan="4" ><span class="font12">BADAN KEUANGAN DAERAH : ________________________ </span></td>
    </tr>
<tr>
<td colspan="4" ><span class="font12">PERHATIAN : Bacalah petunjuk pengisian pada halaman belakang lembar ini terlebih dahulu </span></td>
</tr>

<tr>
<td colspan="4">
<table class="nomyTbl" width="100%">
<tr><td width="1%">A.</td><td>&nbsp;</td><td width="20%">1. Nama Wajib Pajak</td><td>&nbsp;:</td><td><?php echo $row['nama'];?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">2. NPWPD</td><td>&nbsp;:</td><td align="left"><?php echo $row['kd_provinsi'].".".$row['kd_kota'].".".$row['kd_jns'].".".$row['no_reg']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">3. Alamat Wajib Pajak</td><td>&nbsp;:</td><td align="left"><?php echo $row['alamat']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">4. Kelurahan/Desa</td><td>&nbsp;:</td><td align="left"><?php echo $row['kelurahan']; ?>&nbsp;&nbsp;&nbsp;5. RT/RW&nbsp;:&nbsp;______&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6. Kecamatan&nbsp;:&nbsp;&nbsp;<?php echo $row['kecamatan']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">7. Kabupaten/Kota</td><td>&nbsp;:</td><td align="left"><?php echo $row['kota']; ?></td></tr>
</table>
</td>
</tr>
<tr>
<td colspan="4">
<table width="100%" class="nomyTbl">
<tr><td width="1%">B.</td><td>&nbsp;</td><td width="20%">1. Nomor Objek Pajak (NOP) PBB</td><td>&nbsp;:</td><td><?php echo $row['kd_propinsi'].".".$row['kd_dati2'].".".$row['kd_kecamatan'].".".$row['kd_kelurahan'].".".$row['kd_blok'].".".$row['no_urut'].".".$row['kd_jns_op']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">2. Letak tanah dan atau bangunan</td><td>&nbsp;:</td><td align="left"><?php echo $row['alamat_op']; ?> &nbsp; <?php echo $row['nomor_op']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">3. Kelurahan</td><td>&nbsp;:</td><td align="left"><?php echo $row['kelurahan_op']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. RT/RW&nbsp;:&nbsp;<?php echo $row['rt_op'];?>&nbsp;/&nbsp;<?php echo $row['rw_op'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">5. Kecamatan</td><td>&nbsp;:</td><td align="left"><?php echo $row['kecamatan_op']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6. Kabupaten/Kota&nbsp;:&nbsp;Payakumbuh</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">Penghitungan NJOP PBB :</td><td>&nbsp;:</td><td align="left"></td></tr></table></td></tr>
<tr><td colspan="3">
<table class="font10 myTbl" width="90%" align="center"><tr><td align="center" width="40%"><b>Uraian</b></td><td colspan="2" align="center" width="10%"><b>Luas</b><span class="style2"><p>(Diisi luas tanah dan atau bangunan</p><p> yang haknya diperoleh)</p></span></td>
<td colspan="2" align="center" width="10%"><b>NJOP PBB / m2</b><span class="style2"><p>(Diisi berdasarkan SPPT PBB tahun</p><p> terjadinya perolehan hak/tahun)</span></td><td colspan="2" width="35%" align="center"><b>Luas x NJOP PBB / m2</b></td></tr>
<tr><td>Tanah ( bumi )</td><td align="center">7</td><td width="10%" align="center"><?php echo $row['luas_bumi_trk'];?>&nbsp;m2</td><td align="center">9</td><td align="right">Rp.&nbsp;<?php echo number_format($nj_op_bumi);?></td><td align="center">11</td><td align="right">Rp.&nbsp;&nbsp;<?php echo number_format($hasil_bumi);?></td></tr><tr><td>Bangunan</td><td align="center">8</td><td width="10%" align="center"><?php echo $row['luas_bng_trk'];?>&nbsp;m2</td><td align="center">10</td><td align="right">Rp.&nbsp;<?php echo number_format($nj_op_bng);?></td><td align="center">12</td><td align="right">Rp.&nbsp;&nbsp;<?php echo number_format($hasil_bng);?></td></tr><tr><td colspan="5" align="right">NJOP PBB</td><td align="center">13</td><td align="right">Rp.&nbsp;&nbsp;<?php
if($luas_bng == 0 ){
echo number_format($hasil_bumi);
				}else
				{
echo number_format($tot_njop);
				}
?></td></tr>  

 </table></td></tr>
  <tr><td width="1%" colspan="4"><table width="100%" class="nomyTbl">
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">15. Jenis perolehan hak atas tanah dan atau bangunan</td><td>&nbsp;:</td><td><?php echo $row['id_transaksi'];?></td><td>14. Harga Transaksi / Nilai Pasar :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo number_format($row['harga_trk']);?></b></td><td align="left"></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">16. Nomor Sertifikat</td><td>&nbsp;:</td><td colspan="3"><?php echo $row['sertifikat'];?></td></tr>
</table></td></tr>
</td>
</tr>
<tr><td colspan="4">
<table class="nomyTbl"><tr><td width="1%">C.</td><td>&nbsp;</td><td>AKUMULASI NILAI PEROLEHAN HAK SEBELUMNYA</td><td style="width:290px;" align="right">Rp.</td><td align="right">&nbsp;<?php echo number_format($row['akumulasi']);?></td></tr></table>
</td></tr>
<tr><td colspan="4">
<table class="nomyTbl"><tr><td width="1%">D.</td><td>&nbsp;</td><td>PENGHITUNGAN BPHTB (Hanya diisi berdasarkan penghutungan Wajib Pajak)</td><td style="width:100px;" align="right"></td></tr></table>
</td></tr>
<tr><td colspan="4">
<table class="nomyTbl"><tr><td width="1%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;1. Nilai Perolehan Objek Pajak (NPOP) memperhatikan nilai pada B.13 dan B.14</td><td style="width:100px;" align="right">Rp.</td><td align="right">&nbsp;<?php echo number_format($row['npop']);?></td></tr>
<tr><td width="1%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;2. Nilai Perolehan Objek Pajak Tidak Kena Pajak (NPOPTKP)</td><td style="width:100px;" align="right">Rp.</td><td align="right">&nbsp;<?php echo number_format($row['npoptkp']);?></td></tr>
<tr><td width="1%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;3. Nilai Perolehan Objek Pajak Kena Pajak (NPOPKP)</td><td style="width:100px;" align="right">Rp.</td><td align="right">&nbsp;<?php 
if($row['npopkp'] == 0){
echo "NIHIL"; }
else{
echo number_format($row['npopkp']);}?></td></tr>
<tr><td width="1%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;4. Bea Perolehan hak atas Tanah dan Bangunan yang terutang</td><td style="width:100px;" align="right">Rp.</td><td align="right">&nbsp;<?php
if($row['npopkp'] == 0){
echo "NIHIL"; }
else{
 echo number_format($row['pokok_pajak_real']);}?></td></tr>
<tr><td width="1%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;5. Pengurangan<?php if ($row['t_pengurangan'] > 0 ){?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;<?php echo $persen_tarif; ?>&nbsp;%&nbsp;X&nbsp;<?php echo number_format($row['pokok_pajak_real']); ?>&nbsp;)</td><td style="width:100px;" align="right">Rp.</td><td align="right">&nbsp;<?php echo number_format($row['pokok_pajak']);?><?php }?></td></tr></table>
</td></tr>

<tr>
<td colspan="4">
<table class="nomyTbl" width="100%">
<tr><td width="1%">E.</td><td colspan="3">&nbsp;Jumlah Setoran Berdasarkan</td><td></td><td>&nbsp;:</td><td></td></tr>
<tr><td width="1%"></td><td>&nbsp;<?php echo "<img src=\"../../images/box.png\">"; ?>&nbsp;a. Penghitungan Wajib Pajak</td><td align="left"></td><td align="left"></td></tr>
<tr valign="top"><td width="1%"></td><td colspan="2">&nbsp;<?php echo "<img src=\"../../images/box.png\">"; ?>&nbsp;b. STPD BPHTB / SKPD KURANG BAYAR / SKPBD KURANG BAYAR TAMBAHAN *)</td><td></td><td align="left"></td></tr>
<tr><td width="1%"></td><td>&nbsp;<?php echo "<img src=\"../../images/box.png\">"; ?>&nbsp;c. Penghitungan dihitung sendiri menjadi :&nbsp;<?php echo "<img src=\"../../images/box.png\">"; ?><?php echo "<img src=\"../../images/box.png\">"; ?>&nbsp;%</td><td>Nomor :  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tanggal<p>berdasarkan Peraturan KDH No : ............................</p></td><td align="left"></td></tr>
<tr><td width="1%"></td><td>&nbsp;<?php echo "<img src=\"../../images/box.png\">"; ?>&nbsp;d. .......................</td><td align="left"></td><td align="left"></td></tr>
</table>
</td>
</tr>
<tr>
<td colspan="4">
<table class="nomyTbl" width="100%">
<tr><td width="1%"></td><td>&nbsp;JUMLAH YANG DISETOR (dengan angka)</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(dengan huruf)&nbsp;:</td><td></td></tr>
<tr><td width="1%"></td><td style="border: solid #000000 1px;" width="3%" align="center"><?php if($row['pengurangan'] == 0 ){?><span class="font14">&nbsp;<b>Rp. &nbsp;<?php echo number_format($row['pokok_pajak']);?></b></span></td><td align="center">&nbsp;<?php 
if($row['npopkp'] == 0){
echo "NIHIL"; }
else{
echo "<strong><i>".terbilang($row['pokok_pajak'], $style=3)." Rupiah </i></strong>";} } else {  ?><span class="font14">&nbsp;<b>Rp. &nbsp;<?php echo number_format($row['pengurangan']);?></b></span></td><td align="center">&nbsp;<?php echo "<strong><i>".terbilang($row['pengurangan'], $style=3)." Rupiah </i></strong>"; }  ?></td><td align="left"></td></tr>
<tr><td width="1%"></td><td>&nbsp;<span class="font8">(berdasarkan perhitungan C.4 dan pilihan di D)</span></td><td align="left"></td></tr>
</table>
</td>
</tr>
	<?php
	$nip = $row['nip_pejabat'];
	$sql = "SELECT * FROM public.user WHERE nip='$nip'";
	$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
	$row_user = pg_fetch_array($result);
	
	?>
<tr>
<td colspan="4">
<table class="myTbl font10" width="100%">
<tr><td width="1%" align="center" valign="top">Payakumbuh,tgl ..........<p style="line-height:0.2;">WAJIB PAJAK/PENYETOR</p><br/><br/><p><u><?php echo $row['nama'];?></u></p></td><td align="center" valign="top"><p>MENGETAHUI:</p><p style="line-height:0.2;">PPAT/NOTARIS</p><br/><br/><p><u><?php echo $row['nama_ppat'];?></u></p></td><td align="center" valign="top">DITERIMA OLEH :<p style="line-height:0.2;">TEMPAT PEMBAYARAN BPHTB</p><p style="line-height:0.2;">Tanggal: ...............</p><br/><br/>_______________________</td><td align="center">Telah Diverifikasi :<p style="line-height:0.1;">BADAN KEUANGAN DAERAH</p><p style="line-height:0.1;"></p><br/><br/><u></u></td></tr>

</table>
</td>
</tr>
<tr>
<td colspan="4">
<table class="myTbl font10" width="100%">
<tr><td width="1%" align="center" valign="center">Hanya diisi oleh <p>petugas BKD</p></td></td><td align="left" >Nomor Dokumen :  <?php echo $row['tahun'];?>.BPHTB.<?php  echo $row['no_urut_p']; ?><p>NOPPBB baru&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <?php echo "<img src=\"../../images/box.png\">"; ?><?php echo "<img src=\"../../images/box.png\">"; ?>&nbsp;<?php echo "<img src=\"../../images/box.png\">"; ?><?php echo "<img src=\"../../images/box.png\">"; ?>&nbsp;<?php echo "<img src=\"../../images/box.png\">"; ?><?php echo "<img src=\"../../images/box.png\">"; ?><?php echo "<img src=\"../../images/box.png\">"; ?>&nbsp;<?php echo "<img src=\"../../images/box.png\">"; ?><?php echo "<img src=\"../../images/box.png\">"; ?><?php echo "<img src=\"../../images/box.png\">"; ?>&nbsp;<?php echo "<img src=\"../../images/box.png\">"; ?><?php echo "<img src=\"../../images/box.png\">"; ?><?php echo "<img src=\"../../images/box.png\">"; ?>&nbsp;<?php echo "<img src=\"../../images/box.png\">"; ?><?php echo "<img src=\"../../images/box.png\">"; ?><?php echo "<img src=\"../../images/box.png\">"; ?><?php echo "<img src=\"../../images/box.png\">"; ?>&nbsp;<?php echo "<img src=\"../../images/box.png\">"; ?></p></td><td align="center"></td></tr>

</table>
</td>
</tr>

 </table>
 <table class="font10"><tr><td colspan="3">&nbsp; </td><td rowspan="8" style="margin-top:-150px;"><?php QRcode::png("$qrcode","image.png","L",3,3);echo "<img src='image.png'/>";?></td></tr><tr><td colspan="3">Keterangan</td></tr>
 <tr><td>Lembar 1 (Putih)</td><td>:</td><td>Untuk Wajib Pajak </td></tr>
 <tr><td>Lembar 2 (Merah)</td><td>:</td><td>Untuk PPAT/Notaris </td></tr>
  <tr><td>Lembar 3 (Kuning)</td><td>:</td><td>Untuk Kepala Kantor Pertanahan</td></tr>
  <tr><td>Lembar 4 (Hijau)</td><td>:</td><td>Untuk BKD</td></tr>
  <tr><td>Lembar 5 (Biru)</td><td>:</td><td>Untuk Bank Yang Ditunjuk</td></tr></table>
<?php

if($x != $n_row) {
	echo "<div style=\"page-break-before:always;\"></div>";
	$x++;
}

}
pg_free_result($tampil);
pg_close($dbconn);


?>