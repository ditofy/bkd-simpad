<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
$data_ser = $_SESSION['data'];
//$data = unserialize(urldecode($data_ser));
$data = $_REQUEST['postdata'];
$page = pg_escape_string($_REQUEST['page']);
$num_per_page = 30;
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = strtoupper(pg_escape_string($$value["name"]));
}
list($kd_prov,$kd_kota,$kd_jns,$no_reg_wp) = explode(".",$arr_data['npwpd']);
$nm_wp = strtoupper($arr_data['nm-wp']);
$schema = "public";
$num_results = pg_query("SELECT COUNT(*) AS jumlah FROM $schema.wp A WHERE A.kd_provinsi LIKE '%$kd_prov%' AND A.kd_kota LIKE '%$kd_kota%' AND A.kd_jns LIKE '%$kd_jns%' AND A.no_reg LIKE '%$no_reg_wp%' AND A.nama LIKE '%$nm_wp%' AND A.status <> '0'") or die('Query failed: ' . pg_last_error());
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

$query = pg_query("SELECT A.kd_provinsi||'.'||A.kd_kota||'.'||A.kd_jns||'.'||A.no_reg AS npwpd,A.* FROM $schema.wp A WHERE A.kd_provinsi LIKE '%$kd_prov%' AND A.kd_kota LIKE '%$kd_kota%' AND A.kd_jns LIKE '%$kd_jns%' AND A.no_reg LIKE '%$no_reg_wp%' AND A.nama LIKE '%$nm_wp%' AND A.status <> '0' ORDER BY A.kd_provinsi,A.kd_kota,A.kd_jns,A.no_reg LIMIT $num_per_page OFFSET $offset") or die('Query failed: ' . pg_last_error());
?>
<script language="javascript">
$(document).ready(function () {
	$('#cetak-list').click(function(){		
			var inputs = $('#filter-data').serializeArray();
			$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
			$.post( "modul/wp/gen_pdf_list.php", { data: inputs, ukuran: $('#ukuran-kertas').val() })
  						.done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
  					});
	});
	$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
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
    		window.open('/print/list_wp_xls.php');
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
<table id="t-list-wp" class="table table-striped responsive-utilities jambo_table" style="font-size:9px;">
                                        <thead>
                                            <tr class="headings">                                         
                                                <th>No </th>
                                                <th>NPWPD </th>
												<th>NIK </th>
                                                <th>Nama WP </th>
												<th>Alamat WP </th>
												<th>No Telp </th>
												<th>Tgl Terdaftar </th>                                              
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
												<td class=" "><?php echo $row['nama']; ?></td>
												<td class=" "><?php echo $row['alamat']; ?></td>
												<td class=" "><?php echo $row['telp']; ?></td>
												<td class=" "><?php if (!empty($row['tgl_daftar'])){ echo $row['tgl_daftar']; }?></td>
                                            </tr>
<?php
	}
}
pg_free_result($result);
pg_close($dbconn);
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
