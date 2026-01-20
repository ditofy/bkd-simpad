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
<script>
function load_list_menu() {
$.ajax({
                	type: "GET",
                	url: "modul/referensi/list_menu.php",
                	data: '',
               		success: function(data){
						$('#list-menu').hide();
						$('#list-menu').html(data);	
						$('#list-menu').fadeIn("fast");
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#list-menu').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						$('#list-menu').fadeIn("fast");
      				}
            	});
}

function load_list_menu_induk() {
$.ajax({
                	type: "GET",
                	url: "modul/referensi/list_menu_induk.php",
                	data: '',
               		success: function(data){
						$('#menu-induk').html('');
						$('#menu-induk').html(data);
						$("#menu-induk").select2("val", "");	
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#menu-induk').html(ajaxOptions+' '+xhr.status+' : '+thrownError);					
      				}
            	});
}
$(document).ready(function () {
	$(".select2_single").select2({
                    placeholder: "Pilih                      ",
                    allowClear: false
                });
	$( "#tipe" ).change(function() {
  		if ($( "#tipe" ).val() == 'Sub Menu') {
			$("#menu-induk").removeAttr('disabled');
		} else {
			$("#menu-induk").select2("val", "");
			$("#menu-induk").attr("disabled", "disabled");
		}
	});
	
	$('#simpan-menu').click(function(){
		if($('#tipe').val() == 'Sub Menu'){
			if($('#menu-induk').val() == null){
				notif('Notifikasi','Pilih Menu Induk','error');
				return;
			}
		}
		if($('#nama-menu').val() == '' ){
			notif('Notifikasi','Isi Nama Menu','error');
		} else {
			$("#simpan-menu").attr("disabled", "disabled");
			var input_frm_menu = $('#frm-menu').serializeArray();
			$.post( "modul/referensi/p_tambah_menu.php", { postdata: input_frm_menu })
  						.done(function( data ) {
   							notif('Notifikasi',data,'success');
							$('#batal').click();
							$("#frm-menu")[0].reset();
							$("#menu-induk").select2("val", "");
							$("#menu-induk").attr("disabled", "disabled");
							$("#tipe").select2("val", "Menu");
							$("#simpan-menu").removeAttr('disabled');
							load_list_menu_induk();
							load_list_menu();							
  					});
		}
	});
	
	$("#menu-induk").select2("val", "");
	$("#menu-induk").attr("disabled", "disabled");
	load_list_menu_induk();
	load_list_menu();
});
</script>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">Tambah Menu</button>

                                <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">                                               
                                                <h4 class="modal-title" id="myModalLabel">Tambah Menu</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form id="frm-menu" name="frm-menu" data-parsley-validate class="form-horizontal form-label-left">
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tipe Menu
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
        	<select name="tipe" id="tipe" class="select2_single" style="width:200px">
				<option value="Menu">Menu</option>
				<option value="Sub Menu">Sub Menu</option>
			</select>
        </div>
    </div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Menu Induk
        </label>
       	<div class="col-md-6 col-sm-6 col-xs-12" id="list-menu-induk">
			<select name="menu-induk" id="menu-induk" class="select2_single" style="width:200px">
			</select>
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Menu
		</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="nama-menu" name="nama-menu" required="required" class="form-control col-md-7 col-xs-12" placeholder="Nama Menu">
        </div>
	</div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Link
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="link" name="link" required="required" class="form-control col-md-7 col-xs-12" placeholder="Link">
        </div>
	</div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Class
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="class" name="class" required="required" class="form-control col-md-7 col-xs-12" placeholder="Class">
        </div>
	</div>
</form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Batal</button>
                                                <button type="button" class="btn btn-primary" id="simpan-menu">Simpan</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>

<div class="x_panel" id="list-menu">

</div>