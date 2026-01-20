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

$no_sptpd = pg_escape_string($_GET['no_sptpd']);
list($jns_surat,$thn_pajak,$bln_pajak,$kd_obj_pajak,$no_urut_sptpd) = explode(".", $no_sptpd);
$schema = $list_schema[$kd_obj_pajak];
$sql = "SELECT A.*,B.nama,B.alamat,C.rek_apbd FROM $schema.sptpd A INNER JOIN public.wp B ON
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
    <td>NAMA HOTEL </td>
    <td>:</td>
    <td><?php echo $row['nm_usaha']; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>ALAMAT HOTEL </td>
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
	<p align="center"><strong>UNTUK PENGUSAHA HOTEL/PENGINAPAN/WISMA DLL</strong></p>
	KELAS HOTEL/PENGINAPAN/WISMA
		<table width="100%" border="0" class="font11">
		<tr>
			<td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> BINTANG LIMA</td>
			<td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> BINTANG TIGA</td>
			<td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> BINTANG SATU</td>
			<td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> TEMPAT KOS</td>
		</tr>
		<tr style="border-bottom:solid #000000 1px;">
		 <td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> BINTANG EMPAT</td>
		  <td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> BINTANG DUA</td>
		 <td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> BINTANG MELATI</td>
		 <td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> LAIN-LAIN</td>
		  </tr>
		
		</table>
		<br>
		FASILITAS HOTEL/PENGINAPAN/WISMA YANG DIMILIKI
		<table width="100%" border="0" class="font11" >
		<tr>
			<td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> RESTORAN</td>
			<td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> KARAOKE</td>
			<td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> LAUNDRY</td>
			<td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> BUSINESS CENTRE</td>
			<td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> TAXI</td>
		</tr>
		<tr>
		 <td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> BAR</td>
		  <td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> KOLAM RENANG</td>
		 <td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> TELEPON/FAX</td>
		 <td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> FITNES CENTER</td>
		 <td align="left"><?php echo "<img src=\"../../images/box.png\">"; ?> LAIN-LAIN</td>
		  </tr>
		
		</table>
		<br />
		KELAS/GOLONGAN/JENIS/TYPE KAMAR DAN TARIF
		<table width="100%" class="garis font11">
		<tr>
		<td>No</td><td align="center">Kelas/Golongan/Jenis/Type Kamar</td><td align="center">Jumlah Kamar</td><td align="center">Tarif per malam (Rp.)</td>
		</tr>
		<tr>
		<td>1</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
		</tr>
		<tr>
		<td>2</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
		</tr>
		<tr>
		<td>3</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
		</tr>
		<tr>
		<td>4</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
		</tr>
		<tr>
		<td>5</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
		</tr>
		<tr>
		<td>6</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
		</tr>
		<tr>
		<td>7</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
		</tr>
		</table>
		<br/>
		C INFORMASI LAIN ATAS USAHA WAJIB PAJAK
		<table width="100%" class="noborder" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;">
		<tr>
			<td width="50%">* MENGGUNAKAN MESIN KAS REGISTER </td>
			<td width="5%"><?php echo "<img src=\"../../images/box.png\">"; ?></td>
			<td width="20%">Ya</td>
			<td width="5%"><?php echo "<img src=\"../../images/box.png\">"; ?></td>
			<td width="20%">Tidak</td>
		</tr>
		<tr>
		  <td>* MENGGUNAKAN BILL YANG DIPERFORASI </td>
		  <td><?php if($row['dat_pendukung'] == "BILL") { echo "<img src=\"../../images/box_silang.png\">"; }
		  else { echo "<img src=\"../../images/box.png\">"; }
		  ?></td>
		  <td>Ya</td>
		  <td><?php echo "<img src=\"../../images/box.png\">"; ?></td>
		  <td>Tidak</td>
		  </tr>
		<tr>
		  <td>* MENYELENGGARAKAN PEMBUKUAN / PENCATATAN </td>
		  <td><?php echo "<img src=\"../../images/box.png\">"; ?></td>
		  <td>Ya</td>
		  <td><?php echo "<img src=\"../../images/box.png\">"; ?></td>
		  <td>Tidak</td>
		  </tr>
		  <tr>
		  <td colspan="3">* JIKA TELAH MENYELENGGARAKAN PEMBUKUAN , SISTEM PEMBUKUAN YANG DITERAPKAN ADALAH: </td>
		  </tr>
		  <tr>
		  <table class="font11">
		  
		  <tr>
		  <td align="left" width=""><?php echo "<img src=\"../../images/box.png\">"; ?> Sederhana</td>
		  <td align="left" width=""><?php echo "<img src=\"../../images/box.png\">"; ?> Memadai / Cukup</td>
		  <td align="left" width=""><?php echo "<img src=\"../../images/box.png\">"; ?> Baik,sesuai prinsip akuntansi yang lazim</td>
		</tr>
		</table>
		  D. JUMLAH PAJAK DAERAH YANG DILAPORKAN<br />
		<table width="100%" border="0" class="garis font11">
		<tr><td align="center">URAIAN</td><td align="center">JUMLAH PAJAK DAERAH YANG DILAPORKAN</td><td align="center">S/D BULAN LALU(Rp)</td><td align="center">BULAN INI(Rp)</td><td align="center">S/D BULAN INI(Rp)</td></tr>
		<tr><td>PENERIMAAN DARI KAMAR</td><td>&nbsp;</td><td>&nbsp;</td><td align="center">&nbsp;<?php echo number_format($row['dasar_pengenaan_pajak']); ?></td><td>&nbsp;</td></tr>
		<tr><td>PENERIMAAN DARI RESTORAN</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr><td>PENERIMAAN DARI BAR</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr><td>PENERIMAAN DARI KARAOKE</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr><td>PENERIMAAN DARI KOLAM RENANG</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr><td>PENERIMAAN DARI JASA LAUNDRY</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr><td>PENERIMAAN DARI TELEPONE/FAX</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr><td>PENERIMAAN DARI BUSINESS CENTER/SEWA RUANGAN</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr><td>PENERIMAAN DARI FITNES CENTER</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr><td>PENERIMAAN DARI JASA TAXI</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr><td>..........................</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
			</table>
			<table class="font12"  width="100%">
		<tr><td align="center">TOTAL PENERIMAAN</td><td>&nbsp;Rp&nbsp;&nbsp;</td><td><?php echo number_format($row['dasar_pengenaan_pajak']); ?></td></tr>
		<tr><td align="center">PAJAK TERUTANG (10%) DARI TOTAL PENERIMAAN</td><td>&nbsp;Rp&nbsp;&nbsp;</td><td><?php echo number_format($row['pokok_pajak']); ?></td></tr>
		</table>
		<br />
		E. DATA PENDUKUNG	
			<table class="noborder font11">
			<tr><td><?php echo "<img src=\"../../images/box.png\">"; ?> REKAPITULASI PENERIMAAN</td>
		<td><?php echo "<img src=\"../../images/box.png\">"; ?> BILL</td></tr>
			</table>
			</td></tr>
		
		
	  
	<br>
	
	</td>	
  </tr>
  <tr>
  <td style="padding-left:20px;padding-top:5px;padding-right:20px;padding-bottom:5px;" class="font11">Demikianlah SPTPD ini diisi dengan sebenar-benarnya dan apabila dikemudian hari terdapat ketidak benaran kami bersedia dikenakan sanksi sesuai dengan ketentuan yang berlaku </td>
  </tr>
