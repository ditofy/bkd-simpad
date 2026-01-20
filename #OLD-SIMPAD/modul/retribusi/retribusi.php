<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
?>
<link type="text/css" href="css/jquery-ui.css" rel="stylesheet" />
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.numeric.min.js"></script>
<script src="js/input_mask/jquery.inputmask.js"></script>
<script src="js/pemisahribuan.js" type="text/javascript"></script>
<script>
function get_kd_retribusi(kd) {
	if(kd != ""){
		$.post( "modul/retribusi/get_kd_retribusi.php", { kd_opd: kd })
  						.done(function( data1 ) {
							$('#kd-ret').html(data1);
							$("#kd-ret").select2("val", "");
  					});
	}
};
function get_kd_sub_retribusi(kd) {
	if(kd != ""){
		$.post( "modul/retribusi/get_sub_kd_retribusi.php", { t: $('#kd-ret').val(),s: $('#opd-inner').val() })
  						.done(function( data1 ) {
							$('#kd-sub-ret').html(data1);
							$("#kd-sub-ret").select2("val", "");
  					});
	}
};
function get_kd_sub_rinci(kd) {
	if(kd != ""){
		$.post( "modul/retribusi/get_sub_kd_rinci.php", { t: $('#kd-ret').val(),s: $('#opd-inner').val(),p: $('#kd-sub-ret').val() })
  						.done(function( data1 ) {
							$('#kd-sub-ret-rinci').html(data1);
							$("#kd-sub-ret-rinci").select2("val", "");
  					});
	}
};
function get_kd_sub_anak(kd) {
	if(kd != ""){
		$.post( "modul/retribusi/get_sub_kd_anak.php", { t: $('#kd-ret').val(),s: $('#opd-inner').val(),p: $('#kd-sub-ret').val(),q: $('#kd-sub-ret-rinci').val() })
  						.done(function( data1 ) {
							$('#kd-sub-ret-anak').html(data1);
							$("#kd-sub-ret-anak").select2("val", "");
  					});
	}
};
function get_kd_sub_detail(kd) {
	if(kd != ""){
		$.post( "modul/retribusi/get_sub_kd_detail.php", { t: $('#kd-ret').val(),s: $('#opd-inner').val(),p: $('#kd-sub-ret').val(),q: $('#kd-sub-ret-rinci').val(),r: $('#kd-sub-ret-anak').val() })
  						.done(function( data1 ) {
							$('#kd-sub-ret-detail').html(data1);
							$("#kd-sub-ret-detail").select2("val", "");
  					});
	}
};
$(document).ready(function () {
	$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });
				
    $("#opd-inner").select2("val", "");	
	

        
        $('#tgl-setor').daterangepicker({
		format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
        });
	
	$('#proses-retribusi').click(function(){
		if( $('#opd-inner').val() == '' || $('#kd-ret').val() == '' || $('#jumlah').val() == null || $('#tgl-setor').val() == '' || $('#kd-sub-ret').val() == '' || $('#kd-sub-ret-rinci').val() == ''  || $('#kd-sub-ret-anak').val() == '' || $('#kd-sub-ret-detail').val() == ''   ) {
			notif('Notifikasi','Isi Semua Data','error');
		} else {
			
			var inputs = $('#frm-retribusi').serializeArray();
					$.post( "modul/retribusi/penyampaian_p_simpan_retribusi.php", { postdata: inputs })
  						.done(function( data1 ) {
							var obj = jQuery.parseJSON(data1);							
							if( obj.status == "ok") {
								notif('Notifikasi','Retribusi Berhasil Disimpan','success');
								 $("#jumlah").val('');	
								 $("#opd-inner").select2("val", "");
		 						 $("#kd-ret").select2("val", "");	
		  						 $("#kd-sub-ret").select2("val", "");
		    					 $("#kd-sub-ret-rinci").select2("val", "");	
								  $("#kd-sub-ret-anak").select2("val", "");	
								   $("#kd-sub-ret-detail").select2("val", "");	
								 $('#tgl-setor').val('');								
							} 
							
							else if( obj.status == "sudah") {
								notif('Notifikasi','Retribusi Berhasil Di Update ','warning');
								 $("#opd-inner").select2("val", "");
		 						 $("#kd-ret").select2("val", "");	
		  						$("#kd-sub-ret").select2("val", "");
		   						 $("#kd-sub-ret-rinci").select2("val", "");	
								 $("#kd-sub-ret-anak").select2("val", "");		
								 $("#kd-sub-ret-detail").select2("val", "");							
							} 
							
							else {
								notif('Notifikasi',obj.error_msg,'error');	
							}
							//$('#reset').click();														
  					});
		}
	});
	$('#resetfrm').click(function(){
	$("#proses-retribusi").attr("enabled", "enabled");
		$('#frm-retribusi')[0].reset();		
		 $("#opd-inner").select2("val", "");
		  $("#kd-ret").select2("val", "");	
		  $("#kd-sub-ret").select2("val", "");
		    $("#kd-sub-ret-rinci").select2("val", "");	
			 $("#kd-sub-ret-anak").select2("val", "");	
			  $("#kd-sub-ret-detail").select2("val", "");	
	});
					
	
});
</script>				
<form id="frm-retribusi" name="frm-retribusi" data-parsley-validate class="form-horizontal form-label-left">

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">OPD Inner</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="opd-inner" id="opd-inner" class="select2_single" style="width:500px" onchange="get_kd_retribusi($('#opd-inner').val());">
		<?php
			include_once $_SESSION['base_dir']."inc/db.inc.php";
			$sql = "SELECT * FROM retribusi.opd_inner ORDER BY kd_opd ASC";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
				
				echo "<option value=\"".$row['kd_opd']."\">".$row['kd_opd']."&nbsp;&nbsp;---&nbsp;&nbsp;".$row['nama_opd'].".</option>";				
			}
			pg_free_result($tampil);
			//pg_close($dbconn);
		?>
		</select>
    </div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jenis PAD</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="kd-ret" id="kd-ret" class="select2_single" style="width:500px;" onchange="get_kd_sub_retribusi($('#kd-ret').val());">		
		</select>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jenis Sub PAD</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="kd-sub-ret" id="kd-sub-ret" class="select2_single" style="width:500px;" onchange="get_kd_sub_rinci($('#kd-sub-ret').val());" >		
		</select>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jenis Rincian Sub PAD</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="kd-sub-ret-rinci" id="kd-sub-ret-rinci" class="select2_single" style="width:500px;" onchange="get_kd_sub_anak($('#kd-sub-ret-rinci').val());" >		
		</select>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jenis Anak Rincian Sub PAD</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="kd-sub-ret-anak" id="kd-sub-ret-anak" class="select2_single" style="width:500px;" onchange="get_kd_sub_detail($('#kd-sub-ret-anak').val());" >		
		</select>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jenis Detail Rincian Sub PAD</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="kd-sub-ret-detail" id="kd-sub-ret-detail" class="select2_single" style="width:500px;" >		
		</select>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jumlah </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="jumlah" name="jumlah" class="form-control col-md-7 col-xs-12" placeholder="Jumlah " onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal Setor</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="tgl-setor" name="tgl-setor" class="form-control col-md-7 col-xs-12" placeholder="Tanggal Setor" >
    </div>
</div>

	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-retribusi" type="button" class="btn btn-success">Simpan</button>
			<button id="resetfrm" type="button" class="btn btn-primary">Reset</button>			
        </div>
    </div>
</form>