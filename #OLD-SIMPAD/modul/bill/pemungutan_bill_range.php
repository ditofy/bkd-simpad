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
	$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });
				
	$('#proses-setor-bill').click(function(){
	if( $('#nop').val() == '' || $('#npwpd').val() == '' || $('#no-awal').val() == '' || $('#no-akhir').val() == '' ||  parseInt($('#no-awal').val()) > parseInt($('#no-akhir').val())) {			
				notif('Notifikasi','Isi Semua Data','error');			
	} else {
		$('#proses-setor-bill').attr("disabled", "disabled");
		var inputs = $('#frm-setor-bill').serializeArray();
		$.post( "modul/bill/p_pemungutan_bill.php", { postdata: inputs })
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
		$('#frm-setor-bill')[0].reset();		
		$("#proses-setor-bill").removeAttr('disabled');		
		$("#no-awal").removeAttr('readonly');
		$("#no-akhir").removeAttr('readonly');
		$('#verifikasi').removeAttr('disabled');
		$('#omset').attr("readonly", "readonly");
	});
	
	$('#verifikasi').click(function(){
		if( $('#no-awal').val() == '' || $('#no-akhir').val() == '' ) {
			notif('Notifikasi','Isi Semua Data','error');
		} else {
			if( parseInt($('#no-awal').val()) > parseInt($('#no-akhir').val()) ) {
				notif('Notifikasi','No Bill Akhir Kecil Dari No Awal','error');
			} else {
				//cek bil
				
				$("#no-awal").attr("readonly", "readonly");
				$("#no-akhir").attr("readonly", "readonly");
				$('#verifikasi').attr("disabled", "disabled");
				$.post( "modul/bill/verifikasi_data_bill.php", { seri: $('#seri-bill').val(), awal: $('#no-awal').val(), akhir: $('#no-akhir').val() })
  						.done(function( data1 ) {						
							var obj = jQuery.parseJSON(data1);							
							if( obj.status == "ok") {
								$("#nop").val(obj.nop);
								$("#npwpd").val(obj.npwpd);
								$("#nama-wp").val(obj.nm_wp);
								$("#alamat").val(obj.alamat);
								$("#nama-usaha").val(obj.nm_usaha);
								$("#alamat-usaha").val(obj.alamat_usaha);
								$('#omset').removeAttr('readonly');
							} else {
								notif('Notifikasi',obj.msg,'error');
								$("#no-awal").removeAttr('readonly');
								$("#no-akhir").removeAttr('readonly');
								$('#verifikasi').removeAttr('disabled');
							}
							//$('#reset').click();
							
  					});
				
				
			}
		}
	});
					
	$("#no-awal").numeric();
	$("#no-akhir").numeric();
	$("#omset").numeric();
});
</script>				
<form id="frm-setor-bill" name="frm-setor-bill" data-parsley-validate class="form-horizontal form-label-left">
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Seri Bill</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="seri-bill" name="seri-bill" class="form-control col-md-7 col-xs-12" placeholder="Seri Bill" readonly="readonly" value="A" size="2">
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
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<button id="verifikasi" type="button" class="btn btn-dark">Verifikasi</button>
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
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tahun Pajak</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="tahun-pajak" id="tahun-pajak" class="select2_single" style="width:200px">
			<option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
		</select>
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Bulan Pajak</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="bulan-pajak" id="bulan-pajak" class="select2_single" style="width:200px">
			<option value="01" <?php if(date('m') == '01') echo "selected=\"selected\"";?>>Januari</option>
			<option value="02" <?php if(date('m') == '02') echo "selected=\"selected\"";?>>Februari</option>
			<option value="03" <?php if(date('m') == '03') echo "selected=\"selected\"";?>>Maret</option>
			<option value="04" <?php if(date('m') == '04') echo "selected=\"selected\"";?>>April</option>
			<option value="05" <?php if(date('m') == '05') echo "selected=\"selected\"";?>>Mei</option>
			<option value="06" <?php if(date('m') == '06') echo "selected=\"selected\"";?>>Juni</option>
			<option value="07" <?php if(date('m') == '07') echo "selected=\"selected\"";?>>Juli</option>
			<option value="08" <?php if(date('m') == '08') echo "selected=\"selected\"";?>>Agustus</option>
			<option value="09" <?php if(date('m') == '09') echo "selected=\"selected\"";?>>September</option>
			<option value="10" <?php if(date('m') == '10') echo "selected=\"selected\"";?>>Oktober</option>
			<option value="11" <?php if(date('m') == '11') echo "selected=\"selected\"";?>>November</option>
			<option value="12" <?php if(date('m') == '12') echo "selected=\"selected\"";?>>Desember</option>
		</select>
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tarif</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="tarif" id="tarif" class="select2_single" style="width:200px">
		<?php
			include_once $_SESSION['base_dir']."inc/db.inc.php";
			$sql = "SELECT * FROM public.tarif WHERE kd_obj_pajak='03' ORDER BY kd_obj_pajak,kd_tarif";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
				$persen_tarif = $row['tarif'] * 100;
				echo "<option value=\"".$row['tarif']."\">".$persen_tarif."%</option>";				
			}
			pg_free_result($tampil);
			pg_close($dbconn);
		?>
		</select>
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Dasar Pengenaan Pajak Rp.</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="omset" name="omset" class="form-control col-md-7 col-xs-12" placeholder="Omset" readonly="readonly">
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