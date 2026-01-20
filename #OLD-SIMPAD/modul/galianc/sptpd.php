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
include_once $base_dir."inc/api.php";

$no_sptpd = pg_escape_string($_GET['no_sptpd']);
list($jns_surat,$thn_pajak,$bln_pajak,$kd_obj_pajak,$no_urut_sptpd) = explode(".", $no_sptpd);
$schema = $list_schema[$kd_obj_pajak];
$sql = "SELECT A.*,B.nama,B.alamat,C.rek_apbd,C.outlet_id FROM $schema.sptpd A INNER JOIN public.wp B ON
B.kd_provinsi=A.kd_provinsi AND
B.kd_kota=A.kd_kota AND
B.kd_jns=A.kd_jns AND
B.no_reg=A.no_reg_wp
LEFT JOIN public.keg_usaha C ON
C.kd_obj_pajak=A.kd_obj_pajak AND
C.kd_keg_usaha=A.kd_keg_usaha
WHERE A.jns_surat='$jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_sptpd'";
$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
$n_row = pg_num_rows($tampil);
if($n_row <= 0) {
	echo "NO. SPTPD TIDAK DITEMUKAN, SILAHKAN PERIKSA KEMBALI NO. SPTPD";
	pg_free_result($tampil);
	pg_close($dbconn);
	exit;
}
$x = 1;

