<?php
/*if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login');
	exit;
}*/
include_once "tanggal.php";
include_once "phpqrcode/qrlib.php";
$nik = mysql_real_escape_string($_GET['nik']);
$npwpd =mysql_real_escape_string( $_GET['npwpd']);
$nama = mysql_real_escape_string($_GET['nama']);
$alamat = mysql_real_escape_string($_GET['alamat']);
$status_hotel = mysql_real_escape_string($_GET['status_hotel']);
$status_restoran = mysql_real_escape_string($_GET['status_restoran']);
$status_hiburan = mysql_real_escape_string($_GET['status_hiburan']);
$status_reklame = mysql_real_escape_string($_GET['status_reklame']);
$status_air_tanah = mysql_real_escape_string($_GET['status_air_tanah']);
$status_bphtb = mysql_real_escape_string($_GET['status_bphtb']);
$x = 1;
?>
<style type="text/css">
table.noborder td, table.noborder th, table.noborder tr { border:none; font-family:"Arial"; font-size:14px; }

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


<div style="width:100%;height:250mm;">
  <table width="100%" border="0" class="noborder">
  <tr>
  <td width="20"><img src="../../images/payakumbuh.png" height="60"></td>  <td height="50" align="center"><span class="style1">PEMERINTAH DAERAH KOTA PAYAKUMBUH</span><br/>
    <span class="style2">BADAN KEUANGAN DAERAH</span><br/>
    <span class="style3">Jalan Veteran No. 70 Kel. Kapalo Koto Dibalai Kec Payakumbuh Utara</span><br/>
	 <span class="style3">Kota Payakumbuh Telepon / Fax  (0752)  92052  Payakumbuh –  25211</span><br/>
	 <span class="style3">Website: https://bkd.payakumbuhkota.go.id | Email: bkd@payakumbuhkota.go.id</span><br/></td>
    </tr>
	
</table>
<div align="center"><img width="100%" height="6" src="images/garis.gif"></div><br/>
<table width="100%" border="0" class="noborder">
  <tr>
    <td>
<table width="100%" border="0" class="noborder" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;" align="center">
    <tr>
      <td colspan="4" align="center">&nbsp;<span class="style3">SURAT KETERANGAN STATUS WAJIB PAJAK</span></td>
      </tr>
    	
  <tr><td align="center">Nomor: .....................................      </td>    </tr>
</table>
<br/>
<table width="100%" border="0" class="noborder">
	 <tr><td align="justify" colspan="4">Dengan  ini  diberitahukan	bahwa berdasarkan hasil penelitian, kami sampaikan bahwa Wajib Pajak : </td></tr>
	 <tr height="40%"><td align="left">&nbsp;</td></tr>
	 <tr><td width="5%"></td><td align="left" width="25%">Nama</td> <td width="2%">:</td><td><?php echo $nama; ?></td>   </tr>
	 <tr><td></td><td align="left">NPWPD</td><td>:</td><td><?php echo $npwpd; ?></td>   </tr>
	 <tr><td></td><td align="left">Alamat</td> <td>:</td><td><?php echo $alamat; ?></td>   </tr>
	 <tr><td></td><td align="left">Status</td> <td>:</td><td><?php
														if($status_hotel != 1){
															echo "<b>BELUM LUNAS PAJAK DAERAH</b>";
																								 }
														elseif($status_restoran != 1){
															echo "<b>BELUM LUNAS PAJAK DAERAH</b>";
																								 }
														elseif($status_hiburan != 1){
															echo "<b>BELUM LUNAS PAJAK DAERAH</b>";
																								 }
														elseif($status_air_tanah != 1){
															echo "<b>BELUM LUNAS PAJAK DAERAH</b>";
																								 }
														elseif($status_reklame != 1){
															echo "<b>BELUM LUNAS PAJAK DAERAH</b>";
																								 }
														elseif($status_bphtb != 1){
															echo "<b>BELUM LUNAS PAJAK DAERAH</b>";
																								 }
														else{
															echo "<b>LUNAS PAJAK DAERAH</b>";
																								 }
															 ?>&nbsp;</td>     </tr> 
	 <tr height="40%"><td align="left">&nbsp;</td></tr>
	  <tr><td align="justify" colspan="4">Keterangan ini dibuat dalam rangka layanan publik tertentu pada DPMPTSP untuk layanan publik <br/><br/>berupa................................................................................................... tahun ......... </td></tr>
	  <tr height="40%"><td align="left">&nbsp;</td></tr>
	   <tr><td align="justify" colspan="4">Demikian disampaikan, untuk dipergunakan sebagaimana mestinya</td></tr>
</table>
	
  
<br/><br/>
<table align="right" border="0" class="noborder">
<tr>
  <td rowspan="9">
  <td>
  <td width="30%" >&nbsp; </td>
  <td align="center">Payakumbuh,  <?php echo $tgl; ?>&nbsp;<?php echo $bln; ?>&nbsp;<?php echo $thn; ?> </td></tr>
<tr><td ></td>
  <td></td>
  <td width="70%" align="center">a.n KEPALA BADAN KEUANGAN DAERAH </td></tr>
<tr>
  <td ></td>
  <td></td>
  <td><div align="center">KABID PENDAPATAN</div></td>
 
</tr>

<tr>
<td></td>
<td></td>
  <td align="center">&nbsp;<?php 
  $qr=md5("$npwpd");
  $tempdir = "q/";
  //ambil logo
 $logopath="q/bkd.png";

 //isi qrcode jika di scan
 $codeContents = "$qr"; 

 //simpan file qrcode
 QRcode::png($codeContents, $tempdir.'image.png', QR_ECLEVEL_H, 3,3);


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
 imagepng($QR,$tempdir.'image.png');

  
  
//  QRcode::png("$nik","image.png","L",4,4);
echo '<img src="'.$tempdir.'image.png'.'" />';?></td>
</tr>

	<tr><td></td>
	  <td align="center">&nbsp;</td>
	  <td align="center"> <u>NOVALIZA, SE, M.Si</u></td></tr>
	<tr>
	  <td>    
	  <td></td>
	  <td align="center">NIP.&nbsp;19781119 200701 2 002</td>
	  
	  </tr>
	<tr><td colspan="3"></td>
	  </tr>
</table>
</td>
</tr>
</table>

</div>
<?php
if($x != $n_row) {
	echo "<div style=\"page-break-before:always;\"></div>";
	$x++;
}

?>
