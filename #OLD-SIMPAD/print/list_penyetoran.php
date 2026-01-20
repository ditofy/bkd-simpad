<?php

$bdir = $_GET['bdir'];
include_once $bdir."inc/db.inc.php";
include_once $bdir."inc/schema.php";
include_once $bdir."inc/fungsi_indotgl.php";
$data_ser = $_GET['data'];

$data = unserialize(urldecode($data_ser));
$sub_total = 0;
$grand_total = 0;
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = strtoupper(pg_escape_string($$value["name"]));
}
//$schema = $list_schema[$arr_data['kd-obj-pajak']];
$kd_obj_pajak = $arr_data['kd-obj-pajak'];
$nop = $arr_data['nop'];
$npwpd = $arr_data['npwpd'];

$schema = 'bill';
//$nop = $arr_data['nop'];
$bln_pajak = $arr_data['bln-pjk'];
$e=getBulan($bln_pajak);
$bul=strtoupper($e);
$thn_pajak = $arr_data['tahun-pajak'];
$nm_wp = strtoupper($arr_data['nm-wp']);
$seri_bill = $arr_data['seri-bill'];
list($kd_kecamatan,$kd_kelurahan,$kd_obj_pajak,$kd_keg_usaha,$no_reg) = explode(".",$nop);
 $sapi=pg_query("SELECT DISTINCT(A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg) AS NOP,B.nm_usaha,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp AS npwpd,C.nama,B.alamat_usaha,C.alamat FROM bill.dat_bill A 
     INNER JOIN restoran.dat_obj_pajak B ON B.kd_kecamatan=A.kd_kecamatan AND B.kd_kelurahan=A.kd_kelurahan AND B.kd_obj_pajak=A.kd_obj_pajak AND B.kd_keg_usaha=A.kd_keg_usaha AND B.no_reg=A.no_reg 
        INNER JOIN public.wp C ON C.kd_provinsi=B.kd_provinsi AND C.kd_kota=B.kd_kota AND C.kd_jns=B.kd_jns AND C.no_reg=B.no_reg_wp 
        WHERE B.kd_kecamatan='$kd_kecamatan' and B.kd_kelurahan='$kd_kelurahan' and B.kd_obj_pajak='$kd_obj_pajak' and B.kd_keg_usaha='$kd_keg_usaha' and b.no_reg='$no_reg'");
		$row_wp = pg_fetch_array($sapi);
//$numrows=pg_fetch_array($num_results);
//$grand_total = $numrows['g_total'];
pg_free_result($row_wp);

?>
<table id="t-list-restoran" class="table table-striped responsive-utilities jambo_table" style="font-size:11px;">
    <thead>
	<tr><th>LAPORAN BILL BULAN <?php echo $bul;  ?>&nbsp;<?php echo $thn_pajak;  ?></th></tr></thead></table>
      <table style="font-size:9px;"><thead>  <tr class="headings">                                         
            <th align="left">NPWPD </th><th width="5%"></th><th>:</th><th width="5%"></th><th align="left"><?php echo $row_wp['npwpd']; ?></th></tr>
			<tr><th align="left">NOP</th><th width="5%"></th><th>:</th><th width="5%"></th><th align="left"><?php echo $row_wp['nop']; ?></th></tr>
			<tr><th align="left">NAMA USAHA</th><th width="5%"></th><th>:</th><th width="5%"></th><th align="left"><?php echo $row_wp['nm_usaha']; ?></th></tr>
			<tr><th align="left">ALAMAT USAHA</th><th width="5%"></th><th>:</th><th width="5%"></th><th align="left"><?php echo $row_wp['alamat']; ?></th></tr>
			<tr><th align="left">NAMA PEMILIK</th><th width="5%"></th><th>:</th><th width="5%"></th><th align="left"><?php echo $row_wp['nama']; ?></th></tr>
			</thead></table><br/><br/>
<?php
$query = pg_query("select a.seri as seri_a,a.no_bill as no_bill_a,a.dasar_pengenaan_pajak as dasar_pengenaan_pajak_a,a.pokok_pajak as pokok_pajak_a,b.seri as seri_b,b.no_bill as no_bill_b,b.dasar_pengenaan_pajak as dasar_pengenaan_pajak_b,b.pokok_pajak as pokok_pajak_b from bill.pengembalian_bill a full join bill.penyetoran_bill b on a.no_bill=b.no_bill and a.seri=b.seri where a.kd_kecamatan='$kd_kecamatan' and a.kd_kelurahan='$kd_kelurahan' and a.kd_obj_pajak='$kd_obj_pajak' and a.kd_keg_usaha='$kd_keg_usaha' and a.no_reg='$no_reg' and a.bln_pajak='$bln_pajak' and a.thn_pajak='$thn_pajak' order by a.no_bill asc ") or die('Query failed: ' . pg_last_error());
?>
<style type="text/css">
table, tr, td, th, tbody, thead, tfoot {
    page-break-inside: avoid !important;
}
.garis th,tr,thead {
    border: 1px solid #999;

}
.nogaris td {
border:none;

}
</style>
<table id="t-list-restoran" class="table table-striped responsive-utilities " style="font-size:10px; border:solid #000000 1px;  border-collapse: collapse;" width="100%">
    <thead class="garis">
        <tr >  
		 <th colspan="5" align="center"><div align="center" class="garis">DATA BILL DARI BENDAHARA</div></th><th colspan="4" align="right"><div align="center">DATA BILL DARI PENYEDIA</div></th>  </tr>                                      
          <tr>  <th>No </th>
            <th>NO SERI </th>
			<th>NO BILL </th>
			<th>DASAR PENGENAAN </th>
			<th>POKOK PAJAK</th>
            <th>NO SERI </th>
			<th>NO BILL </th>
			<th>DASAR PENGENAAN </th>
			<th>POKOK PAJAK</th>
        </tr>
		
    </thead>
    <tbody>
<?php
$no = $offset;
if(pg_num_rows($query) != '0')
{
	while ($row = pg_fetch_array($query))
	{
	$no++;	
	$dsra=number_format($row['dasar_pengenaan_pajak_a']);
	$dsrb=number_format($row['dasar_pengenaan_pajak_b']);
	$pokoka=number_format($row['pokok_pajak_a']);
	$pokokb=number_format($row['pokok_pajak_b']);
	$sub_total_a = $sub_total_a + $row['pokok_pajak_a'];
	$sub_total_aa = $sub_total_aa + $row['dasar_pengenaan_pajak_a'];
	$sub_total_b = $sub_total_b + $row['pokok_pajak_b'];
	$sub_total_bb = $sub_total_bb + $row['dasar_pengenaan_pajak_b'];
?>                                        
        <tr class="garis">
            <th class=""><?php echo $no; ?></th>
            <th class=" "><?php echo $row['seri_a']; ?></th>
            <th class=" "><?php echo $row['no_bill_a']; ?></th>
            <th class=" "><?php echo $dsra; ?></th>
            <th class=" "><?php echo $pokoka; ?></th>
			
            <th class=" "><?php echo $row['seri_b']; ?></th>
            <th class=" "><?php echo $row['no_bill_b']; ?></th>
            <th class=" "><?php echo $dsrb ?></th>
            <th class=" "><?php echo $pokokb ?></th>
           
	
        </tr>
		
<?php
	} ?><tr class="garis"><th colspan="3" align="center"><b>Total</b></th><th align="center" style="font-size:10px;"><b><?php echo number_format($sub_total_aa); ?></b></th><th align="center" style="font-size:10px;"><b><?php echo number_format($sub_total_a); ?></b></th>
	<th colspan="2" align="center">Total</th><th align="center" style="font-size:10px;"><b><?php echo number_format($sub_total_bb); ?></b></th><th align="center" style="font-size:10px;"><b><?php echo number_format($sub_total_b); ?></b></th></tr>
	
	
<?php }
?>                                            
    </tbody>							
</table>
<br/><br />
<table width="100%" class="nogaris"><tr><td width="50%"></td><td align="center">Mengetahui:</td></tr>
<?php 
$jab=pg_query("select * from public.user where jabatan='KEPALA UPTB PAJAK DAERAH'");
$u=pg_fetch_array($jab);
?>
<tr><td width="50%"></td><td align="center"><?php echo $u['jabatan']; ?></td></tr>
<tr height="45"><td width="50%"></td><td align="center"></td></tr>
<tr><td width="50%"></td><td align="center"><?php echo $u['nama']; ?></td></tr>
<tr><td width="50%"></td><td align="center">Nip.&nbsp;<?php echo $u['nip']; ?></td></tr>
</table>
<?php
pg_free_result($query);
pg_close($dbconn);
?>