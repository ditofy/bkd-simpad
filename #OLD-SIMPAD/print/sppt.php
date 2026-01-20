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
$thn = $_REQUEST['thn'];
//echo $nop; echo $thn; echo $base_dir;

list($kd_propinsi,$kd_dati2,$kd_kecamatan,$kd_kelurahan,$kd_blok,$no_urut,$kd_jns_op) = explode(".",$nop);
$stid = oci_parse($conn,"select A.KD_PROPINSI||'.'||A.KD_DATI2||'.'||A.KD_KECAMATAN||'.'||A.KD_KELURAHAN||'.'||A.KD_BLOK||'-'||A.NO_URUT||'.'||A.KD_JNS_OP AS NOP,A.NM_WP_SPPT,B.JALAN_WP_SPPT,B.BLOK_KAV_NO_WP_SPPT,B.RW_WP_SPPT,B.RT_WP_SPPT,B.KELURAHAN_WP_SPPT,B.KOTA_WP_SPPT,B.JALAN_OP,B.RT_OP,A.RW_OP,A.BLOK_KAV_NO_OP AS NOMOR,A.TOTAL_LUAS_BUMI, A.TOTAL_LUAS_BNG, A.NJOP_BUMI,A.NJOP_BNG, C.NM_KECAMATAN,E.JNS_BUMI, D.NM_KELURAHAN from PBB.SPPT A 
INNER JOIN PBB.DAT_SUBJEK_PAJAK B ON
A.SUBJEK_PAJAK_ID=B.SUBJEK_PAJAK_ID
INNER JOIN PBB.REF_KECAMATAN C ON
A.KD_KECAMATAN=C.KD_KECAMATAN
INNER JOIN PBB.REF_KELURAHAN D ON
A.KD_KECAMATAN=D.KD_KECAMATAN AND
A.KD_KELURAHAN=D.KD_KELURAHAN
INNER JOIN PBB.DAT_OBJEK_PAJAK E ON
A.KD_PROPINSI=E.KD_PROPINSI 
and A.KD_DATI2=E.KD_DATI2 
and A.KD_KECAMATAN=E.KD_KECAMATAN 
and A.KD_KELURAHAN=E.KD_KELURAHAN 
and A.kd_blok=E.kd_blok 
and A.no_urut=E.no_urut
and A.kd_jns_op=E.kd_jns_op
WHERE 
A.KD_KECAMATAN = '010' AND
A.KD_KELURAHAN = '010' AND
A.KD_blok = '003' AND
A.NO_URUT='0355' AND
A.KD_JNS_OP='0'
ORDER BY A.KD_PROPINSI,A.KD_DATI2,A.KD_KECAMATAN,A.KD_KELURAHAN,A.KD_BLOK,A.NO_URUT,A.KD_JNS_OP");
$r = oci_execute($stid);

//if($n_row <= 0) {
	//echo "SKP BELUM ADA UNTUK TAHUN ".$thn." BULAN ".$bln;
	//pg_free_result($tampil);
	//pg_close($dbconn);
	//exit;
//}
$x = 1;
?>
<style type="text/css">
table.noborder td, table.noborder th, table.noborder tr { border:none; font-family:"Verdana"; font-size:14px; }

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
.style8 {
	font-size: 7px;
	font-weight: bold;
}
.style9 {
	font-size: medium;
	font-weight: bold;
}
</style>
<?php
$row= oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
$njopbumi=$data['NJOP_BUMI']/$data['TOTAL_LUAS_BUMI'];
$njopbng=$data['NJOP_BNG']/$data['TOTAL_LUAS_BNG'];
$total=$data['NJOP_BUMI']+$data['NJOP_BNG'];
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
<div style="width:100%;height:190mm;width:205mm;">
 <table class="font11" width="100%" border="1">
 <tr><td width="50%">PEMERINTAH KOTA PAYAKUMBUH<br/>
 BADAN KEUANGAN DAERAH</td><td><div align="center"><span class="style8">SPPT PBB<br/>
   BUKAN MERUPAKAN BUKTI KEPEMILIKAN HAK</span></div></td>
 </tr>
 <tr><td colspan="2"><div align="center" class="style9">SURAT PEMBERITAHUAN PAJAK TERHUTANG<br/>
   PAJAK BUMI DAN BANGUNAN TAHUN <?php echo $thn; ?></div></td>
 </tr>
 <tr><td width="50%">NOP : <?php echo $r; ?>
</td><td></td>
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