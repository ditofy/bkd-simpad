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

$no_skpdkb = pg_escape_string($_GET['no_skpdkb']);
list($jns_surat,$thn_pajak,$bln_pajak,$kd_obj_pajak,$no_urut_surat) = explode(".", $no_skpdkb);
$schema = $list_schema[$kd_obj_pajak];

switch ($kd_obj_pajak) {
case "01":
	   $query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,C.kd_provinsi||'.'||C.kd_kota||'.'||C.kd_jns||'.'||C.no_reg as npwpd,C.nama as nama_wp,b.nm_usaha as nama_objek,C.alamat as alamat_wp,b.alamat_usaha as alamat_objek,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,b.thn_pajak,b.bln_pajak,D.nm_obj_pajak,D.perda,A.tgl_jatuh_tempo,A.dasar_pajak,A.pokok_pajak,A.pajak_disetor,A.pokok_pajak_baru,A.tgl_surat from air_tanah.skpdkb A INNER JOIN 
air_tanah.skp B on A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
LEFT JOIN public.wp C on C.kd_provinsi=b.kd_provinsi and C.kd_kota=B.kd_kota and C.kd_jns=B.kd_jns and C.no_reg=B.no_reg_wp 
LEFT JOIN public.obj_pajak D on D.kd_obj_pajak=A.kd_obj_pajak
WHERE A.jns_surat='$jns_surat' and A.thn_pajak='$thn_pajak' and A.bln_pajak='$bln_pajak' and A.kd_obj_pajak='$kd_obj_pajak' and A.no_urut_surat='$no_urut_surat' ") or die('Query failed: ' . pg_last_error());
       break;
       case "03":
	   $query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,C.kd_provinsi||'.'||C.kd_kota||'.'||C.kd_jns||'.'||C.no_reg as npwpd,C.nama as nama_wp,b.nm_usaha as nama_objek,C.alamat as alamat_wp,b.alamat_usaha as alamat_objek,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,A.thn_pajak,a.bln_pajak,D.nm_obj_pajak,D.perda,A.tgl_jatuh_tempo,A.dasar_pajak,A.pokok_pajak,A.pajak_disetor,A.pokok_pajak_baru,A.tgl_surat,A.nip_p,A.ket from restoran.skpdkb A LEFT JOIN 
restoran.dat_obj_pajak B on A.kd_kecamatan=B.kd_kecamatan and A.kd_kelurahan=B.kd_kelurahan and A.kd_keg_usaha=b.kd_keg_usaha and A.no_reg=B.no_reg
LEFT JOIN public.wp C on C.kd_provinsi=b.kd_provinsi and C.kd_kota=B.kd_kota and C.kd_jns=B.kd_jns and C.no_reg=B.no_reg_wp 
LEFT JOIN public.obj_pajak D on D.kd_obj_pajak=A.kd_obj_pajak
WHERE A.jns_surat='$jns_surat' and A.thn_pajak='$thn_pajak' and A.bln_pajak='$bln_pajak' and A.kd_obj_pajak='$kd_obj_pajak' and A.no_urut_surat='$no_urut_surat' ") or die('Query failed: ' . pg_last_error());
       break; 
	    case "09":
	   $query = pg_query("select A.*,B.nm_obj_pajak,B.perda from bphtb.skpdkb A
LEFT JOIN public.obj_pajak B on B.kd_obj_pajak=A.kd_obj_pajak
WHERE A.jns_surat='$jns_surat' and A.thn_pajak='$thn_pajak' and A.bln_pajak='$bln_pajak' and A.kd_obj_pajak='$kd_obj_pajak' and A.no_urut_surat='$no_urut_surat' ") or die('Query failed: ' . pg_last_error());
       break;
	   
	   default:
	   $query = pg_query("select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,C.kd_provinsi||'.'||C.kd_kota||'.'||C.kd_jns||'.'||C.no_reg as npwpd,C.nama,C.alamat,b.nama_sppt,b.alamat_op,A.pokok_pajak,B.kd_propinsi||'.'||B.kd_dati2||'.'||B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_blok||'.'||B.no_urut||'.'||B.kd_jns_op as nop,b.thn_pajak,b.bln_pajak,D.nm_obj_pajak,D.perda from bphtb.skpdkb A INNER JOIN 
bphtb.sspd B on A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
LEFT JOIN public.wp C on c.nik=b.nik 
LEFT JOIN public.obj_pajak D on D.kd_obj_pajak=A.kd_obj_pajak
WHERE A.jns_surat='$jns_surat' and A.thn_pajak='$thn_pajak' and A.bln_pajak='$bln_pajak' and A.kd_obj_pajak='$kd_obj_pajak' and A.no_urut_surat='$no_urut_surat'") or die('Query failed: ' . pg_last_error());
	   break;
	   
}


if(pg_num_rows($query) <= 0) {
	echo "NO. SKPDKB TIDAK DITEMUKAN, SILAHKAN PERIKSA KEMBALI NO. SKPDKB";
	pg_free_result($tampil);
	pg_close($dbconn);
	exit;
}
$row = pg_fetch_array($query);
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
<div style="width:100%;height:280mm;">
  <table width="100%" border="0" class="skptable">
  <tr>
    <td align="center">
<pre class="font10" style="line-height: 1.6em;">
PEMERINTAH KOTA PAYAKUMBUH
BADAN KEUANGAN DAERAH
KOTA PAYAKUMBUH
JL. VETERAN KOMPLEK BALAIKOTA
PAYAKUMBUH
</pre>
	</td>
    <td align="center">
<pre>
<strong class="font18">S K P D K B</strong>
<strong class="font14">(SURAT KETETAPAN PAJAK DAERAH KURANG BAYAR)</strong>
<strong class="font10">MASA   : <?php echo strtoupper($bulan_s[$row['bln_pajak']]); ?></strong>
<strong class="font10">TAHUN  : <?php echo $thn_pajak; ?></strong>
</pre>
	</td>
    <td align="center">
<pre class="font14">
<strong>No. Urut</strong>
<?php echo "$no_skpdkb"; ?>
</pre>
	</td>
  </tr>
</table>
<table width="100%" border="0" class="skptablesub">
  <tr>
    <td><br />
<table width="100%" border="0" class="noborder" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;">
   <tr>
    <td>NAMA</td>
    <td>:</td>
    <td><?php echo $row['nama_wp']; ?></td>
  </tr>
   <tr>
    <td>ALAMAT WP</td>
    <td>:</td>
    <td><?php echo $row['alamat_wp'];?></td>
  </tr>
   <tr>
    <td>NPWPD</td>
    <td>:</td>
    <td><?php echo $row['npwpd'];?></td>
  </tr>
  <tr>
    <td>NAMA OBJEK</td>
    <td>:</td>
    <td><?php echo $row['nama_objek'];?></td>
  </tr>
 

 
 
  <tr>
    <td>ALAMAT OBJEK</td>
    <td>:</td>
    <td align="justify"><?php echo $row['alamat_objek'];?></td>
  </tr>
  
  <tr>
    <td>TANGGAL JATUH TEMPO </td>
    <td>:</td>
    <td><?php echo date('d',strtotime($row['tgl_jatuh_tempo']))." ".$bulan[date('n',strtotime($row['tgl_jatuh_tempo']))]." ".date('Y',strtotime($row['tgl_jatuh_tempo'])); ?></td>
  </tr>
   
  <tr>
    <td>KETERANGAN</td>
    <td>:</td>
    <td><?php echo $row['ket'];?></td>
  </tr>
</table><br />
	</td>
  </tr>
</table>
<table width="100%" border="0" class="skptablesub">

  <tr>
    <td colspan="2" class="font18" style="padding-left:20px;padding-bottom:5px;padding-top:5px;padding-right:5px;"><br />
	<table class="font11 noborder" ><tr><td valign="top">I.</td><td> Berdasarkan <?php echo $row['perda']; ?>, telah dilakukan Pemeriksaan atau Keterangan Lain atas pelaksanaan kewajiban Wajib Pajak.<br/><br/></td></tr>
	<tr><td valign="top">
	II.</td><td>Dari Pemeriksaan atau Keterangan Lain tersebut diatas, Penghitungan jumlah yang masih harus dibayar adalah sebagai berikut :<br/><br/>
	<table class="font11 skptable">
	<tr><td valign="top">1.</td><td colspan="3">Dasar Pengenaan Pajak&nbsp; <br/></td><td></td><td>Rp.&nbsp;<?php echo number_format($row['dasar_pajak']); ?></td></tr>
	<tr><td valign="top">2.</td><td colspan="3">Pajak yang terutang&nbsp; <br/></td><td></td><td>Rp.&nbsp;<?php echo number_format($row['pokok_pajak_baru']); ?></td></tr>
	<tr><td valign="top">3.</td><td colspan="3">Kredit Pajak&nbsp; <br/></td><td></td></tr>
	<tr><td valign="top"></td><td colspan="3">a. Kompensasi kelebihan pembayaran <br/></td><td>Rp.&nbsp;</td></tr>
	<tr><td valign="top"></td><td colspan="3">b. Setoran yang dilakukan <br/></td><td>Rp.&nbsp;<?php echo number_format($row['pajak_disetor']); ?></td></tr>
	<tr><td valign="top"></td><td colspan="3">c. Lain-lain <br/></td><td>Rp.<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td></tr>
	<tr><td valign="top"></td><td colspan="3">d. Jumlah yang dapat dikreditkan (a+b+c) <br/></td><td>Rp.&nbsp;</td></tr>
	<tr><td valign="top">4.</td><td colspan="3">Jumlah kekurangan pembayaran Pokok Pajak (2-3d)&nbsp; <br/></td><td></td><td>Rp.&nbsp;<?php echo number_format($row['pokok_pajak']); ?></td></tr>
	<tr><td valign="top">5.</td><td colspan="3">Sanksi Administratif&nbsp; <br/></td><td></td></tr>
	<tr><td valign="top"></td><td colspan="3">a. Bunga <br/></td><td>Rp.&nbsp;</td></tr>
	<tr><td valign="top"></td><td colspan="3">b. Kenaikan <br/></td><td>Rp.&nbsp;</td></tr>
	<tr><td valign="top"></td><td colspan="3">c. Jumlah sanksi administratif (a+b) <br/></td><td></td><td>Rp.<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td></tr>
	<tr><td valign="top">6.</td><td colspan="3">Jumlah yang masih harus dibayar (4+5c)&nbsp; <br/></td><td></td><td><b>Rp.&nbsp;<?php echo number_format($row['pokok_pajak']); ?></b></td></tr>
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
	<tr height="10"><td></td></tr>
      <tr>
        <td colspan="2"><strong>PERHATIAN : </strong></td>
        </tr><tr height="10"><td></td></tr>
      <tr>
        <td width="3%" style="vertical-align:top">1.</td>
        <td width="97%" align="justify">Harap penyetoran dilakukan melalui Bank NAGARI dengan menggunakan Surat Ketetapan Pajak Daerah Kurang Bayar ini.</td>
      </tr>
      <tr height="10"><td></td></tr>
        <td style="vertical-align:top">2.</td>
        <td align="justify">SKPDKB dinyatakan LUNAS jika telah disahkan/validasi Kas Register atau cap/tanda tangan Pejabat</td>
      </tr>
	  <tr height="10"><td></td></tr>
	  <tr>
        <td width="3%" style="vertical-align:top">3.</td>
        <td width="97%" align="justify">Apabila SKPDKB ini tidak atau kurang dibayar setelah lewat waktu paling lama 30 (tiga puluh) hari sejak SKPDKB ini diterbitkan dikenakan sanksi administratif berupa bunga 2% (dua persen) per bulan.</td>
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
    <td align="center"><?php echo "Payakumbuh, ".date('d',strtotime($row['tgl_surat']))." ".$bulan[date('n',strtotime($row['tgl_surat']))]." ".date('Y',strtotime($row['tgl_surat'])); ?></td>
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
	$nip = $row['nip_p'];
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