</table>
<table width="100%" border="0" class="noborder">
  <tr>
    <td width="35%">&nbsp;</td>
    <td width="30%">&nbsp;</td>
    <td width="35%">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">Diterima Oleh </td>
    <td>&nbsp;</td>
    <td align="center"><?php echo "Payakumbuh, ".date('d',strtotime($row['tgl_penyampaian']))." ".$bulan[date('n',strtotime($row['tgl_penyampaian']))]." ".date('Y',strtotime($row['tgl_penyampaian'])); ?></td>
  </tr>
  <tr>
    <td align="center">UPTD PAJAK DAERAH </td>
    <td>&nbsp;</td>
    <td align="center">WAJIB PAJAK / PENYETOR 
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
    <td align="center">----------------------</td>
    <td>&nbsp;</td>
    <td align="center"></td>
  </tr>  
</table>
<table class="font11" width="100%">
<tr><td width="60%">&nbsp;</td><td>Lembar ke 1</td><td>:</td><td>Untuk Wajib Pajak</td></tr>
<tr><td width="60%">&nbsp;</td><td>Lembar ke 2 dan 3</td><td>:</td><td>Untuk Bidang Pendapatan Daerah</td></tr>
<tr><td width="60%">&nbsp;</td><td>Lembar ke 4</td><td>:</td><td>Untuk Bendahara Penerima</td></tr>
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
			  	echo strtoupper($bulan[$row['bln_pajak']])." ".($row['thn_pajak']);
			  } else {
			  	echo strtoupper($bulan[$row['bln_pajak']])." ".($row['thn_pajak']);
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