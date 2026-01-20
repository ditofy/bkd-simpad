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

$no_stpd = pg_escape_string($_GET['no_stpd']);
list($jns_surat,$thn_pajak,$bln_pajak,$kd_obj_pajak,$no_urut_stpd) = explode(".", $no_stpd);
$sql = "SELECT A.*,C.nama,C.alamat,D.rek_apbd,B.no_urut_surat FROM reklame.stpd A INNER JOIN reklame.skp B ON
A.kd_kecamatan=B.kd_kecamatan AND
A.kd_kelurahan=B.kd_kelurahan AND
A.kd_obj_pajak=B.kd_obj_pajak AND
A.kd_keg_usaha=B.kd_keg_usaha AND
A.no_reg=B.no_reg AND
A.bln_pajak=B.bln_pajak AND
A.thn_pajak=B.thn_pajak 
INNER JOIN public.wp C ON
C.kd_provinsi=A.kd_provinsi AND
C.kd_kota=A.kd_kota AND
C.kd_jns=A.kd_jns AND
C.no_reg=A.no_reg_wp
LEFT JOIN public.keg_usaha D ON
D.kd_obj_pajak=A.kd_obj_pajak AND
D.kd_keg_usaha=A.kd_keg_usaha
WHERE A.jns_surat='$jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_stpd' AND A.status_pembayaran < '2'";
$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
if(pg_num_rows($tampil) <= 0) {
	echo "NO. SKP TIDAK DITEMUKAN, SILAHKAN PERIKSA KEMBALI NO. SKP";
	pg_free_result($tampil);
	pg_close($dbconn);
	exit;
}
$row = pg_fetch_array($tampil);
//denda
$timeStart=strtotime($row['tmt_awal']);
//$timeStart = strtotime("Y-m-d");
$timeEnd = strtotime($row['tgl_penetapan']);
// Menambah bulan ini + semua bulan pada tahun sebelumnya
$numBulan = 0 + (date("Y",$timeEnd)-date("Y",$timeStart))*12;
// menghitung selisih bulan
$numBulan += date("m",$timeEnd)-date("m",$timeStart);
$total=$row['denda']+$row['pokok_pajak'];
?>
<style type="text/css">
table.noborder td, table.noborder th, table.noborder tr { border:none; font-family:"Verdana"; font-size:12px; }

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
	font-size:20px;
}
</style>
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
<strong class="font18">S T P D</strong>
<strong class="font14">(SURAT TAGIHAN PAJAK DAERAH)</strong>
</pre>
	</td>
    <td align="center">
<pre class="font14">
<strong>No. Urut</strong>
<?php echo "$no_stpd"; ?>
</pre>
	</td>
  </tr>
</table>
<table width="100%" border="0" class="skptablesub">
  <tr>
    <td><br />
<table width="100%" border="0" class="noborder" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;">
  <tr>
    <td width="21%">JENIS PAJAK</td>
    <td width="1%">:</td>
    <td width="78%">PAJAK REKLAME</td>
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
    <td><?php echo strtoupper($bulan_s[$row['bln_pajak']])." ".($row['thn_pajak']); ?></td>
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
    <td>MASA BERLAKU</td>
    <td>:</td>
    <td><?php echo date('d',strtotime($row['tmt_awal']))." ".$bulan[date('n',strtotime($row['tmt_awal']))]." ".date('Y',strtotime($row['tmt_awal']))." s/d ".date('d',strtotime($row['tmt_akhir']))." ".$bulan[date('n',strtotime($row['tmt_akhir']))]." ".date('Y',strtotime($row['tmt_akhir'])); ?></td>
  </tr>
  <tr>
    <td>TANGGAL JATUH TEMPO </td>
    <td>:</td>
    <td><?php echo date('d',strtotime($row['tmt_awal']))." ".$bulan[date('n',strtotime($row['tmt_awal']))]." ".date('Y',strtotime($row['tmt_awal'])); ?></td>
  </tr>
</table><br />
	</td>
  </tr>
