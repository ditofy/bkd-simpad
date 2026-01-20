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
if($hal == "depan") {
//$base_dir = "D:/wamp/www/htdocs/simpad_dev/";
?>
<div style="height:55mm;width:88mm;background:url(../../images/ss.jpg);">
	<div style="height:18mm;background-color:#0D9EFF;width:100%;">
		<img src="../../images/npwpdh" style="width:100%;height:100%;"> 
	</div>
	<div style="padding-left:4mm; padding-top:4mm;width:79mm;height:27mm;">
                <!--
		<div style="font-size:3.7mm;"><strong>NPWPD : <?php //echo $row['npwpd']; ?></strong><br /><br /></div>
		<div style="font-size:3.7mm;"><strong><?php //echo $row['nama']; ?></strong><br /></div>
		<div style="font-size:2.5mm;"><strong><?php //echo $row['alamat']; ?></strong></div>
                -->
	</div>
    <div style="font-size:2.5mm;margin-left:40mm;">
                <!--
		<strong>Diterbitkan tanggal : <?php //echo $row['tgl_daf']; ?></strong>
                -->
	</div>
</div>

<?php
} 
elseif ($hal == "belakang"){
    
?>
<div style="height:55mm;width:88mm;">
	<div style="height:18mm;background-color:#000000;width:100%;">
		
	</div>
	<div style="margin-left:4mm;margin-right:4mm;width:80mm;margin-top:2mm; border-bottom: solid 1px; font-size:2.5mm" align="center">		
            <strong>PERHATIAN</strong>
	</div>
        <div style="width:80mm;margin-left:4mm;margin-right:4mm;margin-top:2mm;font-size:2mm;">
            - Kartu ini harap disimpan baik-baik dan apabila hilang, agar segera<br>&nbsp;&nbsp;melapor ke UPTB Pajak Daerah Kota Payakumbuh<br />
            - NPWPD agar dicantumkan dalam hal berhubungan dengan dokumen<br>&nbsp;&nbsp;perpajakan daerah<br />
            - Dalam hal Wajib Pajak pindah tempat tinggal dan/atau tempat<br>&nbsp;&nbsp;kedudukan usaha agar melapor ke UPTB Pajak Daerah Kota<br>&nbsp;&nbsp;Payakumbuh Gedung Balai Kota Baru Payakumbuh,
            Ex Lapangan <br>&nbsp;&nbsp;Telp/Fax (0752) 8803188
        </div>    
    <div style="width:80mm;margin-left:4mm;margin-right:4mm;margin-top:2mm;font-size:2.5mm;" align="center">
        <strong>http://keuangan.payakumbuhkota.go.id</strong><br />
        <strong>BERSAMA ANDA MEMBANGUN PAYAKUMBUH</strong>
    </div>
    
</div>  
<?php
} elseif($hal == "detail") {
    $npwpd = pg_escape_string($_GET['npwpd']);
    include_once $base_dir."inc/db.inc.php";
    list($kd_provinsi,$kd_kota,$kd_jns,$no_reg) = explode(".",$npwpd);
$query = "SELECT A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg AS npwpd,A.nama,A.alamat,A.kelurahan,A.kecamatan,A.kota,to_char(A.tgl_daftar, 'DD-MM-YYYY') AS tgl_daf FROM public.wp A
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
<div style="height:55mm;width:88mm;background:url(../../images/ss.jpg);">
	<div style="height:18mm;background-color:#0D9EFF;width:100%;">
		<img src="../../images/npwpdh" style="width:100%;height:100%;"> 
	</div>
<div style="height:55mm;width:88mm;">
	<div style="height:18mm;width:100%;">
		
	
	<div style="padding-left:4mm; padding-top:4mm;width:79mm;height:27mm;">
		<div style="font-size:3.7mm;"><strong>NPWPD : <?php echo $row['npwpd']; ?></strong><br /><br /></div>
		<div style="font-size:3.7mm;"><strong><?php echo $row['nama']; ?></strong><br /></div>
		<div style="font-size:2.5mm;"><strong><?php echo $row['alamat']; ?></strong></div>
		<div style="font-size:2.5mm;"><strong><?php echo $row['kelurahan']; ?> <?php echo $row['kecamatan']; ?> <?php echo $row['kota']; ?></strong></div>
	</div>
    <div style="font-size:2.5mm;margin-left:40mm;">
		<strong>Diterbitkan tanggal : <?php echo $row['tgl_daf']; ?></strong>
	</div>
</div>
<?php
pg_free_result($result);
pg_close($dbconn);
}
?>