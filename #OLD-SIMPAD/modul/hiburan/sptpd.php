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
?>
<div style="width:100%;height:330mm;">
  <table width="100%" border="0" class="skptable">
  <tr>
    <td align="center">
<pre class="font10" style="line-height: 1.6em;">
PEMERINTAH KOTA PAYAKUMBUH
BADAN KEUANGAN DAERAH
KOTA PAYAKUMBUH
JL. VETERAN NO. 70
PAYAKUMBUH
</pre>
	</td>
    <td align="center">
<pre>
<strong class="font18">S P T P D</strong>
<strong class="font12">(SURAT PEMBERITAHUAN PAJAK DAERAH)</strong>
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

 ?> <?php echo $row['thn_pajak']; ?></strong>
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
    <td width="2%">A.</td>
    <td colspan="3">IDENTITAS WAJIB PAJAK DAERAH</td>
    </tr>	
  <tr>
    <td width="2%">&nbsp;</td>
    <td width="28%">NPWPD</td>
    <td width="1%">:</td>
    <td width="69%"><?php echo $row['kd_provinsi'].".".$row['kd_kota'].".".$row['kd_jns'].".".$row['no_reg_wp']; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>NOP</td>
    <td>:</td>
    <td align="justify"><?php echo $row['kd_kecamatan'].".".$row['kd_kelurahan'].".".$row['kd_obj_pajak'].".".$row['kd_keg_usaha'].".".$row['no_reg']; ?></td>
  </tr>  
  
  <tr>
    <td>&nbsp;</td>
    <td>NAMA OBJEK </td>
    <td>:</td>
    <td><?php echo $row['nm_usaha']; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>ALAMAT OBJEK </td>
    <td>:</td>
    <td><?php echo $row['alamat_usaha']; ?></td>
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
        <td width="55%">................................................</td>
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
	<p align="center"><strong>DIISI OLEH PENGUSAHA HIBURAN</strong></p>
	<b>1. HIBURAN YANG DISELENGGARAKAN</b>
		<table width="100%" border="0" class="font11">
		<tr>
			<td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> TONTONAN FILM</td>
			<td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> PAGELARAN KESENIAN,MUSIK,TARI ATAU BUSANA</td>
			<td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> KONTES KECANTIKAN,BINARAGA DAN SEJENISNYA</td>
		
			
		</tr>
		<tr style="border-bottom:solid #000000 1px;">
		<td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> PAMERAN</td>
	    <td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> DISKOTEK,KARAOKE,KLAB MALAM DAN SEJENISNYA</td>
		<td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> SIRKUS AKROBAT DAN SULAP</td>
		 
		  </tr>
		<tr style="border-bottom:solid #000000 1px;">
		<td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> PERMAINAN BILYAR,GOLF DAN BOWLING</td>
	    <td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> PACUAN KUDA,KENDERAAN BERMOTOR DAN PERMAINAN KETANGKASAN</td>
		<td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> PANTI PIJAT/TEMPAT URUT,REFLEKSI,MANDI UAP/SPA DAN PUSAT KEBUGARAN</td>
		 
		  </tr>
		  <tr style="border-bottom:solid #000000 1px;">
		<td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> PERTANDINGAN OLAHRAGA</td>
	    
		  </tr>
		</table>
		<br>
		<b>2. HARGA TANDA MASUK YANG BERLAKU</b>
		<table width="100%" border="0" class="font11" >
		<tr>
			<td align="left">A.KELAS</td><td>..............</td><td>&nbsp;Rp.....................</td></tr>
			<tr><td align="left">B.KELAS</td><td>..............</td><td>&nbsp;Rp.....................</td></td></tr>
			<tr><td align="left">C.KELAS</td><td>..............</td><td>&nbsp;Rp.....................</td></td></tr>
		
		</table>
		<br />
		<table class="font11" width="100%" class="skptablesub"><tr><td width="70%">
		<b>3. JUMLAH PERTUNJUKAN RATA-RATA PADA HARI BIASA	</b>	</td><td>&nbsp;:</td><td>&nbsp;................. Kali</td></tr>
	<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;JUMLAH PERTUNJUKAN RATA-RATA PADA HARI LIBUR / MINGGU</td><td>&nbsp;:</td><td>&nbsp;................. Kali</td></tr>
	<tr><td>  &nbsp;&nbsp;&nbsp;&nbsp;(Khusus Untuk Pertunjukan Film,Kesenian dan Sejenisnya,Pagelaran Musik dan Tari)</td></tr></table>
		
		<br/>
       <table class="font11" width="100%"><tr><td width="70%">
		<b>4. JUMLAH PERTUNJUKAN RATA-RATA PADA HARI BIASA	</b>	</td><td>&nbsp;:</td><td>&nbsp;................. Kali</td></tr>
	<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;JUMLAH PERTUNJUKAN RATA-RATA PADA HARI LIBUR / MINGGU</td><td>&nbsp;:</td><td>&nbsp;................. Kali</td></tr>
	</table>
		
		<br/><br/>
		    <table class="font11" width="100%"><tr><td width="70%">
		<b>5. JUMLAH MEJA/MESIN	</b>	</td><td>&nbsp;:</td><td>&nbsp;................. Buah</td></tr>
	<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;(Khusus Untuk Bilyard,Permainan Ketangkasan)</td><td>&nbsp;</td><td>&nbsp;</td></tr>
	</table>
		<br /><br />
		<table class="font11" width="100%"><tr><td width="70%">
		<b>6. JUMLAH KAMAR/RUANGAN (KHUSUS KARAOKE)	</b>	</td><td>&nbsp;:</td><td>&nbsp;................. Buah</td></tr>
	
	</table>
		<br /><br />
		   
		    <table class="font11" width="100%"><tr><td width="70%">
		<b>7. APAKAH PERUSAHAAN MENYEDIAKAN KARCIS BEBAS (FREE KEPADA ORANG TERTENTU)</b>	</td><td>&nbsp;:</td><td><?php echo "<img src=\"../../images/box.png\">"; ?>&nbsp;YA</td><td><?php echo "<img src=\"../../images/box.png\">"; ?>&nbsp;TIDAK</td></tr>
		<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Jika Ya Berapa Jumlah Yang Beredar</td><td>&nbsp;:</td><td>&nbsp;...............Buah</td></tr>
	</table>
	 <br/><br/>
		    <table class="font11" width="100%"><tr><td width="70%">
		<b>8. MELAKSANAKAN PEMBUKUAN/PENCATATAN</b>	</td><td>&nbsp;:</td><td><?php echo "<img src=\"../../images/box.png\">"; ?>&nbsp;YA</td><td><?php echo "<img src=\"../../images/box.png\">"; ?>&nbsp;TIDAK</td></tr>
		
	</table>
			

		<tr>
  <td style="padding-left:20px;padding-top:5px;padding-right:20px;padding-bottom:5px;" class="font11">
 <b> 2. Jumlah Pembayaran dan Pajak terhutang untuk masa pajak sekarang (lampirkan foto copy dokumen)</b>
  <table class="font11" width="100%">
  <tr><td>a.</td><td width="70%">Masa Pajak</td><td>:</td><td>&nbsp;<strong class="font12" ><?php  
