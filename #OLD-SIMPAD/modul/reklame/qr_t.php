<style type="text/css">
body {
   margin: 0;
   padding: 0;
   font-family:Verdana, Arial, Helvetica, sans-serif;
}
</style>
<?php

$hal = pg_escape_string($_GET['hal']);
$base_dir = pg_escape_string($_GET['bdir']);

    $no_skp = pg_escape_string($_GET['no_skp']);
	 include_once $base_dir."inc/db.inc.php"; 
	 include_once $base_dir."phpqrcode/qrlib.php";
list($jns_surat,$thn_pajak,$bln_pajak,$kd_obj_pajak,$no_urut_skp) = explode(".", $no_skp);
$sql = "SELECT A.*,B.nama,B.alamat,C.rek_apbd FROM reklame.skp A INNER JOIN public.wp B ON
B.kd_provinsi=A.kd_provinsi AND
B.kd_kota=A.kd_kota AND
B.kd_jns=A.kd_jns AND
B.no_reg=A.no_reg_wp
LEFT JOIN public.keg_usaha C ON
C.kd_obj_pajak=A.kd_obj_pajak AND
C.kd_keg_usaha=A.kd_keg_usaha
WHERE A.jns_surat='$jns_surat' AND A.thn_pajak='$thn_pajak' AND A.bln_pajak='$bln_pajak' AND A.kd_obj_pajak='$kd_obj_pajak' AND A.no_urut_surat='$no_urut_skp' AND A.status_pembayaran < '2'";

    $result = pg_query($sql) or die('Query failed: ' . pg_last_error());
    $row = pg_fetch_array($result);
	$qrcode=$row['qrcode'];
	$tempdir = $base_dir."temp/"; //Nama folder tempat menyimpan file qrcode
	array_map('unlink', glob($tempdir."*.png"));
	$namaFile=$qrcode.".png";
    $level=QR_ECLEVEL_H;
    $UkuranPixel=2;
    $UkuranFrame=2;
	$link="http://180.250.38.34:10/qr/?qr="."$qrcode";
			// QRcode::png($qrcode, $tempdir.$namaFile, $level, $UkuranPixel, $UkuranFrame); '
	$sapi="$temp_dir"."$namaFile";
?>

<div style="height:50mm;width:90mm;">
<br/>
	<div align="center" style="font-size:7px;"><strong>
	<div style="margin-left:-40px;"><table border="0"><tr><td align="right" rowspan="2" width="18%"><img src="../../images/sapi.png" style="width:70%;height:25%;"></td><td align="left" style="font-size:14px;"><b>PEMERINTAH KOTA PAYAKUMBUH</b></td></tr>
					 <tr><td style="font-size:14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>PAJAK REKLAME</b></td></tr>
	</table></div>
	</div>

		<div align="center" style="margin-left:-40px;"><strong><?php QRcode::png("$link","$sapi","L",2,2);echo "<img src='$sapi'/>";?><?php //QRcode::png("$link","$sapi","L",2,2); echo '<img src="'."$temp_dir"."$namaFile".'"/>'; ?></strong><br/></div>
			<div align="center" style="font-size:10px;margin-left:-20px; "><strong>MASA PAJAK &nbsp;:&nbsp;<?php echo date('d',strtotime($row['tmt_awal']))." ".$bulan[date('n',strtotime($row['tmt_awal']))]." ".date('Y',strtotime($row['tmt_awal']))." s/d ".date('d',strtotime($row['tmt_akhir']))." ".$bulan[date('n',strtotime($row['tmt_akhir']))]." ".date('Y',strtotime($row['tmt_akhir'])); ?></strong><br /></div>
		<div align="center" style="font-size:8px;margin-left:-20px;"><strong>OBJEK PAJAK &nbsp;:&nbsp;<?php echo $row['nm_reklame']; ?></strong><br /></div>
		<div align="center" style="font-size:8px;margin-left:-20px;"><strong><?php echo $row['alamat_reklame']; ?></strong><br /></div>
	 <!--	<div align="center" style="font-size:8px;"><strong>WAJIB PAJAK &nbsp;:&nbsp;<?php// echo $row['nama']; ?></strong><br /></div>
	
   <div style="font-size:2.5mm;margin-left:40mm;">
		<strong>Diterbitkan tanggal : <?php //echo $row['tgl_daf']; ?></strong>
	</div> -->
</div>
<?php
pg_free_result($result);
pg_close($dbconn);
//}
?>