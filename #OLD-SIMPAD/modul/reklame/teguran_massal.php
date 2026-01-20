<?php
/*if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/func_terbilang.inc.php";*/

$base_dir = $_GET['bdir'];
//$base_dir = "D:/wamp/www/htdocs/simpad_dev/";
include_once $base_dir."inc/db.inc.php";
include_once $base_dir."inc/func_terbilang.inc.php";
include_once $base_dir."phpqrcode/qrlib.php";
include_once $base_dir."inc/api.php";

$thn = pg_escape_string($_GET['thn']);
$bln = pg_escape_string($_GET['bln']);
$teguran = pg_escape_string($_GET['teguran']);
$tgl=getdate();
$bulansd=date("m");
$sql = "SELECT C.*,A.nip_pejabat as nipps,A.tgl_surat,A.teguran_ke FROM reklame.teguran A 
LEFT JOIN reklame.skp B ON A.jns_surat=B.jns_surat AND A.thn_pajak=B.thn_pajak AND A.bln_pajak=B.bln_pajak AND A.kd_obj_pajak=B.kd_obj_pajak AND A.no_urut_surat=B.no_urut_surat
INNER JOIN public.wp C ON
C.kd_provinsi=B.kd_provinsi AND
C.kd_kota=B.kd_kota AND
C.kd_jns=B.kd_jns AND
C.no_reg=B.no_reg_wp
WHERE A.thn_pajak='$thn' AND A.bln_pajak='$bln' AND A.teguran_ke='$teguran' group by  C.kd_provinsi,C.kd_kota,C.kd_jns,C.no_reg,A.nip_pejabat,A.tgl_surat,A.teguran_ke order by C.no_reg desc";
$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
$n_row = pg_num_rows($tampil);
if($n_row <= 0) {
	echo "SURAT TEGURAN BELUM ADA UNTUK TAHUN ".$thn." BULAN ".$bln;
	pg_free_result($tampil);
	pg_close($dbconn);
	exit;
}
$x = 1;
?>
<style type="text/css">
table.noborder td, table.noborder th, table.noborder tr { border:none; font-family:"Verdana"; font-size:14px; }

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
	font-family: "Arial";
	font-weight: bold;
}
.style2 {
	font-size: 18px;
	font-family:"Arial";
	font-weight: bold;
	
}
.style3 {
	font-size: 14px;
	font-family:"Arial";
	font-weight: bold;
}
.style4 {
	font-size: 14px;
	font-family:"Arial";
	
}
.style8 {
	font-size: 13px;
	font-family:"Arial";
	
}
.style5 {font-size: 14px; font-family: "Arial";}
.style6 {font-size: 17px; font-weight: bold; }
.style7 {font-size: 10px}
</style>
<?php
$nos=0;
while ($row = pg_fetch_array($tampil)) {
$nos++;
$qrcode=md5($row['jns_surat'].".".$row['thn_pajak'].".".$row['bln_pajak'].".".$row['kd_obj_pajak'].".".$row['no_urut_surat']);


?>

<div style="width:100%;height:330mm; margin-left:13px;">
  <table width="100%" border="0" class="noborder">
  <tr>
   <td width="20"><img src="../../images/payakumbuh.png" height="100"></td>  <td height="50" align="center"><span class="style1">PEMERINTAH DAERAH KOTA PAYAKUMBUH</span><br/>
    <span class="style2">BADAN KEUANGAN DAERAH</span><br/>
    <span class="style3">Jalan. Veteran No. 70 Kel. Kapalo Koto Dibalai Payakumbuh</span><br/>
	 <span class="style3">Telepon / Fax  (0752) 92052 Payakumbuh - 25211</span><br/>
	 <span class="style3">Website : https://bkd.payakumbuhkota.go.id | Email : bkd@payakumbuhkota.go.id</span><br/></td></td>
    </tr>
	
</table>
<div align="center"><img width="100%" height="6" src="../../images/garis.gif"></div><br/>
<table width="100%" border="0" class="noborder">
  <tr>
    <td>
<table width="100%" border="0" class="noborder" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;">
  <tr>
    <td width="7%"></td>
    <td width="22%"></td>
	<td width="70%" align="right" rowspan="4">
	<div align="center"  class="style5" style="margin-left:160px;"><?php echo "Payakumbuh, ".date('d',strtotime($row['tgl_surat']))." ".$bulan[date('n',strtotime($row['tgl_surat']))]." ".date('Y',strtotime($row['tgl_surat'])); ?>
	<?php //echo "Payakumbuh,  &nbsp;&nbsp;&nbsp;   September 2019" ; ?><br/></div>
	<div align="center" class="style5"  style="margin-left:9px;">Kepada <br/></div>
	<div align="center" class="style5"  style="margin-left:90px;">Yth: Bapak/Ibu/Sdr.<br/></div>
	<div align="center" class="style5"  style="margin-left:130px;"><?php echo $row['nama']; ?><br/></div>
	<div align="center" class="style5"  style="margin-left:5px;">di,- <br/></div>
	<div align="center" class="style5"  style="margin-left:80px;"><u>Tempat</u></div></td>
  </tr>	

  
</table>
	</td>
  </tr>
</table><br/>
<table width="100%" class="font12" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;">
<tr><td align="center" class="font15" colspan="3"><div class="style6" style="margin-left:80px;"><u>SURAT TEGURAN</u></div></td></tr>
<tr><td align="center" class="style4" colspan="3"><div style="margin-left:80px;">Nomor : 900/<?php echo $teguran."/".$nos."/BKD-PYK/".$bulansd."-".$tgl['year']."";?></div></td></tr>
<tr height="30"><td colspan="3"></td></tr>
<tr><td width="35%" valign="top" class="style4">&nbsp;Jenis Pajak</td><td>:</td><td align="justify" class="style4">&nbsp;PAJAK REKLAME</td></tr>
<tr><td width="35%" class="style4">&nbsp;NPWPD</td><td>:</td><td align="justify" class="style4">&nbsp;<?php echo $row['kd_provinsi'].".".$row['kd_kota'].".".$row['kd_jns'].".".$row['no_reg']; ?></td></tr>
<tr><td width="35%" class="style4">&nbsp;Alamat</td><td>:</td><td align="justify" class="style4">&nbsp;<?php echo $row['alamat']; ?></td></tr>
<tr><td height="5">&nbsp;</td></tr>
<tr><td colspan="3" class="style4">Menurut pembukuan kami, hingga saat ini Saudara masih mempunyai tunggakan Pajak Reklame, sebagai berikut :</td></tr>
<tr><td height="15">&nbsp;</td></tr>
<tr><td colspan="3"><table class="skptable" class="font10" width="100%"><tr><td align="center"><span class="style4"><b>NO</b></span></td><td align="center"><span class="style4"><b>MASA PAJAK</b></span></td>
<td align="center"><span class="style4"><b>NOMOR SKP</b></span></td><td align="center"><span class="style4"><b>ALAMAT OBJEK</b></span></td><td align="center"><span class="style4"><b>JATUH TEMPO</b></span></td><td align="center"><span class="style4"><b>JUMLAH TUNGGAKAN (Rp)</b></span></td></tr>

<?php $sql_a="SELECT B.*,D.rek_apbd,D.outlet_id FROM reklame.teguran A 
LEFT JOIN reklame.skp B ON A.jns_surat=B.jns_surat AND A.thn_pajak=B.thn_pajak AND A.bln_pajak=B.bln_pajak AND A.kd_obj_pajak=B.kd_obj_pajak AND A.no_urut_surat=B.no_urut_surat
INNER JOIN public.wp C ON
C.kd_provinsi=B.kd_provinsi AND
C.kd_kota=B.kd_kota AND
C.kd_jns=B.kd_jns AND
C.no_reg=B.no_reg_wp
LEFT JOIN public.keg_usaha D ON
D.kd_obj_pajak=B.kd_obj_pajak AND
D.kd_keg_usaha=B.kd_keg_usaha
WHERE B.kd_provinsi='$row[kd_provinsi]' AND B.kd_kota='$row[kd_kota]' AND B.kd_jns='$row[kd_jns]' and B.no_reg_wp='$row[no_reg]' AND A.thn_pajak='$thn' AND A.bln_pajak='$bln' AND A.teguran_ke='$teguran' order by A.no_urut_surat asc";
$tampil_a = pg_query($sql_a) or die('Query failed: ' . pg_last_error());
$n_row_c = pg_num_rows($tampil_a);
$no = 0;
$total=0;
$y=1;
while($row_c=pg_fetch_array($tampil_a)){
$no++;
$total=$total+$row_c['pokok_pajak'];
$no_tagihan=$row_c['jns_surat'].".".$row_c['thn_pajak'].".".$row_c['bln_pajak'].".".$row_c['kd_obj_pajak'].".".$row_c['no_urut_surat'];
$jumlah=$row_c['pokok_pajak'];
$outlet_id=$row_c['outlet_id'];
?>
<tr><td><span class="style8">&nbsp;<?php echo $no; ?></span></td><td align="center"><span class="style8">&nbsp;<?php echo strtoupper($bulan_s[$row_c['bln_pajak']])." ".($row_c['thn_pajak']); ?></span></td><td><span class="style8">&nbsp;<?php echo $row_c['jns_surat'].".".$row_c['thn_pajak'].".".$row_c['bln_pajak'].".".$row_c['kd_obj_pajak'].".".$row_c['no_urut_surat']; ?></span></td><td align="justify"><span class="style8"><?php echo $row_c['nm_reklame']; ?>&nbsp;, &nbsp;<?php echo $row_c['alamat_reklame']; ?></span></td><td align="center"><span class="style4">&nbsp;<?php echo date('d',strtotime($row_c['tmt_awal']))." ".$bulan[date('n',strtotime($row_c['tmt_awal']))]." ".date('Y',strtotime($row_c['tmt_awal'])); ?></span></td><td align="right"><span class="style8">&nbsp;<?php echo number_format($row_c['pokok_pajak']); ?></span></td></tr>

<?php 


}
pg_free_result($tampil_a);
?>
<tr><td colspan="5"><?php echo " <strong><i># ".terbilang($total, $style=3)." Rupiah #</i></strong>"; ?></td><td align="right"><span class="style8">&nbsp;<?php echo "Rp. ".number_format($total); ?></span></td>
</td></table></tr>
<tr><td colspan="3" align="justify" class="style4"><br />&nbsp;&nbsp;Untuk menghindari <b>denda 1% perbulan dan/atau Pembongkaran Reklame tersebut</b> , maka diminta kepada Saudara agar melunasi jumlah tunggakan dalam waktu 7 (tujuh) hari kerja setelah tanggal diterimanya Surat Teguran ini.<br /><br />&nbsp;&nbsp;Dalam hal Saudara telah melunasi Tunggakan tersebut diatas, diminta agar Saudara segera melaporkan kepada Badan Keuangan Daerah cq. Bidang Pendapatan Lantai 3 Kantor Balaikota Jln. Veteran No. 70 Kelurahan Kapalo Koto Dibalai.</td></tr>
  </table>
<br/>

<!--<table align="left" >
	<tr><td align="center"><div align="center" style="margin-left:160px;"><?php //QRcode::png("$qrcode","image.png","L",3,4);
//echo "<img src='image.png'/>";?></div></td>
	</tr>-->
	
</table>

<table  width="100%" border="0" >
<tr>
<td width="50%" class="font11"><?php
			if($n_row_c < 2){
			$cek= $row_c['status_pembayaran'];
 
 			  if ($cek == 0){
		       $no_tagihan_baru= preg_replace("/[^0-9]/", "", $no_tagihan);
			   $kd_surat=substr($no_tagihan_baru,1,1);
			   $kd_tahun=substr($no_tagihan_baru,4,2);
			   $kd_bln=substr($no_tagihan_baru,6,2);
			   $no_pajak=substr($no_tagihan_baru,8,2);
			   $no_urut=substr($no_tagihan_baru,11,3);
			   $billing_number="$kd_surat"."$kd_tahun"."$kd_bln"."$no_pajak"."$no_urut";
		
			   $id = "QR03";
			  // $outlet_id = $row_c['outlet_id'];
			   $amount = intval($jumlah);
		       $data = api($id, $outlet_id, $billing_number, $amount, 0, "", "");
               
				if($data->rc == "00")
				{
				if($id == "QR03")
				{
				echo "<div align='center'><img src='../../images/iconQris.png' width='48%'></div>";
				qrImg($data->data->qrData, $billing_number);
				
				echo "<div align='center' style='margin-top:-10px;'><img src='qrimage/$billing_number.png' width='38%'></div>";
				
				echo "<b>SILAHKAN SCAN QR <BR/>UNTUK PEMBAYARAN ".strtoupper($row_jns_pjk['nm_obj_pajak'])." !!</b><br/>";
				echo "<i>(Nagari, BNI, BRI, BSI, BCA, Mandiri, GoPay, ShopeePay, Dana, OVO, dll)</i>";
               
				}
				}
				}
				}
                
			   ?></td>

  <td align="center"><table border="0" style="margin-top:-10px;" align="center"><tr><td align="center">
  &nbsp;<?php
	$nip = $row['nipps'];
	$sql = "SELECT * FROM public.user WHERE nip='$nip'";
	$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
	$row_user = pg_fetch_array($result);
	echo $row_user['jabatan'];
	//echo "a.n KEPALA BADAN KEUANGAN DAERAH<";
	?>  </br>KOTA PAYAKUMBUH</td></tr>
	
   
    &nbsp;<?php 
  $qr=md5("$npwpd");
  $tempdir = "qrimage/";
  //ambil logo
  $logopath="qrimage/bkd.png";

 //isi qrcode jika di scan
 $codeContents = "$qr"; 

 //simpan file qrcode
 QRcode::png($codeContents, $tempdir.'image.png', QR_ECLEVEL_H, 2,2);
 // ambil file qrcode
 $QR = imagecreatefrompng($tempdir.'image.png');
 // memulai menggambar logo dalam file qrcode
 $logo = imagecreatefromstring(file_get_contents($logopath));
 imagecolortransparent($logo , imagecolorallocatealpha($logo , 0, 0, 0, 127));
 imagealphablending($logo , false);
 imagesavealpha($logo , true);
 $QR_width = imagesx($QR);
 $QR_height = imagesy($QR);
 $logo_width = imagesx($logo);
 $logo_height = imagesy($logo);
 // Scale logo to fit in the QR Code
 $logo_qr_width = $QR_width/3;
 $scale = $logo_width/$logo_qr_width;
 $logo_qr_height = $logo_height/$scale;
 imagecopyresampled($QR, $logo, $QR_width/2.9, $QR_height/2.9, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
 // Simpan kode QR lagi, dengan logo di atasnya
 imagepng($QR,$tempdir.'image.png'); ?>
<tr></td>
<?php

?>  
   </td></tr>
   <tr><td align="center">
   <?php 
   //  QRcode::png("$nik","image.png","L",4,4);
//echo '<img src="'.$tempdir.'image.png'.'" /></br>'; ?>
<br/><br/><br/>
 <?php  echo "<u>".$row_user['nama']."</u>"; ?></br>
   NIP.&nbsp;<?php echo $row_user['nip']; ?>
   </td></tr>
   </table></td>
   

</tr>
<?php
if($n_row_c < 2){
$cek= $row_c['status_pembayaran'];
 if ($cek == 0){?>
<tr><td colspan="2" class="font11"><b>Untuk Cetak Bukti Pembayaran dapat melalui :</b><br/>
1. Website https://pajakqris.payakumbuhkota.go.id <br/>
2. Pada Kolom QRIS Pajak Daerah Masukkan Nomor Bayar / Urut <br/>
3. Pilih Cetak Bukti -> Download Bukti<br/></td></tr>
<?php } }?>
</table>





<!--<table align="right" class="font14">
<tr><td>&nbsp;</td></tr>
	<tr><td align="center"><?php*/
	$nip = $row['nipps'];
	$sql = "SELECT * FROM public.user WHERE nip='$nip'";
	$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
	$row_user = pg_fetch_array($result);
	echo $row_user['jabatan'];
	//echo "a.n KEPALA BADAN KEUANGAN DAERAH<";
	?>	</td></tr>
	<tr><td align="center"> KOTA PAYAKUMBUH</td></tr>
	<tr height="70"><td></td></tr>	
	<tr><td align="center"> <?php //echo "<u>".$row_user['nama']."</u>"; ?></td></tr>
	<tr><td align="center">NIP.&nbsp;<?php //echo $row_user['nip']; ?></td></tr>
</table>
-->



</div>
<?php
if($x != $n_row) {
	echo "<div style=\"page-break-before:always;\"></div>";
	$x++;
	
}
pg_free_result($result);
}
pg_free_result($tampil);
pg_close($dbconn);
?>
<!--<div style="page-break-before:always;">

</div> --!>