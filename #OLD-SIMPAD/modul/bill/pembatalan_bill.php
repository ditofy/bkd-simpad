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
<script>
$(document).ready(function () {
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
	
	$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });
				
	$('#proses-setor-bill').click(function(){
	if( $('#nop').val() == '' || $('#npwpd').val() == '' || $('#no-bill').val() == '' ) {			
				notif('Notifikasi','Isi Semua Data','error');			
	} else {
		$('#proses-setor-bill').attr("disabled", "disabled");
		$.post( "modul/bill/p_pembatalan_bill.php", { seri: $('#seri-bill').val(), no_bill: $('#no-bill').val(), ket: $('#keterangan').val() })
  						.done(function( data1 ) {							
							var obj = jQuery.parseJSON(data1);							
							if( obj.status == "ok") {																
								notif('Notifikasi',obj.msg,'success');
								$('#resetfrm').click();
							} else {								
								notif('Notifikasi',obj.msg,'error');
							}
							//$('#reset').click();														
  					});			
		
	}	
	});
	
	$('#resetfrm').click(function(){
		//$('#frm-setor-bill')[0].reset();		
		$("#proses-setor-bill").removeAttr('disabled');		
		$("#no-bill").removeAttr('readonly');	
		$('#verifikasi').removeAttr('disabled');
		$('#omset').attr("readonly", "readonly");
		$("#nop").val("");
		$("#npwpd").val("");
		$("#nama-wp").val("");
		$("#alamat").val("");
		$("#nama-usaha").val("");
		$("#alamat-usaha").val("");
		$("#no-bill").val("");
		$("#keterangan").val("");
		$("#no-bill").focus();
	});
	
	$("#no-bill").enterKey(function () {
		if( $('#no-bill').val() == '' ) {
			notif('Notifikasi','Isi No Bill','error');
		} else {
			
				//cek bil
				
				$("#no-bill").attr("readonly", "readonly");				
				$.post( "modul/bill/verifikasi_data_bill.php", { seri: $('#seri-bill').val(), no_bill: $('#no-bill').val() })
  						.done(function( data1 ) {						
							var obj = jQuery.parseJSON(data1);							
							if( obj.status == "ok") {
								$("#nop").val(obj.nop);
								$("#npwpd").val(obj.npwpd);
								$("#nama-wp").val(obj.nm_wp);
								$("#alamat").val(obj.alamat);
								$("#nama-usaha").val(obj.nm_usaha);
								$("#alamat-usaha").val(obj.alamat_usaha);								
								$("#keterangan").focus();
							} else {
								notif('Notifikasi',obj.msg,'error');
								$("#no-bill").removeAttr('readonly');																
							}
							//$('#reset').click();
							
  					});

		}
	});
	$("#keterangan").enterKey(function () {		
			$('#proses-setor-bill').click();				
	});
					
	$("#no-bill").numeric();		
	$("#no-bill").focus();
});
</script>				
<form id="frm-setor-bill" name="frm-setor-bill" data-parsley-validate class="form-horizontal form-label-left">
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Seri Bill</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="seri-bill" id="seri-bill" class="select2_single" style="width:50px">
            <?php
                include_once $_SESSION['base_dir']."inc/db.inc.php";
                $dat_seri_bill = pg_query("SELECT * FROM public.ref_bill") or die('Query failed: ' . pg_last_error());
                while ($row_bill = pg_fetch_array($dat_seri_bill))
                {
                    if(date('Y') == $row_bill['tahun'])
                    {
                        echo "<option value=\"".$row_bill['seri']."\" selected=\"selected\">".$row_bill['seri']."</option>";
                    } else {
                        echo "<option value=\"".$row_bill['seri']."\">".$row_bill['seri']."</option>";
                    }                       
                }			
                pg_free_result($dat_seri_bill);
                pg_close($dbconn);
            ?>						
	</select>
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No Bill</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="no-bill" name="no-bill" class="form-control col-md-7 col-xs-12" placeholder="No Bill">
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NOP</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="nop" name="nop" class="form-control col-md-7 col-xs-12" placeholder="Nomor Objek Pajak" readonly="readonly">
    </div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NPWPD</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="npwpd" name="npwpd" class="form-control col-md-7 col-xs-12" placeholder="Nomor Pokok Wajib Pajak Daerah" readonly="readonly">
	</div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama WP</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="nama-wp" name="nama-wp" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Nama Wajib Pajak">
    </div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat</label>
	<div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="alamat" name="alamat" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Alamat Wajib Pajak">
    </div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Usaha</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="nama-usaha" name="nama-usaha" class="form-control col-md-7 col-xs-12" placeholder="Nama Usaha" readonly="readonly">
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat Usaha</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="alamat-usaha" name="alamat-usaha" class="form-control col-md-7 col-xs-12" placeholder="Alamat Usaha" readonly="readonly">
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Keterangan</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="keterangan" name="keterangan" class="form-control col-md-7 col-xs-12" placeholder="Keterangan">
    </div>
</div>
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-setor-bill" type="button" class="btn btn-success">Simpan</button>
			<button id="resetfrm" type="button" class="btn btn-primary">Reset</button>			
        </div>
    </div>
</form>