$bln=$row['bln_pajak'];
$e=getBulan($bln);
echo"$e";

 ?> <?php echo $row['thn_pajak']; ?></strong></td></tr>
  <tr><td>b.</td><td>Dasar pengenaan (Jumlah pembayaran yang diterima)</td><td>:</td><td><strong>Rp&nbsp;<?php echo number_format($row['dasar_pengenaan_pajak']); ?></strong></td></tr>
  <tr><td>c.</td><td>Tarif Pajak</td><td>:</td><td><b>&nbsp;10%</b></td></tr>
  <tr><td>d.</td><td >Pajak Terutang (b x c)</td><td>:</td><td><strong>Rp&nbsp;<?php echo number_format($row['pokok_pajak']); ?></strong></td></tr>
  <tr><td>&nbsp;</td><td >Dengan Huruf&nbsp;:&nbsp;<?php echo "<strong><i># ".terbilang($row['pokok_pajak'], $style=3)." Rupiah #</i></strong>"; ?></td><td>&nbsp;</td><td>&nbsp;</td></tr>
  </table>
  </td>
  </tr>
			
  <tr>
  <td style="padding-left:20px;padding-top:5px;padding-right:20px;padding-bottom:5px;" class="font11">Demikianlah SPTPD ini diisi dengan sebenar-benarnya dan apabila dikemudian hari terdapat ketidak benaran kami bersedia dikenakan sanksi sesuai dengan ketentuan yang berlaku </td>
  </tr>
</table>
<table  width="100%" border="0" class="font11">
<tr>
<td width="50%"><?php
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
				echo "<div align='center'><img src='../../images/iconQris.png' width='38%'></div>";
				qrImg($data->data->qrData, $billing_number);
				
				echo "<div align='center' ><img src='qrimage/$billing_number.png' width='38%'></div>";
				
				echo "<b>SILAHKAN SCAN QR <BR/>UNTUK PEMBAYARAN ".strtoupper($row_jns_pjk['nm_obj_pajak'])." !!</b><br/>";
				echo "<i>(Nagari, BNI, BRI, BSI, BCA, Mandiri, GoPay, ShopeePay, Dana, OVO, dll)</i>";
               
				}
				}
				}
                
			   ?></td>
<td align="center">
<div><?php echo "Payakumbuh, ".date('d',strtotime($row['tgl_penyampaian']))." ".$bulan[date('n',strtotime($row['tgl_penyampaian']))]." ".date('Y',strtotime($row['tgl_penyampaian'])); ?>
<p>WAJIB PAJAK / PENYETOR</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;	 </p>
    <?php  echo "<u>".strtoupper($row['nama'])."</u>";  ?></div>
</td>
</tr>
<tr><td colspan="2">
<?php
if ($cek == 0){ ?>
<b>Untuk Cetak Bukti Pembayaran dapat melalui :</b><br/>
1. Website https://pajakqris.payakumbuhkota.go.id <br/>
&nbsp;&nbsp;&nbsp;&nbsp;Atau melalui aplikasi QRIS Pajak Payakumbuh bisa di download di Playstore<br/>
2. Pada Kolom QRIS Pajak Daerah Masukkan Nomor Bayar / Urut <br/>
3. Pilih Cetak Bukti -> Download Bukti<br/><?php } ?></td></tr>
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
//if($x != $n_row) {
//	echo "<div style=\"page-break-before:always;\"></div>";
//	$x++;
//}

}
pg_free_result($tampil);
pg_close($dbconn);


?>