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
$bulan = pg_escape_string($_GET['bln']);
$bln= strtoupper(getBulan($bulan));
$bln_lapor=$bulan+1;
$bln_lap=getBulan($bln_lapor);
$tahun = pg_escape_string($_GET['thn']);
$npwpd = pg_escape_string($_GET['npwpd']);
list($kd_provinsi_wp,$kd_kota_wp,$kd_jns_wp,$no_reg_wp) = explode(".", $npwpd);
////// CEK DATA WAJIB PAJAK ///////////
$query_wp = "SELECT A.nama,A.alamat,A.kelurahan,A.kecamatan,A.kota,A.nik FROM public.wp A 
WHERE A.kd_provinsi='$kd_provinsi_wp' and A.kd_kota='$kd_kota_wp' and A.kd_jns='$kd_jns_wp' and A.no_reg='$no_reg_wp'";
$result_wp = pg_query($query_wp) or die('Query failed: ' . pg_last_error());
$row_wp = pg_fetch_array($result_wp);
pg_free_result($result_wp);
/////////CEK DATA OBJEK PAJAK ///////////////////////
$query_hotel = "SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,B.nama,B.alamat||' '||B.kelurahan||' '||B.kecamatan||' '||B.kota as alamat_wp FROM hotel.dat_obj_pajak A INNER JOIN public.wp B ON 
A.kd_provinsi=B.kd_provinsi AND
A.kd_kota=B.kd_kota AND
A.kd_jns=B.kd_jns AND
A.no_reg_wp=B.no_reg
WHERE A.kd_provinsi='$kd_provinsi_wp' and A.kd_kota='$kd_kota_wp' and A.kd_jns='$kd_jns_wp' and A.no_reg_wp='$no_reg_wp' and A.status_pajak='1'";
$result_hotel = pg_query($query_hotel) or die('Query failed: ' . pg_last_error());
$row_hotel = pg_fetch_array($result_hotel);
$ada_hotel=pg_num_rows($result_hotel);
pg_free_result($result_hotel);

						
$query_hiburan = "SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,B.nama,B.alamat||' '||B.kelurahan||' '||B.kecamatan||' '||B.kota as alamat_wp FROM hiburan.dat_obj_pajak A INNER JOIN public.wp B ON 
A.kd_provinsi=B.kd_provinsi AND
A.kd_kota=B.kd_kota AND
A.kd_jns=B.kd_jns AND
A.no_reg_wp=B.no_reg
WHERE A.kd_provinsi='$kd_provinsi_wp' and A.kd_kota='$kd_kota_wp' and A.kd_jns='$kd_jns_wp' and A.no_reg_wp='$no_reg_wp' and A.status_pajak='1'";
$result_hiburan = pg_query($query_hiburan) or die('Query failed: ' . pg_last_error());
$row_hiburan = pg_fetch_array($result_hiburan);
$ada_hiburan=pg_num_rows($result_hiburan);
pg_free_result($result_hiburan);
								
$query_parkir = "SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,B.nama,B.alamat||' '||B.kelurahan||' '||B.kecamatan||' '||B.kota as alamat_wp FROM parkir.dat_obj_pajak A INNER JOIN public.wp B ON 
A.kd_provinsi=B.kd_provinsi AND
A.kd_kota=B.kd_kota AND
A.kd_jns=B.kd_jns AND
A.no_reg_wp=B.no_reg
WHERE A.kd_provinsi='$kd_provinsi_wp' and A.kd_kota='$kd_kota_wp' and A.kd_jns='$kd_jns_wp' and A.no_reg_wp='$no_reg_wp' and A.status_pajak='1'";
$result_parkir = pg_query($query_parkir) or die('Query failed: ' . pg_last_error());
$row_parkir = pg_fetch_array($result_parkir);
$ada_parkir=pg_num_rows($result_parkir);
pg_free_result($result_parkir);
						
$query_ppj = "SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,B.nama,B.alamat||' '||B.kelurahan||' '||B.kecamatan||' '||B.kota as alamat_wp FROM hotel.dat_obj_pajak A INNER JOIN public.wp B ON 
A.kd_provinsi=B.kd_provinsi AND
A.kd_kota=B.kd_kota AND
A.kd_jns=B.kd_jns AND
A.no_reg=B.no_reg
WHERE A.kd_provinsi='$kd_provinsi_wp' and A.kd_kota='$kd_kota_wp' and A.kd_jns='$kd_jns_wp' and A.no_reg_wp='$no_reg_wp' and A.status_pajak='1'";
$result_ppj = pg_query($query_ppj) or die('Query failed: ' . pg_last_error());
$row_ppj = pg_fetch_array($result_ppj);
$ada_ppj=pg_num_rows($result_ppj);
pg_free_result($result_ppj);
						
$query_restoran = "SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,B.nama,B.alamat||' '||B.kelurahan||' '||B.kecamatan||' '||B.kota as alamat_wp FROM restoran.dat_obj_pajak A INNER JOIN public.wp B ON 
A.kd_provinsi=B.kd_provinsi AND
A.kd_kota=B.kd_kota AND
A.kd_jns=B.kd_jns AND
A.no_reg_wp=B.no_reg
WHERE A.kd_provinsi='$kd_provinsi_wp' and A.kd_kota='$kd_kota_wp' and A.kd_jns='$kd_jns_wp' and A.no_reg_wp='$no_reg_wp' and A.status_pajak='1'";
$result_restoran = pg_query($query_restoran) or die('Query failed: ' . pg_last_error());
$row_restoran = pg_fetch_array($result_restoran);
$ada_restoran=pg_num_rows($result_restoran);
pg_free_result($result_restoran);

