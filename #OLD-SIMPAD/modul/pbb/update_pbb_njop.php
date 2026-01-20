<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";

?>
<link type="text/css" href="css/jquery-ui.css" rel="stylesheet" />
<script src="js/jquery-ui.js"></script>
<script src="js/input_mask/jquery.inputmask.js"></script>
<script src="js/pemisahribuan.js" ></script>
<script>
function get_kel(kd) {
	if(kd != ""){
	$.ajax({
                	type: "GET",
                	url: "inc/get_kel.php",
                	data: 'kd='+kd,
               		success: function(data){
						$('#kd-kelurahan').html(data);
						$("#kd-kelurahan").select2("val", "");
                	}
            });	
	}		
};

$(document).ready(function () {
  $(".select2_single").select2({
                    placeholder: "Pilih ",
                    allowClear: false
                });
				
	$("#nop").inputmask();
	$("#no-pelayanan").inputmask();	
	
	
	
	
	
	
            
       $(".select2_group").select2({})
	   
       $(".select2_multiple").select2({
                    maximumSelectionLength: 4,
                    placeholder: "With Max Selection limit 4",
                    allowClear: true
                });
				
		
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
			
			$("#nop").enterKey(function () {
				var cek_char = $('#nop').val();
    			if (cek_char.indexOf('_') == -1) {
				/*	$("#nop").attr("readonly", "readonly"); */
   					$.post( "inc/get_data_nop_sppt.php", { nop: $('#nop').val() })
  						.done(function( data1 ) {							
							var obj = jQuery.parseJSON(data1);														
							if( obj.status == "ok") {
							            $("#nop").val(obj.nop);
										$("#nik-sppt").val(obj.nik);
										$("#nama-sppt").val(obj.nama_sppt);
										$("#alamat-wp-sppt").val(obj.alamat_wp_sppt);
										$("#letak-op").val(obj.letak_op);
										$("#nomor-op").val(obj.nomor_op);
										$("#rt-op").val(obj.rt_op);
										$("#rw-op").val(obj.rw_op);
										$("#nama-kel").val(obj.nama_kel);
										$("#nama-kec").val(obj.nama_kec);
										$("#njop-bumi").val(obj.njop_tanah);
										$("#njop-bangunan").val(obj.njop_bng);
										$("#luas-bumi").val(obj.luas_bumi);
										$("#luas-bangunan").val(obj.luas_bng);
										$("#nik-wp").val(obj.nik_wp);
										$("#nama-wp").val(obj.nama_wp);
										$("#alamat-wp").val(obj.alamat_wp);
										$("#kelurahan-wp").val(obj.kelurahan_wp);
										$("#kecamatan-wp").val(obj.kecamatan_wp);
										$("#kota-wp").val(obj.kota_wp);
										$("#no-telp-wp").val(obj.telp_wp);
										$("#no-bukti").focus();
									
									
								
							} else {
								/* $("#no-surat").removeAttr('readonly'); */
								notif('Notifikasi',obj.error_msg,'error');									
							}
							//$('#reset').click();														
  					});
				} else {
					notif('Notifikasi','No Surat Belum Lengkap','error');
				}
			});
			
			
			
			$('#resetfrm').click(function(){
				$("#nik").val("");
				$("#nama-wp").val("");
				
			});
			
			
			$('#proses-update-njop').click(function(){
			
			var lang = [];
			 $("input[name='syarat']:checked").each(function(){
            lang.push(this.value);
        });

	$("#proses-update-pbb-bphtb-nik").attr("disabled", "disabled");
	var inputs = $('#frm-update-pbb-njop').serializeArray();
					$.post( "modul/pbb/proses_update_njop_pbb.php", { postdata: inputs,lang:lang })
  						.done(function( data1 ) {
   							notif('Notifikasi',data1,'success');
							$('#resetfrm').click();
							$("#frm-update-pbb-njop").removeAttr('disabled');
  					});
	
});
			
			
				
			
				
			
});
</script>
<form id="frm-update-pbb-njop" name="frm-update-pbb-njop" data-parsley-validate class="form-horizontal form-label-left<br />
	
	
	 <div class="x_panel">
                                <div class="x_title">
             <h2>DATA OBJEK PAJAK SISMIOP</h2>   <div class="clearfix"></div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NOP
        </label>
       	<div class="col-md-3 col-sm-6 col-xs-12">
			<input type="text" id="nop" name="nop" class="form-control col-md-7 col-xs-12" placeholder="NOP " data-inputmask="'mask': '99.99.999.999.999.9999.9'">
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NIK SPPT
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nik-sppt" name="nik-sppt" class="form-control col-md-7 col-xs-12"  placeholder="NIK SPPT" readonly="readonly">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama SPPT
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-sppt" name="nama-sppt" class="form-control col-md-7 col-xs-12"  placeholder="Nama SPPT" readonly="readonly">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat WP
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="alamat-wp-sppt" name="alamat-wp-sppt" class="form-control col-md-7 col-xs-12"  placeholder="ALamat WP" readonly="readonly">
    	</div>
		
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Letak OP
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="letak-op" name="letak-op" class="form-control col-md-7 col-xs-12"  placeholder="Lokasi OP" readonly="readonly">
    	</div>
		
    </div>
	
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
    	</label>
    	
		<div class="col-md-2 col-sm-2 col-xs-1">
    		<input type="text" id="nomor-op" name="nomor-op" class="form-control col-md-6 col-xs-12"  placeholder="Nomor" readonly="readonly">
    	</div>
		
		<div class="col-md-1 col-sm-1 col-xs-1">
    		<input type="text" id="rt-op" name="rt-op" class="form-control col-md-3 col-xs-12"  placeholder="RT" readonly="readonly">
    	</div>
		
		<div class="col-md-1 col-sm-1 col-xs-1">
    		<input type="text" id="rw-op" name="rw-op" class="form-control col-md-1 col-xs-3"  placeholder="RW" readonly="readonly">
    	</div>
    </div>
	
		<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kelurahan OP
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-kel" name="nama-kel" class="form-control col-md-7 col-xs-12"  placeholder="Kelurahan OP" readonly="readonly">
    	</div>
    </div>		
<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kecamatan OP
    	</label> 
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-kec" name="nama-kec" class="form-control col-md-7 col-xs-12"  placeholder="Kecamatan OP" readonly="readonly">
    	</div>
    </div>		
	
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Luas Bumi
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="luas-bumi" name="luas-bumi" class="form-control col-md-7 col-xs-12"  placeholder="Luas Bumi" readonly="readonly">
    	</div>
    </div>	
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Luas Bangunan
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="luas-bangunan" name="luas-bangunan" class="form-control col-md-7 col-xs-12"  placeholder="Luas Bangunan" readonly="readonly">
    	</div>
    </div>	
	
	
	</div></div>
	

	 <div class="x_panel">
                                
	
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-update-njop" type="button" class="btn btn-success">Update Data</button>
			<button id="resetfrm" type="reset" class="btn btn-primary">Reset</button>
        </div>
    </div>
</form>


<?php
pg_close($dbconn);
?>
