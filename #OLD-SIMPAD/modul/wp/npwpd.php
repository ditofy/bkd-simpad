<?php

$npwpd = pg_escape_string($_GET['npwpd']);
$hal = pg_escape_string($_GET['hal']);
$base_dir = pg_escape_string($_GET['bdir']);
//$base_dir = "D:/wamp/www/htdocs/simpad_dev/";
include_once $base_dir."inc/db.inc.php";
if($hal == "depan"){
list($kd_provinsi,$kd_kota,$kd_jns,$no_reg) = explode(".",$npwpd);
$query = "SELECT A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg AS npwpd,A.nama,A.alamat,to_char(A.tgl_daftar, 'DD-MM-YYYY') AS tgl_daf FROM public.wp A
WHERE
A.kd_provinsi='$kd_provinsi' AND
A.kd_kota='$kd_kota' AND
A.kd_jns='$kd_jns' AND
A.no_reg='$no_reg' AND
A.status <> '0'
";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$row = pg_fetch_array($result);
?>
<style type="text/css">
<!--
.style3 {
	font-size: 12px;
	font-weight: bold;
}
.style4 {
	font-size: 14px;
	font-weight: bold;
}
.style5 {
	font-size: 40px;
	font-weight: bold;
}

-->
</style>

<div style="height:56mm;width:87mm;background:url(../../images/security.jpg);">
<div style="height:17mm;background-color:#0D9EFF;width:100%;">

<table border="0" width="100%">
<tr>
<td style="width:15mm;height:12mm;"><div align="center"><img src="../../images/bg.png" width="100%" height="100%"></div></td>
<td width="71mm"><div style="padding-top:1mm;" align="center">
  <p><span class="style4">PEMERINTAH KOTA PAYAKUMBUH</span><br><br>
    <span class="style3">BADAN KEUANGAN DAERAH</span></p>
  </div></td>
</tr>
	
</table>
</div>
<div style="padding-left:5mm; padding-top:10mm;width:200mm;height:70mm;">
<div class="style5">NPWPD : <?php echo $row['npwpd']; ?><br /><br /></div>
<div class="style5"><?php echo $row['nama']; ?><br /></div>
<div style="font-size:6.5mm;"><?php echo $row['alamat']; ?></div>
</div>
<div style="font-size:6.5mm;margin-left:80mm;">
<strong>Diterbitkan tanggal : <?php echo $row['tgl_daf']; ?></strong>
</div>
</div>
<?php
} else {
?>
<style type="text/css">
<!--
.style3 {
	font-size: 22px;
	font-weight: bold;
}
.style4 {
	font-size: 33px;
	font-weight: bold;
}
-->
</style>

<div style="height:139mm;width:215mm;">
<div style="height:40mm;background-color:#000000;">

<table border="0">
<tr>
<td><div style="width:35mm;" align="center"></div></td>
<td><div style="width:175mm; padding-top:5mm;" align="center">
  <p><span class="style4"></span><br><br>
    <span class="style3"></span></p>
  </div></td>
</tr>
	
</table>
</div>

<div style="width:205mm;border-bottom:solid;margin-left:5mm;margin-right:5mm;margin-top:10mm;font-size:5.6mm;" align="center">
<strong>PERHATIAN</strong>
</div>
<div style="width:205mm;margin-left:5mm;margin-right:5mm;margin-top:5mm;font-size:5.6mm;">
- Kartu ini harap disimpan baik-baik dan apabila hilang, agar segera melapor ke<br />
&nbsp;&nbsp;UPTD Pajak Daerah Kota Payakumbuh<br />
- NPWPD agar dicantumkan dalam hal berhubungan dengan dokumen perpajakan<br />
&nbsp;&nbsp;daerah<br />
- Dalam hal Wajib Pajak pindah tempat tinggal dan/atau tempat kedudukan usaha<br />
&nbsp;&nbsp;agar melapor ke UPTD Pajak Daerah Kota Payakumbuh Jl. Soekarno Hatta<br />
&nbsp;&nbsp;Depan SPBU Koto Nan IV Telp/Fax (0752) 8803188
</div>
<div style="width:205mm;margin-left:5mm;margin-right:5mm;margin-top:15mm;font-size:5.6mm;" align="center">
<strong>www.dppka.payakumbuhkota.go.id</strong><br />
<strong>BERSAMA ANDA MEMBANGUN PAYAKUMBUH</strong>
</div>
</div>
<?php
}
?>