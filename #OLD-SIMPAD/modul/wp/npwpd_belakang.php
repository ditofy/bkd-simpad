<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
?>
<script>
$(document).ready(function () {

$('#proses-cetak-wp-blk').click(function(){
	$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");		
			$.post( "modul/wp/gen_pdf_npwpd.php", { npwpd: "", hal: "belakang" })
  						.done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
		});						
  		
});
$('#proses-cetak-wp-dpn').click(function(){
	$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");		
			$.post( "modul/wp/gen_pdf_npwpd.php", { npwpd: "", hal: "depan" })
  						.done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
		});						
  		
});
});
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">NPWPD</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<form id="frm-update-wp" name="frm-update-wp" data-parsley-validate class="form-horizontal form-label-left">
	<div class="ln_solid"></div>	
	<div class="form-group">
        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">            
        	<button id="proses-cetak-wp-dpn" type="button" class="btn btn-success" data-toggle="modal" data-target=".bs-example-modal-lg">Depan</button>
        </div>    
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">            
        	<button id="proses-cetak-wp-blk" type="button" class="btn btn-success" data-toggle="modal" data-target=".bs-example-modal-lg">Belakang</button>
        </div>
    </div>
</form>
