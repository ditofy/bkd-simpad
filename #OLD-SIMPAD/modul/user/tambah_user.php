<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
if ($_SESSION['level'] != '0')
{
	die('Hak Akses Diberhentikan');
}
?>
<script>
$(document).ready(function () {
                $(".select2_single").select2({
                    placeholder: "Pilih                      ",
                    allowClear: false
                });
                $(".select2_group").select2({});
                $(".select2_multiple").select2({
                    maximumSelectionLength: 4,
                    placeholder: "With Max Selection limit 4",
                    allowClear: true
                });
            });
			
$('#proses-tambah-user').click(function(){
if($('#user-name').val() == '' || $('#password').val() == '' || $('#nama').val() == '' || $('#nip').val() == '' || $('#jabatan').val() == ''){
	notif('Notifikasi','Isi Semua Data','error');			
} else {	
				var inputs = $('#frm-tambah-user').serializeArray();	
				$("#proses-tambah-user").attr("disabled", "disabled");
				$.post( "modul/user/p_tambah_user.php", { postdata: inputs })
  				.done(function( data ) {
				if(data == 'OK'){
					document.getElementById("frm-tambah-user").reset();		
   					notif('Notifikasi','User berhasil di tambah','success');
					$("#proses-tambah-user").removeAttr('disabled');
				} else {
					notif('Notifikasi',data,'error');
					$("#proses-tambah-user").removeAttr('disabled');
				}
  			}); 			
}
  		
});
</script>
<form id="frm-tambah-user" name="frm-tambah-user" data-parsley-validate class="form-horizontal form-label-left">
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">User Name
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="user-name" name="user-name" class="form-control col-md-7 col-xs-12" placeholder="User Name">
    	</div>
    </div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Password
        </label>
       	<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="password" id="password" name="password" required="required" class="form-control col-md-7 col-xs-12" placeholder="Password">
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama
		</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="nama" name="nama" required="required" class="form-control col-md-7 col-xs-12" placeholder="Nama Lengkap">
        </div>
	</div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">N I P
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="nip" name="nip" required="required" class="form-control col-md-7 col-xs-12" placeholder="NIP">
        </div>
	</div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jabatan
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="jabatan" name="jabatan" required="required" class="form-control col-md-7 col-xs-12" placeholder="Jabatan">
        </div>
	</div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Level</label>
       <div class="col-md-6 col-sm-6 col-xs-12">
        	<select name="level" id="level" class="select2_single" style="width:200px">
				<option value="1 - User">1 - User</option>
				<option value="0 - Administrators">0 - Administrators</option>
			</select>
        </div>
	</div>
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-tambah-user" type="button" class="btn btn-success">Tambah</button>
        </div>
    </div>
</form>