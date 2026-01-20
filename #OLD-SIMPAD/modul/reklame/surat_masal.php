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
$bulanse=date('d - M - Y');
$bulansd=date("m");
$sql = "SELECT C.nama,C.alamat,A.kd_provinsi,A.kd_kota,A.kd_jns,A.no_reg_wp,a.nip_pejabat FROM reklame.pemberitahuan A 
INNER JOIN public.wp C ON
C.kd_provinsi=A.kd_provinsi AND
C.kd_kota=A.kd_kota AND
C.kd_jns=A.kd_jns AND
C.no_reg=A.no_reg_wp
WHERE A.thn_pajak='$thn' AND A.bln_pajak='$bln' group by A.kd_provinsi,A.kd_kota,A.kd_jns,A.no_reg_wp,C.nama,C.alamat,A.nip_pejabat order by A.no_reg_wp desc";
$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
$n_row = pg_num_rows($tampil);
if($n_row <= 0) {
	echo "SURAT PEMBERITAHUAN BELUM ADA UNTUK TAHUN ".$thn." BULAN ".$bln;
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
</style>
<?php
$nos=0;
while ($row = pg_fetch_array($tampil)) {
$nos++;
$qrcode=md5($row['kd_provinsi'].".".$row['kd_kota'].".".$row['kd_jns'].".".$row['no_reg_wp']);


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
	<div align="center"  class="style5" style="margin-left:160px;"><?php echo "Payakumbuh, ".$bulanse; ?>
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
<tr><td align="center" class="font15" colspan="3"><div class="style6" style="margin-left:80px;"><u>SURAT PEMBERITAHUAN PERPANJANGAN PAJAK REKLAME</u></div></td></tr>
<tr><td align="center" class="style4" colspan="3"><div style="margin-left:80px;">Nomor : 900/<?php echo $nos."/BKD-PYK/".$bulansd."-".$tgl['year']."";?></div></td></tr>
<tr height="30"><td colspan="3"></td></tr>
<tr><td width="35%" valign="top" class="style4">&nbsp;Jenis Pajak</td><td>:</td><td align="justify" class="style4">&nbsp;PAJAK REKLAME</td></tr>
<tr><td width="35%" class="style4">&nbsp;NPWPD</td><td>:</td><td align="justify" class="style4">&nbsp;<?php echo $row['kd_provinsi'].".".$row['kd_kota'].".".$row['kd_jns'].".".$row['no_reg_wp']; ?></td></tr>
<tr><td width="35%" class="style4">&nbsp;Alamat</td><td>:</td><td align="justify" class="style4">&nbsp;<?php echo $row['alamat']; ?></td></tr>
<tr><td height="5">&nbsp;</td></tr>
<tr><td colspan="3" class="style4">Bersama dengan ini kami beritahukan bahwa masa Pajak Reklame Saudara akan habis masa berlakunya dengan rincian data objek Pajak Reklame sebagai berikut :</td></tr>
<tr><td height="15">&nbsp;</td></tr>
<tr><td colspan="3"><table class="skptable" class="font10" width="100%"><tr><td align="center"><span class="style4"><b>NO</b></span></td>
<td align="center"><span class="style4"><b>ALAMAT OBJEK</b></span></td><td align="center"><span class="style4"><b>UKURAN</b></span></td><td align="center"><span class="style4"><b>BULAN JATUH TEMPO</b></span></td><td align="center"><span class="style4"><b>JUMLAH PAJAK (Rp)</b></span></td></tr>

<?php $sql_a="SELECT E.nm_tarif,E.tarif,B.lama_pasang,B.p,B.l,B.s,B.jlh,B.tmt_akhir,B.nm_reklame,B.alamat_reklame,D.rek_apbd,D.outlet_id,A.nip_pejabat FROM reklame.pemberitahuan A 
LEFT JOIN reklame.dat_obj_pajak B ON A.kd_kecamatan=B.kd_kecamatan AND A.kd_kelurahan=B.kd_kelurahan AND A.kd_obj_pajak=B.kd_obj_pajak AND A.kd_keg_usaha=B.kd_keg_usaha AND A.no_reg=B.no_reg
INNER JOIN public.wp C ON
C.kd_provinsi=B.kd_provinsi AND
C.kd_kota=B.kd_kota AND
C.kd_jns=B.kd_jns AND
C.no_reg=B.no_reg_wp
LEFT JOIN public.keg_usaha D ON
D.kd_obj_pajak=B.kd_obj_pajak AND
D.kd_keg_usaha=B.kd_keg_usaha
LEFT JOIN public.tarif E ON
B.kd_obj_pajak=E.kd_obj_pajak AND B.kd_tarif=E.kd_tarif
WHERE B.kd_provinsi='$row[kd_provinsi]' AND B.kd_kota='$row[kd_kota]' AND B.kd_jns='$row[kd_jns]' and B.no_reg_wp='$row[no_reg_wp]' AND A.thn_pajak='$thn' AND A.bln_pajak='$bln' order by C.no_reg desc";
$tampil_a = pg_query($sql_a) or die('Query failed: ' . pg_last_error());
$n_row_c = pg_num_rows($tampil_a);
$no = 0;
$total=0;
$y=1;
while($row_c=pg_fetch_array($tampil_a)){
$no++;
$p=$row_c['p'];
$l=$row_c['l'];
$s=$row_c['s'];
$jlh=$row_c['jlh'];
$lama=$row_c['lama_pasang'];
$nm_tarif = $row_c['nm_tarif'];
$tarif = $row_c['tarif'];
$pokok= $p*$l*$s*$jlh*$lama*$tarif;
$outlet_id=$row_c['outlet_id'];
?>
<tr><td><span class="style8">&nbsp;<?php echo $no; ?></span></td><td align="justify"><span class="style8">&nbsp;<?php echo $row_c['nm_reklame']; ?>&nbsp;, &nbsp;<?php echo $row_c['alamat_reklame']; ?></span></td><td align="center"><span class="style8"><?php echo $row_c['p']."m"; ?>&nbsp;x&nbsp;<?php echo $row_c['l']."m"; ?>&nbsp;x&nbsp;<?php echo $row_c['s']."sisi"; ?>&nbsp;</span></td><td align="center"><span class="style4">&nbsp;<?php echo $bulan[date('n',strtotime($row_c['tmt_akhir']))]; ?></span></td><td align="right"><span class="style8">&nbsp;<?php echo number_format($pokok); ?></span></td></tr>

<?php 


}
pg_free_result($tampil_a);
?>
<tr>
</td></table></tr>
<tr><td colspan="3" align="justify" class="style4"><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sehubungan dengan hal tersebut diatas, diminta perhatian Saudara untuk datang ke Badan Keuangan Daerah Kota Payakumbuh Cq. Bidang Pendapatan yang beralamat di<b> Jl. Veteran No 70 ( Kantor Balai Kota Baru Lantai 3) Kota Payakumbuh </b>paling lambat<b> 5 (lima) hari </b> setelah diterimanya surat ini guna memberikan konfirmasi perpanjangan atau tidak memperpanjang reklame tersebut. Apabila ada perubahan terhadap data objek reklame diatas dapat dilakukan melalui formulir pemutakhiran sebagaimana terlampir.</td></tr>
  </table>
   <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Demikianlah kami sampaikan , atas perhatian dan kerjasama yang baik kami ucapkan terima kasih.</td></tr>
<br/>
</table>

<table width="100%"  class="noborder">
  <tr>
    <td colspan="2" class="font11" align="center" > <strong>KETERANGAN OBJEK PAJAK REKLAME </strong></td>
    <td width="26%" class="font11" align="center">WAJIB PAJAK / PENANGGUNG JAWAB </td>
    <td width="39%" class="font13" align="center"><?php
	$nip = $row['nip_pejabat'];
	$sql = "SELECT * FROM public.user WHERE nip='$nip'";
	$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
	$row_user = pg_fetch_array($result);
	echo $row_user['jabatan'];
	//echo "a.n KEPALA BADAN KEUANGAN DAERAH<";
	?>     </td>
  </tr>
  <tr>
    <td width="5%" align="center" valign="top" ><img src="../../images/box.png"></td>
    <td width="30%" class="font10">PERPANJANGAN REKLAME (DATA SUDAH SESUAI) </td>
    <td rowspan="5">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="middle" ><img src="../../images/box.png"></td>
    <td width="30%" class="font10">PERUBAHAN WAJIB PAJAK  </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="middle" ><img src="../../images/box.png"></td>
    <td width="30%" class="font10">PERUBAHAN MERK REKLAME </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="middle" ><img src="../../images/box.png" /></td>
    <td class="font10">PERUBAHAN UKURAN </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="middle" ><img src="../../images/box.png" /></td>
    <td class="font10">PERUBAHAN JENIS REKLAME </td>
    <td class="font12">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="middle" ><img src="../../images/box.png" /></td>
    <td class="font10">OBJEK SUDAH TIDAK ADA </td>
    <td class="font10" align="center">( ........................................) </td>
    <td class="font12" align="center"><?php  echo "<u>".$row_user['nama']."</u>"; ?>
      </br>
NIP.&nbsp;<?php echo $row_user['nip']; ?></td>
  </tr>
</table>


  </td>
   

</tr>

</table><br/>
<table align="left">
	<tr><td align="left"><span class="style5">Tembusan disampaikan kepada Yth:</span></td>
	</tr>
	<tr><td><span class="style5">&nbsp;&nbsp;&nbsp;&nbsp;<em>1. Bpk. Walikota Payakumbuh di Payakumbuh</em></span></td>
	</tr>
	<tr><td><span class="style5">&nbsp;&nbsp;&nbsp;&nbsp;2. <em>Bpk. Ka Dinas Satpol PP dan Pemadam Kebakaran Kota Payakumbuh di Payakumbuh</em></span></td>
	</tr>
	<tr><td><span class="style5">&nbsp;&nbsp;&nbsp;&nbsp;3. <em>Arsip</em></span></td>
	</tr>
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