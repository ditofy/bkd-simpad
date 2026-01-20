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
			$.post( "modul/skpdkb/gen_pdf_skpdkb.php", { no_skpdkb: $('#no-skpdkb').val() })
  						.done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
  					});
	});	
	
	$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });
	
	$('#frm-cetak-skpdkb').submit(function() {
  		return false;
	});
	
	$("#no-skpdkb").inputmask();

});
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">SKPDKB</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<form id="frm-cetak-skpdkb" name="frm-cetak-skpdkb" data-parsley-validate class="form-horizontal form-label-left">
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No SKPDKB</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="no-skpdkb" name="no-skpdkb" class="form-control col-md-7 col-xs-12" placeholder="Nomor SKPDKB" data-inputmask="'mask': '99.9999.99.99.9999'">
    </div>
</div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">        				
			<button id="cetak" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak SKPDKB</button>			
		</div>
	</div>
</form>
<div class="ln_solid"></div>

