<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	die('Access Denied');
	exit;
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/schema.php";
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
$npwpd = $arr_data['npwpd'];
$nm_usaha = $arr_data['nm-usaha'];
$nm_wp = strtoupper($arr_data['nm-wp']);
$seri_bill = $arr_data['seri-bill'];

//$num_results = pg_query("SELECT COUNT(*) AS jumlah,SUM(A.pokok_pajak) AS g_total FROM $schema.skp A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp WHERE A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg LIKE '%$nop%' AND A.nm_usaha LIKE '%$nm_usaha%' AND A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg_wp LIKE '%$npwpd%' AND B.nama LIKE '%$nm_wp%' AND A.bln_pajak LIKE '%$bulan_pajak%' AND A.status_pembayaran != '2'") or die('Query failed: ' . pg_last_error());

//$num_results = pg_query("SELECT COUNT(*) AS jumlah,SUM(A.pokok_pajak) AS g_total FROM $schema.skp A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp WHERE A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg LIKE '%$nop%' AND A.kd_keg_usaha LIKE '%$kd_keg_usaha%' AND A.nm_reklame LIKE '%$nm_usaha%' AND A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg_wp LIKE '%$npwpd%' AND B.nama LIKE '%$nm_wp%' AND A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat LIKE '%$no_surat%' AND A.bln_pajak LIKE '%$bulan_pajak%' AND A.status_pembayaran != '2'") or die('Query failed: ' . pg_last_error());
$num_results = pg_query("SELECT COUNT(DISTINCT(A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg)) AS jumlah FROM $schema.dat_bill A "
        . "INNER JOIN restoran.dat_obj_pajak B ON B.kd_kecamatan=A.kd_kecamatan AND B.kd_kelurahan=A.kd_kelurahan AND B.kd_obj_pajak=A.kd_obj_pajak AND B.kd_keg_usaha=A.kd_keg_usaha AND B.no_reg=A.no_reg "
        . "INNER JOIN public.wp C ON C.kd_provinsi=B.kd_provinsi AND C.kd_kota=B.kd_kota AND C.kd_jns=B.kd_jns AND C.no_reg=B.no_reg_wp "
        . "WHERE A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg LIKE '%$nop%' "
        . "AND B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp LIKE '%$npwpd%' "
        . "AND B.nm_usaha LIKE '%$nm_usaha%' "
        . "AND C.nama LIKE '%$nm_wp%' "
        . "AND A.seri = '$seri_bill'") or die('Query failed: ' . pg_last_error());
