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
include_once $base_dir."inc/api.php";
include_once $base_dir."phpqrcode/qrlib.php";
include_once $base_dir."inc/va.inc.php";

$thn = pg_escape_string($_GET['thn']);
$bln = pg_escape_string($_GET['bln']);
//list($jns_surat,$thn_pajak,$bln_pajak,$kd_obj_pajak,$no_urut_skp) = explode(".", $no_skp);
$sql = "SELECT A.*,B.nama,B.alamat,C.rek_apbd,C.outlet_id,C.rek_va FROM air_tanah.skp A INNER JOIN public.wp B ON
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
$no_tagihan=$row['jns_surat'].".".$row['thn_pajak'].".".$row['bln_pajak'].".".$row['kd_obj_pajak'].".".$row['no_urut_surat'];
$jumlah=$row['pokok_pajak'];
$nama_wp=$row['nm_usaha'];
$thn_pajak=$row['thn_pajak'];
$bln_pajak=$row['bln_pajak'];
$kd_obj_pajak=$row['kd_obj_pajak'];
$no_urut_skp=$row['no_urut_surat'];
////////
 $nops = "01.".$thn_pajak.".".$bln_pajak.".".$kd_obj_pajak.".".$no_urut_skp;
 deleteVaPjd($nops);
 $va = getVaPjd($nops, $nama_wp, $jumlah);

 $sql_update = "UPDATE AIR_TANAH.SKP A SET va='$va' WHERE A.jns_surat='$jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_skp'";
 $update_va = pg_query($sql_update) or die('Query failed: ' . pg_last_error());
 
 
 if($update_va){
 $sql_va= "SELECT A.VA FROM AIR_TANAH.SKP A WHERE A.jns_surat='$jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_skp'";
 $tampil_va = pg_query($sql_va) or die('Query failed: ' . pg_last_error());
 $row_va = pg_fetch_array ($tampil_va);
 }

?>
<div style="width:100%;height:300mm;">
  <table width="100%" border="0" class="skptable">
  <tr>
    <td align="center">
<pre class="font10" style="line-height: 1.6em;">
PEMERINTAH KOTA PAYAKUMBUH
BADAN KEUANGAN DAERAH
KOTA PAYAKUMBUH
JL. VETERAN NO 70 KAPALO KOTO DIBALAI
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
<strong>No. Bayar</strong>
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
    <td width="78%">PAJAK AIR BAWAH TANAH</td>
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
			  	echo strtoupper($bulan_s[$row['bln_pajak']])." ".($row['thn_pajak']);
			  } else {
			  	echo strtoupper($bulan_s[$row['bln_pajak']])." ".($row['thn_pajak']);
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
	//if($row['bln_pajak'] == '01') { 
			echo "PAJAK AIR BAWAH TANAH ".$row['nm_usaha']." DI ".$row['alamat_usaha'];
			 // } else {
			  //	echo "PAJAK AIR BAWAH TANAH ".$row['nm_usaha']." DI ".$row['alamat_usaha']." MASA PAJAK BULAN ".strtoupper($bulan[$row['bln_pajak']-1])." ".($row['thn_pajak']);
			 // }
	?></td>
    </tr>
  <tr>
    <td width="12%" height="100">&nbsp;</td>
    <td width="10%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
    <td width="10%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
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
	<table  width="100%" border="0" class="font11 noborder">
 <?php
  $cek= $row['status_pembayaran'];
  if ($cek == 0 and $jumlah > 0 && $jumlah < 10000000){
 		       $no_tagihan_baru= preg_replace("/[^0-9]/", "", $no_skp);
			   $kd_surat=substr($no_tagihan_baru,1,1);
			   $kd_tahun=substr($no_tagihan_baru,4,2);
			   $kd_bln=substr($no_tagihan_baru,6,2);
			   $no_pajak=substr($no_tagihan_baru,8,2);
			   $no_urut=substr($no_tagihan_baru,11,3);
			   $billing_number="$kd_surat"."$kd_tahun"."$kd_bln"."$no_pajak"."$no_urut";
			   $id = "QR03";
			   $outlet_id = $row['outlet_id'];
			   $amount = intval($jumlah);
		       $data = api($id, $outlet_id, $billing_number, $amount, 0, "", "");
              // echo $outlet_id;
			  ?>
<tr><td colspan="2" align="left"><span class="style9">PILIH METODE PEMBAYARAN DIBAWAH </span></td></tr>
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
<span class="style10">(expired 30 hari sejak tanggal cetak)</span>
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

	</td>
  </tr>
</table>
 <?php } ?>
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
    <td align="center">
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
    <td>&nbsp;<?php
	//$nip = $row['nipps'];
	//$sql = "SELECT * FROM public.user WHERE nip='$nip'";
	//$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
	//$row_user = pg_fetch_array($result);
	//echo $row_user['jabatan'];
	//echo "a.n KEPALA BADAN KEUANGAN DAERAH<";
	?>  
	
   
    &nbsp;<?php 
/* $qr=md5("$no_skp");
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
 imagepng($QR,$tempdir.'image.png'); 
 echo '<img src="'.$tempdir.'image.png'.'" /></br>' */?> </td>
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