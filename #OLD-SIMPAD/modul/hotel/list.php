<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
$data = $_REQUEST['postdata'];
$page = pg_escape_string($_REQUEST['page']);
$schema = "hotel";
$num_per_page = 30;
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = strtoupper(pg_escape_string($$value["name"]));
}
list($kd_kecamatan,$kd_kelurahan,$kd_obj_pajak,$kd_keg_usaha,$no_reg) = explode(".",$arr_data['nop']);
list($kd_prov,$kd_kota,$kd_jns,$no_reg_wp) = explode(".",$arr_data['npwpd']);
$nm_usaha = $arr_data['nm-usaha'];
$nm_wp = strtoupper($arr_data['nm-wp']);
if($kd_keg_usaha == ""){
	list($kd_keg_usaha,$nm_keg_usaha) = explode(".",$arr_data['keg-usaha']);
}
if($kd_kecamatan == ""){
	list($kd_kecamatan,$nm_kecamatan) = explode(".",$arr_data['kd-kecamatan']);
}
if($kd_kelurahan == ""){
	$kd_kelurahan = substr($arr_data['kd-kelurahan'],0,2);
	$nm_kel = substr($arr_data['kd-kelurahan'],5,strlen($arr_data['kd-kelurahan']));
}
$num_results = pg_query("SELECT COUNT(*) AS jumlah FROM $schema.dat_obj_pajak A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp WHERE A.kd_kecamatan LIKE '%$kd_kecamatan%' AND A.kd_kelurahan LIKE '%$kd_kelurahan%' AND A.kd_obj_pajak LIKE '%$kd_obj_pajak%' AND A.kd_keg_usaha LIKE '%$kd_keg_usaha%' AND A.no_reg LIKE '%$no_reg%' AND A.nm_usaha LIKE '%$nm_usaha%' AND A.kd_provinsi LIKE '%$kd_prov%' AND A.kd_kota LIKE '%$kd_kota%' AND A.kd_jns LIKE '%$kd_jns%' AND A.no_reg_wp LIKE '%$no_reg_wp%' AND B.nama LIKE '%$nm_wp%' AND A.status_pajak='1'") or die('Query failed: ' . pg_last_error());
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
$query = pg_query("SELECT A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg_wp AS npwpd, A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_obj_pajak||'.'||A.kd_keg_usaha||'.'||A.no_reg AS nop,A.jenis_penetapan,CASE WHEN jenis_penetapan='01' THEN 'SKP' WHEN jenis_penetapan='02' THEN 'SELF ASSESSMENT' ELSE '' END AS jns_p,A.*,B.* FROM $schema.dat_obj_pajak A INNER JOIN public.wp B ON B.kd_provinsi=A.kd_provinsi AND B.kd_kota=A.kd_kota AND B.kd_jns=A.kd_jns AND B.no_reg=A.no_reg_wp WHERE A.kd_kecamatan LIKE '%$kd_kecamatan%' AND A.kd_kelurahan LIKE '%$kd_kelurahan%' AND A.kd_obj_pajak LIKE '%$kd_obj_pajak%' AND A.kd_keg_usaha LIKE '%$kd_keg_usaha%' AND A.no_reg LIKE '%$no_reg%' AND A.nm_usaha LIKE '%$nm_usaha%' AND A.kd_provinsi LIKE '%$kd_prov%' AND A.kd_kota LIKE '%$kd_kota%' AND A.kd_jns LIKE '%$kd_jns%' AND A.no_reg_wp LIKE '%$no_reg_wp%' AND B.nama LIKE '%$nm_wp%' AND A.status_pajak='1' ORDER BY A.id LIMIT $num_per_page OFFSET $offset") or die('Query failed: ' . pg_last_error());
?>
<script language="javascript">
$(document).ready(function () {
	$('#cetak-list').click(function(){		
			var inputs = $('#filter-data').serializeArray();
			$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
			$.post( "modul/hotel/gen_pdf_list.php", { data: inputs, ukuran: $('#ukuran-kertas').val() })
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
    		window.open('/print/list_hotel_xls.php');
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
                                                <th>NPWPD </th>
												<th>NIK </th>
                                                <th>NOP </th>
                                                <th>Nama Usaha </th>
                                                <th>Alamat Usaha </th>
                                                <th>Nama WP </th>
												<th>Alamat WP </th>
												<th>No Telp </th>
												<th>Jns Penetapan </th>
												<th>Tgl Terdaftar </th>
												<th>Ketetapan </th>
												<th>Keterangan </th>                                              
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
                                                <td class=" "><?php echo $row['npwpd']; ?></td>
												<td class=" "><?php echo $row['nik']; ?></td>
                                                <td class=" "><?php echo $row['nop']; ?></td>
                                                <td class=" "><?php echo $row['nm_usaha']; ?></td>
												<td class=" "><?php echo $row['alamat_usaha']; ?></td>
												<td class=" "><?php echo $row['nama']; ?></td>
												<td class=" "><?php echo $row['alamat']; ?></td>
												<td class=" "><?php echo $row['telp']; ?></td>
												<td class=" "><?php if (!empty($row['jns_p'])) {echo $row['jns_p'];} ?></td>
												<td class=" "><?php echo $row['tgl_daftar']; ?></td>
												<td class=" "><?php echo number_format($row['ketetapan']); ?></td>
												<td class=" "><?php echo $row['ket']; ?></td>                                           
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
