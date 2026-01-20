<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	die('Access Denied');
	exit;
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/schema.php";
include_once $_SESSION['base_dir']."inc/fungsi_indotgl.php";
$data = $_REQUEST['postdata'];
$page = pg_escape_string($_REQUEST['page']);
$num_per_page = 10;

$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = strtoupper(pg_escape_string($$value["name"]));
}
$schema = 'bill';
$nop = $arr_data['nop'];
$bln_pajak = $arr_data['bln-pjk'];
$thn_pajak = $arr_data['tahun-pajak'];
$nm_wp = strtoupper($arr_data['nm-wp']);
$seri_bill = $arr_data['seri-bill'];
$e=getBulan($bln_pajak);
$bul=strtoupper($e);
list($kd_kecamatan,$kd_kelurahan,$kd_obj_pajak,$kd_keg_usaha,$no_reg) = explode(".",$nop);
 $sapi=pg_query("SELECT DISTINCT(A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg) AS NOP,B.nm_usaha,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp AS npwpd,C.nama,B.alamat_usaha,C.alamat FROM bill.dat_bill A 
     INNER JOIN restoran.dat_obj_pajak B ON B.kd_kecamatan=A.kd_kecamatan AND B.kd_kelurahan=A.kd_kelurahan AND B.kd_obj_pajak=A.kd_obj_pajak AND B.kd_keg_usaha=A.kd_keg_usaha AND B.no_reg=A.no_reg 
        INNER JOIN public.wp C ON C.kd_provinsi=B.kd_provinsi AND C.kd_kota=B.kd_kota AND C.kd_jns=B.kd_jns AND C.no_reg=B.no_reg_wp 
        WHERE B.kd_kecamatan='$kd_kecamatan' and B.kd_kelurahan='$kd_kelurahan' and B.kd_obj_pajak='$kd_obj_pajak' and B.kd_keg_usaha='$kd_keg_usaha' and b.no_reg='$no_reg'");
		$row_wp = pg_fetch_array($sapi);
 
 ?>
<script language="javascript">
$(document).ready(function () {
	$('#cetak-list').click(function(){		
			var inputs = $('#filter-data').serializeArray();			
			$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
			$.post( "modul/pelaporan/gen_pdf_penyetoran_bill.php", { data: inputs, ukuran: $('#ukuran-kertas').val() })
  						.done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
  					}); 
	});
	
	$('#download-xls').click(function(){		
			var inputs = $('#filter-data').serializeArray();
			$.post('inc/to_xls.php', { data: inputs }, function(response){
    		if (!response.status) {
        		notif('Notifikasi','Error calling save','error');
        		return;
    		}
    		if (response.status != 'OK') {
        		notif('Notifikasi',response.status,'error');
        		return;
    		}
    		window.open('/print/list_penyerahan_bill_xls.php');
			});	
	});
	
	$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });
});
</script>
<button id="cetak-list" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak</button>
<select name="ukuran-kertas" id="ukuran-kertas" class="select2_single">
<option value="F4">F4</option>
<option value="A3">A3</option>
</select>
<button id="download-xls" type="button" class="btn btn-dark">Download XLS</button>
<table id="t-list-penyetoran" class="table table-striped responsive-utilities jambo_table" style="font-size:11px;">
    <thead>
	<tr><th>LAPORAN BILL BULAN <?php echo $bul;  ?>&nbsp;<?php echo $thn_pajak;  ?></th></tr></thead></table>
      <table style="font-size:9px;"><thead>  <tr class="headings">                                         
            <th>NPWPD </th><th width="5%"></th><th>:</th><th width="5%"></th><th><?php echo $row_wp['npwpd']; ?></th></tr>
			<tr><th>NOP</th><th width="5%"></th><th>:</th><th width="5%"></th><th><?php echo $row_wp['nop']; ?></th></tr>
			<tr><th>NAMA USAHA</th><th width="5%"></th><th>:</th><th width="5%"></th><th><?php echo $row_wp['nm_usaha']; ?></th></tr>
			<tr><th>ALAMAT USAHA</th><th width="5%"></th><th>:</th><th width="5%"></th><th><?php echo $row_wp['alamat']; ?></th></tr>
			<tr><th>NAMA PEMILIK</th><th width="5%"></th><th>:</th><th width="5%"></th><th><?php echo $row_wp['nama']; ?></th></tr>
			</thead></table><br/><br/>

<?php
$num_results = pg_query("select count(a.no_bill) as jumlah,sum(a.dasar_pengenaan_pajak) as dasar_a,sum(b.dasar_pengenaan_pajak) as dasar_b,sum(a.pokok_pajak) as pokok_a,sum(b.pokok_pajak) as pokok_b from bill.pengembalian_bill a full join bill.penyetoran_bill b on a.no_bill=b.no_bill and a.seri=b.seri where a.kd_kecamatan='$kd_kecamatan' and a.kd_kelurahan='$kd_kelurahan' and a.kd_obj_pajak='$kd_obj_pajak' and a.kd_keg_usaha='$kd_keg_usaha' and a.no_reg='$no_reg' and a.bln_pajak='$bln_pajak' and a.thn_pajak='$thn_pajak'") or die('Query failed: ' . pg_last_error());
$numrows=pg_fetch_array($num_results);
$g_dasar_a=$numrows['dasar_a'];
$g_dasar_b=$numrows['dasar_b'];
$g_pokok_a=$numrows['pokok_a'];
$g_pokok_b=$numrows['pokok_b'];
$num_pages = ceil($numrows['jumlah'] / $num_per_page);
if ($page > $num_pages || $page == '') {
  $page = $num_pages - 1;
}
if ($page <= 0) {
  $page = 0;
}
$offset = $page * $num_per_page;
pg_free_result($num_results); 

