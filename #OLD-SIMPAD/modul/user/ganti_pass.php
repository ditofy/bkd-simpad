<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
?>
<script>
$('#proses-ganti-pass').click(function(){
if($('#pwd-lama').val() == '' || $('#pwd-baru').val() == '' || $('#pwd-baru1').val() == ''){
	notif('Notifikasi','Isi Semua Data','error');			
} else {
	if($('#pwd-baru').val() !=  $('#pwd-baru1').val()){
		notif('Notifikasi','Password Baru Tidak Sama','error');	
	} else {
				var inputs = $('#frm-ganti-pass').serializeArray();	
				$("#proses-ganti-pass").attr("disabled", "disabled");
				$.post( "modul/user/p_ganti_pass.php", { postdata: inputs })
  				.done(function( data ) {
				if(data == 'OK'){
					document.getElementById("frm-ganti-pass").reset();		
   					notif('Notifikasi','Password berhasil di ganti','success');
					$("#proses-ganti-pass").removeAttr('disabled');
				} else {
					notif('Notifikasi',data,'error');
					$("#proses-ganti-pass").removeAttr('disabled');
				}
  			}); 
			
	}		
			
}
  		
});
</script>
<div class="">
<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			
				<h2>Ganti Password User</h2>
			<div class="x_title">
			</div>
			<form id="frm-ganti-pass" name="frm-ganti-pass" data-parsley-validate class="form-horizontal form-label-left">
			<div class="form-group">
            	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">User Name
            	</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                	<input type="text" id="user-name" name="user-name" disabled="disabled" class="form-control col-md-7 col-xs-12" value=<?php echo $_SESSION['user']; ?>>
                </div>
            </div>
			<div class="form-group">
            	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Password Lama
            	</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                	<input type="password" id="pwd-lama" name="pwd-lama" required="required" class="form-control col-md-7 col-xs-12" placeholder="Password Lama">
                </div>
            </div>
			<div class="form-group">
            	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Password Baru
            	</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                	<input type="password" id="pwd-baru" name="pwd-baru" required="required" class="form-control col-md-7 col-xs-12" placeholder="Password Baru">
                </div>
            </div>
			<div class="form-group">
            	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Password Baru
            	</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                	<input type="password" id="pwd-baru1" name="pwd-baru1" required="required" class="form-control col-md-7 col-xs-12" placeholder="Password Baru">
                </div>
            </div>
			<div class="ln_solid"></div>
			<div class="form-group">
            	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
            		<button id="proses-ganti-pass" type="button" class="btn btn-success">Ganti</button>
            	</div>
            <div>
			</form>
		</div>
	</div>
</div>
<?php
include_once $_SESSION['base_dir']."footer.php";
?>
</div>