//////// END MENCEK OBJEK PAJAK //////////////////////
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
    border-top: 1;
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
.font13 {
	font-family:"Verdana";
	font-size:13px;
}
.font14 {
	font-family:"Verdana";
	font-size:14px;
}
.font16 {
	font-family:"Verdana";
	font-size:16px;
}
.font9 {
	font-family:"Verdana";
	font-size:9px;
}
.font18 {
	font-family:"Verdana";
	font-size:18px;
}
.style1 {color: #FFFFFF}
</style>
<div style="width:100%;height:300mm;">
<table width="100%" class="skptable" >
<tr>
<td align="center" width="8%" valign="center"><img src="lambang.png" height="40"></td>
<td><pre>
<strong class="font13">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PEMERINTAH KOTA PAYAKUMBUH</strong>
<strong class="font14">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BADAN KEUANGAN DAERAH</strong>
<strong class="font10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JLN. VETERAN NO. 70 KAPALO KOTO DAIBALAI TELP: (0752) 932079	</strong>
<strong class="font9">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Website: https://bkd.payakumbuhkota.go.id || Email : bkd@payakumbuhkota.go.id </strong>
</pre></td>
<td valign="center"><pre>
<strong class="font12">&nbsp;&nbsp;No SPTPD : <?php echo $bulan.".".$tahun.".".$kd_jns_wp.".".$no_reg_wp; ?> </strong>
<strong class="font12">&nbsp;&nbsp;Masa Pajak : <?php echo ucfirst($bln);?> </strong>
<strong class="font12">&nbsp;&nbsp;Tahun Pajak : <?php echo $tahun; ?> </strong>
</pre></td>
</tr>
</table>
<table width="100%" class="skptable" >
<tr>
<td align="center"><pre>
<strong class="font18">S P T P D</strong>
<strong class="font16">(SURAT PEMBERITAHUAN PAJAK DAERAH)</strong>
<strong class="font12">PAJAK BARANG JASA TERTENTU (PBJT)	</strong>
</pre></td>
</tr>
</table>
<table width="100%" class="garis" >
<tr>
<td width="2%">&nbsp;</td>
    <td width="69%"><br/><div class="font12">Kepada Yth: </div>
<div class="font14"><b>Kepala Badan Keuangan Daerah</b></div>
<div class="font13">Kota Payakumbuh </div></td>
    <td width="1%"></td>
    <td width="50%"></td>
	<td width="18%" valign="top"></td>
</tr>
</table>
<table width="100%" class="skptablesub">
<tr>
<td>
<table width="100%" border="0" class="noborder" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;">	
  <tr>
   
    <td colspan="4"><b>1. DATA WAJIB PAJAK BARANG JASA TERTENTU (PBJT) :</b></td>
    </tr>	<tr>
    <td width="2%">&nbsp;</td>
    <td width="18%">NPWPD / NIK / NIB</td>
    <td width="1%">:</td>
    <td width="50%"><?php echo $npwpd; ?> / <?php echo $row_wp['nik']; ?></td>
	
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>NAMA WAJIB PAJAK </td>
    <td>:</td>
    <td><?php echo $row_wp['nama']; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td valign="middle">ALAMAT WAJIB PAJAK </td>
    <td>:</td>
    <td><?php echo $row_wp['alamat']." ".$row_wp['kelurahan']."".$row_wp['kecamatan']." ".$row_wp['kota']; ?> </td>
  </tr>
  </table><br/>
<table width="100%"  class="noborder">
<tr><td>&nbsp;&nbsp;&nbsp;<b>2. DATA OBJEK PAJAK BARANG JASA TERTENTU (PBJT) :</b></td></tr>	
</table>
<?php 
////// MENAMPILKAN SPTPD ////////////////////
////RESTORAN/////
if($ada_restoran > 0){
$schema = $list_schema[$kd_obj_pajak];
$jns_pbjt=$list_pbjt[$kd_obj_pajak];
$sql_restoran = "SELECT A.NM_USAHA,A.ALAMAT_USAHA,A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.kd_obj_pajak FROM RESTORAN.SPTPD A 
WHERE  A.kd_provinsi='$kd_provinsi_wp' and A.kd_kota='$kd_kota_wp' and A.kd_jns='$kd_jns_wp' and A.no_reg_wp='$no_reg_wp' and A.status_pembayaran < '2' AND A.BLN_PAJAK ='$bulan' AND A.THN_PAJAK='$tahun'
GROUP BY NOP,A.NM_USAHA,A.ALAMAT_USAHA,A.kd_obj_pajak ";
$tampil_restoran = pg_query($sql_restoran);
$n_row = pg_num_rows($tampil_restoran);
if($n_row <= 0) {
	echo "NO. SPTPD TIDAK DITEMUKAN, SILAHKAN PERIKSA KEMBALI NO. SPTPD";
	pg_free_result($tampil);
	pg_close($dbconn);
	exit;
}
$x = 1; 
?>

<table width="100%" class="noborder">
<?php
$nos=1;
while ($row = pg_fetch_array($tampil_restoran)) { 
$kd=$row['kd_obj_pajak'];
?>
<tr>
    <td width="6%" align="center"><b>&Omicron;</b></td>
	<td width="15%">NOP</td>
    <td width="1%">:</td>
    <td align="justify" colspan="3"><?php echo $row['nop']; ?></td>
  </tr>  
   <tr>
   <td>&nbsp;</td>
    <td>JENIS PBJT </td>
    <td>:</td>
    <td colspan="3"><b><?php echo $list_pbjt[$kd]; ?></b></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
    <td>NAMA USAHA </td>
    <td>:</td>
    <td colspan="3"><?php echo $row['nm_usaha']; ?></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
    <td>ALAMAT USAHA </td>
    <td>:</td>
    <td colspan="3"><?php echo $row['alamat_usaha']; ?></td>
  </tr>
   <tr>
   <td>&nbsp;</td>
    <td> </td>
    <td>&nbsp;</td>
    <td colspan="3"></td>
  </tr>
<tr><td></td><td colspan="6"><b>RINCIAN DOKUMEN PEMBAYARAN (SSPD) :</b></td></tr>
<tr><td></td><td colspan="6">
<table width="100%" class="skptable">
<tr><td><b>No</b></td><td width="20%"><b>Nomor SSPD</b></td><td><b>Transaksi</b></td><td><b>Tarif</b></td><td><b>Pokok Pajak</b></td><td><b>Kanal Pembayaran</b></td><td><b>Tanggal Bayar</b></td></tr>
<?php 
list($kd_kec,$kd_kel,$kd_obj_p,$kd_keg,$no_reg) = explode(".", $row['nop']);
$sql_sspd = "SELECT A.tarif,A.dasar_pengenaan_pajak,B.TGL_BAYAR,A.POKOK_PAJAK,B.NM_CAB_BANK,A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_sspd FROM RESTORAN.SPTPD A 
LEFT JOIN RESTORAN.PEMBAYARAN B ON
A.JNS_SURAT = B.JNS_SURAT AND
A.THN_PAJAK = B.THN_PAJAK AND
A.BLN_PAJAK = B.BLN_PAJAK AND
A.KD_OBJ_PAJAK = B.KD_OBJ_PAJAK AND 
A.NO_URUT_SURAT = B.NO_URUT_SURAT
WHERE A.KD_KECAMATAN = '$kd_kec' AND A.KD_KELURAHAN = '$kd_kel' AND A.KD_OBJ_PAJAK ='$kd_obj_p' AND A.KD_KEG_USAHA ='$kd_keg' AND A.NO_REG = '$no_reg' AND A.BLN_PAJAK ='$bulan' AND A.THN_PAJAK='$tahun'
GROUP BY NOP,B.TGL_BAYAR,A.POKOK_PAJAK,NO_SSPD,B.NM_CAB_BANK,A.dasar_pengenaan_pajak,A.tarif";
$tampil_sspd = pg_query($sql_sspd);
$total=0;
$dasar=0;
$no=1;
while ($row_sspd = pg_fetch_array($tampil_sspd)) { 

?>
<tr><td><?php echo $no; ?></td>
    <td><?php echo $row_sspd['no_sspd']; ?></td>
	 <td>Rp. <?php echo number_format($row_sspd['dasar_pengenaan_pajak']); ?></td>
	 <td><?php echo number_format($row_sspd['tarif']*100); ?>%</td>
    <td>Rp. <?php echo number_format($row_sspd['pokok_pajak']); ?></td>
	 <td align="left"><?php echo $row_sspd['nm_cab_bank']; ?></td>
    <td align="left"><?php echo $row_sspd['tgl_bayar']; ?></td>
    </tr><?php
	  $no++;}
	
	  ?> 
	 
</table>
	<tr><td></td><td colspan="6">&nbsp;</td></tr> 
<?php 
$nos++;}
?>

</table>
<?php }
///////// END RESTORAN /////////////
/////////// HOTEL /////////////////
if($ada_hotel > 0){
$schema = $list_schema[$kd_obj_pajak];
$jns_pbjt=$list_pbjt[$kd_obj_pajak];
$sql_hotel = "SELECT A.NM_USAHA,A.ALAMAT_USAHA,A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.kd_obj_pajak FROM HOTEL.SPTPD A 
WHERE A.kd_provinsi='$kd_provinsi_wp' and A.kd_kota='$kd_kota_wp' and A.kd_jns='$kd_jns_wp' and A.no_reg_wp='$no_reg_wp' and A.status_pembayaran < '2' AND A.BLN_PAJAK ='$bulan' AND A.THN_PAJAK='$tahun'
GROUP BY NOP,A.NM_USAHA,A.ALAMAT_USAHA,A.kd_obj_pajak ";
$tampil_hotel = pg_query($sql_hotel);
$n_row = pg_num_rows($tampil_hotel);
if($n_row <= 0) {
	echo "NO. SPTPD TIDAK DITEMUKAN, SILAHKAN PERIKSA KEMBALI NO. SPTPD $no_reg_wp";
	pg_free_result($tampil_hotel);
	pg_close($dbconn);
	exit;
}
$x = 1; 
?>

<table width="100%" class="noborder">
<?php
while ($row_hotel = pg_fetch_array($tampil_hotel)) { 
$kd=$row_hotel['kd_obj_pajak'];
?>
<tr>
    <td width="6%" align="center"><b>&Omicron;</b></td>
	<td width="15%">NOP</td>
    <td width="1%">:</td>
    <td align="justify" colspan="3"><?php echo $row_hotel['nop']; ?></td>
	
  </tr>  
   <tr>
   <td>&nbsp;</td>
    <td>JENIS PBJT </td>
    <td>:</td>
    <td colspan="3"><b><?php echo $list_pbjt[$kd]; ?></b></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
    <td>NAMA USAHA </td>
    <td>:</td>
    <td colspan="3"><?php echo $row_hotel['nm_usaha']; ?></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
    <td>ALAMAT USAHA </td>
    <td>:</td>
    <td colspan="3"><?php echo $row_hotel['alamat_usaha']; ?></td>
  </tr>
   <tr>
   <td>&nbsp;</td>
    <td> </td>
    <td>&nbsp;</td>
    <td colspan="3"></td>
  </tr>
<tr><td></td><td colspan="6"><b>RINCIAN DOKUMEN PEMBAYARAN (SSPD) :</b></td></tr>
<tr><td></td><td colspan="6">
<table width="100%" class="skptable">
<tr><td><b>No</b></td><td width="20%"><b>Nomor SSPD</b></td><td><b>Transaksi</b></td><td><b>Tarif</b></td><td><b>Pokok Pajak</b></td><td><b>Kanal Pembayaran</b></td><td><b>Tanggal Bayar</b></td></tr>

<?php 

list($kd_kec,$kd_kel,$kd_obj_p,$kd_keg,$no_reg) = explode(".", $row_hotel['nop']);
$sql_sspd = "SELECT A.tarif,A.dasar_pengenaan_pajak,B.TGL_BAYAR,A.POKOK_PAJAK,B.NM_CAB_BANK,A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_sspd FROM HOTEL.SPTPD A 
LEFT JOIN HOTEL.PEMBAYARAN B ON
A.JNS_SURAT = B.JNS_SURAT AND
A.THN_PAJAK = B.THN_PAJAK AND
A.BLN_PAJAK = B.BLN_PAJAK AND
A.KD_OBJ_PAJAK = B.KD_OBJ_PAJAK AND 
A.NO_URUT_SURAT = B.NO_URUT_SURAT
WHERE A.KD_KECAMATAN = '$kd_kec' AND A.KD_KELURAHAN = '$kd_kel' AND A.KD_OBJ_PAJAK ='$kd_obj_p' AND A.KD_KEG_USAHA ='$kd_keg' AND A.NO_REG = '$no_reg' AND A.BLN_PAJAK ='$bulan' AND A.THN_PAJAK='$tahun'
GROUP BY NOP,B.TGL_BAYAR,A.POKOK_PAJAK,NO_SSPD,B.NM_CAB_BANK,A.dasar_pengenaan_pajak,A.tarif";
$tampil_sspd = pg_query($sql_sspd);
$no=1;
while ($row_sspd = pg_fetch_array($tampil_sspd)) { 

?>
<tr><td><?php echo $no; ?></td>
    <td><?php echo $row_sspd['no_sspd']; ?></td>
	<td>Rp. <?php echo number_format($row_sspd['dasar_pengenaan_pajak']); ?></td>
	<td><?php echo number_format($row_sspd['tarif']*100); ?>%</td>
    <td>Rp. <?php echo number_format($row_sspd['pokok_pajak']); ?></td>
	 <td align="left"><?php echo $row_sspd['nm_cab_bank']; ?></td>
    <td align="left"><?php echo $row_sspd['tgl_bayar']; ?></td>
    </tr><?php
	  }
	
	  ?> 
	 
</table>
	<tr><td></td><td colspan="6">&nbsp;</td></tr> 
<?php 
$nos++;}
?>
</table>
<?php }
////////// END HOTEL ////////////

////////// JASA KESENIAN DAN HIBURAN ////////////
if($ada_hiburan > 0){
$schema = $list_schema[$kd_obj_pajak];
$jns_pbjt=$list_pbjt[$kd_obj_pajak];
$sql_hiburan = "SELECT A.NM_USAHA,A.ALAMAT_USAHA,A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.kd_obj_pajak FROM HIBURAN.SPTPD A 
LEFT JOIN HIBURAN.PEMBAYARAN B ON
A.JNS_SURAT = B.JNS_SURAT AND
A.THN_PAJAK = B.THN_PAJAK AND
A.BLN_PAJAK = B.BLN_PAJAK AND
A.KD_OBJ_PAJAK = B.KD_OBJ_PAJAK AND 
A.NO_URUT_SURAT = B.NO_URUT_SURAT
WHERE  A.kd_provinsi='$kd_provinsi_wp' and A.kd_kota='$kd_kota_wp' and A.kd_jns='$kd_jns_wp' and A.no_reg_wp='$no_reg_wp' and A.status_pembayaran < '2' AND A.BLN_PAJAK ='$bulan' AND A.THN_PAJAK='$tahun'
GROUP BY NOP,A.NM_USAHA,A.ALAMAT_USAHA,A.kd_obj_pajak ";
$tampil_hiburan = pg_query($sql_hiburan);
$n_row = pg_num_rows($tampil_hiburan);
if($n_row <= 0) {
	echo "NO. SPTPD TIDAK DITEMUKAN, SILAHKAN PERIKSA KEMBALI NO. SPTPD";
	pg_free_result($tampil_hotel);
	pg_close($dbconn);
	exit;
}
$x = 1; 
?>

<table width="100%" class="noborder">
<?php
while ($row_hiburan = pg_fetch_array($tampil_hiburan)) { 
$kd=$row_hiburan['kd_obj_pajak'];
?>
<tr>
    <td width="6%" align="center"><b>&Omicron;</b></td>
	<td width="15%">NOP</td>
    <td width="1%">:</td>
    <td align="justify" colspan="3"><?php echo $row_hiburan['nop']; ?></td>
	
  </tr>  
   <tr>
   <td>&nbsp;</td>
    <td>JENIS PBJT </td>
    <td>:</td>
    <td colspan="3"><b><?php echo $list_pbjt[$kd]; ?></b></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
    <td>NAMA USAHA </td>
    <td>:</td>
    <td colspan="3"><?php echo $row_hiburan['nm_usaha']; ?></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
    <td>ALAMAT USAHA </td>
    <td>:</td>
    <td colspan="3"><?php echo $row_hiburan['alamat_usaha']; ?></td>
  </tr>
   <tr>
   <td>&nbsp;</td>
    <td> </td>
    <td>&nbsp;</td>
    <td colspan="3"></td>
  </tr>

<tr><td></td><td colspan="6"><b>RINCIAN DOKUMEN PEMBAYARAN (SSPD) :</b></td></tr>
<tr><td></td><td colspan="6">
<table width="100%" class="skptable">
<tr><td><b>No</b></td><td width="20%"><b>Nomor SSPD</b></td><td><b>Transaksi</b></td><td><b>Tarif</b></td><td><b>Pokok Pajak</b></td><td><b>Kanal Pembayaran</b></td><td><b>Tanggal Bayar</b></td></tr>

<?php 

list($kd_kec,$kd_kel,$kd_obj_p,$kd_keg,$no_reg) = explode(".", $row_hiburan['nop']);
$sql_sspd = "SELECT A.tarif,A.dasar_pengenaan_pajak,B.TGL_BAYAR,A.POKOK_PAJAK,B.NM_CAB_BANK,A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_sspd FROM HIBURAN.SPTPD A 
LEFT JOIN HIBURAN.PEMBAYARAN B ON
A.JNS_SURAT = B.JNS_SURAT AND
A.THN_PAJAK = B.THN_PAJAK AND
A.BLN_PAJAK = B.BLN_PAJAK AND
A.KD_OBJ_PAJAK = B.KD_OBJ_PAJAK AND 
A.NO_URUT_SURAT = B.NO_URUT_SURAT
WHERE A.KD_KECAMATAN = '$kd_kec' AND A.KD_KELURAHAN = '$kd_kel' AND A.KD_OBJ_PAJAK ='$kd_obj_p' AND A.KD_KEG_USAHA ='$kd_keg' AND A.NO_REG = '$no_reg' AND A.BLN_PAJAK ='$bulan' AND A.THN_PAJAK='$tahun'
GROUP BY NOP,B.TGL_BAYAR,A.POKOK_PAJAK,NO_SSPD,B.NM_CAB_BANK,A.dasar_pengenaan_pajak,A.tarif";
$tampil_sspd = pg_query($sql_sspd);
$total=0;
$dasar=0;
$no=1;
while ($row_sspd = pg_fetch_array($tampil_sspd)) { 

?>
<tr><td><?php echo $no; ?></td>
    <td><?php echo $row_sspd['no_sspd']; ?></td>
	<td>Rp. <?php echo number_format($row_sspd['dasar_pengenaan_pajak']); ?></td>
	<td><?php echo number_format($row_sspd['tarif']*100); ?>%</td>
    <td>Rp. <?php echo number_format($row_sspd['pokok_pajak']); ?></td>
	 <td align="left"><?php echo $row_sspd['nm_cab_bank']; ?></td>
    <td align="left"><?php echo $row_sspd['tgl_bayar']; ?></td>
    </tr><?php
	  $no++;}
	
	  ?> 
	 
</table>
	<tr><td></td><td colspan="6">&nbsp;</td></tr> 
<?php 
}
?>
</table>
<?php }
///////// END JASA KESENIAN DAN HIBURAN //////////

/////// PARKIR ///////////////////////
/*if($ada_parkir > 0){
$schema = $list_schema[$kd_obj_pajak];
$jns_pbjt=$list_pbjt[$kd_obj_pajak];
$sql_parkir = "SELECT A.NM_USAHA,A.ALAMAT_USAHA,A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.kd_obj_pajak FROM PARKIR.SPTPD A 
WHERE  A.kd_provinsi='$kd_provinsi_wp' and A.kd_kota='$kd_kota_wp' and A.kd_jns='$kd_jns_wp' and A.no_reg_wp='$no_reg_wp' and A.status_pembayaran < '2' AND A.BLN_PAJAK ='$bulan' AND A.THN_PAJAK='$tahun'
GROUP BY NOP,A.NM_USAHA,A.ALAMAT_USAHA,A.kd_obj_pajak";
$tampil_parkir = pg_query($sql_parkir);
$n_row = pg_num_rows($tampil_parkir);
if($n_row <= 0) {
	echo "NO. SPTPD TIDAK DITEMUKAN, SILAHKAN PERIKSA KEMBALI NO. SPTPD";
	pg_free_result($tampil_parkir);
	pg_close($dbconn);
	exit;
}
$x = 1; 
?>

<table width="100%" class="noborder">
<?php
while ($row_parkir = pg_fetch_array($tampil_parkir)) { 
$kd=$row_parkir['kd_obj_pajak'];
?>
<tr>
    <td width="6%" align="center"><b>&Omicron;</b></td>
	<td width="15%">NOP</td>
    <td width="1%">:</td>
    <td align="justify" colspan="3"><?php echo $row_parkir['nop']; ?></td>
	
  </tr>  
   <tr>
   <td>&nbsp;</td>
    <td>JENIS PBJT </td>
    <td>:</td>
    <td colspan="3"><b><?php echo $list_pbjt[$kd]; ?></b></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
    <td>NAMA USAHA </td>
    <td>:</td>
    <td colspan="3"><?php echo $row_parkir['nm_usaha']; ?></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
    <td>ALAMAT USAHA </td>
    <td>:</td>
    <td colspan="3"><?php echo $row_parkir['alamat_usaha']; ?></td>
  </tr>
   <tr>
   <td>&nbsp;</td>
    <td> </td>
    <td>&nbsp;</td>
    <td colspan="3"></td>
  </tr>
<tr><td></td><td colspan="6"><b>RINCIAN DOKUMEN PEMBAYARAN (SSPD) :</b></td></tr>
<tr><td></td><td colspan="6">
<table width="100%" class="skptable">
<tr><td><b>No</b></td><td width="20%"><b>Nomor SSPD</b></td><td><b>Transaksi</b></td><td><b>Tarif</b></td><td><b>Pokok Pajak</b></td><td><b>Kanal Pembayaran</b></td><td><b>Tanggal Bayar</b></td></tr>

<?php 

list($kd_kec,$kd_kel,$kd_obj_p,$kd_keg,$no_reg) = explode(".", $row_parkir['nop']);
$sql_sspd = "SELECT A.tarif,A.dasar_pengenaan_pajak,B.TGL_BAYAR,A.POKOK_PAJAK,B.NM_CAB_BANK,A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_sspd FROM PARKIR.SPTPD A 
LEFT JOIN PARKIR.PEMBAYARAN B ON
A.JNS_SURAT = B.JNS_SURAT AND
A.THN_PAJAK = B.THN_PAJAK AND
A.BLN_PAJAK = B.BLN_PAJAK AND
A.KD_OBJ_PAJAK = B.KD_OBJ_PAJAK AND 
A.NO_URUT_SURAT = B.NO_URUT_SURAT
WHERE A.KD_KECAMATAN = '$kd_kec' AND A.KD_KELURAHAN = '$kd_kel' AND A.KD_OBJ_PAJAK ='$kd_obj_p' AND A.KD_KEG_USAHA ='$kd_keg' AND A.NO_REG = '$no_reg' AND A.BLN_PAJAK ='$bulan' AND A.THN_PAJAK='$tahun'
GROUP BY NOP,B.TGL_BAYAR,A.POKOK_PAJAK,NO_SSPD,B.NM_CAB_BANK,A.dasar_pengenaan_pajak,A.tarif";
$tampil_sspd = pg_query($sql_sspd);
$total=0;
$dasar=0;
$no=1;
while ($row_sspd = pg_fetch_array($tampil_sspd)) { 

?>
<tr><td><?php echo $no; ?></td>
    <td><?php echo $row_sspd['no_sspd']; ?></td>
	<td>Rp. <?php echo number_format($row_sspd['dasar_pengenaan_pajak']); ?></td>
	<td><?php echo number_format($row_sspd['tarif']*100); ?>%</td>
    <td>Rp. <?php echo number_format($row_sspd['pokok_pajak']); ?></td>
	 <td align="left"><?php echo $row_sspd['nm_cab_bank']; ?></td>
    <td align="left"><?php echo $row_sspd['tgl_bayar']; ?></td>
    </tr><?php
	  }
	
	  ?> 
	 
</table>
	<tr><td></td><td colspan="6">&nbsp;</td></tr> 
<?php 
$nos++;}
?>
</table>
<?php }*/
/////// END PARKIR ///////////////////
///////// PPJ ///////////////////
if($ada_ppj > 0){
$schema = $list_schema[$kd_obj_pajak];
$jns_pbjt=$list_pbjt[$kd_obj_pajak];
$sql_ppj = "SELECT A.NM_USAHA,A.ALAMAT_USAHA,A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.kd_obj_pajak FROM PPJ.SPTPD A 
LEFT JOIN PPJ.PEMBAYARAN B ON
A.JNS_SURAT = B.JNS_SURAT AND
A.THN_PAJAK = B.THN_PAJAK AND
A.BLN_PAJAK = B.BLN_PAJAK AND
A.KD_OBJ_PAJAK = B.KD_OBJ_PAJAK AND 
A.NO_URUT_SURAT = B.NO_URUT_SURAT
WHERE  A.kd_provinsi='$kd_provinsi_wp' and A.kd_kota='$kd_kota_wp' and A.kd_jns_wp='$kd_jns' and A.no_reg_wp='$no_reg_wp' and A.status_pembayaran < '2' AND A.BLN_PAJAK ='$bulan' AND A.THN_PAJAK='$tahun'
GROUP BY NOP,A.NM_USAHA,A.ALAMAT_USAHA,A.kd_obj_pajak ";
$tampil_ppj = pg_query($sql_ppj);
$n_row = pg_num_rows($tampil_ppj);
if($n_row <= 0) {
	echo "NO. SPTPD TIDAK DITEMUKAN, SILAHKAN PERIKSA KEMBALI NO. SPTPD";
	pg_free_result($tampil_ppj);
	pg_close($dbconn);
	exit;
}
$x = 1; 
?>

<table width="100%" class="noborder">
<?php
$nos=1;
while ($row_ppj = pg_fetch_array($tampil_ppj)) { 
$kd=$row_ppj['kd_obj_pajak'];
?>
<tr>
    <td width="6%" align="center"><b>&Omicron;</b></td>
	<td width="15%">NOP</td>
    <td width="1%">:</td>
    <td align="justify" colspan="3"><?php echo $row_ppj['nop']; ?></td>
	
  </tr>  
   <tr>
   <td>&nbsp;</td>
    <td>JENIS PBJT </td>
    <td>:</td>
    <td colspan="3"><b><?php echo $list_pbjt[$kd]; ?></b></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
    <td>NAMA USAHA </td>
    <td>:</td>
    <td colspan="3"><?php echo $row_ppj['nm_usaha']; ?></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
    <td>ALAMAT USAHA </td>
    <td>:</td>
    <td colspan="3"><?php echo $row_ppj['alamat_usaha']; ?></td>
  </tr>
   <tr>
   <td>&nbsp;</td>
    <td> </td>
    <td>&nbsp;</td>
    <td colspan="3"></td>
  </tr>
<tr><td></td><td colspan="6"><b>RINCIAN DOKUMEN PEMBAYARAN (SSPD) :</b></td></tr>
<tr><td></td><td colspan="6">
<table width="100%" class="skptable">
<tr><td><b>No</b></td><td width="20%"><b>Nomor SSPD</b></td><td><b>Transaksi</b></td><td><b>Tarif</b></td><td><b>Pokok Pajak</b></td><td><b>Kanal Pembayaran</b></td><td><b>Tanggal Bayar</b></td></tr>

<?php 

list($kd_kec,$kd_kel,$kd_obj_p,$kd_keg,$no_reg) = explode(".", $row_pppj['nop']);
$sql_sspd = "SELECT A.tarif,A.dasar_pengenaan_pajak,B.TGL_BAYAR,B.POKOK_PAJAK,B.NM_CAB_BANK,A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_sspd FROM PPJ.SPTPD A 
LEFT JOIN PPJ.PEMBAYARAN B ON
A.JNS_SURAT = B.JNS_SURAT AND
A.THN_PAJAK = B.THN_PAJAK AND
A.BLN_PAJAK = B.BLN_PAJAK AND
A.KD_OBJ_PAJAK = B.KD_OBJ_PAJAK AND 
A.NO_URUT_SURAT = B.NO_URUT_SURAT
WHERE A.KD_KECAMATAN = '$kd_kec' AND A.KD_KELURAHAN = '$kd_kel' AND A.KD_OBJ_PAJAK ='$kd_obj_p' AND A.KD_KEG_USAHA ='$kd_keg' AND A.NO_REG = '$no_reg' AND A.BLN_PAJAK ='$bulan' AND A.THN_PAJAK='$tahun'
GROUP BY NOP,B.TGL_BAYAR,B.POKOK_PAJAK,NO_SSPD,B.NM_CAB_BANK,A.dasar_pengenaan_pajak,A.tarif";
$tampil_sspd = pg_query($sql_sspd);
$total=0;
$dasar=0;
$no=1;
while ($row_sspd = pg_fetch_array($tampil_sspd)) { 

?>
<tr><td><?php echo $no; ?></td>
    <td><?php echo $row_sspd['no_sspd']; ?></td>
	<td>Rp. <?php echo number_format($row_sspd['dasar_pengenaan_pajak']); ?></td>
	<td><?php echo number_format($row_sspd['tarif']*100); ?>%</td>
    <td>Rp. <?php echo number_format($row_sspd['pokok_pajak']); ?></td>
	 <td align="left"><?php echo $row_sspd['nm_cab_bank']; ?></td>
    <td align="left"><?php echo $row_sspd['tgl_bayar']; ?></td>
    </tr><?php
	  $no++;}
	
	  ?> 
	 
</table>
	<tr><td></td><td colspan="6">&nbsp;</td></tr> 
<?php 
}
?>
</table>
<?php }
//////// END PPJ //////////////////
/////// END MENAMPILKAN SPTPD ///////////////
?>
<?php /////////////MENAMPILKAN TOTAL PENGENAAN PAJAK ////// ?>
<table width="100%"  class="noborder">
<tr><td>&nbsp;&nbsp;&nbsp;<b>3. DATA TOTAL PENGENAAN PAJAK BARANG JASA TERTENTU (PBJT) :</b></td></tr>	
</table>
<table width="100%" class="noborder">
<?php
//// TOTAL RESTORAN ///////////
$sql_resto ="SELECT SUM(A.DASAR_PENGENAAN_PAJAK) AS DASAR_RESTO,SUM(A.POKOK_PAJAK) AS POKOK_RESTO,A.TARIF FROM RESTORAN.SPTPD A
WHERE  A.kd_provinsi='$kd_provinsi_wp' and A.kd_kota='$kd_kota_wp' and A.kd_jns='$kd_jns_wp' and A.no_reg_wp='$no_reg_wp' AND A.BLN_PAJAK='$bulan' AND A.THN_PAJAK='$tahun' 
GROUP BY A.TARIF";
$tampil_resto = pg_query($sql_resto);
$jlh_resto = pg_num_rows($tampil_resto);
$row_resto = pg_fetch_array($tampil_resto);
pg_free_result($tampil_resto);
/////
$sql_hotel ="SELECT SUM(A.DASAR_PENGENAAN_PAJAK) AS DASAR_HOTEL,SUM(A.POKOK_PAJAK) AS POKOK_HOTEL,A.TARIF FROM HOTEL.SPTPD A
WHERE  A.kd_provinsi='$kd_provinsi_wp' and A.kd_kota='$kd_kota_wp' and A.kd_jns='$kd_jns_wp' and A.no_reg_wp='$no_reg_wp' AND A.BLN_PAJAK='$bulan' AND A.THN_PAJAK='$tahun' 
GROUP BY A.TARIF";
$tampil_hotel = pg_query($sql_hotel);
$jlh_hotel = pg_num_rows($tampil_hotel);
$row_hotel = pg_fetch_array($tampil_hotel);
pg_free_result($tampil_hotel);
///////
$sql_hiburan ="SELECT SUM(A.DASAR_PENGENAAN_PAJAK) AS DASAR_HIBURAN,SUM(A.POKOK_PAJAK) AS POKOK_HIBURAN,A.TARIF FROM HIBURAN.SPTPD A
WHERE  A.kd_provinsi='$kd_provinsi_wp' and A.kd_kota='$kd_kota_wp' and A.kd_jns='$kd_jns_wp' and A.no_reg_wp='$no_reg_wp' AND A.BLN_PAJAK='$bulan' AND A.THN_PAJAK='$tahun' 
GROUP BY A.TARIF";
$tampil_hiburan = pg_query($sql_hiburan);
$jlh_hiburan = pg_num_rows($tampil_hiburan);
$row_hiburan = pg_fetch_array($tampil_hiburan);
pg_free_result($tampil_hiburan);
//////
/*$sql_parkir ="SELECT SUM(A.DASAR_PENGENAAN_PAJAK) AS DASAR_PARKIR,SUM(A.POKOK_PAJAK) AS POKOK_PARKIR,A.TARIF FROM PARKIR.SPTPD A
WHERE  A.kd_provinsi='$kd_provinsi_wp' and A.kd_kota='$kd_kota_wp' and A.kd_jns='$kd_jns_wp' and A.no_reg_wp='$no_reg_wp' AND A.BLN_PAJAK='$bulan' AND A.THN_PAJAK='$tahun' 
GROUP BY A.TARIF";
$tampil_parkir = pg_query($sql_parkir);
$jlh_parkir = pg_num_rows($tampil_parkir);
$row_parkir = pg_fetch_array($tampil_parkir);
pg_free_result($tampil_parkir);*/
///////
$sql_ppj ="SELECT SUM(A.DASAR_PENGENAAN_PAJAK) AS DASAR_PPJ,SUM(A.POKOK_PAJAK) AS POKOK_PPJ,A.TARIF FROM PPJ.SPTPD A
WHERE  A.kd_provinsi='$kd_provinsi_wp' and A.kd_kota='$kd_kota_wp' and A.kd_jns='$kd_jns_wp' and A.no_reg_wp='$no_reg_wp' AND A.BLN_PAJAK='$bulan' AND A.THN_PAJAK='$tahun' 
GROUP BY A.TARIF";
$tampil_ppj = pg_query($sql_ppj);
$jlh_ppj = pg_num_rows($tampil_ppj);
$row_ppj = pg_fetch_array($tampil_ppj);
pg_free_result($tampil_ppj);
///
?>
<tr><td width="6%">&nbsp;</td><td><b>DASAR PENGENAAN   </b></td><td colspan="3" width="30%"></td><td>=</td><td align="right"><b> Rp. <?php echo number_format($row_resto['dasar_resto']+$row_hotel['dasar_hotel']+$row_hiburan['dasar_hiburan']+$row_parkir['dasar_parkir']+$row_ppj['dasar_ppj']); ?>,-</b></td></tr>
<tr><td width="6%">&nbsp;</td><td><b>POKOK PAJAK  </b></td><td colspan="3" width="30%"></td><td>=</td><td align="right"><b>Rp. <?php echo number_format($row_resto['pokok_resto']+$row_hotel['pokok_hotel']+$row_hiburan['pokok_hiburan']+$row_parkir['pokok_parkir']+$row_ppj['pokok_ppj']); ?>,-</b></td></tr>
<tr><td></td><td colspan="4">&nbsp;</td><td>&nbsp;</td></tr>
</table>
<?php ////////////END MENAMPILKAN TOTAL PENGENAAN PAJAK /////////////</strong> ?>
<table class="skptablesub noborder font14" >
<tr><td>&nbsp;</td><td colspan="4"><b>PERHATIAN :</b></td></tr>
<tr><td>&nbsp;</td><td valign="top">1.</td><td colspan="4" align="justify">Berdasarkan Peraturan Daerah Nomor 1 Tahun 2024, Tentang Pajak Daerah dan Retribusi Daerah Pelaporan SPTPD Paling Lambat adalah <b>15 (lima belas) hari kerja</b> aetelah berakhirnya masa pajak.</td></tr>
<tr><td>&nbsp;</td><td valign="top">2.</td><td colspan="4" align="justify">Apabila Kewajiban Pelaporan SPTPD sebagimana point(1) tidak dipenuhi maka dikenakan <b>Denda sebesar Rp. 100.000,- (Seratus Ribu Rupiah)</b> untuk setiap SPTPD.</td></tr>
</table>
<table class="noborder font14">
<tr><td valign="top"></td><td align="justify" class="font14">Dengan menyadari sepenuhnya akan akibat termasuk sanksi-sanksi sesuai dengan ketentuan perundang-undangan yang berlaku, saya atau yang saya beri kuasa menyatakan apa yang telah kami beritahukan tersebut diatas beserta lampiran-lampirannya adalah benar, lengkap dan jelas.</td></tr>
</table><br/>
<table width="100%" class="noborder" >
  <tr>
    <td align="center"></td>
    <td>&nbsp;</td>
    <td align="center"><?php echo "Payakumbuh,      &nbsp;&nbsp;&nbsp;&nbsp; ".ucfirst($bln_lap)." ".$tahun; ?></td>
  </tr>
  <tr>
    <td align="center">&nbsp; </td>
    <td>&nbsp;</td>
    <td align="center"><b>WAJIB PAJAK </b></td>
  </tr>
 
  <tr>
    <td rowspan="3" valign="top">&nbsp;<div style="margin-top:-20px;">&nbsp;</div></td>
    <td align="center">&nbsp;<?php 
  $qr=md5("$npwpd");
  $tempdir = "qrimage/";
  //ambil logo
  $logopath="qrimage/bkd.png";

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
    <td align="center" valign="bottom"><b><?php echo "<u>".strtoupper($row_wp['nama'])."</u>"; ?></b></td>
  </tr>
  <tr style="height:20px;">
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center"></td>
  </tr> 
  <tr>
    <td align="center"></td>
    <td align="center">&nbsp;</td>
    <td align="center"></td>
  </tr> 
</table>
</div>