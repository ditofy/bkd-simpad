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
//include_once $base_dir."phpqrcode/qrlib.php";

$no_sspd = pg_escape_string($_GET['no_sspd']);
list($jns_surat,$thn_pajak,$bln_pajak,$kd_obj_pajak,$no_urut_sspd) = explode(".", $no_sspd);
$schema = $list_schema[$kd_obj_pajak];
$sql = "SELECT A.*,B.nama,B.alamat,C.rek_apbd,C.outlet_id,D.nm_usaha,D.alamat_usaha FROM $schema.sptpd A INNER JOIN public.wp B ON
B.kd_provinsi=A.kd_provinsi AND
B.kd_kota=A.kd_kota AND
B.kd_jns=A.kd_jns AND
B.no_reg=A.no_reg_wp
LEFT JOIN $schema.dat_obj_pajak D
ON A.kd_kecamatan=D.kd_kecamatan AND
A.kd_kelurahan=D.kd_kelurahan AND
A.kd_obj_pajak=D.kd_obj_pajak AND
A.kd_keg_usaha=D.kd_keg_usaha AND
A.no_reg=D.no_reg
LEFT JOIN public.keg_usaha C ON
C.kd_obj_pajak=A.kd_obj_pajak AND
C.kd_keg_usaha=A.kd_keg_usaha
WHERE A.jns_surat='$jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_sspd'";
$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
$n_row = pg_num_rows($tampil);
if($n_row <= 0) {
	echo "NO. SSPD TIDAK DITEMUKAN, SILAHKAN PERIKSA KEMBALI NO. SSPD";
	pg_close($dbconn);	pg_free_result($tampil);

	exit;
}
$x = 1;

?>
<style type="text/css">


.myTbl {
  border: 2px solid #000;
  border-collapse: collapse;
  border-style:inset;
  
}

