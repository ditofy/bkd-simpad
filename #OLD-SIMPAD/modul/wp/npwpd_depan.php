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
							
			$('#reset').click(function(){
				$("#npwpd").removeAttr('readonly');
			});
			
			$('#npwpd').autocomplete({
      			source: "inc/get_npwpd.php",
				select: function(event, ui) {
            		if (ui.item.value != 'Data Tidak Ada')  {
					$("#npwpd").attr("readonly", "readonly");
				$.ajax({
                	type: "GET",
                	url: "inc/get_detail_wp.php",
                	data: 'search='+ui.item.value,
               		success: function(data){
						var obj = jQuery.parseJSON(data);
						$('#nik').val(obj[0].nik);
						$('#nama-wp').val(obj[0].nama);
						$('#alamat').val(obj[0].alamat);
						$('#no-telp').val(obj[0].telp);
						$('#tgl-daftar').val(obj[0].tgl_daf);
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						notif('Notifikasi',ajaxOptions+' '+xhr.status+' : '+thrownError,'error');
      				}
            	});
			} 	
				}
    		});
				
            });

$('#proses-cetak-wp').click(function(){
	if($('#npwpd').val() == ''){
		$('#modal-cnt').html("NPWPD Tidak Ditemukan");			
	} else {
		$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");		
			$.post( "modul/wp/gen_pdf_npwpd.php", { npwpd: $('#npwpd').val(), hal: "detail" })
  						.done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
		}); 
	/*$.post('inc/p_cetak_npwpd.php', { npwpd: $('#npwpd').val(), hal: "depan" }, function(response){
    		if (!response.status) {
        		notif('Notifikasi','Error calling save','error');
        		return;
    		}
    		if (response.status != 'OK') {
        		notif('Notifikasi',response.status,'error');
        		return;
    		}
    		window.open('cetak_npwpd.php');
		});		*/
			
	}
  		
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
			<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NPWPD
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="npwpd" name="npwpd" class="form-control col-md-7 col-xs-12" placeholder="Nomor Pokok Wajib Pajak">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NIK
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nik" name="nik" readonly="readonly" class="form-control col-md-7 col-xs-12" placeholder="Nomor Induk Kependudukan">
    	</div>
    </div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama WP
        </label>
       	<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="nama-wp" readonly="readonly" name="nama-wp" required="required" class="form-control col-md-7 col-xs-12" placeholder="Nama Wajib Pajak">
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat
		</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<textarea id="alamat" required="required" readonly="readonly" name="alamat" class="form-control col-md-7 col-xs-12" placeholder="Alamat Lengkap"></textarea>
        </div>
	</div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No Telp / HP
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="no-telp" name="no-telp" readonly="readonly" required="required" class="form-control col-md-7 col-xs-12" placeholder="Nomor Telephone / HP">
        </div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal Pendaftaran</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tgl-daftar" name="tgl-daftar" readonly="readonly" class="form-control has-feedback-left" placeholder="Tanggal Pendaftaran" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	
	</div>
	
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-cetak-wp" type="button" class="btn btn-success" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak</button>
			<button id="reset" type="reset" class="btn btn-primary">Reset</button>
        </div>
    </div>
</form>