?>
<style type="text/css">
table.noborder td, table.noborder th, table.noborder tr { border:none; font-family:"Verdana"; font-size:13px; }

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
    border-width:0px;
    border-color:#000;
	border-top:1px;
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
.font18 {
	font-family:"Verdana";
	font-size:18px;
}
</style>
<?php
while ($row = pg_fetch_array($tampil)) { 
$jns_surat=$row['jns_surat'];
		$thn_pajak=$row['thn_pajak'];
		$bln_pajak=$row['bln_pajak'];
		$kd_obj_pajak=$row['kd_obj_pajak'];
		$no_urut_surat=$row['no_urut_surat'];
		 $no_tagihan=$row['jns_surat'].".".$row['thn_pajak'].".".$row['bln_pajak'].".".$row['kd_obj_pajak'].".".$row['no_urut_surat'];
  $jumlah=$row['pokok_pajak'];
		
?>
<div style="width:215mm;height:300mm;">
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
    <td align="center"><br/>
<pre>
<strong class="font18">S P T P D</strong>
<strong class="font12">(SURAT PEMBERITAHUAN TAGIHAN PAJAK DAERAH)</strong>
<strong class="font12"><?php $q_jns_pjk="SELECT nm_obj_pajak FROM public.obj_pajak WHERE kd_obj_pajak='$kd_obj_pajak'";
		$rs_jns_pjk = pg_query($q_jns_pjk) or die('Query failed: ' . pg_last_error());
		$row_jns_pjk = pg_fetch_array($rs_jns_pjk);
		
		echo strtoupper($row_jns_pjk['nm_obj_pajak']);
		pg_free_result($rs_jns_pjk);
	 ?>	</strong>
<strong class="font12" >Masa Pajak : <?php  
$bln=$row['bln_pajak'];
$e=getBulan($bln);
echo"$e";
$kd_kg_ush=$row['kd_keg_usaha'];

 ?> <?php echo $row['thn_pajak']; ?></strong>
</pre><br/><br/>
	</td>
    <td align="center">
<pre class="font14">
<strong>No. Urut</strong>
<?php echo $row['jns_surat'].".".$row['thn_pajak'].".".$row['bln_pajak'].".".$row['kd_obj_pajak'].".".$row['no_urut_surat']; ?>
</pre>
	</td>
  </tr>
  
</table>
<table width="100%" border="0" class="skptablesub font12">
  <tr>
    <td>
<table width="100%" border="0" class="noborder" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;">
  <tr height="35%">
    <td colspan="2">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="2"><b>Perhatian :</b></td>
    </tr>	
  <tr>
    <td>1.</td>
	<td>Harap diisi dalam rangkap 2 (dua) dan ditulis dengan huruf CETAK</td>
  </tr>
  <tr>
   <td>2.</td>
	<td>Beri Nomor Pada Kotak yang tersedia unutuk jawaban yang diberikan.</td>
    
  </tr>  
  
  <tr>
     <td valign="top">3.</td>
	<td>Setelah diisi dan ditandatangani , harap diserahkan kembali ke Badan Keuangan Daerah Kota Payakumbuh paling lambat tanggal 20 bulan berikutnya.</td>
  
  </tr>
  <tr>
    <td valign="top">4.</td>
	<td>Keterlambatan penyerahan SPTPD dikenakan sanksi sesuai ketentuan yang berlaku.</td>
    
  </tr>
</table>
	
<table width="100%" border="0" class="noborder font12" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;">
  <tr height="35%">
    <td colspan="2">&nbsp;</td>
    </tr>
  <tr>
    <td width="2%"><b>I.</b></td>
    <td colspan="4"><b>IDENTITAS WAJIB PAJAK</b> </td>
    </tr>	
	<tr>
   <td width="2%"></td>
    <td>e.</td>
    <td>NPWPD</td>
    <td>:</td>
    <td><?php echo $row['kd_provinsi'].".".$row['kd_kota'].".".$row['kd_jns'].".".$row['no_reg_wp']; ?></td>
  </tr>
	 <tr>
	 <td width="2%"></td>
    <td width="2%">a.</td>
    <td width="28%">Nama Wajib Pajak</td>
    <td width="1%">:</td>
    <td width="69%"><?php echo $row['nama']; ?></td>
  </tr>
  <tr>
   <td width="2%"></td>
    <td width="2%">b.</td>
    <td width="28%">Alamat</td>
    <td width="1%">:</td>
    <td width="69%"><?php echo $row['alamat']; ?></td>
  </tr>
  <tr>
   <td width="2%"></td>
    <td>f.</td>
    <td>NOP</td>
    <td>:</td>
    <td><?php echo $row['kd_kecamatan'].".".$row['kd_kelurahan'].".".$row['kd_obj_pajak'].".".$row['kd_keg_usaha'].".".$row['no_reg']; ?></td>
  </tr>
  <tr>
   <td width="2%"></td>
    <td>c.</td>
    <td>Nama Pekerjaan</td>
    <td>:</td>
    <td align="justify"><?php echo $row['nm_usaha']; ?></td>
  </tr>  
  
  <tr>
   <td width="2%"></td>
    <td>d.</td>
    <td>Alamat Pekerjaan</td>
    <td>:</td>
    <td><?php echo $row['alamat_usaha']; ?></td>
  </tr>
  <tr height="20%"><td>&nbsp;</td></tr>
  <tr>
    <td width="2%"><b>II.</b></td>
    <td colspan="4"><b>DIISI OLEH WAJIB PAJAK</b> </td>
    </tr>
	<tr>
   <td width="2%"></td>
    <td>a.</td>
    <td>Klasifikasi Usaha</td>
    <td>:</td>
    <td><?php 
	$keg_usaha="SELECT nm_keg_usaha FROM public.keg_usaha WHERE kd_obj_pajak='$kd_obj_pajak' and kd_keg_usaha='$kd_kg_ush'";
	$keg_usaha_q=pg_query($keg_usaha) or die('Query failed: ' . pg_last_error());
	$row_keg_usaha = pg_fetch_array($keg_usaha_q);
	echo $row_keg_usaha['nm_keg_usaha']; ?></td>
  </tr>
  <tr>
   <td width="2%"></td>
    <td>b.</td>
    <td>Pembayaran Biaya ........................</td>
    <td>:</td>
    <td>Rp.</td>
  </tr>
   <tr>
   <td width="2%"></td>
    <td valign="top">c.</td>
    <td valign="top">Dasar Pengenaan Pajak (DPP)</td>
    <td valign="top">:</td>
    <td><table border="1" width="100%"><tr><td><b>No</b></td><td width="40%"><b>Jenis Objek</b></td><td><b>Volume / m3</b></td><td><b>Jumlah Pajak</b></td></tr>
	<?php
	$no=1;
	$queri_kali="SELECT * from galianc.perkalian A where A.jns_surat='$jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_surat' ";
	$result_kali=pg_query($queri_kali) or die('Query failed: ' . pg_last_error());
	while ($row_k=pg_fetch_array($result_kali))
	{
	$kd_op=$row_k['kd_op']; ?>
	
	<tr><td><?php echo $no; ?></td><td><?php echo $row_k['jns_op']; ?></td><td align="center"><?php echo $row_k['v_op']; ?></td><td align="center"><?php echo number_format($row_k['t_op']); ?></td></tr>
<?php	$no++;}
	
	?>
	
	</table></td>
  </tr>
   <tr>
   <td width="2%"></td>
    <td>d.</td>
    <td>Pajak Terutang (<?php
	$persen_tarif = $row['tarif'] * 100;
	 echo $persen_tarif; ?>&nbsp;%&nbsp;x&nbsp;DPP)</td>
    <td>:</td>
    <td>Rp.&nbsp;<?php echo number_format($row['pokok_pajak']);?></td>
  </tr>
  <tr>
   <td width="2%"></td>
    <td>e.</td>
    <td>Pajak Kurang atau Lebih Bayar</td>
    <td>:</td>
    <td>Rp.&nbsp;</td>
  </tr>
  <tr>
   <td width="2%"></td>
    <td>f.</td>
    <td>Sanksi Administrasi</td>
    <td>:</td>
    <td>Rp.&nbsp;</td>
  </tr>
  <tr>
   <td width="2%"></td>
    <td>g.</td>
    <td>Jumlah Pajak yang Dibayar</td>
    <td>:</td>
    <td>Rp.&nbsp;<?php echo number_format($row['pokok_pajak']);?></td>
  </tr>
  <tr>
   <td width="2%"></td>
    <td>h.</td>
    <td>Data Pendukung</td>
    <td>:</td>
    <td>Lampiran       *)</td>
  </tr>
  
