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
include_once $base_dir."inc/fungsi_indotgl.php";
$thn = pg_escape_string($_GET['thn']);
$bln = pg_escape_string($_GET['bln']);


$sql = "SELECT A.*,B.nik,B.nama,B.alamat,B.telp,B.tgl_daftar,B.kd_provinsi,B.kd_kota,B.no_reg as no_reg_b FROM hotel.dat_obj_pajak A INNER JOIN public.wp B ON
 A.kd_provinsi=B.kd_provinsi AND
 A.kd_kota=B.kd_kota AND
 A.kd_jns=B.kd_jns AND
 A.no_reg_wp=B.no_reg";
$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
$n_row = pg_num_rows($tampil);
if($n_row <= 0) {
	echo "SKP BELUM ADA UNTUK TAHUN ".$thn." BULAN ".$bln;
	pg_free_result($tampil);
	pg_close($dbconn);
	exit;
}
$x = 1;

?>
<style type="text/css">
table.noborder td, table.noborder th, table.noborder tr { border:none; font-family:"Verdana"; font-size:11px; }

.skptable { border-collapse: collapse; }

.skptable td, .skptable th {
    border-style:solid;
    border-width:1px;
    border-color:#000;
}

.garis
	{
		border:1px solid black;
		border-collapse:collapse;
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
</style>
<?php
 while ($row = pg_fetch_array($tampil)) { 

 $pembukuan=$row['pembukuan'];
 if($pembukuan == 1){
$pmbk="Pembukuan"; }
  if($pembukuan == 2){
$pmbk="Pencatatan"; }
  if($pembukuan == 3){
$pmbk="Cash Register"; }

 $parkir=$row['parkir'];
 if($parkir == 2){
$pkr="Berbayar"; }
  if($pembukuan == 1){
$pkr="Gratis"; }


$kd_kecamatan=$row['kd_kecamatan'];
$kd_kelurahan=$row['kd_kelurahan'];
$kd_obj_pajak=$row['kd_obj_pajak'];
$kd_keg_usaha=$row['kd_keg_usaha'];
$no_reg=$row['no_reg'];
?>
<div style="width:100%;height:300mm;">
  <table width="100%" border="0" class="skptable">
  <tr>
  <td align="center"><img src="../../images/payakumbuh.png" height="60"></td>
    <td height="25" align="center">
<div class="font14">&nbsp;PEMERINTAH KOTA PAYAKUMBUH</div>
<div class="font18">&nbsp;BADAN KEUANGAN DAERAH</div>
<div class="font12">&nbspJln. Veteran Balaikota Payakumbuh</div>
</td>
    </tr>
</table>
<table width="100%" border="0" class="skptablesub">
  <tr>
    <td>
<table width="100%" border="0" class="noborder" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;">
  <tr>
    <td width="2%">A.</td>
    <td colspan="3">DATA OBJEK PAJAK</td>
    </tr>	
  <tr>
    <td width="2%">&nbsp;</td>
    <td width="28%">NAMA HOTEL</td>
    <td width="1%">:</td>
    <td width="69%"><?php echo $row['nm_usaha']; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>ALAMAT HOTEL </td>
    <td>:</td>
    <td><?php echo $row['alamat_usaha']; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>TANGGAL DAFTAR </td>
    <td>:</td>
    <td><?php echo date('d',strtotime($row['tgl_daftar']))." ".$bulan[date('n',strtotime($row['tgl_daftar']))]." ".date('Y',strtotime($row['tgl_daftar'])); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>NOP</td>
    <td>:</td>
    <td align="justify"><?php echo $row['kd_kecamatan'].".".$row['kd_kelurahan'].".".$row['kd_obj_pajak'].".".$row['kd_keg_usaha'].".".$row['no_reg']; ?></td>
  </tr>  
  <tr>
    <td>&nbsp;</td>
    <td>METODE PEMBUKUAN </td>
    <td>:</td>
    <td><?php echo $pmbk; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>SUMBER AIR </td>
    <td>:</td>
    <td><?php echo $row['sumber_air']; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>LUAS TEMPAT USAHA </td>
    <td>:</td>
    <td>BUMI&nbsp;<?php echo $row['bumi']; ?>&nbsp;m2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BANGUNAN&nbsp;<?php echo $row['bangunan']; ?>&nbsp;m2</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>KARYAWAN </td>
    <td>:</td>
    <td><?php echo $row['karyawan']; ?>&nbsp;orang</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>OCCUPANCY KAMAR PER HARI </td>
    <td>:</td>
    <td><?php echo $row['occupancy']; ?>&nbsp;%</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>FASILITAS PARKIR </td>
    <td>:</td>
    <td><?php echo $pkr; ?>&nbsp;</td>
  </tr>
</table>
	</td>
  </tr>
</table>
<table width="100%" border="0" class="skptablesub">
  <tr>
    <td width="55%" align="center" class="font11">
	<table width="100%" border="0" class="noborder" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;">
      <tr>
        <td width="3%">B.</td>
        <td colspan="3">INFORMASI UMUM WAJIB PAJAK DAERAH </td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td width="40%">SURAT IZIN NOMOR </td>
        <td width="2%">:</td>
        <td width="55%"><?php  echo $row['izin'];?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>TANGGAL SURAT IZIN </td>
        <td>:</td>
        <td>................................................</td>
      </tr>
    </table></td>
    <td width="45%" class="font11" style="padding-left:20px;padding-bottom:5px;padding-top:5px;padding-right:20px;">BERLAKU S/D TANGGAL .................... </td>
  </tr>  
</table>
<table width="100%" border="0" class="skptablesub">
  <tr>
    <td colspan="2" class="font11" style="padding-left:20px;padding-bottom:5px;padding-top:5px;padding-right:20px;">
	
	TIPE ,JUMLAH DAN TARIF KAMAR HOTEL
		<table width="100%" border="0" class="skptablesub font11" style="border-top:1px;border-top-color:#000000;border-style:solid;">
		<tr>
			<td align="center">No</td>
			<td align="center">Type Kamar</td>
			<td align="center">Spesifikasi</td>
			<td align="center">Unit</td>
			<td align="center">Tarif</td>
			<td align="center">Keterangan</td>
		</tr>
		<?php 
		$no=1;
		$sql_kamar = "SELECT * FROM hotel.kamar WHERE kd_kecamatan='$kd_kecamatan' AND kd_kelurahan='$kd_kelurahan' AND kd_obj_pajak='$kd_obj_pajak' AND kd_keg_usaha='$kd_keg_usaha' AND no_reg='$no_reg'";
$tampil_kamar = pg_query($sql_kamar) or die('Query failed: ' . pg_last_error());
while($row_k=pg_fetch_array($tampil_kamar))

{   ?>
		<tr>
		  <td align="center">&nbsp;<?php echo $no; ?></td>
		  <td align="center">&nbsp;<?php echo $row_k['tipe']; ?></td>
		  <td align="center">&nbsp;</td>
		  <td align="center">&nbsp;<?php echo $row_k['unit']; ?></td>
		  <td align="center">&nbsp;<?php echo number_format($row_k['tarif']); ?></td>
		  <td align="center">&nbsp;</td>
		  </tr>
		<?php $no++;} ?>
		</table>
		<br>
		
	</td>
  </tr>
  <tr>
    <td>
		<table width="100%" border="0" class="noborder" style="padding-left:5px;padding-top:5px;padding-right:20px;padding-bottom:5px;">
			<tr>
				<td width="2%">C.</td>
				<td colspan="5">DATA WAJIB PAJAK</td>
			</tr>
			
			<tr>
			  <td>&nbsp;</td>
			  <td width="35%">NPWPD </td>
			  <td width="1%">:</td>
			  <td ><?php echo $row['kd_provinsi'].".".$row['kd_kota'].".".$row['kd_jns'].".".$row['no_reg_b']; ?></td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td>NIK </td>
			  <td>:</td>
			  <td><?php echo $row['nik']; ?></td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td>NAMA PEMILIK </td>
			  <td>:</td>
			  <td><?php echo $row['nama']; ?></td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td>ALAMAT </td>
			  <td>:</td>
			  <td><?php echo $row['alamat']; ?></td>
		  </tr>
		  <tr>
			  <td>&nbsp;</td>
			  <td>NOMOR KANTOR </td>
			  <td>:</td>
			  <td><?php echo $row['no_telp']; ?></td>
		  </tr>
		  <tr>
			  <td>&nbsp;</td>
			  <td>NOMOR HANDPHONE </td>
			  <td>:</td>
			  <td><?php echo $row['telp']; ?></td>
		  </tr>
		</table>
	</td>
  </tr>
  <tr>
    <td>
	<table width="100%" border="0" class="noborder" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;">
      <tr>
        <td>D.</td>
        <td colspan="4">KETERANGAN LAIN-LAIN</td>
        </tr>
      <tr>
        <td width="2%" style="vertical-align:top">&nbsp;</td>
      </tr>	  
    </table></td>	
  </tr>
  <tr>
  <td style="padding-left:20px;padding-top:5px;padding-right:20px;padding-bottom:5px;" class="font11">Dengan menyadari sepenuhnya akan akibatnya termasuk sanksi sesuai peraturan perundang-undangan yang berlaku,maka saya menyatakan data yang diisikan beserta lampiran-lampiranya adlah BENAR dan LENGKAP.</td>
  </tr>
</table>
<table width="100%" border="0" class="noborder">
  <tr>
    <td width="35%">&nbsp;</td>
    <td width="30%">&nbsp;</td>
    <td width="35%">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"> </td>
    <td>&nbsp;</td>
    <td align="center"><?php echo "Payakumbuh, ".date('d',strtotime($row['tgl_daftar']))." ".$bulan[date('n',strtotime($row['tgl_daftar']))]." ".date('Y',strtotime($row['tgl_daftar'])); ?></td>
  </tr>
  <tr>
    <td align="center"> </td>
    <td>&nbsp;</td>
    <td align="center">WAJIB PAJAK 
	 </td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center"></td>
    <td>&nbsp;</td>
    <td align="center"><?php echo "<u>".strtoupper($row['nama'])."</u>"; ?></td>
  </tr>
  <tr>
    <td align="center"></td>
    <td>&nbsp;</td>
    <td align="center"></td>
  </tr>  
</table>
</div>
<div style=\"page-break-before:always;\"></div>

<?php

if($x != $n_row) {
	echo "<div style=\"page-break-before:always;\"></div>";
	$x++;
}

}
pg_free_result($tampil);
pg_close($dbconn);


?>