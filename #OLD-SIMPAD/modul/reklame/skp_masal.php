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

$thn = pg_escape_string($_GET['thn']);
$bln = pg_escape_string($_GET['bln']);
$sql = "SELECT A.*,B.nama,B.alamat,B.kelurahan,B.kecamatan,B.kota,C.rek_apbd,c.outlet_id,C.rek_va FROM reklame.skp A INNER JOIN public.wp B ON
B.kd_provinsi=A.kd_provinsi AND
B.kd_kota=A.kd_kota AND
B.kd_jns=A.kd_jns AND
B.no_reg=A.no_reg_wp
LEFT JOIN public.keg_usaha C ON
C.kd_obj_pajak=A.kd_obj_pajak AND
C.kd_keg_usaha=A.kd_keg_usaha
WHERE A.thn_pajak='$thn' AND A.bln_pajak='$bln' AND A.status_pembayaran < '2' ORDER BY jns_surat,thn_pajak,bln_pajak,kd_obj_pajak,no_urut_surat";
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
$rek=substr($row['rek_va'],8);
?>
<div style="width:100%;height300mm;">
  <table width="100%" border="0" class="skptable">  <tr>
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
    <td align="justify"><?php echo $row['alamat']; ?>&nbsp;<?php echo $row['kelurahan']; ?>&nbsp;<?php echo $row['kecamatan']; ?>&nbsp;<?php echo $row['kota']; ?></td>
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
    <td valign="top" align="center" class="font11" style="padding-left:5px;padding-top:10px;padding-right:5px;padding-bottom:5px;">&nbsp;1</td>
    <td valign="top" align="center" class="font11" style="padding-left:5px;padding-top:10px;padding-right:5px;padding-bottom:5px;"><?php echo $rek; ?></td>
    <td>
<table width="100%" border="0" class="noborder" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;">
  <tr>
    <td colspan="12" align="justify"><?php echo "PAJAK REKLAME ".$row['nm_reklame']." YANG DIPASANG DI ".$row['alamat_reklame'];?></td>
    </tr>
  <tr>
    <td width="12%">&nbsp;</td>
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
   <tr>
    <td colspan="12"><?php if(strpos($row['nm_tarif'], 'LEMBAR') !== false) { echo $row['nm_tarif']; } ?></td>
    </tr>
	<?php
	if(strpos($row['nm_tarif'], 'LEMBAR') !== false) {
	?>
	<tr>  	
    <td>Ukuran</td>
    <td align="center"><?php echo $row['p']."m"; ?></td>
    <td align="center">x</td>
    <td align="center"><?php echo $row['l']."m"; ?></td>
    <td align="center">x</td>
    <td align="center"><?php echo $row['s']."sisi"; ?></td>
    <td align="center"></td>
    <td align="center"></td>
    <td align="center"></td>
    <td align="center"></td>
    <td></td>
    <td></td>	
  </tr>
  <tr>
    <td>Perkalian</td>
    <td align="center"><?php echo $row['jlh']." bh"; ?></td>
    <td align="center">x</td>
    <td align="center"><?php echo $row['lama_pasang']; ?></td>
    <td>x</td>
    <td align="center"><?php echo number_format($row['tarif']); ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>=</td>
    <td><?php echo "Rp. ".number_format($row['pokok_pajak']); ?></td>
  </tr>
	<?php
	} else {
	?>
  <tr>  	
    <td>Ukuran</td>
    <td align="center"><?php echo $row['p']."m"; ?></td>
    <td align="center">x</td>
    <td align="center"><?php echo $row['l']."m"; ?></td>
    <td align="center">x</td>
    <td align="center"><?php echo $row['s']."sisi"; ?></td>
    <td align="center">x</td>
    <td align="center"><?php echo $row['jlh']."bh"; ?></td>
    <td align="center">x</td>
    <td align="center"><?php echo $row['lama_pasang']; ?></td>
    <td>=</td>
    <td><?php echo $row['p']*$row['l']*$row['s']*$row['jlh']*$row['lama_pasang']."m&sup2;"; ?></td>	
  </tr>
  <tr>
    <td>Perkalian</td>
    <td align="center"><?php echo $row['p']*$row['l']*$row['s']*$row['jlh']*$row['lama_pasang']."m&sup2;"; ?></td>
    <td align="center">x</td>
    <td align="center"><?php echo number_format($row['tarif']); ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>=</td>
    <td><?php echo "Rp. ".number_format($row['pokok_pajak']); ?></td>
  </tr>
  <?php
	}
  ?>
  <tr>
    <td height="50">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
<tr>
<td width="40%"><?php
			$cek= $row['status_pembayaran'];
 
 			   if ($cek == 0){
		       $no_tagihan_baru= preg_replace("/[^0-9]/", "", $no_tagihan);
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
               
				if($data->rc == "00")
				{
				if($id == "QR03")
				{
				echo "<div align='center'><img src='../../images/iconQris.png' width='50%'></div>";
				qrImg($data->data->qrData, $billing_number);
				
				echo "<div align='center'><img src='qrimage/$billing_number.png' width='45%'></div>";
				
				echo "<b>SILAHKAN SCAN QR <BR/>UNTUK PEMBAYARAN ".strtoupper($row_jns_pjk['nm_obj_pajak'])." !!</b><br/>";
				echo "<i>(Nagari, BNI, BRI, BSI, BCA, Mandiri, GoPay, ShopeePay, Dana, OVO, dll)</i>";
               
				}
				}
				}
                
			   ?></td>
<td align="left" valign="top" >
<b>Untuk Cetak Bukti Pembayaran dapat melalui :</b><br/>
1. Website https://pajakqris.payakumbuhkota.go.id <br/>
&nbsp;&nbsp;&nbsp;&nbsp;Atau melalui aplikasi <b>QRIS Pajak Payakumbuh</b> di Playstore<br/>
2. Pada Kolom QRIS Pajak Daerah Masukkan Nomor Bayar. <br/>
3. Pilih Cetak Bukti -> Download Bukti<br/></td>
</td>
</tr>
<tr><td colspan="2">Berdasarkan Peraturan Daerah Nomor 1 Tahun 2024 Tentang Pajak Daerah dan Retribusi Daerah Dilakukan Penyesuaian Tarif Pajak Reklame Menjadi 25% Terhitung 1 Januari 2024 </b> </td></tr>
<tr><td colspan="2">Apabila SKPD ini tidak atau belum dibayar lewat waktu paling lama 30 hari sejak SKPD diterima akan dikenakan <b>sanksi administrasi berupa bunga sebesar 1% perbulan.</b> </td></tr>
</table></td>
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
	KOTA PAYAKUMBUH </td>
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
    <td><span style="padding-left:15px;">NAMA : <?php echo $row['nama']; ?></span></td>
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
<!--<div style="page-break-before:always;">

</div> --!>