$numrows=pg_fetch_array($num_results);
$num_pages = ceil($numrows['jumlah'] / $num_per_page);
if ($page > $num_pages || $page == '') {
  $page = $num_pages - 1;
}
if ($page <= 0) {
  $page = 0;
}
$offset = $page * $num_per_page;
pg_free_result($num_results);
$query = pg_query("SELECT DISTINCT(A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg) AS NOP,B.nm_usaha,B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp AS npwpd,C.nama,B.alamat_usaha,C.alamat FROM $schema.dat_bill A INNER JOIN restoran.dat_obj_pajak B ON B.kd_kecamatan=A.kd_kecamatan AND B.kd_kelurahan=A.kd_kelurahan AND B.kd_obj_pajak=A.kd_obj_pajak AND B.kd_keg_usaha=A.kd_keg_usaha AND B.no_reg=A.no_reg 
 INNER JOIN public.wp C ON C.kd_provinsi=B.kd_provinsi AND C.kd_kota=B.kd_kota AND C.kd_jns=B.kd_jns AND C.no_reg=B.no_reg_wp WHERE A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg LIKE '%$nop%' AND B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg_wp LIKE '%$npwpd%' AND B.nm_usaha LIKE '%$nm_usaha%' AND C.nama LIKE '%$nm_wp%' AND A.seri = '$seri_bill' LIMIT $num_per_page OFFSET $offset") or die('Query failed: ' . pg_last_error());
?>
<script language="javascript">
$(document).ready(function () {
	$('#cetak-list').click(function(){		
			var inputs = $('#filter-data').serializeArray();			
			$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
			$.post( "modul/pelaporan/gen_pdf_penyerahan_bill.php", { data: inputs, ukuran: $('#ukuran-kertas').val() })
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
<table id="t-list-restoran" class="table table-striped responsive-utilities jambo_table" style="font-size:9px;">
    <thead>
        <tr class="headings">                                         
            <th>No </th>
            <th>NOP </th>
            <th>NPWPD </th>
            <th>Nama Usaha / Alamat </th>
            <th>Nama WP / Alamat</th>
            <th width="60%">Data Bill </th>
			<th width="20%">Bill Yg Dikembalikan </th>
          
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
?>                                        
        <tr class="even pointer">
            <td class=" "><?php echo $no; ?></td>
            <td class=" "><?php echo $row['nop']; ?></td>
            <td class=" "><?php echo $row['npwpd']; ?></td>
            <td class=" "><?php echo $row['nm_usaha']."<br>".$row['alamat_usaha']; ?></td>
            <td class=" "><?php echo $row['nama']."<br>".$row['alamat']; ?></td>
            <?php
                $n_o_p = $row['nop'];                
                $q_dat_bill = pg_query("SELECT seri,no_bill,buku,to_char(tgl_penyerahan, 'DD-MM-YYYY') AS tgl_serah FROM bill.dat_bill "
                        . "where kd_kecamatan||'.'||kd_kelurahan||'.'||kd_obj_pajak||'.'||kd_keg_usaha||'.'||no_reg = '$n_o_p' "
                        . "AND seri='$seri_bill' AND buku <> '' ORDER BY no_bill") or die('Query failed: ' . pg_last_error());
                echo "<td >";                               
                while ($row_bill = pg_fetch_array($q_dat_bill))
                {                        
                    if($row_bill['buku'] == 'AW') {
                        echo "SERI ".$row_bill['seri']." ".$row_bill['no_bill']." - ";
                    } else {
                        echo $row_bill['no_bill']." (".$row_bill['tgl_serah'].")<br>";
                    }                                          
                }                          
                echo "</td>";
                pg_free_result($q_dat_bill);
            ?>
			 <?php
                $n_o_p_e = $row['nop'];                
                $q_dat_bilss = pg_query("SELECT seri,no_bill,buku,to_char(tgl_penyerahan, 'DD-MM-YYYY') AS tgl_serah FROM bill.dat_bill "
                        . "where kd_kecamatan||'.'||kd_kelurahan||'.'||kd_obj_pajak||'.'||kd_keg_usaha||'.'||no_reg = '$n_o_p_e' "
                        . "AND seri='$seri_bill' AND status_pengembalian='1' ORDER BY no_bill") or die('Query failed: ' . pg_last_error());
                echo "<td>";
    
				$i=1;                              
                while ($row_bills = pg_fetch_array($q_dat_bilss))
				
                {                        
                   /*echo "$row_bills[no_bill]";
				 /*  if($i == '15' ){
				 echo "<br/>"; }
				 if($i == '30' ){
				 echo "<br/>"; }
				  if($i == '45' ){
				 echo "<br/>"; }
				  if($i == '60' ){
				 echo "<br/>"; }
				  if($i == '75' ){
				 echo "<br/>"; }
				  if($i == '90' ){
				 echo "<br/>"; }
				  if($i == '115' ){
				 echo "<br/>"; }
				  if($i == '130' ){
				 echo "<br/>"; }
				  if($i == '145' ){
				 echo "<br/>"; }
				  if($i == '160' ){
				 echo "<br/>"; }*/
				   if ($row_bills['buku'] == 'AK'){
				   echo "<br/>";}
				   else{
				    echo "$row_bills[no_bill]&nbsp;,";
				   }                                  
           //  $i++; 
			   }                          
                echo "</td>";
                pg_free_result($q_dat_bill);
            ?>
        </tr>
<?php
	}
}
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
