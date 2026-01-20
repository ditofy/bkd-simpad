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
$sub_total = 0;
$grand_total = 0;
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = strtoupper(pg_escape_string($$value["name"]));
}
$nop = strtoupper($arr_data['nop']);
list($kd_prov,$kd_dati2,$kd_kec,$kd_kel,$kd_blok,$no_urut,$kd_jns_op) = explode(".",$nop);

$nopel= strtoupper($arr_data['no-pel']);
list($thn_p,$no_urut_p) = explode(".",$nopel);

list($kd_prop,$kd_kota,$kd_jns,$no_reg_wp) = explode(".",$arr_data['npwpd']);
$kd_ppat_a = $arr_data['kd-ppat'];
if($kd_ppat_a != ""){
	list($kd_ppat,$nm_ppat) = explode(".",$arr_data['kd-ppat']);
	
}
$kd_transaksi = $arr_data['kd-transaksi'];
$nm_wp = strtoupper($arr_data['nm-wp']);
$kode_kecamatan_a= $arr_data['kd-kecamatan'];
$status_byr = $arr_data['status-byr'];
$tahun_pajak = $arr_data['tahun-pajak'];
$bulan_pajak = $arr_data['bln-pjk'];
$pokok_pajak = $arr_data['pokok-pajak'];
if($kode_kecamatan_a != ""){
	list($kode_kecamatan,$nm_kecamatan) = explode(".",$kode_kecamatan_a);
}
$kode_kelurahan_a= pg_escape_string($arr_data['kd-kelurahan']);
if($kode_kelurahan_a != ""){
	list($kode_kelurahan,$nm_kelurahan) = explode(".",$kode_kelurahan_a);
}

$num_results = pg_query("SELECT COUNT(*) AS jumlah ,SUM(A.pokok_pajak_real) AS g_total FROM $schema.sspd A INNER JOIN public.wp B ON B.nik=A.nik WHERE B.kd_provinsi LIKE '%$kd_prop%' AND B.kd_kota LIKE '%$kd_kota%' AND B.kd_jns like '%$kd_jns%' AND B.no_reg LIKE '%$no_reg_wp%' AND A.kd_propinsi LIKE '%$kd_prov%' AND A.kd_dati2 LIKE '%$kd_dati2%' AND A.kd_kecamatan LIKE '%$kd_kec%' AND A.kd_kelurahan LIKE '%$kd_kel%' AND A.kd_blok LIKE '%$kd_blok%' AND A.no_urut LIKE '%$no_urut%' AND A.kd_jns_op LIKE '%$kd_jns_op%' AND B.nama LIKE '%$nm_wp%'  AND A.kd_kecamatan LIKE '%$kode_kecamatan%' AND A.kd_kelurahan LIKE '%$kode_kelurahan%' AND A.id_transaksi LIKE '%$kd_transaksi%' AND CAST(A.id_ppat AS TEXT) like '$kd_ppat%' AND A.tahun_p LIKE '%$thn_p%' AND A.no_urut_p LIKE '%$no_urut_p%' AND A.status_pembayaran LIKE '%$status_byr%' AND A.bln_pajak LIKE '%$bulan_pajak%' AND A.thn_pajak='$tahun_pajak' AND CAST(A.pokok_pajak_real AS TEXT) LIKE '%$pokok_pajak%'") or die('Query failed: ' . pg_last_error());
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
$kd_ppat_b = (string)$kd_ppat;

