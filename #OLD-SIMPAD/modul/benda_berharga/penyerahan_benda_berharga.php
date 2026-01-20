<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	die('Access Denied');
	exit;
}
?>
<link type="text/css" href="css/jquery-ui.css" rel="stylesheet" />
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.numeric.min.js"></script>
<script src="js/input_mask/jquery.inputmask.js"></script>
<script>
function get_kd_benda(kd) {
	if(kd != ""){
		$.post( "modul/benda_berharga/get_kd_benda.php", { kd_opd: kd })
  						.done(function( data1 ) {
							$('#kd-benda').html(data1);
							$("#kd-benda").select2("val", "");
  					});
	}
};
$(document).ready(function () {
	$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });
				
    $("#opd-inner").select2("val", "");	
	

        
        $('#tanggal-penyerahan').daterangepicker({
		format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
        });
	
	$('#proses-serah-bill').click(function(){
	if( $('#nop').val() == '' || $('#tanggal-penyerahan').val() == '' || $('#npwpd').val() == '' || $('#no-awal').val() == '' || $('#no-akhir').val() == '' ||  parseInt($('#no-awal').val()) > parseInt($('#no-akhir').val())) {
			if( parseInt($('#no-awal').val()) > parseInt($('#no-akhir').val()) ) {
				notif('Notifikasi','No Bill Akhir Kecil Dari No Awal','error');
			} else {
				notif('Notifikasi','Isi Semua Data','error');
			}
	} else {
		$.post( "modul/bill/cek_bill_belum_kembali.php", { postdata: $('#nop').val() })
  						.done(function( data1 ) {
							var obj = jQuery.parseJSON(data1);							
							if( obj.status == "ok") {
								
								$("#proses-serah-bill").attr("disabled", "disabled");
								var inputs = $('#frm-serah-bill').serializeArray();
								$.post( "modul/bill/p_serah_bill.php", { postdata: inputs })
  								.done(function( data1 ) {								
									var obj = jQuery.parseJSON(data1);																				
									if( obj.status == "ok") {
										notif('Notifikasi',obj.msg,'success');
										$('#resetfrm').click();							
									} else {									
										if( obj.status == "ada") {
											notif('Notifikasi',obj.msg,'error');
											$("#proses-serah-bill").removeAttr('disabled');
											$("#nop").removeAttr('readonly');
										} else {
											notif('Notifikasi',obj.error_msg,'error');
											$("#proses-serah-bill").removeAttr('disabled');
											$("#nop").removeAttr('readonly');
										}									
									}																																
  								});
								
								
								
							} else {
								if( obj.status == "ada") {
									if (confirm('Masih Ada Bill Yang Belum Dikembalikan NOP '+$('#nop').val()+'\nLanjutkan Pemberian Bill?' )) {
                                    	$("#proses-serah-bill").attr("disabled", "disabled");
										var inputs = $('#frm-serah-bill').serializeArray();
										$.post( "modul/bill/p_serah_bill.php", { postdata: inputs })
  										.done(function( data1 ) {								
											var obj = jQuery.parseJSON(data1);																				
										if( obj.status == "ok") {
											notif('Notifikasi',obj.msg,'success');
											$('#resetfrm').click();							
										} else {									
											if( obj.status == "ada") {
												notif('Notifikasi',obj.msg,'error');
												$("#proses-serah-bill").removeAttr('disabled');
												$("#nop").removeAttr('readonly');
											} else {
												notif('Notifikasi',obj.error_msg,'error');
												$("#proses-serah-bill").removeAttr('disabled');
												$("#nop").removeAttr('readonly');
											}									
										}																																
  										});
    									// Save it!
									} else {
									    // Do nothing!
									}
								} else {
									notif('Notifikasi',obj.error_msg,'error');
								}
								
							}
							//$('#reset').click();														
  					});				
		
	}	
	});
	
	$('#resetfrm').click(function(){
		$('#frm-serah-bill')[0].reset();		
		$("#proses-serah-bill").removeAttr('disabled');
		$("#nop").removeAttr('readonly');
	});
					
	$("#no-awal").numeric();
	$("#no-akhir").numeric();
});
</script>				
<form id="frm-serah-bill" name="frm-serah-bill" data-parsley-validate class="form-horizontal form-label-left">

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">OPD Inner</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="opd-inner" id="opd-inner" class="select2_single" style="width:500px" onchange="get_kd_benda($('#opd-inner').val());">
		<?php
			include_once $_SESSION['base_dir']."inc/db.inc.php";
			$sql = "SELECT * FROM benda_berharga.opd_inner ORDER BY kd_opd ASC";
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
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Benda Berharga</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="kd-benda" id="kd-benda" class="select2_single" style="width:500px;">		
		</select>
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
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No Awal</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="no-awal" name="no-awal" class="form-control col-md-7 col-xs-12" placeholder="No Awal">
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No Akhir</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="no-akhir" name="no-akhir" class="form-control col-md-7 col-xs-12" placeholder="No Akhir">
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal Penyerahan</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="tanggal-penyerahan" name="tanggal-penyerahan" class="form-control col-md-7 col-xs-12" placeholder="Tanggal Penyerahan">
    </div>
</div>

	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-serah-bill" type="button" class="btn btn-success">Simpan</button>
			<button id="resetfrm" type="button" class="btn btn-primary">Reset</button>			
        </div>
    </div>
</form>