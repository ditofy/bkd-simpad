<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
$data = $_REQUEST['postdata'];
$page = pg_escape_string($_REQUEST['page']);
$schema = "bphtb";
$num_per_page = 30;
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = strtoupper(pg_escape_string($$value["name"]));
}
$nop = strtoupper($arr_data['nop']);
list($kd_prov,$kd_dati2,$kd_kec,$kd_kel,$kd_blok,$no_urut,$kd_jns_op) = explode(".",$nop);

list($kd_prop,$kd_kota,$kd_jns,$no_reg_wp) = explode(".",$arr_data['npwpd']);
$kd_ppat_a = $arr_data['kd-ppat'];
if($kd_ppat_a != ""){
	list($kd_ppat,$nm_ppat) = explode(".",$arr_data['kd-ppat']);
	
}
$kd_transaksi = $arr_data['kd-transaksi'];
$nm_wp = strtoupper($arr_data['nm-wp']);
$kode_kecamatan_a= $arr_data['kd-kecamatan'];
if($kode_kecamatan_a != ""){
	list($kode_kecamatan,$nm_kecamatan) = explode(".",$kode_kecamatan_a);
}
$kode_kelurahan_a= pg_escape_string($arr_data['kd-kelurahan']);
if($kode_kelurahan_a != ""){
	list($kode_kelurahan,$nm_kelurahan) = explode(".",$kode_kelurahan_a);
}

$num_results = pg_query("SELECT COUNT(*) AS jumlah FROM $schema.pelayanan A INNER JOIN public.wp B ON B.nik=A.nik WHERE B.kd_provinsi LIKE '%$kd_prop%' AND B.kd_kota LIKE '%$kd_kota%' AND B.kd_jns like '%$kd_jns%' AND B.no_reg LIKE '%$no_reg_wp%' AND A.kd_propinsi LIKE '%$kd_prov%' AND A.kd_dati2 LIKE '%$kd_dati2%' AND A.kd_kecamatan LIKE '%$kd_kec%' AND A.kd_kelurahan LIKE '%$kd_kel%' AND A.kd_blok LIKE '%$kd_blok%' AND A.no_urut LIKE '%$no_urut%' AND A.kd_jns_op LIKE '%$kd_jns_op%' AND B.nama LIKE '%$nm_wp%'  AND A.kd_kecamatan LIKE '%$kode_kecamatan%' AND A.kd_kelurahan LIKE '%$kode_kelurahan%' AND A.id_transaksi LIKE '%$kd_transaksi%' AND CAST(A.id_ppat AS TEXT) like '$kd_ppat%'") or die('Query failed: ' . pg_last_error());
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
$kd_ppat_b = (string)$kd_ppat;

$query = pg_query("SELECT B.kd_provinsi||'.'||B.kd_kota||'.'||B.kd_jns||'.'||B.no_reg AS npwpd, A.kd_propinsi||'.'||A.kd_dati2||'.'||A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_blok||'.'||A.no_urut||'.'||A.kd_jns_op AS nop,A.tahun||'.'||A.no_urut_p AS no_pelayanan,C.nm_transaksi,D.nama_ppat,A.*,B.* FROM $schema.pelayanan A INNER JOIN public.wp B ON B.nik=A.nik INNER JOIN public.TRANSAKSI C ON A.id_transaksi=C.id INNER JOIN public.PPAT D ON A.id_ppat=D.id WHERE B.kd_provinsi LIKE '%$kd_prop%' AND B.kd_kota LIKE '%$kd_kota%' AND B.kd_jns like '%$kd_jns%' AND B.no_reg LIKE '%$no_reg_wp%' AND A.kd_propinsi LIKE '%$kd_prov%' AND A.kd_dati2 LIKE '%$kd_dati2%' AND A.kd_kecamatan LIKE '%$kd_kec%' AND A.kd_kelurahan LIKE '%$kd_kel%' AND A.kd_blok LIKE '%$kd_blok%' AND A.no_urut LIKE '%$no_urut%' AND A.kd_jns_op LIKE '%$kd_jns_op%' AND B.nama LIKE '%$nm_wp%' AND A.kd_kecamatan LIKE '%$kode_kecamatan%' AND A.kd_kelurahan LIKE '%$kode_kelurahan%' AND A.id_transaksi LIKE '%$kd_transaksi%' AND CAST(A.id_ppat AS TEXT) like '$kd_ppat%' LIMIT $num_per_page OFFSET $offset") or die('Query failed: ' . pg_last_error());
?>
<script language="javascript">
$(document).ready(function () {
	$('#cetak-list').click(function(){		
			var inputs = $('#filter-data').serializeArray();
			$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
			$.post( "modul/bphtb/gen_pdf_list.php", { data: inputs, ukuran: $('#ukuran-kertas').val() })
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
    		window.open('/print/list_bphtb_xls.php');
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
                                                <th>NOP </th>
                                                <th>No Pelayanan </th>
                                                <th>Nama WP </th>
												<th>Alamat OP </th>
												<th>Jns Transaksi </th>
												<th>Tgl Terdaftar </th>
												<th>Harga Transaksi </th>
												<th>Pokok Pajak </th>
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
                                                <td class=" "><?php echo $row['nop']; ?></td>
                                                <td class=" "><?php echo $row['no_pelayanan']; ?></td>
												<td class=" "><?php echo $row['nama']; ?></td>
												<td class=" "><?php echo $row['alamat_op']; ?></td>
												<td class=" "><?php echo $row['nm_transaksi']; ?></td>
												<td class=" "><?php echo $row['tgl_verifikasi']; ?></td>
												<td class=" "><?php echo number_format($row['harga_trk']); ?></td>
												<td class=" "><?php echo number_format($row['pokok_pajak']); ?></td>
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
