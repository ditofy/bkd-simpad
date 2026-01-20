<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
?>
<script src="js/input_mask/jquery.inputmask.js"></script>
<script type="text/javascript">
function list_data(pg) {
	$('#list-skpd-user').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");	
	var inputs = $('#filter-data').serializeArray();	
			$.post( "modul/pelaporan/list_skpd_u_user.php", { postdata: inputs, page: pg })
  						.done(function( data1 ) {
							$('#list-skpd-user').html(data1);
  					});
};
function get_keg_usaha(kd) {
	if(kd != ""){
		$.post( "modul/pelaporan/get_keg_usaha.php", { kd_obj: kd })
  						.done(function( data1 ) {
							$('#keg-usaha').html(data1);
							$("#keg-usaha").select2("val", "");
  					});
	}
};
$(document).ready(function () {
	$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });
				
	$('#resetfrm').click(function(event) {
		event.preventDefault();
		$('#filter-data')[0].reset();	
		$("#bln-pjk").select2("val", "");
		$("#pemungut").select2("val", "");
		$("#kd-obj-pajak").select2("val", "");
		
	});
	
	$('#tampilkan').click(function() {
		if($("#kd-obj-pajak").val() != null) {
			list_data(0);
		} else {
			notif('Notifikasi','Pilih Jenis Pajak','error');
		}
	});
	
		
	$("#bln-pjk").select2("val", "");
	$("#kd-obj-pajak").select2("val", "");
	$("#pemungut").select2("val", "");		
});
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">DATA SKPD</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<div class="x_content">
<form id="filter-data" name="filter-data">
	<table width="100%" border="0" class="table table-striped">
	<tr>
	<td colspan="6">Filter Data</td>
	</tr>
  
  <tr>
    <td style="vertical-align:middle" align="right">Pajak</td>
    <td><select name="kd-obj-pajak" id="kd-obj-pajak" class="select2_single" style="width:150px;" >
		<option value="01">Pajak ABT</option>
		<option value="02">Pajak Reklame</option>
		
		</select></td>
    <td style="vertical-align:middle" align="right">Tahun Pajak</td>
    <td><select name="tahun-pajak" id="tahun-pajak" class="select2_single" style="width:60px;">
    <?php 
  	$thn = date('Y')+1;
  while($thn >= '2017') {
  	if($thn == date('Y')) {
    	echo "<option selected=\"selected\" value=\"$thn\">$thn</option>";
	} else {
		echo "<option value=\"$thn\">$thn</option>";
	}
   	$thn--; 
   } 
   ?>
  </select></td>
    <td style="vertical-align:middle" align="right">Bulan Pajak</td>
    <td><select name="bln-pjk" id="bln-pjk" class="select2_single" style="width:100px">
			<option value="01">Januari</option>
			<option value="02">Februari</option>
			<option value="03">Maret</option>
			<option value="04">April</option>
			<option value="05">Mei</option>
			<option value="06">Juni</option>
			<option value="07">Juli</option>
			<option value="08">Agustus</option>
			<option value="09">September</option>
			<option value="10">Oktober</option>
			<option value="11">November</option>
			<option value="12">Desember</option>
		</select></td>
		<td style="vertical-align:middle" align="right">Pemungut</td><td><select name="pemungut" id="pemungut" class="select2_single" style="width:200px">
			<?php
$query = "SELECT nama,nip FROM public.user where pemungut='3' order by nama ASC";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());		
	while ($row = pg_fetch_array($result))
			{
				echo "<option value=\"".$row['nip']."\">".$row['nama']."</option>";	
			}
?>
		</select></td>
  </tr>
  
 
    <td colspan="8" align="center" style="vertical-align:middle">
	<button id="tampilkan" type="button" class="btn btn-success">Tampilkan</button>
	<button id="resetfrm" type="reset" class="btn btn-primary">Reset</button>
	</td>
    </tr>
</table>
</form>
</div>
<div id="list-skpd-user">

</div>