</table>
	</td>
  </tr>
</table>
<table width="100%" border="0" class="skptablesub">
  <tr>
    <td><table width="100%" class="noborder font12">
	<tr height="10%"><td>&nbsp;</td></tr>
		<tr><td width="5%">&nbsp;</td><td>i.</td><td width="25%">Surat Setoran Pajak Daerah</td><td>:</td><td>Ada/Tidak Ada</td></tr>
		<tr><td width="5%">&nbsp;</td><td>ii.</td><td width="25%">Rekapitulasi Hasil Usaha</td><td>:</td><td>Ada/Tidak Ada</td></tr>
		<tr><td width="5%">&nbsp;</td><td>iii.</td><td width="25%">Jumlah Harian</td><td>:</td><td>Ada/Tidak Ada</td></tr>
		<tr><td width="5%">&nbsp;</td><td>iv.</td><td width="25%">........................</td><td>:</td><td>Ada/Tidak Ada</td></tr>
		
		</table></td>
  </tr>  
</table>
<table width="100%" border="0" class="skptablesub">
  <tr>
  <td style="padding-left:20px;padding-top:5px;padding-right:20px;padding-bottom:10px;" class="font13">Demikianlah Formulir SPTPD ini diisi dengan sebenar-benarnya dan apabila terdapat ketidak benaran dalam memenuhi kewajiban pengisian SPTPD ini, saya bersedia dikenakan sanksi sesuai dengan Peraturan Daerah yang berlaku. </td>
  </tr>
</table><br/><br/><br/><br/>
<table  width="100%" border="0" class="font11">
<tr>
<td width="50%"><?php
			$cek= $row['status_pembayaran'];
 
 			   //if ($cek == 0){
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
				echo "<div align='center'><img src='../../images/iconQris.png' width='38%'></div>";
				qrImg($data->data->qrData, $billing_number);
				
				echo "<div align='center' style='margin-top:-120px;'><img src='qrimage/$billing_number.png' width='38%'></div>";
				
				echo "<b>SILAHKAN SCAN QR <BR/>UNTUK PEMBAYARAN ".strtoupper($row_jns_pjk['nm_obj_pajak'])." !!</b><br/>";
				echo "<i>(Nagari, BNI, BRI, BSI, BCA, Mandiri, GoPay, ShopeePay, Dana, OVO, dll)</i>";
               
				//}
				}
				}
                
			   ?></td>
<td align="center" style="margin-top:-60px;">
<div style="margin-top:-50px;"><?php echo "Payakumbuh, ".date('d',strtotime($row['tgl_penyampaian']))." ".$bulan[date('n',strtotime($row['tgl_penyampaian']))]." ".date('Y',strtotime($row['tgl_penyampaian'])); ?>
<p>WAJIB PAJAK / PENYETOR</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;	 </p>
    <?php // echo "<u>".strtoupper($row['nama'])."</u>";  ?>(_______________________)</div>