</table>
<table width="100%" border="0" class="skptablesub">

  <tr>
    <td colspan="2" class="font18" style="padding-left:20px;padding-bottom:5px;padding-top:5px;padding-right:5px;"><br />
	<table class="font11 noborder" ><tr><td valign="top">I.</td><td> Berdasarkan Pasal 12 dan Pasal 13 Peraturan Daerah Kota Payakumbuh No 8 Tahun 2011 Tentang Pajak Reklame Telah Dilakukan Penelitian dan/atau Pemeriksaan atau Keterangan Lain atas Pelaksanaan Kewajiban Perpajakan Daerah<br/><br/></td></tr>
	<tr><td valign="top">
	II.</td><td>Dari Penelitian / Pemeriksaan atau Keterangan Lain tersebut diatas, penghitungan jumlah Pajak Terhutang Yang Masih Harus Dibayar adalah sebagai berikut:<br/><br/>
	<table class="font11 skptable"><tr><td valign="top">1.</td><td colspan="2">Pokok Pajak Yang Harus Dibayar Berdasarkan SKPD Nomor:&nbsp;<?php echo"01.".$row['thn_pajak'].".".$row['bln_pajak'].".".$row['kd_obj_pajak'].".".$row['no_urut_surat']; ?>&nbsp;atas Objek Pajak &nbsp<?php echo $row['nm_reklame']." YANG DIPASANG DI ".$row['alamat_reklame'];?> <br/></td><td></td></tr><tr height="5"><td></td></tr>
	<tr><td valign="middle">-</td><td>Dengan Ukuran : <?php echo $row['p']."m"; ?>&nbsp;x&nbsp;<?php echo $row['l']."m"; ?>&nbsp;x&nbsp;<?php echo $row['s']."sisi"; ?>&nbsp;x&nbsp;<?php echo $row['jlh']."bh"; ?>&nbsp;x&nbsp;<?php echo $row['lama_pasang']; ?>&nbsp;&nbsp;=&nbsp;<?php echo $row['p']*$row['l']*$row['s']*$row['jlh']*$row['lama_pasang']."m&sup2;"; ?><br/></td><td></td></tr>
	<tr><td valign="middle">-</td><td>Dengan Perkalian : <?php echo $row['p']*$row['l']*$row['s']*$row['jlh']*$row['lama_pasang']."m&sup2;"; ?>&nbsp;x&nbsp;<?php echo number_format($row['tarif']); ?> </td><td><?php  echo "<strong>Rp. ".number_format($row['pokok_pajak'])."</strong>"?><br/></td></tr><tr height="10"><td></td></tr>
	<tr><td>2.</td><td>Telah Dibayar Tanggal</td><td><strong>Rp.&nbsp;&nbsp;&nbsp;&nbsp;-</strong></td><td></td></tr><tr height="10"><td></td></tr>
	<tr><td>3.</td><td>Kurang Bayar (1-2)</td><td><strong><?php  echo "Rp. ".number_format($row['pokok_pajak']).""?></strong></td><td></td></tr><tr height="10"><td></td></tr>
	<tr><td>4.</td><td>Sanksi Administrasi : Denda = <?php echo $numBulan;?> Bulan x 2%</td><td><?php  echo "<strong>Rp.&nbsp;&nbsp; ".number_format($row['denda'])."</strong>"?></td><td></td></tr><tr height="10"><td></td></tr>
	<tr><td>5.</td><td>Jumlah Yang Harus Dibayar </td><td><?php  echo "<strong>Rp. ".number_format($total)."</strong>"?></td><td></td></tr>
	</table></td></tr>
	
	</table></td>
  </tr>
 
      
      
    </table>

<table width="100%" border="0" class="skptablesub">
  <tr>
    <td colspan="2" class="font12" style="padding-left:20px;padding-bottom:5px;padding-top:5px;padding-right:5px;"><?php echo "Dengan Huruf : <strong><i># ".terbilang($total, $style=3)." Rupiah #</i></strong>"; ?></td>
  </tr>
  <tr>
    <td colspan="2" class="font12" style="padding-left:20px;padding-bottom:5px;padding-top:5px;padding-right:5px;">
	<table width="100%" border="0" class="noborder">
      <tr>
        <td colspan="2"><strong>PERHATIAN : </strong></td>
        </tr><tr height="10"><td></td></tr>
      <tr>
        <td width="3%" style="vertical-align:top">1.</td>
        <td width="97%" align="justify">Harap penyetoran dilakukan melalui Bendahara Penerima Bdan Keuangan Daerah Kota Payakumbuh (Kantor Bidang Pendapatan/UPTB Pajak Daerah Jln.Pemuda ex.Lapangan Poliko Kota Payakumbuh) atau ke Kas Daerah pada Bank NAGARI Rek. No : 0100.0101.00201.6  dengan menggunakan Surat Setoran Pajak Daerah (SSPD)</td>
      </tr>
      <tr height="25"><td></td></tr>
        <td style="vertical-align:top">2.</td>
        <td align="justify">Apabila STPD ini tidak atau kurang dibayar setelah lewat waktu 30 (Tiga Puluh) hari sejak STPD ini diterima selanjutnya akan dilakukan tindakan sesuai dengan peraturan perundang-undangan yang berlaku.</td>
      </tr><tr height="10"><td></td></tr>
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
	KOTA PAYAKUMBUH </td>
  </tr>
  <tr>
    <td><span style="padding-left:5px;">TANDA TERIMA</span></td>
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
    <td>&nbsp;Tanggal</td>
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
    <td align="center"><?php echo "<u>".$row_user['nama']."</u>"; ?></td>
  </tr>
  <tr>
    <td><span style="padding-left:15px;">NAMA : <?php echo $row['nama']; ?></span></td>
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
pg_free_result($tampil);
pg_free_result($result);
pg_close($dbconn);
?>
<!--<div style="page-break-before:always;">

</div> --!>