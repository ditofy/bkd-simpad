<link href="css/datatables/tools/css/dataTables.tableTools.css" rel="stylesheet">
<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}

include $_SESSION['base_dir']."inc/db.orcl.inc.php";
include $_SESSION['base_dir']."inc/db.inc.php";
$data = $_REQUEST['postdata'];
$page = pg_escape_string($_REQUEST['page']);

$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = strtoupper(pg_escape_string($$value["name"]));
}
$kd_kecamatan = $arr_data['kd-kecamatan'];
$kd_kelurahan = $arr_data['kd-kelurahan'];

$q_kel = "SELECT kelurahan_op FROM bphtb.sspd WHERE kd_kecamatan='$kd_kecamatan' and kd_kelurahan='$kd_kelurahan' group by kelurahan_op";
$rs_kel=pg_query($q_kel);
$row_kel = pg_fetch_array($rs_kel);
// Perform the logic of the query
$query = pg_query("SELECT B.nama,A.kd_kecamatan,A.kd_kelurahan,A.kd_blok,A.no_urut,A.kd_jns_op,A.njop_bumi,A.njop_bng,A.luas_bumi_trk,A.luas_bng_trk, A.npop,A.kd_propinsi||'.'||A.kd_dati2||'.'||A.kd_kecamatan||'.'||A.kd_kelurahan||'.'||A.kd_blok||'.'||A.no_urut||'.'||A.kd_jns_op AS nop FROM bphtb.sspd A INNER JOIN public.wp B ON B.nik=A.nik WHERE A.kd_kecamatan='$kd_kecamatan' AND A.kd_kelurahan='$kd_kelurahan' AND A.status_pembayaran='1'") or die('Query failed: ' . pg_last_error());
?>
<script src="js/datatables/js/jquery.dataTables.js"></script>
<script src="js/datatables/tools/js/dataTables.tableTools.js"></script>
 <script language="javascript">
            $(document).ready(function () {
                
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
    		window.open('/print/list_njop_xls.php');
			});	
			});
            });

          
          
        </script>

<button id="download-xls" type="button" class="btn btn-dark">Download XLS</button>
<table id="t-detail-pembayaran" class="table table-striped responsive-utilities jambo_table" style="font-size:12px;">										
                                        <thead>
											<tr class="headings">
											 <td colspan="10" align="center"><h3>DATA NJOP BPHTB KELURAHAN  <?php echo $row_kel['kelurahan_op']; ?></h3></td>
											 </tr>
                                            <tr class="headings">  
											 	<th>NO </th>                                       
                                                <th>NOP </th>
                                                <th>Nama WP </th>
												<th>Luas Bumi / Bng</th>
												<th>NPOP </th> 
												<th>NJOP BUMI / M (BPHTB)</th>  
												<th>PERSEN</th>    
												<th>NJOP BUMI / M (PBB)</th> 
												<th>KODE ZNT</th>                                    	
                                            </tr>
                                        </thead>
										<tbody>
<?php
$no=0;
while ($row = pg_fetch_array($query))
	{
$no++;
$total_npop_pbb= $row['njop_bumi']+$row['njop_bng'];
if($row['njop_bng'] > 0){
$njop_b=round(($row['npop']-$total_npop_pbb)/$total_npop_pbb *100,0);
$njop_bm=$row['njop_bumi']/$row['luas_bumi_trk'];
$njop_c=($njop_bm*($njop_b/100))+$njop_bm;
}
else {
$njop_c=$row['npop']/$row['luas_bumi_trk'];
$njop_b=round(($row['npop']-$row['njop_bumi'])/$row['njop_bumi']*100,0);
}
$kd_kec=$row['kd_kecamatan'];
$kd_kel=$row['kd_kelurahan'];
$kd_blok=$row['kd_blok'];
$no_urut=$row['no_urut'];
$kd_jns_op=$row['kd_jns_op'];
///QUERY CARI DATA PBB ORACLE//
$stid = oci_parse($conn, "SELECT A.NJOP_BUMI_SPPT,A.LUAS_BUMI_SPPT,B.KD_ZNT FROM PBB.SPPT A LEFT JOIN PBB.DAT_OP_BUMI B ON
A.KD_PROPINSI=B.KD_PROPINSI and
A.KD_DATI2=B.KD_DATI2 and
A.KD_KECAMATAN=B.KD_KECAMATAN and
A.KD_KELURAHAN=B.KD_KELURAHAN and
A.KD_BLOK=B.KD_BLOK and
A.NO_URUT=B.NO_URUT
WHERE
A.KD_KECAMATAN = '$kd_kec' AND
A.KD_KELURAHAN = '$kd_kel' AND
A.KD_BLOK= '$kd_blok' AND
A.NO_URUT= '$no_urut' AND
A.KD_JNS_OP= '$kd_jns_op' AND
A.THN_PAJAK_SPPT='2023'
ORDER BY B.KD_ZNT ASC");
if (!$stid) {
    	 $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
	$r = oci_execute($stid);
	if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
while ($row_pbb = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
$njop_pbbb=$row_pbb['NJOP_BUMI_SPPT'];
$luas_pbbb=$row_pbb['LUAS_BUMI_SPPT'];
$njop_pb=$njop_pbbb/$luas_pbbb;



?>
<tr class="even pointer">
                                                <td class=" "><?php echo $no; ?></td>                                         
                                                <td class=" "><?php echo $row['nop']; ?></td>
                                                <td class=" "><?php echo $row['nama']; ?></td>
												<td class=" " align="center"><?php echo $row['luas_bumi_trk']; ?> / <?php echo $row['luas_bng_trk']; ?></td>
												<td class=" " align="right"><?php echo number_format($row['npop']); ?></td>
												<td class=" " align="right" bgcolor="#0099FF"><b><?php echo number_format($njop_c); ?></b></td>
												<td class=" " align="center"><?php echo $njop_b; ?>%</td>
												<td class=" " align="left" bgcolor="#00FF00"><b><?php echo  number_format($njop_pb); ?></b></td> 
												<td class=" " align="center"><?php echo $row_pbb['KD_ZNT']; ?></td>
                                            </tr>
<?php
}//TUTUP PBB
}

?>

</tr>
</tbody>

                                    </table>
                           
<?php  //end if
?>