$query = pg_query("select a.seri as seri_a,a.no_bill as no_bill_a,a.dasar_pengenaan_pajak as dasar_pengenaan_pajak_a,a.pokok_pajak as pokok_pajak_a,b.seri as seri_b,b.no_bill as no_bill_b,b.dasar_pengenaan_pajak as dasar_pengenaan_pajak_b,b.pokok_pajak as pokok_pajak_b from bill.pengembalian_bill a full join bill.penyetoran_bill b on a.no_bill=b.no_bill and a.seri=b.seri where a.kd_kecamatan='$kd_kecamatan' and a.kd_kelurahan='$kd_kelurahan' and a.kd_obj_pajak='$kd_obj_pajak' and a.kd_keg_usaha='$kd_keg_usaha' and a.no_reg='$no_reg' and a.bln_pajak='$bln_pajak' and a.thn_pajak='$thn_pajak' order by a.no_bill asc LIMIT $num_per_page OFFSET $offset") or die('Query failed: ' . pg_last_error());
		
	
?>

<table id="t-list-restoran" class="table table-striped responsive-utilities " style="font-size:10px;">
    <thead>
        <tr class="">  
		 <th colspan="5" align="center"><div align="center">DATA BILL DARI BENDAHARA</div></th><th colspan="4" align="right"><div align="center">DATA BILL DARI PENYEDIA</div></th>  </tr>                                      
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
        <tr class="even pointer">
            <td class=" "><?php echo $no; ?></td>
            <td class=" "><?php echo $row['seri_a']; ?></td>
            <td class=" "><?php echo $row['no_bill_a']; ?></td>
            <td class=" "><?php echo $dsra; ?></td>
            <td class=" "><?php echo $pokoka; ?></td>
			
            <td class=" "><?php echo $row['seri_b']; ?></td>
            <td class=" "><?php echo $row['no_bill_b']; ?></td>
            <td class=" "><?php echo $dsrb ?></td>
            <td class=" "><?php echo $pokokb ?></td>
           
	
        </tr>
		
<?php
	} ?><tr><td colspan="3" align="center">Sub Total</td><td align="left" style="font-size:10px;"><b><?php echo number_format($sub_total_aa); ?></b></td><td align="left" style="font-size:10px;"><b><?php echo number_format($sub_total_a); ?></b></td>
	<td colspan="2"></td><td align="left" style="font-size:10px;"><b><?php echo number_format($sub_total_bb); ?></b></td><td align="left" style="font-size:10px;"><b><?php echo number_format($sub_total_b); ?></b></td></tr>
	
	<tr><td colspan="3" align="center">Grand Total</td><td align="left" style="font-size:10px;"><b><?php echo number_format($g_dasar_a); ?></b></td><td align="left" style="font-size:10px;"><b><?php echo number_format($g_pokok_a); ?></b></td>
	<td colspan="2"></td><td align="left" style="font-size:10px;"><b><?php echo number_format($g_dasar_b); ?></b></td><td align="left" style="font-size:10px;"><b><?php echo number_format($g_pokok_b); ?></b></td></tr>
<?php }
?>                                            
    </tbody>							
</table>
<?php
$paging_paging = 10;
$max_paging_paging = ceil($num_pages / $paging_paging);
$paging_page_sekarang = ceil(($page+1) / $paging_paging);
echo "<center><ul class=\"pagination\">";
$page_start_paging = (($paging_page_sekarang*$paging_paging)-$paging_paging);
$pange_end_paging = (($paging_page_sekarang*$paging_paging)-1);
//echo "start:$page_start_paging End:$pange_end_paging";
$backward = $paging_page_sekarang - 1;
if($backward < 1) {
	echo "<li class=\"disabled\"><a href=\"#\"><span class=\"glyphicon glyphicon-backward\"></span></a></li>";
} else {
		$page_back = (($backward*$paging_paging)-$paging_paging);
		echo "<li><a href=\"#\" onClick=\"list_data($page_back)\"><span class=\"glyphicon glyphicon-backward\"></span></a></li>";
}
for($i=$page_start_paging;$i <= $pange_end_paging;$i++) {
	if($i > ($num_pages - 1)) {
		break;
	}
		if($i == ($page)){
			echo "<li class=\"active\"><a href=\"#\">$i</a></li>";	
		} else {
			echo "<li><a href=\"#\" onClick=\"list_data($i)\">$i</a></li>";
		}
}
$forward = $paging_page_sekarang + 1;
if($forward > $max_paging_paging) {
	echo "<li class=\"disabled\"><a href=\"#\"><span class=\"glyphicon glyphicon-forward\"></span></a></li>";	
} else {
	$page_next = (($forward*$paging_paging)-$paging_paging);
	echo "<li><a href=\"#\" onClick=\"list_data($page_next)\"><span class=\"glyphicon glyphicon-forward\"></span></a></li>";
}
echo "</ul>
</center>";
pg_free_result($query);
pg_close($dbconn);
?>
