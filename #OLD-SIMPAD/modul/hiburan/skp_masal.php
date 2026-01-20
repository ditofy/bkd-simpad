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

$thn = pg_escape_string($_GET['thn']);
$bln = pg_escape_string($_GET['bln']);
//list($jns_surat,$thn_pajak,$bln_pajak,$kd_obj_pajak,$no_urut_skp) = explode(".", $no_skp);
$sql = "SELECT A.*,B.nama,B.alamat,C.rek_apbd FROM hiburan.skp A INNER JOIN public.wp B ON
B.kd_provinsi=A.kd_provinsi AND
B.kd_kota=A.kd_kota AND
B.kd_jns=A.kd_jns AND
B.no_reg=A.no_reg_wp
LEFT JOIN public.keg_usaha C ON
C.kd_obj_pajak=A.kd_obj_pajak AND
C.kd_keg_usaha=A.kd_keg_usaha
WHERE A.thn_pajak='$thn' AND A.bln_pajak='$bln' ORDER BY jns_surat,thn_pajak,bln_pajak,kd_obj_pajak,no_urut_surat";
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
?>
<div style="width:100%;height:300mm;">
  <table width="100%" border="0" class="skptable">
  <tr>
    <td align="center">
<pre class="font10" style="line-height: 1.6em;">
PEMERINTAH KOTA PAYAKUMBUH
BADAN KEUANGAN DAERAH
KOTA PAYAKUMBUH
JL. SOEKARNO HATTA BUKIK SIBALUIK
PAYAKUMBUH
</pre>
	</td>
    <td align="center">
<pre>
<strong class="font18">S K P D</strong>
<strong class="font14">(SURAT KETETAPAN PAJAK DAERAH)</strong>
</pre>
	</td>
    <td align="center">
<pre class="font14">
<strong>No. Urut</strong>
<?php echo $row['jns_surat'].".".$row['thn_pajak'].".".$row['bln_pajak'].".".$row['kd_obj_pajak'].".".$row['no_urut_surat']; ?>
</pre>
	</td>
  </tr>
</table>
<table width="100%" border="0" class="skptablesub">
  <tr>
    <td>
<table width="100%" border="0" class="noborder" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;">
  <tr>
    <td width="21%">JENIS PAJAK</td>
    <td width="1%">:</td>
    <td width="78%">PAJAK HIBURAN</td>
  </tr>	
  <tr>
    <td width="21%">NOP</td>
    <td width="1%">:</td>
    <td width="78%"><?php echo $row['kd_kecamatan'].".".$row['kd_kelurahan'].".".$row['kd_obj_pajak'].".".$row['kd_keg_usaha'].".".$row['no_reg']; ?></td>
  </tr>
  <tr>
    <td>TAHUN</td>
    <td>:</td>
    <td><?php echo $row['thn_pajak']; ?></td>
  </tr>
  <tr>
    <td>MASA</td>
    <td>:</td>
    <td><?php 
	if($row['bln_pajak'] == '01') { 
			  	echo strtoupper($bulan[$row['bln_pajak']-1])." ".($row['thn_pajak']-1);
			  } else {
			  	echo strtoupper($bulan[$row['bln_pajak']-1])." ".($row['thn_pajak']);
			  }
	?></td>
  </tr>
  <tr>
    <td>NPWPD</td>
    <td>:</td>
    <td><?php echo $row['kd_provinsi'].".".$row['kd_kota'].".".$row['kd_jns'].".".$row['no_reg_wp']; ?></td>
  </tr>
  <tr>
    <td>NAMA</td>
    <td>:</td>
    <td><?php echo $row['nama']; ?></td>
  </tr>
  <tr>
    <td>ALAMAT</td>
    <td>:</td>
    <td align="justify"><?php echo $row['alamat']; ?></td>
  </tr>  
  <tr>
    <td>TANGGAL JATUH TEMPO </td>
    <td>:</td>
    <td><?php echo date('d',strtotime($row['tgl_jatuh_tempo']))." ".strtoupper($bulan[date('n',strtotime($row['tgl_jatuh_tempo']))])." ".date('Y',strtotime($row['tgl_jatuh_tempo'])); ?></td>
  </tr>
</table>
	</td>
  </tr>
