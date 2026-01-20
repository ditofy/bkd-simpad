<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
include_once $_SESSION['base_dir']."inc/db.orcl.inc.php";
$data = $_REQUEST['postdata'];
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = strtoupper(pg_escape_string($$value["name"]));
}
$nm_wp = strtoupper(pg_escape_string($arr_data['nm-wp']));


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

$cek_njoptkp = oci_parse($conn,"SELECT B.KD_PROPINSI||'.'||B.KD_DATI2||'.'||B.KD_KECAMATAN||'.'||B.KD_KELURAHAN||'.'||B.KD_BLOK||'-'||B.NO_URUT||'.'||B.KD_JNS_OP AS NOP, A.SUBJEK_PAJAK_ID, A.JALAN_WP||' '||A.BLOK_KAV_NO_WP||' '||A.RW_WP||''||A.RT_WP||' '||A.KELURAHAN_WP||' '||A.KOTA_WP AS ALAMAT,A.NM_WP,A.TELP_WP FROM PBB.DAT_SUBJEK_PAJAK A 
INNER JOIN PBB.DAT_OBJEK_PAJAK B ON
A.SUBJEK_PAJAK_ID= B.SUBJEK_PAJAK_ID 
WHERE NM_WP LIKE '%$nm_wp%'
ORDER BY A.NM_WP ASC");

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
												<th>ALAMAT WP </th>
												<th>TELPON</th>                                     
                                            </tr>
                                        </thead>
										<tbody>
<?php
oci_execute($cek_njoptkp);
$jum_njoptkp=oci_num_rows($cek_njoptkp);
$no = $offset;
//if($jum_njoptkp > 0)
//{
	while ($hasil_cek_njoptkp = oci_fetch_array($cek_njoptkp, OCI_ASSOC))
	{
	$no++;

?>                                        
                                            <tr class="even pointer">
                                                <td class=" "><?php echo $no; ?></td>
                                               <td class=" "><?php echo $hasil_cek_njoptkp['NOP']; ?></td> 
												<td class=" "><?php echo $hasil_cek_njoptkp['SUBJEK_PAJAK_ID']; ?></td>
                                                <td class=" "><?php echo $hasil_cek_njoptkp['NM_WP']; ?></td>
												<td class=" "><?php echo $hasil_cek_njoptkp['ALAMAT']; ?></td>
                                              <td class=" "><?php echo $hasil_cek_njoptkp['TELP_WP']; ?></td>                                      
                                            </tr>
											
<?php
	}
//}
?>
											

                                            
                                        </tbody>										
</table>

