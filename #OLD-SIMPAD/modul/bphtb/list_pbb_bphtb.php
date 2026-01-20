<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
$data = $_REQUEST['postdata'];
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = strtoupper(pg_escape_string($$value["name"]));
}
$nm_wp = strtoupper($arr_data['nm-wp']);


/*$cek_njoptkp = oci_parse($conn,"SELECT A.KD_PROPINSI||'.'||A.KD_DATI2||'.'||A.KD_KECAMATAN||'.'||A.KD_KELURAHAN||'.'||A.KD_BLOK||'-'||A.NO_URUT||'.'||A.KD_JNS_OP AS NOP,C.SUBJEK_PAJAK_ID, A.NM_WP_SPPT,A.JALAN_WP||''||A.BLOK_KAV_NO_WP||''||A.RW_WP||''||A.RT_WP||''||A.KELURAHAN_WP||''||A.KOTA_WP AS ALAMAT,A.NJOPTKP_SPPT FROM PBB.SPPT A 
INNER JOIN PBB.DAT_OBJEK_PAJAK C ON 
A.KD_PROPINSI=C.KD_PROPINSI and
A.KD_DATI2=C.KD_DATI2 and 
A.KD_KECAMATAN=C.KD_KECAMATAN and 
A.KD_KELURAHAN=C.KD_KELURAHAN and 
A.kd_blok=C.kd_blok and 
A.no_urut=C.no_urut and
A.kd_jns_op=C.kd_jns_op 
WHERE NM_WP_SPPT = '$nm_wp' AND THN_PAJAK_SPPT='2024' AND STATUS_PEMBAYARAN_SPPT < '2'
ORDER BY NOP ASC");*/

$sql = "SELECT A.kd_propinsi||'.'||A.kd_dati2||'.'||A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_blok||'.'||A.no_urut||'.'||A.kd_jns_op AS nop,A.nik,B.nama,A.nama_sppt,B.alamat,B.telp,B.kelurahan,B.kecamatan,B.kota FROM BPHTB.SSPD A 
	INNER JOIN PUBLIC.WP B ON
	A.NIK=B.NIK
	WHERE A.nama_sppt LIKE '%$nm_wp%'";
	$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
	//$arr_det_wp = array();
    $row_wp = pg_fetch_array($tampil);

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
												<th>NOP </th> 
                                                <th>NIK</th>
                                                <th>NAMA WP </th>
												 <th>NAMA SPPT </th>
												<th>ALAMAT WP </th>
												<th>TELPON</th>                                     
                                            </tr>
                                        </thead>
										<tbody>
<?php

//if($jum_njoptkp > 0)
//{
	while ($row = pg_fetch_array($tampil))
	{
	$no++;

?>                                        
                                            <tr class="even pointer">
                                                <td class=" "><?php echo $no; ?></td>
                                              <td class=" "><?php echo $row['nop']; ?></td>
												<td class=" "><?php echo $row['nik']; ?></td>
                                                <td class=" "><?php echo $row['nama']; ?></td>
												<td class="" bgcolor="#FFFF00"><?php echo $row['nama_sppt']; ?></td>
												<td class=" "><?php echo $row['alamat']; ?></td>
                                              <td class=" "><?php echo $row['telp']; ?></td>                                      
                                            </tr>
											
<?php
	}
//}
?>
											

                                            
                                        </tbody>										
</table>