</table>
<table width="100%" border="0" class="skptablesub">
  <tr>
    <td width="4%" align="center" class="font11">NO</td>
    <td width="14%" align="center" class="font11">KODE REKENING</td>
    <td width="67%" align="center" class="font11">URAIAN PAJAK</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td valign="top" align="center" class="font11" style="padding-left:5px;padding-top:10px;padding-right:5px;padding-bottom:5px;"><?php echo $row['rek_apbd']; ?></td>
    <td>
<table width="100%" border="0" class="noborder" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;">
  <tr>
    <td colspan="12" align="justify"><?php
	if($row['bln_pajak'] == '01') { 
			echo "PAJAK HIBURAN ".$row['nm_usaha']." DI ".$row['alamat_usaha']." MASA PAJAK BULAN ".strtoupper($bulan[$row['bln_pajak']-1])." ".($row['thn_pajak']-1);
			  } else {
			  	echo "PAJAK HIBURAN ".$row['nm_usaha']." DI ".$row['alamat_usaha']." MASA PAJAK BULAN ".strtoupper($bulan[$row['bln_pajak']-1])." ".($row['thn_pajak']);
			  } 	
	?></td>
    </tr>
  <tr>
    <td width="12%" height="500">&nbsp;</td>
    <td width="10%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
    <td width="10%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
    <td width="10%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
    <td width="10%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
    <td width="10%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
    <td width="28%">&nbsp;</td>
  </tr>
</table>	</td>
    </tr>
</table>
<table width="100%" border="0" class="skptablesub">
  <tr>
    <td colspan="2" class="font12" style="padding-left:20px;padding-bottom:5px;padding-top:5px;padding-right:5px;"><?php echo "Jumlah Ketetapan Pokok Pajak : <strong>Rp. ".number_format($row['pokok_pajak'])."</strong>"; ?></td>
  </tr>
  <tr>
    <td colspan="2" class="font12" style="padding-left:20px;padding-bottom:5px;padding-top:5px;padding-right:5px;"><?php echo "Dengan Huruf : <strong><i># ".terbilang($row['pokok_pajak'], $style=3)." Rupiah #</i></strong>"; ?></td>
  </tr>
  <tr>
    <td colspan="2" class="font12" style="padding-left:20px;padding-bottom:5px;padding-top:5px;padding-right:5px;">
	<table width="100%" border="0" class="noborder">
      <tr>
        <td colspan="2">PERHATIAN : </td>
        </tr>
      <tr>
        <td width="3%" style="vertical-align:top">1.</td>
        <td width="97%">Harap penyetoran dilakukan pada Bank / Bendahara Penerima .................................................... </td>
      </tr>
      <tr>
        <td style="vertical-align:top">2.</td>
        <td>Apabila SKPD ini tidak atau kurang dibayar lewat waktu paling lama 30 hari sejak SKPD diterima (tanggal jatuh tempo) dikenakan sanksi administrasi berupa bunga sebesar 2% perbulan. </td>
      </tr>
    </table></td>
  </tr>
</table>
<table width="100%" border="0" class="noborder">
  <tr>
    <td width="35%">&nbsp;</td>
    <td width="30%">&nbsp;</td>
    <td width="35%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center"><?php echo "Payakumbuh, ".date('d',strtotime($row['tgl_penetapan']))." ".$bulan[date('n',strtotime($row['tgl_penetapan']))]." ".date('Y',strtotime($row['tgl_penetapan'])); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">An. KEPALA BADAN KEUANGAN DAERAH
	KOTA PAYAKUMBUH
	 </td>
  </tr>
  <tr>
    <td><span style="padding-left:5px;">TANDA TERIMA </span></td>
    <td>&nbsp;</td>
    <td align="center">
	<?php
	$nip = $row['nip_pejabat'];
	$sql = "SELECT * FROM public.user WHERE nip='$nip'";
	$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
	$row_user = pg_fetch_array($result);
	echo $row_user['jabatan'];
	?>	</td>
  </tr>
  <tr>
    <td><span style="padding-left:15px;">NAMA : <?php echo strtoupper($row['nama']); ?></span></td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center"><?php echo "<u>".strtoupper($row_user['nama'])."</u>"; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center"><?php echo "NIP. ".$row_user['nip']; ?></td>
  </tr>
  <tr>
    <td style="padding-left:5px;">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-left:15px;">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
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