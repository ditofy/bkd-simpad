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
include_once $base_dir."inc/api.php";
include_once $base_dir."phpqrcode/qrlib.php";
include_once $base_dir."inc/va.inc.php";

$no_sspd = pg_escape_string($_GET['no_sspd']);
list($jns_surat,$thn_pajak,$bln_pajak,$kd_obj_pajak,$no_urut_sspd) = explode(".", $no_sspd);
if($jns_surat == 04 ){
$schema = "bphtb";
$sql = "SELECT A.*,B.*,C.*,D.nm_obj_pajak FROM $schema.sspd A INNER JOIN public.wp B ON
B.nik=A.nik 
INNER JOIN public.ppat C ON
A.id_ppat=c.id
INNER JOIN public.obj_pajak D ON
A.kd_obj_pajak=D.kd_obj_pajak
WHERE A.jns_surat='$jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_sspd'";
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
.style4 {
	font-size: 13px;
	font-family:Arial;
}
.style6 {font-size: 12px}
.style7 {
	font-size: 14px;
	font-weight: bold;
}
.style8 {
	font-size: 12px;
	font-weight: bold;
}
.style9 {
	font-size: 16px;
	font-weight: bold;
}
.style10 {
	font-size: 10px;
	font-weight: bold;
	font-style: italic;
}
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
 $al=$row['alamat'];
 $nama_wp=$row['nama'];
 $no_pel=$row['tahun'].".".$row['no_urut_p'];
 $qrcode=$row['qrcode'];
 $no_tagihan=$row['jns_surat'].".".$row['thn_pajak'].".".$row['bln_pajak'].".".$row['kd_obj_pajak'].".".$row['no_urut_surat'];
 $jumlah=$row['pokok_pajak_real'];
 ////////
 $nops = "04.".$thn_pajak.".".$bln_pajak.".".$kd_obj_pajak.".".$no_urut_sspd;
 deleteVaPjd($nops);
 $va = getVaPjd($nops, $nama_wp, $jumlah);

 $sql_update = "UPDATE BPHTB.SSPD A SET va='$va' WHERE A.jns_surat='$jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_sspd'";
 $update_va = pg_query($sql_update) or die('Query failed: ' . pg_last_error());
 
 
 if($update_va){
 $sql_va= "SELECT A.VA FROM BPHTB.SSPD A WHERE A.jns_surat='$jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_sspd'";
 $tampil_va = pg_query($sql_va) or die('Query failed: ' . pg_last_error());
 $row_va = pg_fetch_array ($tampil_va);
 }
?>
<div style="width:215mm;height:300mm;">
  <table width="100%" border="0" class="myTbl" align="center">
  <tr>
    <td rowspan="2" align="center">
<img src="../../images/payakumbuh.png" width="60">
<p><pre class="font10" style="line-height: 1.6em;">KOTA PAYAKUMBUH</pre></p></td>
    <td align="center">
<pre>

<strong class="font18">PEMERINTAH KOTA PAYAKUMBUH</strong>
<strong class="font20">BADAN KEUANGAN DAERAH</strong></pre>	
  </td>
<td rowspan="2" align="center">
<?php QRcode::png("$qrcode","image.png","L",2,2);echo "<img src='image.png'/>"; ?>
	</td>
  </tr>
  <tr>
    <td align="center">&nbsp;
	 <span class="style4">Komplek Perkantoran Balaikota Baru Payakumbuh Jl. Veteran No. 70</span><br/> <span class="style4"> Kelurahan Kapalo Koto Dibalai Kecamatan Payakumbuh Utara Kota Payakumbuh </span></br>
	 <span class="style4">Telepon / Fax  (0752) 93279 Payakumbuh - 25211</span><br/>
	 <span class="style4">Website : https://bkd.payakumbuhkota.go.id | Email : bkd@payakumbuhkota.go.id</span><br/>
	
   </td>
  </tr>
  <tr height="50">
    <td colspan="4" align="center" ><span class="font16">SSPD PEMBAYARAN BPHTB </span></td>
</tr>

<tr>
<td colspan="4">
<table class="nomyTbl" width="100%">
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">1. No Bayar</td><td>&nbsp;:</td><td colspan="2"><?php echo $row['jns_surat'].".".$row['thn_pajak'].".".$row['bln_pajak'].".".$row['kd_obj_pajak'].".".$row['no_urut_surat']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">2. Jenis Pajak</td><td>&nbsp;:</td><td colspan="2"><?php echo $row['nm_obj_pajak'];?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">3. Nama Wajib Pajak</td><td>&nbsp;:</td><td colspan="2"><?php echo $row['nama'];?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">4. NPWPD</td><td>&nbsp;:</td><td align="left" colspan="2"><?php echo $row['kd_provinsi'].".".$row['kd_kota'].".".$row['kd_jns'].".".$row['no_reg']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">5. Alamat Wajib Pajak</td><td>&nbsp;:</td><td align="left" colspan="2"><?php echo $row['alamat']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">6. Alamat Objek Pajak</td><td>&nbsp;:</td><td align="left" colspan="2"><?php echo $row['alamat_op']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">7. Menyetor Berdasarkan</td><td>&nbsp;:</td><td ><?php if($row['jns_surat'] == "04") { echo "<td><img src=\"../../images/box_centang.png\">&nbsp;SSPD</td>"; }?></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><td><img src="../../images/box.png">&nbsp;SPTPD</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><td><img src="../../images/box.png">&nbsp;SKPDKB</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><td><img src="../../images/box.png">&nbsp;SKPDKBT</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><td><img src="../../images/box.png">&nbsp;STPD</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><td><img src="../../images/box.png">&nbsp;SK PEMBETULAN</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><td><img src="../../images/box.png">&nbsp;Lain-lain</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><td></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">8. Uang Sebesar</td><td>&nbsp;:</td><td align="left" colspan="2">Rp.&nbsp;<?php echo number_format($row['pokok_pajak_real']);?>&nbsp;,-</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">9. Dengan Huruf</td><td>&nbsp;:</td><td align="left" colspan="2"><?php echo "<strong><i>".terbilang($row['pokok_pajak_real'], $style=3)." Rupiah </i></strong>";?>&nbsp;,-</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">10. Guna Pembayaran</td><td>&nbsp;:</td><td align="left" colspan="2">BPHTB dengan NOP : <?php echo $row['kd_propinsi'].".".$row['kd_dati2'].".".$row['kd_kecamatan'].".".$row['kd_kelurahan'].".".$row['kd_blok'].".".$row['no_urut'].".".$row['kd_jns_op']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">11. Jenis Pembayaran</td><td>&nbsp;:</td><td align="left" colspan="2">(Tunai / Cek / BG/ Transfer)</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">12. No. Cek/BG</td><td>&nbsp;:</td><td align="left" colspan="2">__</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">13. Tgl. Cek/BG/Transfer</td><td>&nbsp;:</td><td align="left" colspan="2">__</td></tr>
</table>
</td>
</tr>
<br/>
<!--- TAMPIL METODE PEMBAYARAN ------->
<table  width="100%" border="0" class="font11 noborder">
 <?php
  $cek= $row['status_pembayaran'];
  if ($cek == 0 and $jumlah > 0 && $jumlah < 10000000){
 		       $no_tagihan_baru= preg_replace("/[^0-9]/", "", $no_sspd);
			   $kd_surat=substr($no_tagihan_baru,1,1);
			   $kd_tahun=substr($no_tagihan_baru,4,2);
			   $kd_bln=substr($no_tagihan_baru,6,2);
			   $no_pajak=substr($no_tagihan_baru,8,2);
			   $no_urut=substr($no_tagihan_baru,11,3);
			   $billing_number="$kd_surat"."$kd_tahun"."$kd_bln"."$no_pajak"."$no_urut";
			   $id = "QR03";
			   $outlet_id = "207190082";
			   $amount = intval($jumlah);
		       $data = api($id, $outlet_id, $billing_number, $amount, 0, "", "");
              // echo $outlet_id;
			  ?>
<tr><td colspan="2" align="left"><span class="style9">PILIH METODE PEMBAYARAN BPHTB DIGITAL DIBAWAH </span></td></tr>
<tr>
<td width="37%">
 <?php 
				if($data->rc == "00")
				{
				if($id == "QR03")
				{
				echo "<div align='center'><img src='../../images/iconQris.png' width='62%'></div>";
				qrImg($data->data->qrData, $billing_number);
				
				echo "<div align='center'><img src='qrimage/$billing_number.png' width='62%'></div><br/>";
				}
				}
			   ?></td>
<td valign="top"><span class="style7">1. QRIS Dinamis</span><br/>
<span class="style6">
<?php
echo "&nbsp;&nbsp;&nbsp;&nbsp<b>SILAHKAN SCAN QR UNTUK PEMBAYARAN ".strtoupper($row_jns_pjk['nm_obj_pajak'])." !!</b><br/>";
	echo "&nbsp;&nbsp;&nbsp;&nbsp<i>(Nagari, BNI, BRI, BSI, BCA, Mandiri, GoPay, ShopeePay, Dana, OVO, dll)</i>";
//$qrcode = "https://pajakqris.payakumbuhkota.go.id/bukti/DAERAH/".$billing_number;
//QRcode::png("$qrcode","image.png","L",2,2);echo "<img src='image.png'/>";
?></span><br/>
<span class="style6"><b>&nbsp;&nbsp;&nbsp;&nbsp;Untuk Cetak Bukti Pembayaran dapat melalui :</b><br/>
&nbsp;&nbsp;&nbsp;&nbspa. Website https://pajakqris.payakumbuhkota.go.id 
&nbsp;Atau melalui aplikasi <b>QRIS Pajak &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbspPayakumbuh</b> di Playstore<br/>
&nbsp;&nbsp;&nbsp;&nbspb. Pada Kolom QRIS Pajak Daerah Masukkan Nomor Bayar. <br/>
&nbsp;&nbsp;&nbsp;&nbspc. Pilih Cetak Bukti - Download Bukti</span><br/><br/>

<span class="style7">2. Virtual Account Number :&nbsp;&nbsp;</span><b><span class="font18"><?php echo $row_va['va']; ?></span></b><br/>
<span class="style6">&nbsp;&nbsp;&nbsp;&nbsp;a. Melalui Aplikasi Bank Nagari<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pilih Menu Transfer - Pilih Virtual Account - Input Nomor VA diatas - Pilih Bayar<br/>
&nbsp;&nbsp;&nbsp;&nbspb. Melalui Aplikasi Bank Lainnya<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbspPilih Menu Transfer - Pilih Antar Bank - Cari Bank Nagari - Input Nomor VA diatas - Input &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbspNominal - Lanjutkan Proses<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="style10">(expired va number <?php
echo date('d-m-Y', strtotime("+30 days")); ?>)</span></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td valign="top">
    <p>&nbsp; </p></td>
</tr>
 </table>
 <?php } else if($cek == 0 and $jumlah > 0 && $jumlah > 10000000){?>
 <tr>
<td valign="top" width="100%"><br/>
<span class="style6"><span class="style9">SILAHKAN PILIH METODE PEMBAYARAN DIBAWAH</span> <br/>
<p><strong>Virtual Account Number :&nbsp;&nbsp;</strong><b><span class="font18"><?php echo $row_va['va']; ?></span></b></p>
<span class="font14">a. Melalui Aplikasi Bank Nagari</span><br/>
<span class="font12">&nbsp;&nbsp;&nbsp;&nbsp;Pilih Menu Transfer - Pilih Virtual Account - Input Nomor VA diatas - Pilih Bayar</span><br/><br/>
<span class="font14">b. Melalui Aplikasi Bank Lainnya</span><br/>
<span class="font12">&nbsp;&nbsp;&nbsp;&nbsp;Pilih Menu Transfer - Pilih Antar Bank - Cari Bank Nagari - Input Nomor VA diatas - Input Nominal - Lanjutkan Proses</span><br/><br/>
<span class="style10"><span class="style10">(expired va number <?php
echo date('d-m-Y', strtotime("+30 days")); ?>)</span></span>
</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td valign="top">
    <p>&nbsp; </p></td>
</tr>
 <?php } else {?>
 <tr>
<td valign="top" width="100%"><br/>


</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td valign="top">
    <p>&nbsp; </p></td>
</tr>

</table>
 <?php } ?>
