<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
?>
<link type="text/css" href="css/jquery-ui.css" rel="stylesheet" />
<script src="js/jquery-ui.js"></script>
<script>
$(document).ready(function () {
	$('#nop').autocomplete({
      			source: "modul/air_tanah/get_nop.php",
				select: function(event, ui) {
            		if (ui.item.value != 'Data Tidak Ada')  {
					$("#nop").attr("readonly", "readonly");					
				$.ajax({
                	type: "GET",
                	url: "modul/air_tanah/get_detail_objek.php",
                	data: 'search='+ui.item.value,
               		success: function(data){			
						var obj = jQuery.parseJSON(data);									
						$('#nama-wp').val(obj[0].nama);
						$('#alamat').val(obj[0].alamat);						
						$('#nama-usaha').val(obj[0].nm_usaha);
						$('#alamat-usaha').val(obj[0].alamat_usaha);
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						notif('Notifikasi',ajaxOptions+' '+xhr.status+' : '+thrownError,'error');
      				}
            	});
			} 	
				}
    		});
	$('#resetfrm').click(function(){				
				$('#frm-hapus-nop-abt')[0].reset();
				$("#nop").removeAttr('readonly');				
			});
	$('#proses-hapus-nop-abt').click(function(){
if($('#nop').val() == ''){
	notif('Notifikasi','Isi NOP','error');			
} else {
	$("#proses-hapus-nop-abt").attr("disabled", "disabled");
	var inputs = $('#frm-hapus-nop-abt').serializeArray();
					$.post( "modul/air_tanah/p_hapus_nop.php", { postdata: inputs })
  						.done(function( data1 ) {
   							notif('Notifikasi',data1,'success');
							$('#resetfrm').click();							
							$("#proses-hapus-nop-abt").removeAttr('disabled');
  					});
}	
});
});
</script>
<form id="frm-hapus-nop-abt" name="frm-hapus-nop-abt" data-parsley-validate class="form-horizontal form-label-left">
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NOP
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="nop" name="nop" class="form-control col-md-7 col-xs-12" placeholder="Nomor Objek Pajak">
    </div>
</div>
<div class="ln_solid"></div>
<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama WP
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-wp" name="nama-wp" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Nama Wajib Pajak">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat
		</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="alamat" name="alamat" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Alamat Wajib Pajak">
        </div>
	</div>
<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Usaha
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-usaha" name="nama-usaha" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Nama Usaha">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat Usaha
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="alamat-usaha" name="alamat-usaha" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Alamat Usaha">
    	</div>
    </div>
<div class="ln_solid"></div>
<div class="form-group">
    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
       	<button id="proses-hapus-nop-abt" type="button" class="btn btn-success">Hapus</button>
		<button id="resetfrm" type="button" class="btn btn-primary">Reset</button>
    </div>
</div>	
</form>