<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
?>
<link type="text/css" href="css/jquery-ui.css" rel="stylesheet" />
<script src="js/jquery-ui.js"></script>
<script>
var kd_kelu = "";
function get_kel(kd) {
	if(kd != ""){	
	$.ajax({
                	type: "GET",
                	url: "inc/get_kel.php",
                	data: 'kd='+kd,
               		success: function(data){
						$('#kd-kelurahan').html(data);
						$("#kd-kelurahan").select2("val", kd_kelu);
                	}
            });	
	}		
};
$(document).ready(function () {
	$('#nop').autocomplete({
      			source: "modul/reklame/get_nop.php",
				select: function(event, ui) {
            		if (ui.item.value != 'Data Tidak Ada')  {
					$("#nop").attr("readonly", "readonly");
					var nop_lama = ui.item.value.split(".");					
					$("#kd-kecamatan").select2("val", nop_lama[0]);
					kd_kelu = nop_lama[1];
					$("#kd-keg-usaha").select2("val", nop_lama[3]);
				$.ajax({
                	type: "GET",
                	url: "modul/reklame/get_detail_objek.php",
                	data: 'search='+ui.item.value,
               		success: function(data){			
						var obj = jQuery.parseJSON(data);									
						$('#nama-wp').val(obj[0].nama);
						$('#alamat').val(obj[0].alamat);						
						$('#nama-usaha').val(obj[0].nm_reklame);
						$('#alamat-usaha').val(obj[0].alamat_reklame);
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						notif('Notifikasi',ajaxOptions+' '+xhr.status+' : '+thrownError,'error');
      				}
            	});
			} 	
				}
    		});
	 $(".select2_single").select2({
                    placeholder: "Pilih                      ",
                    allowClear: false
                });
	$('#resetfrm').click(function(){				
				$('#frm-update-nop-resto')[0].reset();
				$("#nop").removeAttr('readonly');
				$("#kd-kecamatan").select2("val", "");
				$("#kd-kelurahan").select2("val", "");
				$("#kd-keg-usaha").select2("val", "");				
				$("#kd-kelurahan").html("");
			});
	$('#proses-update-nop-resto').click(function(){
if($('#nop').val() == '' || $('#kd-kecamatan').val() == null || $('#kd-kelurahan').val() == null || $('#kd-keg-usaha').val() == null){
	notif('Notifikasi','Isi Semua Data','error');			
} else {
	$("#proses-update-nop-resto").attr("disabled", "disabled");
	var inputs = $('#frm-update-nop-resto').serializeArray();
					$.post( "modul/reklame/p_mutasi_nop.php", { postdata: inputs })
  						.done(function( data1 ) {
   							notif('Notifikasi',data1,'success');
							$('#resetfrm').click();							
							$("#proses-update-nop-resto").removeAttr('disabled');
  					});
}	
});
	$("#kd-kecamatan").select2("val", "");
	$("#kd-keg-usaha").select2("val", "");
});
</script>
<form id="frm-update-nop-resto" name="frm-update-nop-resto" data-parsley-validate class="form-horizontal form-label-left">
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NOP Lama
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="nop" name="nop" class="form-control col-md-7 col-xs-12" placeholder="Nomor Objek Pajak Lama">
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
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Reklame
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-usaha" name="nama-usaha" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Nama Usaha">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat Reklame
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="alamat-usaha" name="alamat-usaha" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Alamat Usaha">
    	</div>
    </div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kecamatan
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
		<select name="kd-kecamatan" id="kd-kecamatan" class="select2_single" style="width:300px" onchange="get_kel($('#kd-kecamatan').val());">
			<?php
			include_once $_SESSION['base_dir']."inc/db.inc.php"; 
			$sql = "SELECT kd_kecamatan,nm_kecamatan FROM public.kecamatan";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
			
				echo "<option value=\"".$row['kd_kecamatan']."\">".$row['kd_kecamatan']." - ".$row['nm_kecamatan']."</option>";
			}
			pg_free_result($tampil);
			?>
		</select>
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kelurahan
	</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="kd-kelurahan" id="kd-kelurahan" class="select2_single" style="width:300px">
		</select>
	</div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Objek Pajak
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
		<input type="text" id="kd-obj-pajak" name="kd-obj-pajak" required="required" class="form-control col-md-7 col-xs-12" readonly="readonly" value="02 - REKLAME">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kegiatan Usaha
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <select name="kd-keg-usaha" id="kd-keg-usaha" class="select2_single" style="width:300px">
		<?php 
			$sql = "SELECT kd_keg_usaha,nm_keg_usaha FROM public.keg_usaha WHERE kd_obj_pajak='02'";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{			
				echo "<option value=\"".$row['kd_keg_usaha']."\">".$row['kd_keg_usaha']." - ".$row['nm_keg_usaha']."</option>";
			}
			pg_free_result($tampil);
			pg_close($dbconn);
		?>
		</select>
   </div>
</div>
<div class="ln_solid"></div>
<div class="form-group">
    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
       	<button id="proses-update-nop-resto" type="button" class="btn btn-success">Proses</button>
		<button id="resetfrm" type="button" class="btn btn-primary">Reset</button>
    </div>
</div>	
</form>