$query = pg_query("SELECT A.jns_surat||'.'||A.thn_pajak||'.'||A.bln_pajak||'.'||A.kd_obj_pajak||'.'||A.no_urut_surat AS no_sspd,B.nik, B.alamat||' '||B.kelurahan||' '||B.kecamatan||' '||B.kota as alamat_wp,A.kd_propinsi||'.'||A.kd_dati2||'.'||A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_blok||'.'||A.no_urut||'.'||A.kd_jns_op AS nop,A.tahun_p||'.'||A.no_urut_p AS no_pelayanan,C.nm_transaksi,D.nama_ppat,E.tgl_bayar,A.*,B.* FROM $schema.sspd A INNER JOIN public.wp B ON B.nik=A.nik INNER JOIN public.TRANSAKSI C ON A.id_transaksi=C.id INNER JOIN public.PPAT D ON A.id_ppat=D.id LEFT JOIN bphtb.pembayaran E ON A.jns_surat=E.jns_surat AND A.thn_pajak=E.thn_pajak AND A.bln_pajak=E.bln_pajak AND A.kd_obj_pajak=E.kd_obj_pajak AND A.no_urut_surat=E.no_urut_surat WHERE B.kd_provinsi LIKE '%$kd_prop%' AND B.kd_kota LIKE '%$kd_kota%' AND B.kd_jns like '%$kd_jns%' AND B.no_reg LIKE '%$no_reg_wp%' AND A.kd_propinsi LIKE '%$kd_prov%' AND A.kd_dati2 LIKE '%$kd_dati2%' AND A.kd_kecamatan LIKE '%$kd_kec%' AND A.kd_kelurahan LIKE '%$kd_kel%' AND A.kd_blok LIKE '%$kd_blok%' AND A.no_urut LIKE '%$no_urut%' AND A.kd_jns_op LIKE '%$kd_jns_op%' AND B.nama LIKE '%$nm_wp%' AND A.kd_kecamatan LIKE '%$kode_kecamatan%' AND A.kd_kelurahan LIKE '%$kode_kelurahan%' AND A.id_transaksi LIKE '%$kd_transaksi%' AND CAST(A.id_ppat AS TEXT) like '$kd_ppat%' AND A.tahun_p LIKE '%$thn_p%' AND A.no_urut_p LIKE '%$no_urut_p%' AND A.status_pembayaran LIKE '%$status_byr%' AND A.bln_pajak LIKE '%$bulan_pajak%' AND A.thn_pajak='$tahun_pajak'   AND CAST(A.pokok_pajak_real AS TEXT) LIKE '%$pokok_pajak%' LIMIT $num_per_page OFFSET $offset") or die('Query failed: ' . pg_last_error());
?>
<script language="javascript">
$(document).ready(function () {
	$('#cetak-list').click(function(){		
			var inputs = $('#filter-data').serializeArray();
			$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
			$.post( "modul/bphtb/gen_pdf_list_bphtb.php", { data: inputs, ukuran: $('#ukuran-kertas').val() })
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
												<th>NO SSPD </th>
                                                <th>NIK</th>
                                                <th>NOP </th>
                                                <th>No Pelayanan </th>
                                                <th>Nama WP </th>
												<th>Alamat WP </th>
												<th>Alamat OP </th>
												<th>Jns Transaksi </th>
												<th>Harga Trk Awal </th>
												<th>Harga Trk Valid </th>
												<th>Pokok Pajak </th>
												<th>PPAT </th>
												<th>Tgl Bayar </th>                                              
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
	$sub_total = $sub_total + $row['pokok_pajak_real'];
?>                                        
                                            <tr class="even pointer">
                                                <td class=" "><?php echo $no; ?></td>
												<td class=" "><?php echo $row['no_sspd']; ?></td>
                                                <td class=" "><?php echo $row['nik']; ?></td>
                                                <td class=" "><?php echo $row['nop']; ?></td>
                                                <td class=" "><?php echo $row['no_pelayanan']; ?></td>
												<td class=" "><?php echo $row['nama']; ?></td>
												<td class=" "><?php echo $row['alamat_wp']; ?></td>
												<td class=" "><?php echo $row['alamat_op']; ?></td>
												<td class=" "><?php echo $row['nm_transaksi']; ?></td>
												<td class=" "><?php echo number_format($row['harga_trk_awal']); ?></td>
												<td class=" "><?php echo number_format($row['harga_trk']); ?></td>
												<td class=" "><?php echo number_format($row['pokok_pajak_real']); ?></td>
												<td class=" "><?php echo $row['nama_ppat']; ?></td>
												<td class=" "><?php echo $row['tgl_bayar']; ?></td>                                           
                                            </tr>
											
<?php
	}
}
?>
											<tr>
											<td colspan="10">Sub. Total</td>
											<td align="right" style="font-size:12px;"><?php echo number_format($sub_total); ?></td>
											<td></td>
											<td></td>
											</tr>
											<tr>
											<td colspan="10">Grand Total</td>
											<td align="right" style="font-size:12px;"><?php echo number_format($grand_total); ?></td>
											<td></td>
											<td></td>
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
