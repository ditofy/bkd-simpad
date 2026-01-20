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
				
				$('#nop').autocomplete({
      			source: "modul/reklame/get_nop.php",
				select: function(event, ui) {
            		if (ui.item.value != 'Data Tidak Ada')  {
					$("#nop").attr("readonly", "readonly");
				$.ajax({
                	type: "GET",
                	url: "modul/reklame/get_detail_objek.php",
                	data: 'search='+ui.item.value,
               		success: function(data){
						var obj = jQuery.parseJSON(data);
						$('#npwpd').val(obj[0].npwpd);
						$('#nik').val(obj[0].nik);
						$('#nama-wp').val(obj[0].nama);
						$('#alamat').val(obj[0].alamat);
						$('#nama-usaha').val(obj[0].nm_reklame);
						$('#alamat-usaha').val(obj[0].alamat_reklame);
						$('#panjang').val(obj[0].p);
						$('#lebar').val(obj[0].l);
						$('#sisi').val(obj[0].s);
						$('#jumlah').val(obj[0].jlh);
						$("#jenis-penetapan").select2("val", obj[0].jenis_penetapan);
						$('#tmt-awal').val(obj[0].tmt_aw);
						$('#tmt-akhir').val(obj[0].tmt_ak);
						$('#tgl-daftar').val(obj[0].tgl_daf);
						$("#status-pajak").select2("val", obj[0].status_pajak);
						$('#keterangan').val(obj[0].ket);						
						$('#tarif').select2("val", obj[0].kd_tarif);
						$('#lama-pasang').val(obj[0].lama_pasang);
						$('#status-pemasangan').select2("val", obj[0].status_pemasangan);
						
						//alert($('#status-pemasangan').val(obj[0].status_pemasangan));
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						notif('Notifikasi',ajaxOptions+' '+xhr.status+' : '+thrownError,'error');
      				}
            	});
			} 	
				}
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
						$("#tarif").select2("val", "");
						$("#status-pemasangan").select2("val", "");
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						notif('Notifikasi',ajaxOptions+' '+xhr.status+' : '+thrownError,'error');
      				}
            	});
			} 	
				}
    		});	
			
			$('#resetfrm').click(function(){
				$("#nop").removeAttr('readonly');
				$("#npwpd").removeAttr('readonly');
				$("#jenis-penetapan").select2("val", "");
				$("#status-pajak").select2("val", "");
				$("#tarif").select2("val", "");
				$("#status-pemasangan").select2("val", "");
			});
			
			$('#tgl-daftar').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
			
			$('#tmt-awal').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
			
			$('#tmt-akhir').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
			
			$('#proses-update-reklame').click(function(){
if($('#nop').val() == '' || $('#npwpd').val() == '' || $('#nama-usaha').val() == '' || $('#alamat-usaha').val() == '' || $('#tgl-daftar').val() == '' || $("#status-pajak").val() == null || $("#jenis-penetapan").val() == null || $('#panjang').val() == '' || $('#lebar').val() == '' || $('#sisi').val() == '' || $('#jumlah').val() == '' || $("#lama-pasang").val() == '' || $("#tarif").val() == null || $("#status-pemasangan").val() == null){
	notif('Notifikasi','Isi Semua Data','error');			
} else {
	$("#proses-update-reklame").attr("disabled", "disabled");
	var inputs = $('#frm-update-reklame').serializeArray();
					$.post( "modul/reklame/p_update.php", { postdata: inputs })
  						.done(function( data1 ) {
   							notif('Notifikasi',data1,'success');
							$('#resetfrm').click();
							$("#proses-update-reklame").removeAttr('disabled');
  					});
}	
});
			
			$("#jenis-penetapan").select2("val", "");
			$("#status-pajak").select2("val", "");	
			$("#tarif").select2("val", "");
			$("#status-pemasangan").select2("val", "");
			$("#panjang").numeric();
			$("#lebar").numeric();
			$("#sisi").numeric();
			$("#jumlah").numeric();
			$("#lama-pasang").numeric();
});
</script>
<form id="frm-update-reklame" name="frm-update-reklame" data-parsley-validate class="form-horizontal form-label-left">
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NOP
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nop" name="nop" class="form-control col-md-7 col-xs-12" placeholder="Nomor Objek Pajak">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NPWPD
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="npwpd" name="npwpd" class="form-control col-md-7 col-xs-12" placeholder="Nomor Pokok Wajib Pajak Daerah">
    	</div>
    </div>
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
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NIK
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nik" name="nik" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Nomor Induk Kependudukan">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Reklame
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-usaha" name="nama-usaha" class="form-control col-md-7 col-xs-12" placeholder="Nama Reklame">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat Reklame
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="alamat-usaha" name="alamat-usaha" class="form-control col-md-7 col-xs-12" placeholder="Alamat Reklame">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jenis Penetapan
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<select name="jenis-penetapan" id="jenis-penetapan" class="select2_single" style="width:200px">
				<option value="01">01 - SKP</option>
			</select>
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Ukuran
    	</label>
    	<div class="col-md-1 col-sm-6 col-xs-12">
    		<input type="text" id="panjang" name="panjang" class="form-control col-md-7 col-xs-12" placeholder="Pjg">
    	</div>
		<div class="col-md-1 col-sm-6 col-xs-12">
    		<input type="text" id="lebar" name="lebar" class="form-control col-md-7 col-xs-12" placeholder="Lebar">
    	</div>
		<div class="col-md-1 col-sm-6 col-xs-12">
    		<input type="text" id="sisi" name="sisi" class="form-control col-md-7 col-xs-12" placeholder="Sisi">
    	</div>
		<div class="col-md-1 col-sm-6 col-xs-12">
    		<input type="text" id="jumlah" name="jumlah" class="form-control col-md-7 col-xs-12" placeholder="Jumlah">
    	</div>
    </div>
	<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tarif</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="tarif" id="tarif" class="select2_single" >
		<?php
			include_once $_SESSION['base_dir']."inc/db.inc.php";
			$sql = "SELECT * FROM public.tarif WHERE kd_obj_pajak='02' ORDER BY kd_obj_pajak,kd_tarif";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
				echo "<option value=\"".$row['kd_tarif']."\">".$row['nm_tarif']." (Rp. ".number_format($row['tarif']).")</option>";				
			}
		?>
		</select>
    </div>
	</div>
	<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Lama Pemasangan</label>
    	<div class="col-md-1 col-sm-6 col-xs-12">
    		<input type="text" id="lama-pasang" name="lama-pasang" class="form-control col-md-7 col-xs-12" placeholder="Lama">
    	</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">TMT Awal</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tmt-awal" name="tmt-awal" class="form-control has-feedback-left" placeholder="TMT Awal" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">TMT Akhir</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tmt-akhir" name="tmt-akhir" class="form-control has-feedback-left" placeholder="TMT Akhir" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal Pendaftaran</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tgl-daftar" name="tgl-daftar" class="form-control has-feedback-left" placeholder="Tanggal Pendaftaran" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Status Pajak
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<select name="status-pajak" id="status-pajak" class="select2_single" style="width:200px">
				<option value="1">1 - AKTIF</option>
				<option value="0">0 - TIDAK AKTIF</option>
			</select>
    	</div>
    </div>
	<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Status Pemasangan</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="status-pemasangan" id="status-pemasangan" class="select2_single">
			<option value="PERMANEN">PERMANEN</option>
			<option value="SEMENTARA">SEMENTARA</option>
		</select>
    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Keterangan
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="keterangan" name="keterangan" class="form-control col-md-7 col-xs-12" placeholder="Keterangan">
    	</div>
    </div>
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-update-reklame" type="button" class="btn btn-success">Update</button>
			<button id="resetfrm" type="reset" class="btn btn-primary">Reset</button>
        </div>
    </div>
</form>