</td>
</tr>
<tr><td colspan="2"><b>Untuk Cetak Bukti Pembayaran dapat melalui :</b><br/>
1. Website https://pajakqris.payakumbuhkota.go.id <br/>
2. Pada Kolom QRIS Pajak Daerah Masukkan Nomor Bayar / Urut <br/>
3. Pilih Cetak Bukti -> Download Bukti<br/></td></tr>
</table>

</div>
<?php
if($row['dat_pendukung'] == "BILL") {
?>
<div style=\"page-break-before:always;\"></div>
<table width="100%" border="0" class="noborder font18">
<tr>
<td align="center"><font class="font16">
LAMPIRAN SPTPD NO. <?php echo $row['jns_surat'].".".$row['thn_pajak'].".".$row['bln_pajak'].".".$row['kd_obj_pajak'].".".$row['no_urut_surat']; ?><br />
DASAR PENGENAAN PAJAK<br />
MASA <?php 
			  if($row['bln_pajak'] == '01') { 
			  	echo strtoupper($bulan[$row['bln_pajak']-1])." ".($row['thn_pajak']-1);
			  } else {
			  	echo strtoupper($bulan[$row['bln_pajak']-1])." ".($row['thn_pajak']);
			  }
 ?></font>
</td>
<tr>
</tr>
	<td align="center"><br />PERINCIAN PENJUALAN BARANG / JASA</td>
</tr>
</table>
<table width="100%" border="1" class="garis font11" cellpadding="4px">
			<tr>
				<td align="center">No.</td>
				<td align="center">Penjualan Barang / Jasa</td>
				<td align="center">(Rp.)</td>
			</tr>
			<tr>
				<td align="center">1.</td>
				<td align="left">Makanan / Minuman</td>
				<td align="center"></td>
			</tr>			
			<?php
				$sql_detail = "SELECT * FROM bill.pengembalian_bill A
WHERE A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_kecamatan='".$row['kd_kecamatan']."' AND A.kd_kelurahan='".$row['kd_kelurahan']."' AND A.kd_obj_pajak='".$row['kd_obj_pajak']."' AND A.kd_keg_usaha='".$row['kd_keg_usaha']."' AND A.no_reg='".$row['no_reg']."'";
				$tampil_data = pg_query($sql_detail) or die('Query failed: ' . pg_last_error());
				while ($row_detail = pg_fetch_array($tampil_data)) {
					echo "<tr>";
					echo "<td></td>";
					echo "<td style=\"padding-left:20px\">Bill Seri ".$row_detail['seri']." Nomor ".$row_detail['no_bill']."</td>";
					echo "<td align=\"right\">".number_format($row_detail['dasar_pengenaan_pajak'])."</td>";
					echo "</tr>";
				}
				pg_free_result($tampil_data);
			?>
			<tr>
				<td align="center"></td>
				<td align="center">JUMLAH</td>
				<td align="right"><?php echo number_format($row['dasar_pengenaan_pajak']); ?></td>
			</tr>
</table>
		<table width="100%" class="noborder font11">
			<tr>
				<td width="56%" >&nbsp;</td>
				<td width="19%" >&nbsp;</td>
				<td width="1%" >&nbsp;</td>
				<td width="24%" >&nbsp;</td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			  <td>DITERIMA TANGGAL </td>
			  <td>:</td>
			  <td><?php echo date('d',strtotime($row['tgl_penyampaian']))." ".$bulan[date('n',strtotime($row['tgl_penyampaian']))]." ".date('Y',strtotime($row['tgl_penyampaian'])); ?></td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td>NAMA PETUGAS </td>
			  <td>:</td>
			  <td>&nbsp;</td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td>TANDA TANGAN </td>
			  <td>:</td>
			  <td>&nbsp;</td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td colspan="3" align="center">MENGETAHUI</td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td colspan="3" align="center">SETELAH DIPERIKSA OLEH </td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td colspan="3" align="center">KEPALA BIDANG PENDAPATAN </td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td colspan="3" align="center">&nbsp;</td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td colspan="3" align="center">&nbsp;</td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td colspan="3" align="center">&nbsp;</td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td colspan="3" align="center"><u><?php echo $nm_kabid; ?></u></td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td colspan="3" align="center"><?php echo $nip_kabid; ?></td>
		  </tr>
		</table>
<?php
}
if($x != $n_row) {
	echo "<div style=\"page-break-before:always;\"></div>";
	$x++;
}

}
pg_free_result($tampil);
pg_close($dbconn);


?>