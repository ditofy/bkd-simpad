<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
?>
<script src="js/input_mask/jquery.inputmask.js"></script>
<script>
        $(document).ready(function () {
		
		$('#proses').click(function(){
			$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
			$.post( "modul/pbb/gen_pdf_sknjop.php", { nop: $('#nop').val(), id_pln: $('#id-pln').val() })
  						.done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
  					});
				});
		
		
		
		
		
            $(":input").inputmask();
			$( "#nop" ).focus();
			
			
			
			$("#nop").enterKey(function () {
    			$('#proses').click();
			});
			
        });
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">SK NJOP</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<div class="">
<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
				<h2>Cetak SK NJOP PBB</h2>
			<div class="x_title">
			</div>
				<div class="form-group">
    				<div class="col-md-12 col-sm-6 col-xs-12">
					<label>
    					NOP : <input type="text" class="form-control" data-inputmask="'mask': '99.99.999.999.999.9999.9'" id="nop">
						<br/>	<br/>	
						ID PLN : <input type="text" class="form-control" id="id-pln">
						<br/>	<br/>	
							<button id="proses" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak SK NJOP</button>
					</label>
						 
    				</div>
    			</div>
				
		<div class="col-md-12 col-sm-3 col-xs-12 bg-white" id="ctk-sk">		
							 
        </div>
		</div>
	</div>
</div>
<?php
include_once $_SESSION['base_dir']."footer.php";
?>
</div>
