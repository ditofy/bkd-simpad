<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
?>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">REALISASI PBB</h4>
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
				<h2>Catatan Pembayaran</h2>
			<div class="x_title">
			</div>
				<div class="form-group">
    				<div class="col-md-12 col-sm-6 col-xs-12">
					<label>
    					NOP : <input type="text" class="form-control" data-inputmask="'mask': '99.99.999.999.999.9999.9'" id="nop">
						<div class="checkbox">
                         	<label>
                            	<input type="checkbox" class="flat" id="kunci"> Kunci
                            </label>
                         </div>
						 <button id="proses" type="button" class="btn btn-success">Proses</button>
					</label>
						 
    				</div>
    			</div>
				
		<div class="col-md-12 col-sm-3 col-xs-12 bg-white" id="ctt-bayar">		
							 
        </div>
		</div>
	</div>
</div>
<?php
include_once $_SESSION['base_dir']."footer.php";
?>
</div>
<script src="js/input_mask/jquery.inputmask.js"></script>
<script>
        $(document).ready(function () {
			var kunci = "";
			$.fn.enterKey = function (fnc) {
    			return this.each(function () {
        		$(this).keypress(function (ev) {
            	var keycode = (ev.keyCode ? ev.keyCode : ev.which);
            	if (keycode == '13') {
                	fnc.call(this, ev);
            	}
        		})
    			})
			}
            $(":input").inputmask();
			$( "#nop" ).focus();
			$('#proses').click(function(){
				var str = $("#nop").val();
				var res = str.replace(/\_/g, "");
				if (res.length == 24) {		
					$('#ctt-bayar').html("<img src='images/loading.gif' />");		
					$.post( "modul/pbb/ajax_ctt_pembayaran.php", { nop: $('#nop').val() })
  						.done(function( data ) {
   							$('#ctt-bayar').html(data);
  						});
					$("#nop").val(kunci);
					$( "#nop" ).focus();
						
				} else {
					notif('Notifikasi','NOP BELUM LENGKAP','error');
					$( "#nop" ).focus();
				}
			});
			$("#nop").enterKey(function () {
    			$('#proses').click();
			});
			$('#kunci').click(function(){
				if (kunci == "") {
					kunci = $("#nop").val().replace(/\_/g, "");
					$( "#nop" ).focus();
					$('#nop').click();
				} else {
					kunci = "";
					$( "#nop" ).focus();
					$('#nop').click();
				}
			})
        });
</script>