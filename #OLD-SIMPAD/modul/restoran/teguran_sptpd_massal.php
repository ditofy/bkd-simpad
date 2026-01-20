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
include_once $base_dir."inc/schema.php";

$thn = pg_escape_string($_GET['thn']);
$bln = pg_escape_string($_GET['bln']);
$sql = "SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg as NOP,A.bln_pajak,A.thn_pajak,A.tgl_surat, C.kd_provinsi||'.'||C.kd_kota||'.'||C.kd_jns||'.'||C.no_reg as npwpd,B.alamat_usaha,B.nm_usaha,D.nm_obj_pajak,D.perda,A.nip_p FROM restoran.teguran_sptpd A INNER JOIN restoran.dat_obj_pajak B ON
A.kd_kecamatan=B.kd_kecamatan AND
A.kd_kelurahan=B.kd_kelurahan AND
A.kd_obj_pajak=B.kd_obj_pajak AND
A.kd_keg_usaha=B.kd_keg_usaha AND
A.no_reg=B.no_reg INNER JOIN public.wp C ON
C.kd_provinsi=B.kd_provinsi AND
C.kd_kota=B.kd_kota AND
C.kd_jns=B.kd_jns AND
C.no_reg=B.no_reg_wp
LEFT JOIN public.obj_pajak D ON
D.kd_obj_pajak=B.kd_obj_pajak 
WHERE A.thn_pajak='$thn' AND A.bln_pajak='$bln' ";
$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
$n_row = pg_num_rows($tampil);
if($n_row <= 0) {
	echo "SURAT TEGURAN STPD BELUM ADA UNTUK TAHUN ".$thn." BULAN ".$bln;
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
.style1 {
	font-size: 18px;
	font-weight: bold;
}
.style2 {
	font-size: 34px;
	font-weight: bold;
	
}
.style3 {
	font-size: 16px;
	font-weight: bold;
}
.style5 {font-size: 12px}
</style>
<?php
while ($row = pg_fetch_array($tampil)) {
?>
<div style="width:100%;height:300mm;">
  <table width="100%" border="0" class="noborder">
  <tr>
  <td width="20"><img src="../../images/payakumbuh.png" height="60"></td>  <td height="50" align="center"><span class="style1">PEMERINTAH KOTA PAYAKUMBUH</span><br/>
    <span class="style2">BADAN KEUANGAN DAERAH</span><br/>
    <span class="style3">Jln.Soekarno Hatta Bukik Sibaluik -Payakumbuh </span></td>
    </tr>
	
</table>
<div align="center"><img width="100%" height="6" src="../../images/garis.gif"></div><br/>
<table width="100%" border="0" class="noborder">
  <tr>
    <td>
<table width="100%" border="0" class="noborder" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;">
  <tr>
    <td width="7%">Nomor</td>
    <td width="1%">:</td>
    <td width="32%">973/<?php echo $row['bln_pajak']."/ &nbsp;&nbsp;&nbsp;/BKD-PYK/".$row['thn_pajak']."";?></td>
	<td width="50%" align="right" rowspan="4"><?php echo "Payakumbuh, ".date('d',strtotime($row['tgl_surat']))." ".$bulan[date('n',strtotime($row['tgl_surat']))]." ".date('Y',strtotime($row['tgl_surat'])); ?><br/><div align="center" style="margin-left:100px;">Kepada Yth: <br/></div>
	<div align="left" style="margin-left:100px;">MANAJEMEN&nbsp;<b><?php echo $row['nm_usaha']; ?></b></br/></div>
	<div align="left" style="margin-left:100px;">NPWPD&nbsp;:&nbsp;<b><?php echo $row['npwpd']; ?></b></br/></div>
	<div align="left" style="margin-left:100px;">ALAMAT&nbsp;:&nbsp;<b><?php echo $row['alamat_usaha']; ?></b></br/></div>
	<div align="left" style="margin-left:190px;">di,- <br/></div><div align="center" style="margin-left:110px;"><u>Tempat</u></div></td>
  </tr>	
  <tr>
    <td width="5%">Lamp</td>
    <td width="1%">:</td>
    <td width="15%">-</td>
  </tr>
  <tr>
    <td width="5%" valign="top">Perihal</td>
    <td width="1%" valign="top">:</td>
    <td width="25%"><b>Pemberitahuan/Teguran</b></td>
  </tr>
</table>
	</td>
  </tr>
</table><br/><br/><br/>
<table width="100%" border="0" class="noborder" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;">
  <tr>
  
    <td align="left" class="font11"><div style="margin-left:80px;">Dengan Hormat</div></td>
	<tr height="20"><td></td></tr>
    </tr>
  <tr><td align="justify"><div style="margin-left:80px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sesuai dengan Ketentuan &nbsp;<?php echo $row['perda'];?>, maka kami memberitahukan kepada Wajib Pajak agar menyampaikan SPTPD beserta Data Pendukung yang benar dan lengkap serta melakukan pembayaran pajak berdasarkan SPTPD paling lambat<b> 15 (lima belas) hari</b> setelah berakhirnya masa pajak.</b></div></td></tr>
  <tr height="20"><td></td></tr>
	<tr><td align="justify"><div style="margin-left:80px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Berdasarkan hal tersebut diatas, diberitahukan kepada saudara agar segera menyampaikan SPTPD beserta data pendukung yang lengkap dan benar untuk bulan <b> <?php echo strtoupper($bulan_s[$row['bln_pajak']]);?> </b>Tahun<b> <?php echo $row['thn_pajak'];?> </b>paling lambat tanggal 15 (lima belas) bulan <?php echo "<b>Juni 2019</b>";//echo strtoupper($bulan_s[$row['bln_pajak']+1]);?></div></td></tr>
	<tr height="20"><td></td></tr>
	<tr><td align="justify"><div style="margin-left:80px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Apabila SPTPD tidak disampaikan dalam jangka waktu sebagaimana dimaksud diatas, Wajib Pajak akan dikenakan sanksi administratif berupa bunga sebesar 2% (dua persen) sebulan dihitung dari Pajak yang kurang atau terlambat dibayar unutuk jangka waktu paling lama 24(dua puluh empat) bulan dihitung sejak saat terutangnya Pajak.</div></td></tr>
	<tr height="20"><td></td></tr>
	<tr><td align="justify"><div style="margin-left:80px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Apabila kewajiban mengisi SPTPD tidak dipenuhi, Pajak yang terutang dihitung secara Jabatan dan dikenakan sanksi administratif berupa kenaikan sebesar 25% (dua puluh lima persen) dari Pokok Pajak ditambah sanksi administratif berupa bunga sebesar 2% (dua persen) sebulan dihitung dari pajak yang kurang atau terlambat dibayar untuk jangka waktu paling lama 24 (dua puluh empat) bulan dihitung sejak saat terutangnya Pajak.</div></td></tr>
	<tr height="20"><td></td></tr>
	<tr><td align="justify"><div style="margin-left:80px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Apabila Wajib Pajak sudah memenuhi kewajiban Perpajakan sebagaimana dimaksud diatas, maka surat ini dapat diabaikan.</div></td></tr>
	<tr height="20"><td></td></tr>
	<tr><td align="justify"><div style="margin-left:80px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Demikianlah untuk diperhatikan dan dilaksanakan, atas perhatiannya kami ucapkan terima kasih , atas perhatian dan kerjasama yang baik kami ucapkan terima kasih.</div></td></tr>
  </table>
<br/><br/>
<table align="right">


	<tr><td align="center"><?php
	$nip = $row['nip_p'];
	$sql = "SELECT * FROM public.user WHERE nip='$nip'";
	$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
	$row_user = pg_fetch_array($result);
	echo $row_user['jabatan'];
	?>	</td></tr>
	<tr height="70"><td></td></tr>
	<tr><td align="center"> <?php echo "<u>".$row_user['nama']."</u>"; ?></td></tr>
	<tr><td align="center">NIP.&nbsp;<?php echo $row_user['nip']; ?></td></tr>
</table>




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