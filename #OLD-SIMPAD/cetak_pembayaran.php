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

$no_surat = pg_escape_string($_GET['no_sptpd']);
list($kd_jns_surat,$thn_pajak,$bln_pajak,$kd_obj_pajak,$no_urut_surat) = explode(".", $no_surat);
$no_npwpd = pg_escape_string($_GET['npwpd']);
list($kd_provinsi,$kd_kota,$kd_jns,$no_reg) = explode(".", $no_npwpd);
$schema = $list_schema[$kd_obj_pajak];
$table = $jns_surat[$kd_jns_surat];

switch ($table) {
	case "SKP":
		if($schema == 'reklame') {
			$sql = "SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg_wp AS npwpd,A.nm_reklame AS nm_objek,A.alamat_reklame as alamat_obj, A.pokok_pajak,A.status_pembayaran,0 AS denda,B.nama,C.nm_obj_pajak,B.alamat as alamat_wp,D.tgl_bayar  FROM $schema.$table A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp INNER JOIN public.obj_pajak C ON c.kd_obj_pajak=A.kd_obj_pajak LEFT JOIN $schema.pembayaran D ON A.jns_surat=D.jns_surat AND A.thn_pajak=D.thn_pajak AND A.bln_pajak=D.bln_pajak AND A.kd_obj_pajak=D.kd_obj_pajak AND A.no_urut_surat=D.no_urut_surat WHERE A.jns_surat='$kd_jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_surat'";
		} else {
			$sql = "SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg_wp AS npwpd,A.nm_usaha AS nm_objek,A.pokok_pajak,A.status_pembayaran,0 AS denda,B.nama,C.nm_obj_pajak  FROM $schema.$table A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp INNER JOIN public.obj_pajak C ON c.kd_obj_pajak=A.kd_obj_pajak WHERE A.jns_surat='$kd_jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_surat'";
		}
		break;
	case "SPTPD":
		$sql = "SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg_wp AS npwpd,A.nm_usaha AS nm_objek,A.pokok_pajak,A.status_pembayaran,0 AS denda,B.nama,C.nm_obj_pajak,A.alamat_usaha as alamat_obj,B.alamat as alamat_wp,D.tgl_bayar,A.bln_pajak,A.thn_pajak FROM $schema.$table A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp INNER JOIN public.obj_pajak C ON c.kd_obj_pajak=A.kd_obj_pajak LEFT JOIN $schema.pembayaran D ON A.jns_surat=D.jns_surat AND A.thn_pajak=D.thn_pajak AND A.bln_pajak=D.bln_pajak AND A.kd_obj_pajak=D.kd_obj_pajak AND A.no_urut_surat=D.no_urut_surat WHERE A.jns_surat='$kd_jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_surat' AND A.kd_provinsi='$kd_provinsi' AND A.kd_kota='$kd_kota' AND A.kd_jns='$kd_jns' AND A.no_reg_wp='$no_reg'";
		break;
	/*	case "SSPD":
		$sql = "SELECT A.kd_propinsi||'.'||A.kd_dati2||'.'||A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_blok||'.'||A.no_urut||'.'||A.kd_jns_op AS nop,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg AS npwpd,A.nama_sppt AS nm_objek,C.pokok_pajak_real as pokok_pajak,C.status_pembayaran,0 AS denda,B.nama,D.nm_obj_pajak,A.pengurangan  FROM bphtb.pelayanan A INNER JOIN public.wp B ON B.nik=A.nik INNER JOIN bphtb.sspd C ON C.tahun_p=A.tahun AND C.no_urut_p=A.no_urut_p  INNER JOIN public.obj_pajak D ON D.kd_obj_pajak=C.kd_obj_pajak WHERE C.jns_surat='$kd_jns_surat' AND C.thn_pajak='$thn_pajak' AND C.bln_pajak='$bln_pajak' AND C.kd_obj_pajak='$kd_obj_pajak' AND C.no_urut_surat='$no_urut_surat'";
		break;
		case "SKPDKB":
		$sql="select A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,C.kd_provinsi||'.'||C.kd_kota||'.'||C.kd_jns||'.'||C.no_reg as npwpd,C.nama,b.nm_usaha as nm_objek,C.alamat as alamat_wp,b.alamat_usaha as alamat_objek,A.pokok_pajak,B.kd_kecamatan||'.'||B.kd_kelurahan||'.'||B.kd_keg_usaha||'.'||B.no_reg as nop,A.thn_pajak,a.bln_pajak,D.nm_obj_pajak,D.perda,A.tgl_jatuh_tempo,A.dasar_pajak,A.pokok_pajak,A.pajak_disetor,A.pokok_pajak_baru,A.tgl_surat,A.nip_p,A.ket,A.status_pembayaran,0 as denda from $schema.$table A LEFT JOIN 
$schema.dat_obj_pajak B on A.kd_kecamatan=B.kd_kecamatan and A.kd_kelurahan=B.kd_kelurahan and A.kd_keg_usaha=b.kd_keg_usaha and A.no_reg=B.no_reg
LEFT JOIN public.wp C on C.kd_provinsi=b.kd_provinsi and C.kd_kota=B.kd_kota and C.kd_jns=B.kd_jns and C.no_reg=B.no_reg_wp 
LEFT JOIN public.obj_pajak D on D.kd_obj_pajak=A.kd_obj_pajak
WHERE A.jns_surat='05' and A.thn_pajak='$thn_pajak' and A.bln_pajak='$bln_pajak' and A.kd_obj_pajak='$kd_obj_pajak' and A.no_urut_surat='$no_urut_surat'";
		break;*/
}
$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
$n_row = pg_num_rows($tampil);
if($n_row <= 0) {
	echo "NO. SSPD BPHTB TIDAK DITEMUKAN, SILAHKAN PERIKSA KEMBALI NO. SSPD";
	pg_free_result($tampil);
	pg_close($dbconn);
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
	font-size:13px;
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
 $luas_njop_bumi=$row['luas_bumi_trk'];
 $luas_njop_bng=$row['luas_bng_trk'];
 $njop_bumi=$row['njop_bumi'];
 $njop_bng=$row['njop_bng'];
 $hasil_bumi=$luas_njop_bumi*$njop_bumi;
 $hasil_bng=$luas_njop_bng*$njop_bng;
 $t_njop=$hasil_bumi+$hasil_bng;
 $persen_tarif = $row['t_pengurangan'] * 100;
 $al=$row['alamat'];
 $no_pel=$row['tahun'].".".$row['no_urut_p'];
 $qrcode=$row['qrcode'];
 $bln= strtoupper(getBulan($row['bln_pajak']));
 $thn= $row['thn_pajak'];
 
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
<td rowspan="2" align="center">
<?php QRcode::png("$qrcode","image.png","L",2,2);echo "<img src='image.png'/>";?>
	</td>
  </tr>
  <tr>
    <td align="center">&nbsp;<span class="font14">Jln. Veteran Komplek Perkantoran Balaikota Payakumbuh.<br/> </span>
	
   </td>
  </tr>
  <tr height="50">
    <td colspan="4" align="center" ><span class="font16">SURAT SETORAN PAJAK DAERAH </span></td>
    </tr>

<tr>
<td colspan="4">
<table class="nomyTbl" width="100%">
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">1. No Bayar</td><td>&nbsp;:</td><td colspan="2"><?php echo $no_surat; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%"></td><td>&nbsp;</td><td colspan="2">&nbsp;</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">2. Jenis Pajak</td><td>&nbsp;:</td><td colspan="2"><?php echo $row['nm_obj_pajak'];?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%"></td><td>&nbsp;</td><td colspan="2">&nbsp;</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">3. Masa Pajak</td><td>&nbsp;:</td><td colspan="2"><?php echo $bln;?>&nbsp;<?php echo $thn;?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%"></td><td>&nbsp;</td><td colspan="2">&nbsp;</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">4. Nama Objek</td><td>&nbsp;:</td><td colspan="2"><?php echo $row['nm_objek'];?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%"></td><td>&nbsp;</td><td colspan="2">&nbsp;</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">5. Alamat Objek Pajak</td><td>&nbsp;:</td><td align="left" colspan="2"><?php echo $row['alamat_obj']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%"></td><td>&nbsp;</td><td colspan="2">&nbsp;</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">6. Nama Wajib Pajak</td><td>&nbsp;:</td><td colspan="2"><?php echo $row['nama'];?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%"></td><td>&nbsp;</td><td colspan="2">&nbsp;</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">7. NPWPD</td><td>&nbsp;:</td><td align="left" colspan="2"><?php echo $row['npwpd']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%"></td><td>&nbsp;</td><td colspan="2">&nbsp;</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">8. Alamat Wajib Pajak</td><td>&nbsp;:</td><td align="left" colspan="2"><?php echo $row['alamat_wp']; ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%"></td><td>&nbsp;</td><td colspan="2">&nbsp;</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">9. Pokok Pajak</td><td>&nbsp;:</td><td align="left" colspan="2">Rp.&nbsp;<?php echo number_format($row['pokok_pajak']);?>&nbsp;,-</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%"></td><td>&nbsp;</td><td colspan="2">&nbsp;</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">10. Dengan Huruf</td><td>&nbsp;:</td><td align="left" colspan="2"><?php echo "<strong><i>".terbilang($row['pokok_pajak'], $style=3)." Rupiah </i></strong>";?>&nbsp;,-</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%"></td><td>&nbsp;</td><td colspan="2">&nbsp;</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">11. Tanggal Bayar</td><td>&nbsp;:</td><td align="left" colspan="2"><?php 
if($row['tgl_bayar'] != ""){
echo date('d',strtotime($row['tgl_bayar']))." ".$bulan[date('n',strtotime($row['tgl_bayar']))]." ".date('Y',strtotime($row['tgl_bayar']));
}
else {
echo "---"; 
} ?></td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%"></td><td>&nbsp;</td><td colspan="2">&nbsp;</td></tr>
<tr><td width="1%"></td><td>&nbsp;</td><td width="20%">12. Status Pembayaran</td><td>&nbsp;:</td><td align="left" colspan="2">
<?php if($row['tgl_bayar'] != 0){

echo"<b>LUNAS</b>"; }
else{echo "<b>BELUM BAYAR</b>";}

?>&nbsp;</td></tr>
</table>
</td>
</tr>

  



</table>
</td>
</tr>


 </table>
<table class="font14">
<tr><td>&nbsp;</td></tr>
<tr><td>Dicetak Tanggal:</td>
</tr>

<tr><td><?php

//$nama = pg_escape_string($_GET['nama']);
 //echo $nama; ?></td></tr>
 <tr><td><?php
//$nip = pg_escape_string($_GET['nip']);

// echo "Nip. &nbsp;$nip "; ?></td></tr>
 <tr><td><?php
$tg = date("Y-m-d h:i:s");

 echo "$tg"; ?></td></tr></table>
<?php

if($x != $n_row) {
	echo "<div style=\"page-break-before:always;\"></div>";
	$x++;
}

}
pg_free_result($tampil);
pg_close($dbconn);


?>