.myTbl th, .myTbl td {
  white-space: nowrap;
  border-right: 1px solid #000;
  border-bottom: 1px solid #000;
  padding: 2px;
}
.nomyTbl {
border:1px solid #FFF;
	font-family:"Verdana";
	font-size:12px;
}
.nomyTbl th, .nomyTbl td {
border:1px solid #FFF;

}
.font8 {
	font-family:"Verdana";
	font-size:8px;
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
.font20 {
	font-family:"Verdana";
	font-size:20px;
}
.style2 {font-size: 7px}
.style3 {color: #FFFFFF}
</style>
<?php
 while ($row = pg_fetch_array($tampil)) {
 $qrcode=$row['qrcode'];
 $no_tagihan=$row['jns_surat'].".".$row['thn_pajak'].".".$row['bln_pajak'].".".$row['kd_obj_pajak'].".".$row['no_urut_surat'];
 $jumlah=$row['pokok_pajak'];
 
 
?>
<div style="width:215mm;height:300mm;">
  <table width="100%" border="0" class="myTbl" align="center">
  <tr>
    <td rowspan="2" align="center">
<img src="../../images/payakumbuh.png" width="60">
<p><pre class="font10" style="line-height: 1.6em;">KOTA PAYAKUMBUH</pre></p></td>
    <td align="center">
<pre>

<strong class="font18">PEMERINTAH KOTA PAYAKUMBUH</strong>
<strong class="font20">BADAN KEUANGAN DAERAH</strong></pre>	
  </td>

  </tr>
  <tr>
    <td align="center">&nbsp;<span class="font14">Jln. Veteran No 70. Komplek Perkantoran Balaikota Payakumbuh. Telepon dan Faksimile: (0752) - 923709<br/> 
	Website : https://bkd.payakumbuhkota.go.id || E-mail : bkd@payakumbuhkota.go.id</span>
	
   </td>
  </tr>
  <tr height="50">
    <td colspan="4" align="center" ><span class="font16">SSPD PEMBAYARAN PAJAK BARANG JASA TERTENTU (PBJT) </span></td>
    </tr>

<tr>
<td colspan="4">
<table class="nomyTbl" width="100%">
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">1. No Bayar</td><td>&nbsp;:</td><td colspan="2"><?php echo $row['jns_surat'].".".$row['thn_pajak'].".".$row['bln_pajak'].".".$row['kd_obj_pajak'].".".$row['no_urut_surat']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">2. Jenis Jasa</td><td>&nbsp;:</td><td colspan="2"><?php echo strtoupper($schema);?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">3. Nama Wajib Pajak</td><td>&nbsp;:</td><td colspan="2"><?php echo $row['nama'];?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">4. NPWPD</td><td>&nbsp;:</td><td align="left" colspan="2"><?php echo $row['kd_provinsi'].".".$row['kd_kota'].".".$row['kd_jns'].".".$row['no_reg']; ?></td></tr>

<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">5. Menyetor Berdasarkan</td><td>&nbsp;:</td><td ><?php if($row['jns_surat'] == "02") { echo "<td><img src=\"../../images/box_centang.png\">&nbsp;SSPD</td>"; }?></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><td><img src="../../images/box.png">&nbsp;SPTPD</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><td><img src="../../images/box.png">&nbsp;SKPDKB</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><td><img src="../../images/box.png">&nbsp;SKPDKBT</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><td><img src="../../images/box.png">&nbsp;STPD</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><td><img src="../../images/box.png">&nbsp;SK PEMBETULAN</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><td><img src="../../images/box.png">&nbsp;Lain-lain</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">&nbsp;</td><td>&nbsp;</td><td colspan="1"><td></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">6. Uang Sebesar</td><td>&nbsp;:</td><td align="left" colspan="2">Rp.&nbsp;<?php echo number_format($row['pokok_pajak']);?>&nbsp;,-</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">7. Dengan Huruf</td><td>&nbsp;:</td><td align="left" colspan="2"><?php echo "<strong><i>".terbilang($row['pokok_pajak'], $style=3)." Rupiah </i></strong>";?>&nbsp;,-</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">8. Guna Pembayaran</td><td>&nbsp;:</td><td align="left" colspan="2">Objek PBJT : <b><?php echo $row['nm_usaha']; ?></b> <b><?php echo $row['alamat_usaha']; ?></b></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">9. Masa Pajak</td><td>&nbsp;:</td><td align="left" colspan="2"><b><?php echo $bulan_s[$bln_pajak]."  ".$row['thn_pajak']; ?></b></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">10. Jatuh Tempo Pembayaran</td><td>&nbsp;:</td><td align="left" colspan="2"><b><?php 
 $jumlah_hari = 10;
 function lastOfMonth($year, $month) {
 return date("Y-m-d", strtotime('-1 second', strtotime('+1 month',strtotime($month . '/01/' . $year. ' 00:00:00'))));
 }
$tn=lastOfMonth("$thn_pajak", "$bln_pajak");
 $tanggal = date("$tn",time());
// $tanggal = '2024-01-31';
    for($i=$jumlah_hari;$i>=1;$i--)
    {
        $tanggal = date("Y-m-d",strtotime("+1 days",strtotime($tanggal)));
        $hari = date('l',strtotime($tanggal));
        if($hari =='Saturday' OR $hari =='Sunday')
        {
            $i = $i + 1;
            continue;
        }
    }
    $tanggals = $tanggal;
	list($tanggal_tempo,$bulan_tempo,$thn_tempo) = explode("-", $tanggals);
    echo $thn_tempo." ".$bulan_s[$bulan_tempo]." ".$tanggal_tempo;
   ?></b></td></tr>
   <tr><td width="1%"></td><td>&nbsp;</td><td colspan="4"><b><i>Apabila Lewat Jatuh Tempo Pembayaran SSPD Belum Dibayar Maka Dikenakan Sanksi Administrasi Berupa Denda Sebesar 1% Per Bulannya</i> </b></td></tr>
</table>
</td>
</tr>

  



<table border="0">
</td>
</tr>
<tr>
<td width="37%">
  <?php
 $cek= $row['status_pembayaran'];
  if ($cek == 0){
 
		       $no_tagihan_baru= preg_replace("/[^0-9]/", "", $no_sspd);
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
				if($data->rc == "00")
				{
				if($id == "QR03")
				{
				echo "<div align='center'><img src='../../images/iconQris.png' width='52%'></div>";
				qrImg($data->data->qrData, $billing_number);
				
				echo "<div align='center'><img src='qrimage/$billing_number.png' width='52%'></div><br/>";
				
				echo "<b>SILAHKAN SCAN QR <BR/>UNTUK PEMBAYARAN ".strtoupper($row_jns_pjk['nm_obj_pajak'])." !!</b><br/>";
				echo "<i>(Nagari, BNI, BRI, BSI, BCA, Mandiri, GoPay, ShopeePay, Dana, OVO, dll)</i>";
               
				}
				}
				
				
				///
			   ?>
</td>
<td valign="top">
<b>Untuk Cetak Bukti Pembayaran dapat melalui :</b><br/>
1. Website https://pajakqris.payakumbuhkota.go.id <br/>
&nbsp;&nbsp;&nbsp;&nbsp;Atau melalui aplikasi <b>QRIS Pajak Payakumbuh</b> diPlaystore<br/>
2. Pada Kolom QRIS Pajak Daerah Masukkan Nomor Bayar. <br/>
3. Pilih Cetak Bukti -> Download Bukti<br/>

<?php
//$qrcode = "https://pajakqris.payakumbuhkota.go.id/bukti/DAERAH/".$billing_number;
//QRcode::png("$qrcode","image.png","L",2,2);echo "<img src='image.png'/>";
}

?>
</td>
</tr>

 </table>

<?php

if($x != $n_row) {
	echo "<div style=\"page-break-before:always;\"></div>";
	$x++;
}

}
pg_free_result($tampil);
pg_close($dbconn);


?>