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
$nop = $arr_data['nop'];
$npwpd = $arr_data['npwpd'];

$no_surat = $arr_data['no-surat'];
$nm_usaha = $arr_data['nm-usaha'];
$nm_wp = strtoupper($arr_data['nm-wp']);

$kd_keg_usaha =$arr_data['keg-usaha'];

$tahun_pajak = $arr_data['tahun-pajak'];
$bulan_pajak = $arr_data['bln-pjk'];
$status_byr = $arr_data['status-byr'];
switch ($kd_obj_pajak) {
case "03":
$num_results = pg_query("SELECT COUNT(*) AS jumlah,SUM(A.pokok_pajak_baru) AS g_total FROM $schema.skpdkb A LEFT JOIN
$schema.dat_obj_pajak B ON A.kd_kecamatan=B.kd_kecamatan AND A.kd_kelurahan=B.kd_kelurahan AND A.kd_keg_usaha=B.kd_keg_usaha AND A.no_reg=B.no_reg 
LEFT JOIN public.wp C ON B.kd_provinsi=C.kd_provinsi AND B.kd_kota=C.kd_kota AND B.kd_jns=C.kd_jns AND B.no_reg_wp=C.no_reg
LEFT JOIN restoran.pembayaran D ON
A.jns_surat=D.jns_surat AND A.thn_pajak=D.thn_pajak AND A.bln_pajak=D.bln_pajak AND A.kd_obj_pajak=D.kd_obj_pajak AND A.no_urut_surat=D.no_urut_surat
WHERE A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg LIKE '%$nop%' AND B.kd_keg_usaha LIKE '%$kd_keg_usaha%' AND B.nm_usaha LIKE '%$nm_usaha%' AND C.kd_provinsi||'.'||C.kd_kota||'.'||C.kd_jns||'.'||C.no_reg LIKE '%$npwpd%' AND C.nama LIKE '%$nm_wp%' AND A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat LIKE '%$no_surat%' AND A.bln_pajak LIKE '%$bulan_pajak%' AND A.thn_pajak='$tahun_pajak' AND A.status_pembayaran LIKE '%$status_byr%' AND A.status_pembayaran != '2'") or die('Query failed: ' . pg_last_error());
break;
 case"09":
$num_results = pg_query("SELECT COUNT(*) AS jumlah,SUM(A.pokok_pajak) AS g_total from bphtb.skpdkb A LEFT JOIN 
			bphtb.pembayaran B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=a.kd_obj_pajak and d.kd_keg_usaha=a.kd_sh
			WHERE a.kd_sh LIKE '%$kd_keg_usaha%' AND a.nmobj LIKE '%$nm_usaha%' AND a.nama_wp LIKE '%$nm_wp%' AND a.jns_surat||'.'||a.thn_pajak||'.'||a.bln_pajak||'.'||a.kd_obj_pajak||'.'||a.no_urut_surat LIKE '%$no_surat%' AND a.bln_pajak LIKE '%$bulan_pajak%' AND a.thn_pajak='$tahun_pajak' AND a.status_pembayaran LIKE '%$status_byr%' AND a.status_pembayaran != '2'") or die('Query failed: ' . pg_last_error());
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

switch ($kd_obj_pajak) {
       case "03":
$query = pg_query("SELECT A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,B.nm_usaha,B.alamat_usaha,C.nama,C.alamat,A.pokok_pajak,to_char(A.tgl_surat, 'DD-MM-YYYY') AS tgl_tetap,(CASE WHEN A.status_pembayaran ='1' THEN 'Sudah Bayar' ELSE 'BelumBayar' END) AS status_bayar,D.tgl_bayar FROM $schema.skpdkb A 
LEFT JOIN $schema.dat_obj_pajak B ON A.kd_kecamatan=B.kd_kecamatan AND A.kd_kelurahan=B.kd_kelurahan AND A.kd_keg_usaha=B.kd_keg_usaha AND A.no_reg=B.no_reg
LEFT JOIN public.wp C ON B.kd_provinsi=C.kd_provinsi AND B.kd_kota=C.kd_kota AND B.kd_jns=C.kd_jns AND B.no_reg_wp=C.no_reg
LEFT JOIN $schema.pembayaran D ON
A.jns_surat=D.jns_surat AND A.thn_pajak=D.thn_pajak AND A.bln_pajak=D.bln_pajak AND A.kd_obj_pajak=D.kd_obj_pajak AND A.no_urut_surat=D.no_urut_surat
 WHERE A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg LIKE '%$nop%' AND A.kd_keg_usaha LIKE '%$kd_keg_usaha%' AND B.nm_usaha LIKE '%$nm_usaha%' AND C.kd_provinsi||'.'||C.kd_kota||'.'||C.kd_jns||'.'||C.no_reg LIKE '%$npwpd%' AND C.nama LIKE '%$nm_wp%' AND A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat LIKE '%$no_surat%' AND A.bln_pajak LIKE '%$bulan_pajak%' AND A.thn_pajak='$tahun_pajak' AND A.status_pembayaran LIKE '%$status_byr%' AND A.status_pembayaran != '2' LIMIT $num_per_page OFFSET $offset") or die('Query failed: ' . pg_last_error());
break;
case "09":
$query = pg_query("SELECT A.nama_wp as nama,A.alamat_wp as alamat,A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_surat,A.pokok_pajak,A.nop,A.nmobj as nm_usaha,A.alamatobj as alamat_usaha,to_char(A.tgl_surat, 'DD-MM-YYYY') AS tgl_tetap,B.tgl_bayar from bphtb.skpdkb A LEFT JOIN 
			bphtb.pembayaran B on A.jns_surat=B.jns_surat and A.thn_pajak=B.thn_pajak and A.bln_pajak=B.bln_pajak and A.kd_obj_pajak=b.kd_obj_pajak and A.no_urut_surat=B.no_urut_surat 
			LEFT JOIN public.keg_usaha D on d.kd_obj_pajak=a.kd_obj_pajak and d.kd_keg_usaha=a.kd_sh
			WHERE a.kd_sh LIKE '%$kd_keg_usaha%' AND a.nmobj LIKE '%$nm_usaha%' AND a.nama_wp LIKE '%$nm_wp%' AND a.jns_surat||'.'||a.thn_pajak||'.'||a.bln_pajak||'.'||a.kd_obj_pajak||'.'||a.no_urut_surat LIKE '%$no_surat%' AND a.bln_pajak LIKE '%$bulan_pajak%' AND a.thn_pajak='$tahun_pajak' AND a.status_pembayaran LIKE '%$status_byr%' AND a.status_pembayaran != '2' LIMIT $num_per_page OFFSET $offset") or die('Query failed: ' . pg_last_error());
 break;
}
?>
<script language="javascript">
$(document).ready(function () {
	$('#cetak-list').click(function(){		
			var inputs = $('#filter-data').serializeArray();			
			$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
			$.post( "modul/pelaporan/gen_pdf_list_skpd.php", { data: inputs, ukuran: $('#ukuran-kertas').val() })
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
    		window.open('/print/list_skpd_xls.php');
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
                                                <th>NO SKPDKB </th>
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
