<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
	
}
?>
<script src="js/input_mask/jquery.inputmask.js"></script>
<script>
$(document).ready(function () {
	$('#cetak').click(function(){
	
		$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");		
			$.post( "modul/bphtb/gen_pdf_sspd_bayar.php", { no_sspd: $('#no-sspd-bayar').val() })
  						.done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
  					});
	});	
	
	$('#frm-cetak-sspd-byr').submit(function() {
  		return false;
	});
	

				
	$("#no-sspd-bayar").inputmask();
		
	});	
	


</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">SSPD BPHTB</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<form id="frm-cetak-sspd-byr" name="frm-cetak-sspd-byr" data-parsley-validate class="form-horizontal form-label-left">
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No SSPD</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="no-sspd-bayar" name="no-sspd-bayar" class="form-control col-md-7 col-xs-12" placeholder="Nomor SSPD BPHTB" data-inputmask="'mask': '99.9999.99.99.9999'">
    </div>
</div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">        				
			<button id="cetak" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak SSPD</button>			
		</div>
	</div>
</form>
<div class="ln_solid"></div>