<br/>
 <table class="font14" align="right" >
 <tr><td align="center"><?php QRcode::png("https://skm.go.id/skm-form/4d1050926d94dae9b41522113b1a8961ae289651cbd8bd9c0450c95640b","image_skm.png","L",2,2); echo "<img src='image_skm.png'/>"; ?><br/>
   <span class="style8">Silahkan Scan QR Code Untuk Isi SKM BKD Kota Payakumbuh..!!</span></td>
 </tr>
</table>
<?php

if($x != $n_row) {
	echo "<div style=\"page-break-before:always;\"></div>";
	$x++;
}

}
pg_free_result($tampil);
pg_free_result($tampil_va);
pg_close($dbconn);
}
///////SKPKBB
else{
$schema = "bphtb";
$sql = "SELECT A.*,D.nm_obj_pajak FROM bphtb.skpdkb A 
INNER JOIN public.obj_pajak D ON
A.kd_obj_pajak=D.kd_obj_pajak
WHERE A.jns_surat='$jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_sspd'";
$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
$n_row = pg_num_rows($tampil);
if($n_row <= 0) {
	echo "NO. SKPDKB BPHTB TIDAK DITEMUKAN, SILAHKAN PERIKSA KEMBALI NO. SSPD";
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
.style4 {
	font-size: 13px;
	font-family:Arial;
}
.style6 {font-size: 12px}
.style7 {
	font-size: 14px;
	font-weight: bold;
}
.style8 {
	font-size: 12px;
	font-weight: bold;
}
.style9 {
	font-size: 16px;
	font-weight: bold;
}
.style10 {
	font-size: 10px;
	font-weight: bold;
	font-style: italic;
}
</style>
<?php

 while ($row = pg_fetch_array($tampil)) { 

 
 $al=$row['alamat_wp'];
 $nama_wp=$row['nama_wp'];
 $no_pel=$row['tahun'].".".$row['no_urut_p'];
 $qrcode=$row['qrcode'];
 $no_tagihan=$row['jns_surat'].".".$row['thn_pajak'].".".$row['bln_pajak'].".".$row['kd_obj_pajak'].".".$row['no_urut_surat'];
 $jumlah=$row['pokok_pajak_baru'];
 ////////
 $nops = "05.".$thn_pajak.".".$bln_pajak.".".$kd_obj_pajak.".".$no_urut_sspd;
 deleteVaPjd($nops);
 $va = getVaPjd($nops, $nama_wp, $jumlah);

 $sql_update = "UPDATE BPHTB.SKPDKB A SET va='$va' WHERE A.jns_surat='$jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_sspd'";
 $update_va = pg_query($sql_update) or die('Query failed: ' . pg_last_error());
 
 
 if($update_va){
 $sql_va= "SELECT A.VA FROM BPHTB.SKPDKB A WHERE A.jns_surat='$jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_sspd'";
 $tampil_va = pg_query($sql_va) or die('Query failed: ' . pg_last_error());
 $row_va = pg_fetch_array ($tampil_va);
 }
?>
<div style="width:215mm;height:300mm;">
  <table width="100%" border="0" class="myTbl" align="center">
  <tr>
    <td rowspan="2" align="center">
<img src="../../images/payakumbuh.png" width="60">
<p><pre class="font10" style="line-height: 1.6em;">KOTA PAYAKUMBUH</pre></p></td>
    <td align="center">
<pre>

<strong class="font18">PEMERINTAH KOTA PAYAKUMBUH</strong>
<strong class="font20">BADAN KEUANGAN DAERAH</strong></pre>	
  </td>
<td rowspan="2" align="center">
<?php QRcode::png("$qrcode","image.png","L",2,2);echo "<img src='image.png'/>"; ?>
	</td>
  </tr>
  <tr>
    <td align="center">&nbsp;
	 <span class="style4">Komplek Perkantoran Balaikota Baru Payakumbuh Jl. Veteran No. 70</span><br/> <span class="style4"> Kelurahan Kapalo Koto Dibalai Kecamatan Payakumbuh Utara Kota Payakumbuh </span></br>
	 <span class="style4">Telepon / Fax  (0752) 93279 Payakumbuh - 25211</span><br/>
	 <span class="style4">Website : https://bkd.payakumbuhkota.go.id | Email : bkd@payakumbuhkota.go.id</span><br/>
	
   </td>
  </tr>
  <tr height="50">
    <td colspan="4" align="center" ><span class="font16">SSPD PEMBAYARAN BPHTB </span></td>
</tr>

<tr>
<td colspan="4">
<table class="nomyTbl" width="100%">
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">1. No Bayar</td><td>&nbsp;:</td><td colspan="2"><?php echo $row['jns_surat'].".".$row['thn_pajak'].".".$row['bln_pajak'].".".$row['kd_obj_pajak'].".".$row['no_urut_surat']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">2. Jenis Pajak</td><td>&nbsp;:</td><td colspan="2"><?php echo $row['nm_obj_pajak'];?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">3. Nama Wajib Pajak</td><td>&nbsp;:</td><td colspan="2"><?php echo $row['nama_wp'];?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">4. NPWPD</td><td>&nbsp;:</td><td align="left" colspan="2"><?php echo $row['kd_provinsi'].".".$row['kd_kota'].".".$row['kd_jns'].".".$row['no_reg']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">5. Alamat Wajib Pajak</td><td>&nbsp;:</td><td align="left" colspan="2"><?php echo $row['alamat_wp']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">6. Alamat Objek Pajak</td><td>&nbsp;:</td><td align="left" colspan="2"><?php echo $row['alamat_op']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">7. Menyetor Berdasarkan</td><td colspan="1">&nbsp;:</td><td ><img src="../../images/box.png">&nbsp;SSPD</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><img src="../../images/box.png" />&nbsp;SPTPD
<td>&nbsp;</td>
</tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;<?php if($row['jns_surat'] == "05") { echo "<td><img src=\"../../images/box_centang.png\">&nbsp;SKPDKB</td>"; }?>
<td>&nbsp;</td>
</tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><img src="../../images/box.png" />&nbsp;SKPDKBT
<td>&nbsp;</td>
</tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><img src="../../images/box.png" />&nbsp;STPD
<td>&nbsp;</td>
</tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><img src="../../images/box.png" />&nbsp;SK PEMBETULAN
<td>&nbsp;</td>
</tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><img src="../../images/box.png" />&nbsp;Lain-lain
<td>&nbsp;</td>
</tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><td></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">8. Uang Sebesar</td><td>&nbsp;:</td><td align="left" colspan="2">Rp.&nbsp;<?php echo number_format($row['pokok_pajak_baru']);?>&nbsp;,-</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">9. Dengan Huruf</td><td>&nbsp;:</td><td align="left" colspan="2"><?php echo "<strong><i>".terbilang($row['pokok_pajak_baru'], $style=3)." Rupiah </i></strong>";?>&nbsp;,-</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">10. Guna Pembayaran</td><td>&nbsp;:</td><td align="left" colspan="2">BPHTB dengan NOP : <?php echo $row['kd_propinsi'].".".$row['kd_dati2'].".".$row['kd_kecamatan'].".".$row['kd_kelurahan'].".".$row['kd_blok'].".".$row['no_urut'].".".$row['kd_jns_op']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">11. Jenis Pembayaran</td><td>&nbsp;:</td><td align="left" colspan="2">(Tunai / Cek / BG/ Transfer)</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">12. No. Cek/BG</td><td>&nbsp;:</td><td align="left" colspan="2">__</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">13. Tgl. Cek/BG/Transfer</td><td>&nbsp;:</td><td align="left" colspan="2">__</td></tr>
</table>
</td>
</tr>
<br/>
<!--- TAMPIL METODE PEMBAYARAN ------->
<table  width="100%" border="0" class="font11 noborder">
 <?php
  $cek= $row['status_pembayaran'];
  if ($cek == 0 and $jumlah > 0 && $jumlah < 10000000){
 		       $no_tagihan_baru= preg_replace("/[^0-9]/", "", $no_sspd);
			   $kd_surat=substr($no_tagihan_baru,1,1);
			   $kd_tahun=substr($no_tagihan_baru,4,2);
			   $kd_bln=substr($no_tagihan_baru,6,2);
			   $no_pajak=substr($no_tagihan_baru,8,2);
			   $no_urut=substr($no_tagihan_baru,11,3);
			   $billing_number="$kd_surat"."$kd_tahun"."$kd_bln"."$no_pajak"."$no_urut";
			   $id = "QR03";
			   $outlet_id = "207190082";
			   $amount = intval($jumlah);
		       $data = api($id, $outlet_id, $billing_number, $amount, 0, "", "");
              // echo $outlet_id;
			  ?>
<tr><td colspan="2" align="left"><span class="style9">PILIH METODE PEMBAYARAN BPHTB DIGITAL DIBAWAH </span></td></tr>
<tr>
<td width="37%">
 <?php 
				if($data->rc == "00")
				{
				if($id == "QR03")
				{
				echo "<div align='center'><img src='../../images/iconQris.png' width='62%'></div>";
				qrImg($data->data->qrData, $billing_number);
				
				echo "<div align='center'><img src='qrimage/$billing_number.png' width='62%'></div><br/>";
				}
				}
			   ?></td>
<td valign="top"><span class="style7">1. QRIS Dinamis</span><br/>
<span class="style6">
<?php
echo "&nbsp;&nbsp;&nbsp;&nbsp<b>SILAHKAN SCAN QR UNTUK PEMBAYARAN ".strtoupper($row_jns_pjk['nm_obj_pajak'])." !!</b><br/>";
	echo "&nbsp;&nbsp;&nbsp;&nbsp<i>(Nagari, BNI, BRI, BSI, BCA, Mandiri, GoPay, ShopeePay, Dana, OVO, dll)</i>";
//$qrcode = "https://pajakqris.payakumbuhkota.go.id/bukti/DAERAH/".$billing_number;
//QRcode::png("$qrcode","image.png","L",2,2);echo "<img src='image.png'/>";
?></span><br/>
<span class="style6"><b>&nbsp;&nbsp;&nbsp;&nbsp;Untuk Cetak Bukti Pembayaran dapat melalui :</b><br/>
&nbsp;&nbsp;&nbsp;&nbspa. Website https://pajakqris.payakumbuhkota.go.id 
&nbsp;Atau melalui aplikasi <b>QRIS Pajak &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbspPayakumbuh</b> di Playstore<br/>
&nbsp;&nbsp;&nbsp;&nbspb. Pada Kolom QRIS Pajak Daerah Masukkan Nomor Bayar. <br/>
&nbsp;&nbsp;&nbsp;&nbspc. Pilih Cetak Bukti - Download Bukti</span><br/><br/>

<span class="style7">2. Virtual Account Number :&nbsp;&nbsp;</span><b><span class="font18"><?php echo $row_va['va']; ?></span></b><br/>
<span class="style6">&nbsp;&nbsp;&nbsp;&nbsp;a. Melalui Aplikasi Bank Nagari<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pilih Menu Transfer - Pilih Virtual Account - Input Nomor VA diatas - Pilih Bayar<br/>
&nbsp;&nbsp;&nbsp;&nbspb. Melalui Aplikasi Bank Lainnya<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbspPilih Menu Transfer - Pilih Antar Bank - Cari Bank Nagari - Input Nomor VA diatas - Input &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbspNominal - Lanjutkan Proses<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="style10">(expired va number <?php
echo date('d-m-Y', strtotime("+30 days")); ?>)</span></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td valign="top">
    <p>&nbsp; </p></td>
</tr>
 </table>
 <?php } else if($cek == 0 and $jumlah > 0 && $jumlah > 10000000){?>
 <tr>
<td valign="top" width="100%"><br/>
<span class="style6"><span class="style9">SILAHKAN PILIH METODE PEMBAYARAN DIBAWAH</span> <br/>
<p><strong>Virtual Account Number :&nbsp;&nbsp;</strong><b><span class="font18"><?php echo $row_va['va']; ?></span></b></p>
<span class="font14">a. Melalui Aplikasi Bank Nagari</span><br/>
<span class="font12">&nbsp;&nbsp;&nbsp;&nbsp;Pilih Menu Transfer - Pilih Virtual Account - Input Nomor VA diatas - Pilih Bayar</span><br/><br/>
<span class="font14">b. Melalui Aplikasi Bank Lainnya</span><br/>
<span class="font12">&nbsp;&nbsp;&nbsp;&nbsp;Pilih Menu Transfer - Pilih Antar Bank - Cari Bank Nagari - Input Nomor VA diatas - Input Nominal - Lanjutkan Proses</span><br/><br/>
<span class="style10"><span class="style10">(expired va number <?php
echo date('d-m-Y', strtotime("+30 days")); ?>)</span></span>
</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td valign="top">
    <p>&nbsp; </p></td>
</tr>
 <?php } else {?>
 <tr>
<td valign="top" width="100%"><br/>


</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td valign="top">
    <p>&nbsp; </p></td>
</tr>

</table>
 <?php } ?>
<br/>
 <table class="font14" align="right" >
 <tr><td align="center"><?php QRcode::png("https://skm.go.id/skm-form/4d1050926d94dae9b41522113b1a8961ae289651cbd8bd9c0450c95640b","image_skm.png","L",2,2); echo "<img src='image_skm.png'/>"; ?><br/>
   <span class="style8">Silahkan Scan QR Code Untuk Isi SKM BKD Kota Payakumbuh..!!</span></td>
 </tr>
</table>
<?php

if($x != $n_row) {
	echo "<div style=\"page-break-before:always;\"></div>";
	$x++;
}

}
pg_free_result($tampil);
pg_free_result($tampil_va);
pg_close($dbconn);
}
?>