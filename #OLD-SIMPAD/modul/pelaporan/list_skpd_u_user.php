<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
include_once $_SESSION['base_dir']."inc/schema.php";
$data = $_REQUEST['postdata'];
$page = pg_escape_string($_REQUEST['page']);
$num_per_page = 30;
$sub_total = 0;
$grand_total = 0;
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = strtoupper(pg_escape_string($$value["name"]));
}
$schema = $list_schema[$arr_data['kd-obj-pajak']];
$kd_obj_pajak = $arr_data['kd-obj-pajak'];
//$nop = $arr_data['nop'];
//$npwpd = $arr_data['npwpd'];

//$no_surat = $arr_data['no-surat'];
//$nm_usaha = $arr_data['nm-usaha'];
//$nm_wp = strtoupper($arr_data['nm-wp']);

//$kd_keg_usaha =$arr_data['keg-usaha'];

$tahun_pajak = $arr_data['tahun-pajak'];
$bulan_pajak = $arr_data['bln-pjk'];
//$status_byr = $arr_data['status-byr'];
$pemungut=$arr_data['pemungut'];
if($kd_obj_pajak !== '02') {
$num_results = pg_query("SELECT COUNT(*) AS jumlah,SUM(A.pokok_pajak) AS g_total FROM $schema.skp A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp 
LEFT JOIN $schema.pembayaran C ON
A.thn_pajak=C.thn_pajak AND A.bln_pajak=C.bln_pajak AND A.kd_obj_pajak=C.kd_obj_pajak AND A.no_urut_surat=C.no_urut_surat
WHERE A.kd_obj_pajak LIKE '%$kd_obj_pajak%' AND A.bln_pajak LIKE '%$bulan_pajak%' AND A.thn_pajak='$tahun_pajak' AND C.pemungut LIKE '%$pemungut%' AND A.status_pembayaran != '2'") or die('Query failed: ' . pg_last_error());
} else {
$num_results = pg_query("SELECT COUNT(*) AS jumlah,SUM(A.pokok_pajak) AS g_total FROM $schema.skp A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp 
LEFT JOIN $schema.pembayaran C ON
A.thn_pajak=C.thn_pajak AND A.bln_pajak=C.bln_pajak AND A.kd_obj_pajak=C.kd_obj_pajak AND A.no_urut_surat=C.no_urut_surat
WHERE  A.kd_obj_pajak LIKE '%$kd_obj_pajak%' AND A.bln_pajak LIKE '%$bulan_pajak%' AND A.thn_pajak='$tahun_pajak' AND C.pemungut LIKE '%$pemungut%' AND A.status_pembayaran != '2'") or die('Query failed: ' . pg_last_error());
}
$numrows=pg_fetch_array($num_results);
$num_pages = ceil($numrows['jumlah'] / $num_per_page);
if ($page > $num_pages || $page == '') {
  $page = $num_pages - 1;
}
if ($page <= 0) {
  $page = 0;
}
$offset = $page * $num_per_page;
$grand_total = $numrows['g_total'];
pg_free_result($num_results);
if($kd_obj_pajak !== '02') {
$query = pg_query("SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,A.nm_usaha,A.alamat_usaha,B.nama,B.alamat,A.pokok_pajak,to_char(A.tgl_penetapan, 'DD-MM-YYYY') AS tgl_tetap,(CASE WHEN A.status_pembayaran ='1' THEN 'Sudah Bayar' ELSE 'BelumBayar' END) AS status_bayar,C.tgl_bayar,C.no_bukti FROM $schema.skp A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp
LEFT JOIN $schema.pembayaran C ON
A.thn_pajak=C.thn_pajak AND A.bln_pajak=C.bln_pajak AND A.kd_obj_pajak=C.kd_obj_pajak AND A.no_urut_surat=C.no_urut_surat
WHERE  A.kd_obj_pajak LIKE '%$kd_obj_pajak%' AND A.bln_pajak LIKE '%$bulan_pajak%' AND A.thn_pajak='$tahun_pajak' AND C.pemungut LIKE '%$pemungut%' AND A.status_pembayaran != '2' LIMIT $num_per_page OFFSET $offset") or die('Query failed: ' . pg_last_error());
} else {
$query = pg_query("SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,A.nm_reklame AS nm_usaha,A.alamat_reklame AS alamat_usaha,B.nama,B.alamat,A.pokok_pajak,to_char(A.tgl_penetapan, 'DD-MM-YYYY') AS tgl_tetap,to_char(A.tmt_awal, 'DD-MM-YYYY') AS tmt_ak,A.p AS panjang,A.l AS lebar,A.s AS sisi,A.jlh AS banyak,A.tarif,(CASE WHEN A.status_pembayaran ='1' THEN 'Sudah Bayar' ELSE 'BelumBayar' END) AS status_bayar,C.tgl_bayar,C.no_bukti FROM $schema.skp A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp
LEFT JOIN $schema.pembayaran C ON
A.thn_pajak=C.thn_pajak AND A.bln_pajak=C.bln_pajak AND A.kd_obj_pajak=C.kd_obj_pajak AND A.no_urut_surat=C.no_urut_surat
 WHERE  A.kd_obj_pajak LIKE '%$kd_obj_pajak%' AND A.bln_pajak LIKE '%$bulan_pajak%' AND A.thn_pajak='$tahun_pajak' AND C.pemungut LIKE '%$pemungut%' AND A.status_pembayaran != '2' LIMIT $num_per_page OFFSET $offset") or die('Query failed: ' . pg_last_error());
}
?>
<script language="javascript">
$(document).ready(function () {
	$('#cetak-list').click(function(){		
			var inputs = $('#filter-data').serializeArray();			
			$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
			$.post( "modul/pelaporan/gen_pdf_list_skpd_user.php", { data: inputs, ukuran: $('#ukuran-kertas').val() })
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
    		window.open('/print/list_skpd_user_xls.php');
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
                                                <th>NO SKPD </th>
                                                <th>NOP </th>
                                                <th>Nama Objek </th>
												<?php
												if($kd_obj_pajak == '02') {
													echo "<th>Ukuran </th>";
													echo "<th>Tarif </th>";
												}
												?>	
                                                <th>Alamat Objek </th>
                                                <th>Nama WP </th>
												<th>Alamat WP </th>
												<th>Pokok Pajak </th>												
												<th>Tgl Penetapan </th>	
												<?php
												if($kd_obj_pajak == '02') {
													echo "<th>Tmt Awal </th>";
												}
												?>											
												<th>No Bukti</th>
												<th> Tanggal Bayar</th>                                             
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
	$sub_total = $sub_total + $row['pokok_pajak'];
	$w=$row['tgl_bayar'];
	
?>                                        
                                            <tr class="even pointer">
                                                <td class=" "><?php echo $no; ?></td>
                                                <td class=" "><?php echo $row['no_surat']; ?></td>
                                                <td class=" "><?php echo $row['nop']; ?></td>
                                                <td class=" "><?php echo $row['nm_usaha']; ?></td>
												<?php
												if($kd_obj_pajak == '02') {
													echo "<td class=\" \">".$row['panjang']."m x ".$row['lebar']."m x ".$row['sisi']." sisi x ".$row['banyak']." buah"."</td>";
													echo "<td class=\" \">".number_format($row['tarif'])."</td>";
												}
												?>	
												<td class=" "><?php echo $row['alamat_usaha']; ?></td>
												<td class=" "><?php echo $row['nama']; ?></td>
												<td class=" "><?php echo $row['alamat']; ?></td>																								
												<td class=" " align="right"><?php echo number_format($row['pokok_pajak']); ?></td>
												<td class=" "><?php echo $row['tgl_tetap']; ?></td>
												<?php
												if($kd_obj_pajak == '02') {
													echo "<td class=\" \">".$row['tmt_ak']."</td>";
												}
												?>	
												<td><?php echo $row['no_bukti'];?></td>
												<td><?php 
												if ($w){
												echo date('d-M-Y',strtotime($w)); }
												else{
												}  ?></td>                                           
                                            </tr>
<?php
	}
}
?>                                      <tr>
											<?php
												if($kd_obj_pajak == '02') {
													echo "<td colspan=\"9\" align=\"center\" style=\"font-size:12px;\">Sub. Total</td>";
												} else {
													echo "<td colspan=\"7\" align=\"center\" style=\"font-size:12px;\">Sub. Total</td>";
												}
											?>											
											<td align="right" style="font-size:12px;"><?php echo number_format($sub_total); ?></td>
											<td></td>
											<td></td>
											<?php
												if($kd_obj_pajak == '02') {
													echo "<td class=\" \"></td>";
												}
											?>	
										</tr> 
										<tr>
											<?php
												if($kd_obj_pajak == '02') {
													echo "<td colspan=\"9\" align=\"center\" style=\"font-size:12px;\">Grand Total</td>";
												} else {
													echo "<td colspan=\"7\" align=\"center\" style=\"font-size:12px;\">Grand Total</td>";
												}
											?>
											<td align="right" style="font-size:12px;"><?php echo number_format($grand_total); ?></td>
											<td></td>
											<td></td>
											<?php
												if($kd_obj_pajak == '02') {
													echo "<td class=\" \"></td>";
												}
											?>	
										</tr>        
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
