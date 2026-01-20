<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	die('silahkan login');
	exit;
}
?>
<script src="js/input_mask/jquery.inputmask.js"></script>
<script type="text/javascript">
function list_data(pg) {
	$('#list-datbil').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");	
	var inputs = $('#filter-data').serializeArray();	
			$.post( "modul/pelaporan/list_penyetoran_bill.php", { postdata: inputs, page: pg })
  						.done(function( data1 ) {
							$('#list-datbil').html(data1);
  					});
};
$(document).ready(function () {
	$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });
				
	$('#resetfrm').click(function(event) {
		event.preventDefault();
		$('#filter-data')[0].reset();		
	});
	
	$('#tampilkan').click(function() {		
			if($("#nop").val() != null) {
			list_data(0);
		} else {
			notif('Notifikasi','Silahkan Isi NOP','error');
		}						
	});
	
	$("#nop").inputmask();
	$("#npwpd").inputmask();		
});
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">DATA BILL</h4>
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
    
    <td style="vertical-align:middle" align="right">NOP</td>
    <td><input type="text" id="nop" name="nop" class="form-control col-md-7 col-xs-12" placeholder="Nomor Objek Pajak" data-inputmask="'mask': '99.99.99.99.9999'"></td>
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
  </tr>
  <tr>
    
    
    <td colspan="6" style="vertical-align:middle" align="right"> </td>
    <td></td>
  </tr>  
  <tr>
    <td colspan="6" align="center" style="vertical-align:middle">
	<button id="tampilkan" type="button" class="btn btn-success">Tampilkan</button>
	<button id="resetfrm" type="reset" class="btn btn-primary">Reset</button>
	</td>
    </tr>
</table>
</form>
</div>
<div id="list-datbil">

</div>