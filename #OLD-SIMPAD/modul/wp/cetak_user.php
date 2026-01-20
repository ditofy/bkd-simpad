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

$npwpd = pg_escape_string($_GET['npwpd']);
$nama = pg_escape_string($_GET['nama']);
$pass = pg_escape_string($_GET['pass']);
    
?>
<div style="height:55mm;width:88mm;background:url(../../images/ss.jpg);">
	<div style="height:18mm;background-color:#0D9EFF;width:100%;">
		<img src="../../images/npwpdh" style="width:100%;height:100%;"> 
	</div>

<div style="height:18mm;width:100%;">
		
	
	<div style="padding-left:4mm; padding-top:4mm;width:79mm;height:27mm;">
	<div align="center" style="font-size:3.7mm;"><strong>USER & PASSWORD LOGIN E-SPTPD</strong><br /><br /></div>
		<div style="font-size:3.7mm;">USERNAME :<strong> <b><?php echo $npwpd; ?></b></strong><br /></div>
		<div style="font-size:3.7mm;">PASSWORD :<strong> <?php echo $pass; ?></strong><br /></div>
	<div style="font-size:3.7mm;"><?php echo $nama; ?><br /></div>
	</div>
    <div style="font-size:3.0mm;" align="center">
		<strong>Aplikasi E-SPTPD : https://sptpd.payakumbuhkota.go.id </strong>
	</div>

