<?php
/*if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}*/

$base_dir = $_GET['bdir'];
//$base_dir = "D:/wamp/www/htdocs/simpad_dev/";
include_once $base_dir."inc/db.inc.php";
include_once $base_dir."inc/func_terbilang.inc.php";
include_once $base_dir."inc/db.orcl.inc.php";
include_once $base_dir."inc/schema.php";
include_once $base_dir."phpqrcode/qrlib.php";

$nop = $_REQUEST['nop'];
$id_pln = $_REQUEST['id_pln'];
list($kd_propinsi,$kd_dati2,$kd_kecamatan,$kd_kelurahan,$kd_blok,$no_urut,$kd_jns_op) = explode(".",$nop);
$stid = oci_parse($conn,"select A.KD_PROPINSI||'.'||A.KD_DATI2||'.'||A.KD_KECAMATAN||'.'||A.KD_KELURAHAN||'.'||A.KD_BLOK||'-'||A.NO_URUT||'.'||A.KD_JNS_OP AS NOP,B.NM_WP,B.JALAN_WP,B.BLOK_KAV_NO_WP,B.RW_WP,B.RT_WP,B.KELURAHAN_WP,B.KOTA_WP,B.NPWP,A.JALAN_OP,A.RT_OP,A.RW_OP,A.BLOK_KAV_NO_OP AS NOMOR,A.TOTAL_LUAS_BUMI, A.TOTAL_LUAS_BNG, A.NJOP_BUMI,A.NJOP_BNG, C.NM_KECAMATAN,E.JNS_BUMI, D.NM_KELURAHAN from PBB.DAT_OBJEK_PAJAK A 
INNER JOIN PBB.DAT_SUBJEK_PAJAK B ON
A.SUBJEK_PAJAK_ID=B.SUBJEK_PAJAK_ID
INNER JOIN PBB.REF_KECAMATAN C ON
A.KD_KECAMATAN=C.KD_KECAMATAN
INNER JOIN PBB.REF_KELURAHAN D ON
A.KD_KECAMATAN=D.KD_KECAMATAN AND
A.KD_KELURAHAN=D.KD_KELURAHAN
INNER JOIN PBB.DAT_OP_BUMI E ON
A.KD_PROPINSI=E.KD_PROPINSI 
and A.KD_DATI2=E.KD_DATI2 
and A.KD_KECAMATAN=E.KD_KECAMATAN 
and A.KD_KELURAHAN=E.KD_KELURAHAN 
and A.kd_blok=E.kd_blok 
and A.no_urut=E.no_urut
and A.kd_jns_op=E.kd_jns_op
WHERE 
A.KD_KECAMATAN = '$kd_kecamatan' AND
A.KD_KELURAHAN = '$kd_kelurahan' AND
A.KD_blok = '$kd_blok' AND
A.NO_URUT='$no_urut' AND
A.KD_JNS_OP='$kd_jns_op'
ORDER BY A.KD_PROPINSI,A.KD_DATI2,A.KD_KECAMATAN,A.KD_KELURAHAN,A.KD_BLOK,A.NO_URUT,A.KD_JNS_OP");
	oci_execute($stid);
	
/////// INSERT DATA PLN /////////////////////////
$pln = oci_parse($conn,"select * FROM PBB.REF_PLN WHERE 
KD_KECAMATAN = '$kd_kecamatan' AND
KD_KELURAHAN = '$kd_kelurahan' AND
KD_BLOK = '$kd_blok' AND
NO_URUT='$no_urut' AND
KD_JNS_OP='$kd_jns_op'");
oci_execute($pln);
$hasil_pln = oci_fetch_array($pln, OCI_ASSOC);
$jum=oci_num_rows($pln);

if($jum == 0) {
$queryInsert = "INSERT INTO PBB.REF_PLN
(
    NO_PEL_PLN,
	KD_PROPINSI,
    KD_DATI2,
	KD_KECAMATAN,
	KD_KELURAHAN,
	KD_BLOK,
	NO_URUT,
	KD_JNS_OP
) 
VALUES
(
    '$id_pln',
	'13', 
    '76',
	'$kd_kecamatan',
	'$kd_kelurahan',
	'$kd_blok',
	'$no_urut',
	'$kd_jns_op' 
)";
$stidInsert = oci_parse($conn, $queryInsert);	
$u=oci_execute($stidInsert);	
	if ($u)
	{
	echo "sukses";
	}
	/*echo "SKP BELUM ADA UNTUK TAHUN ".$thn." BULAN ".$bln;
	pg_free_result($tampil);
	pg_close($dbconn);
	exit;*/
}
//$x = 1;
?>
<style type="text/css">
table.noborder td, table.noborder th, table.noborder tr { border:none; font-family:"Arial Unicode MS"; font-size:14px; }

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
	font-family:"Arial";
	font-size:10px;
}
.font11 {
	font-family:"Arial";
	font-size:11px;
}
.font12 {
	font-family:"Arial";
	font-size:12px;
}
.font14 {
	font-family:"Arial";
	font-size:14px;
}
.font16 {
	font-family:"Arial";
	font-size:16px;
}
.font18 {
	font-family:"Arial";
	font-size:18px;
}
.style1 {
	font-size: 18px;
	font-family:Arial;
	font-weight: bold;
}
.style2 {
	font-size: 30px;
	font-family:Arial;
	font-weight: bold;
	
}
.style3 {
	font-size: 13px;
	font-family:Arial;
	font-weight: bold;
}
.style4 {
	font-size: 13px;
	font-family:Arial;
}
</style>
<?php
$data = oci_fetch_array($stid, OCI_ASSOC);
$njopbumi=$data['NJOP_BUMI']/$data['TOTAL_LUAS_BUMI'];
////NAIKKAN KELAS //
$nilai_m= $njopbumi/1000;
$cari_kls_tanah = oci_parse($conn,"SELECT (A.KD_KLS_TANAH-8) AS NAIK FROM PBB.KELAS_TANAH A WHERE A.NILAI_MIN_TANAH <= '$nilai_m' AND A.NILAI_MAX_TANAH >='$nilai_m' AND THN_AWAL_KLS_TANAH ='2011' AND THN_AKHIR_KLS_TANAH ='9999'");
oci_execute($cari_kls_tanah);
$cari_n = oci_fetch_array($cari_kls_tanah, OCI_ASSOC);
$naik=sprintf("%03d", $cari_n['NAIK']);
$cari_nilai_tanah = oci_parse($conn,"SELECT A.NILAI_PER_M2_TANAH FROM PBB.KELAS_TANAH A WHERE A.KD_KLS_TANAH='$naik' AND THN_AWAL_KLS_TANAH ='2011' AND THN_AKHIR_KLS_TANAH ='9999'");
oci_execute($cari_nilai_tanah);
$cari_nil = oci_fetch_array($cari_nilai_tanah, OCI_ASSOC);
$nil=$cari_nil['NILAI_PER_M2_TANAH']*1000;
$njop_bumi=$nil*$data['TOTAL_LUAS_BUMI'];
/////
$njopbng=$data['NJOP_BNG']/$data['TOTAL_LUAS_BNG'];
$total=$njop_bumi+$data['NJOP_BNG'];
if($data['JNS_BUMI'] == '1'){ $jns_op="Tanah dan Bangunan"; } if($data['JNS_BUMI'] == '2'){$jns_op="Kavling Siap Bangun";}if($data['JNS_BUMI'] == '3'){$jns_op="Tanah Kosong";}if($data['JNS_BUMI'] == '4') {$jns_op="Fasilitas Umum";}
if($data['JNS_BUMI'] == '1'){
$sapi = oci_parse($conn,"select A.NM_JPB_JPT FROM PBB.JPB_JPT A INNER JOIN PBB.DAT_OP_BANGUNAN B ON A.KD_JPB_JPT=B.KD_JPB WHERE B.KD_KECAMATAN = '$kd_kecamatan' AND
B.KD_KELURAHAN = '$kd_kelurahan' AND
B.KD_blok = '$kd_blok' AND
B.NO_URUT='$no_urut' AND
B.KD_JNS_OP='$kd_jns_op'");
oci_execute($sapi);
$kuda = oci_fetch_array($sapi, OCI_ASSOC);
$jp=$kuda['NM_JPB_JPT'];
}
else{$jp="-";}
?>
<div style="width:100%;height:300mm;">
  <table width="100%" border="0" class="noborder">
  <tr>
  <td width="20"><img src="../../images/payakumbuh.png" height="100"></td>  <td height="50" align="center"><span class="style1">PEMERINTAH KOTA PAYAKUMBUH</span><br/>
    <span class="style2">BADAN KEUANGAN DAERAH</span><br/>
    <span class="style4">Komplek Perkantoran Balaikota Baru Payakumbuh Jl. Veteran No. 70</span><br/> <span class="style4"> Kelurahan Kapalo Koto Dibalai Kecamatan Payakumbuh Utara Kota Payakumbuh </span></br>
	 <span class="style4">Telepon / Fax  (0752) 93279 Payakumbuh - 25211</span><br/>
	 <span class="style4">Website : https://bkd.payakumbuhkota.go.id | Email : bkd@payakumbuhkota.go.id</span><br/></td></td>
    </tr>
	
</table>
<div align="center"><img width="100%" height="6" src="../../images/garis.gif"></div><br/>
<table width="100%" border="0" class="noborder">
  <tr>
    <td>
<table width="100%" border="0" class="noborder" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;" align="center">
    <tr>
      <td colspan="4" align="center"> <span class="style3">SURAT KETERANGAN NJOP </span></td>
      </tr>
    	
  <tr><td align="center">Nomor:........../SK-NJOP/BKD-PYK/<?php echo date('Y'); ?></td>    </tr>
</table>
<br/>
<table width="100%" border="0" class="noborder" align="center">
    <tr><td align="left">Nama</td><td>:</td><td>ANDRI NARWAN, S.Sos,M.M, CGCAE</td></tr>
    <tr><td align="left">Jabatan</td> <td>:</td><td>KEPALA BADAN KEUANGAN DAERAH KOTA PAYAKUMBUH</td>   </tr>
	 <tr height="40%"><td align="left"> </td>   </tr>
	 <tr><td align="justify" colspan="3">Sesuai dengan ketentuan pasal 40 ayat (1) Undang Undang Nomor 1 Tahun 2022 tentang Hubungan Keuangan Antara Pemerintah Pusat dan Pemerintah Daerah
dan Pasal 7 Ayat (1) Peraturan Daerah Kota Payakumbuh Nomor 1 Tahun 2024 Tentang Pajak Daerah dan Retribusi Daerah, dengan ini menerangkan bahwa sesuai dengan basis data Badan Keuangan Daerah Kota Payakumbuh atas objek pajak : </td>   </tr>
	  <tr height="40%"><td align="left"> </td>   </tr>
	 <tr><td align="left">Nomor Objek Pajak</td> <td>:</td><td><?php echo $data['NOP']; ?></td>   </tr>
	 <tr><td align="left">Jenis Objek Pajak</td> <td>:</td><td><?php echo $jns_op; ?></td>   </tr>
	 <tr><td align="left">Jenis Penggunaan</td> <td>:</td><td><?php echo $jp; ?></td>   </tr>
	 <tr><td align="left">Letak Objek Pajak</td> <td>:</td><td><?php echo $data['JALAN_OP']; ?> <?php echo $data['NOMOR']; ?> RT/RW <?php echo $data['RT_OP']; ?> / <?php echo $data['RW_OP']; ?>, </td>   </tr>
	  <tr><td align="left"> </td> <td></td><td><?php echo $data['NM_KELURAHAN']; ?> ,<?php echo $data['NM_KECAMATAN']; ?>, PAYAKUMBUH</td>   </tr>
	   <tr height="40%"><td align="left"> </td>   </tr>
	   <tr><td align="left" colspan="3">Diperoleh data sebagai berikut :</td>    </tr>
	   
		<tr><td align="left" colspan="3"><table width="100%">
		<tr><td>Luas Bumi</td><td>:</td><td align="right"><?php echo $data['TOTAL_LUAS_BUMI']; ?></td><td>M2</td></tr>
		<tr><td>Luas Bangunan</td><td>:</td><td align="right"><?php echo $data['TOTAL_LUAS_BNG']; ?></td><td>M2</td></tr>
		<tr><td>NJOP Bumi</td><td>:</td><td align="right"><?php echo $data['TOTAL_LUAS_BUMI']; ?></td><td>M2</td><td>X Rp</td><td align="right"><?php echo number_format($nil); ?></td><td>/M2=Rp</td><td align="right"><?php echo number_format($njop_bumi); ?></td></tr>
		<tr><td>NJOP Bumi Bersama</td><td>:</td><td align="right">0</td><td>M2</td><td>X Rp</td><td align="right">0</td><td>/M2=Rp</td><td align="right">0</td></tr>
		<tr><td>NJOP Bangunan</td><td>:</td><td align="right"><?php echo $data['TOTAL_LUAS_BNG']; ?></td><td>M2</td><td>X Rp</td><td align="right"><?php echo number_format($njopbng); ?></td><td>/M2=Rp</td><td align="right"><?php echo number_format($data['NJOP_BNG']); ?></td></tr>
		
		<tr><td>NJOP Bangunan Bersama</td><td>:</td><td align="right">0</td><td>M2</td><td>X Rp</td><td align="right">0</td><td>/M2=Rp</td><td align="right">0</td></tr>
		<tr><td></td><td></td><td align="center"></td><td></td><td></td><td align="right"></td><td></td><td align="right">_____________+</td></tr>
		<tr><td></td><td></td><td></td><td></td><td></td><td align="right"></td><td>   =Rp</td><td align="right"><?php echo number_format($total); ?></td></tr>
		</table></td>   </tr>
		  <tr height="30%"><td align="left"> </td>   </tr>
		<tr><td align="left" colspan="3">Nilai Jual Objek Pajak Keseluruhan</td>    </tr>
		<tr><td align="left" colspan="3"><?php echo " <strong><i>( ".terbilang($total, $style=3)." Rupiah )</i></strong>"; ?></td>  </tr>
		<tr height="30%"><td align="left"> </td>   </tr>
		 <tr><td align="left">Nama Wajib Pajak</td> <td>:</td><td><?php echo $data['NM_WP']; ?></td>   </tr>
	 <tr><td align="left">Alamat Wajib Pajak</td> <td>:</td><td width="75%"><?php echo $data['JALAN_WP']; ?> <?php echo $data['BLOK_KAV_NO_WP']; ?> <?php echo $data['RW_WP']; ?> <?php echo $data['RT_WP']; ?> <?php echo $data['KELURAHAN_WP']; ?> <?php echo $data['KOTA_WP']; ?></td>   </tr>
	 <tr><td align="left">NPWPD</td> <td>:</td><td><?php echo $data['NPWP']; ?></td>   </tr>
	  <tr><td align="left">ID PLN</td> <td>:</td><td><?php echo $id_pln; ?></td>   </tr>
</table>
	
  
<br/><br/>
<table align="right" border="0">
<tr>
  <td rowspan="9" align="left">
	<?php 
    $datalinkqr = $nop; // link ke alamat web untuk verifikasi document
	$qrcode = QRcode::png($datalinkqr, __DIR__ . '/qr_sk_njop/' . $nop. '.png', QR_ECLEVEL_L, 4);
    echo '<img src="qr_sk_njop/' . $nop . '" width="100" height="100">';
	?>
  <td>
  <td width="30%"> </td>
  <td>Diterbitkan di Payakumbuh</td></tr>
<tr><td></td>
  <td colspan="2"><div align="center">Pada Tanggal </div></td>
  <td> </td></tr>
<tr><td ></td>
  <td colspan="2"><div align="center">KEPALA BADAN KEUANGAN DAERAH </div></td>
  <td> </td></tr>
<tr>
  <td ></td>
  <td colspan="2"><div align="center">KOTA PAYAKUMBUH</div></td>
  <td> </td>
</tr>
<tr height="190%">
  <td ></td>
  <td></td>
  <td> </td>
  <td> </td>
</tr>
<tr height="100%"><td align="center"><?php
	$nip = $row['nip_pejabat'];
	$sql = "SELECT * FROM public.user WHERE nip='$nip'";
	$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
	$row_user = pg_fetch_array($result);
	?>	</td></tr>
<tr height="100%">
  <td align="center"> </td>
</tr>
<tr height="100%">
  <td align="center"> </td>
</tr>

	<tr><td></td>
	  <td colspan="2" align="center"> <u>ANDRI NARWAN, S.Sos, M.M, CGCAE</u></td>
	  </tr>
	<tr>
	  <td>    
	  <td></td>
	  <td colspan="2" align="center">NIP. 19730319 199308 1 001</td>
	  </tr>
	<tr><td colspan="3"></td>
	  </tr>
</table>




</div>
<?php
if($x != $n_row) {
	echo "<div style=\"page-break-before:always;\"></div>";
	$x++;
}
pg_free_result($result);
//}
pg_free_result($tampil);
pg_close($dbconn);
?>
<!--<div style="page-break-before:always;">

</div> --!>