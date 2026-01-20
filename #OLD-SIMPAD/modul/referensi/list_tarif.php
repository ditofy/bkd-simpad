<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
if ($_SESSION['level'] != '0')
{
	die('Hak Akses Diberhentikan');
}
?>
<script src="js/jquery.numeric.min.js"></script>
<script>
function load_list_tarif_child() {
if ($( "#kd_obj" ).val() != null) {
			$.ajax({
                	type: "GET",
                	url: "modul/referensi/list_tarif_child.php",
                	data: 'kd_obj='+$( "#kd_obj" ).val(),
               		success: function(data){
						$('#list-tarif-child').hide();
						$('#list-tarif-child').html(data);	
						$('#list-tarif-child').fadeIn("fast");
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#list-tarif-child').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						$('#list-tarif-child').fadeIn("fast");
      				}
            	});	
		}
}
$(document).ready(function () {
	$('#simpan-tarif').click(function(){
		if ($( "#nama-tarif" ).val() != "" && $( "#tarif" ).val() != "") {
			$("#simpan-tarif").attr("disabled", "disabled");
			var input_frm_tarif = $('#frm-tarif').serializeArray();
			$.post( "modul/referensi/p_tambah_tarif.php", { postdata: input_frm_tarif, kd_obj: $( "#kd_obj" ).val() })
  						.done(function( data ) {
   							notif('Notifikasi',data,'success');	
							$( "#cancel" ).click();						
							$( "#nama-tarif" ).val('');
							$( "#tarif" ).val('');						
							$("#simpan-tarif").removeAttr('disabled');
							load_list_tarif_child()												
  					});
			
			
		} else {
			notif('Notifikasi','Isi Semua Data','error');
		}
	});
	$("#tarif").numeric();
	load_list_tarif_child()
});
</script>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">Tambah Tarif</button>

                                <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modal-tarif">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">                                               
                                                <h4 class="modal-title" id="myModalLabel">Tambah Tarif</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form id="frm-tarif" name="frm-tarif" data-parsley-validate class="form-horizontal form-label-left">
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Tarif
		</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="nama-tarif" name="nama-tarif" required="required" class="form-control col-md-7 col-xs-12" placeholder="Nama Tarif">
        </div>
	</div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tarif
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tarif" name="tarif" required="required" class="form-control col-md-7 col-xs-12" placeholder="Tarif">
        </div>
	</div>
</form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal" id="cancel">Batal</button>
                                                <button type="button" class="btn btn-primary" id="simpan-tarif">Simpan</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
	
<div id="list-tarif-child">
</div>