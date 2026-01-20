<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
?>
<script src="js/input_mask/jquery.inputmask.js"></script>
<script type="text/javascript">
var es;
var jlhskp;
var persenp;

function start_penetapan() {
	if( $('#tahun-pajak').val() == null || $('#bulan-pajak').val() == null || $('#tanda-tangan').val() == null || $('#tanggal-penetapan').val() == '' ) {
		notif('Notifikasi','Isi Semua Data','error');
		return;
	}
	$("#proses-penetapan").attr("disabled", "disabled");
	$("#tahun-pajak").attr("disabled", "disabled");
	$("#bulan-pajak").attr("disabled", "disabled");
	$("#tanda-tangan").attr("disabled", "disabled");
	$("#tanggal-penetapan").attr("disabled", "disabled");
    es = new EventSource('/modul/restoran/penetapan_p_penetapan_masal_skp.php?tahun='+$('#tahun-pajak').val()+'&bulan='+$('#bulan-pajak').val()+'&pejabat='+$('#tanda-tangan').val()+'&tanggal_tetap='+$('#tanggal-penetapan').val());
      
    //a message is received
    es.addEventListener('message', function(e) {
        var result = JSON.parse( e.data );
          
       // addLog(result.message);       
          
        if(e.lastEventId == 'SELESAI') {
			es.close();
            addLog('Selesai Menetapkan Masal '+result.progress+' Objek');
			notif('Notifikasi','Penetapan Masal Selesai','success');
			$(".progress-bar").removeClass("progress-bar-striped active");
            $('.progress-bar').css('width', persenp+'%').attr('aria-valuenow', jlhskp);
	  		$('.progress-bar').html(persenp+'%');
			$("#cetak").removeAttr('disabled');
        } else {
			if(e.lastEventId == 'INFO') {
				jlhskp = result.progress;
				//$('.progress-bar').attr('aria-valuemax', jlhskp);
				$('.progress-bar').css('width', '0%').attr('aria-valuemax', jlhskp);
				$(".progress-bar").addClass("progress-bar-striped active");
				addLog(result.message);
			} else {
				if(e.lastEventId == 'ERROR') {
					es.close();
					addLog(result.message);
					$("#proses-penetapan").removeAttr('disabled');
					$("#tahun-pajak").removeAttr('disabled');
					$("#bulan-pajak").removeAttr('disabled');
					$("#tanda-tangan").removeAttr('disabled');
					$("#tanggal-penetapan").removeAttr('disabled');
				} else {
					addLog(result.message);
					persenp = (result.progress/jlhskp)*100;
            		$('.progress-bar').css('width', persenp+'%').attr('aria-valuenow', result.progress);
	  				$('.progress-bar').html(persenp.toFixed(2)+'%');
				}
			}
		}
    });
      
    es.addEventListener('error', function(e) {
        addLog('Error occurred');
        es.close();
    });
}

function addLog(message) {
	
	$('#proc-status').html(message);
}

$(document).ready(function () {
	$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });
	$('#tanggal-penetapan').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
    });
	$('#cetak').click(function(){
		if($('#bulan-pajak').val() == null) {
			$('#modal-cnt').html("<center><br><br>Silahkan Pilih Bulan Pajak<br><br></center>");
			return;
		}
		$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");		
		var es;
		es = new EventSource('/modul/restoran/gen_pdf_skp_masal.php?thn_pajak='+$('#tahun-pajak').val()+'&bln_pajak='+$('#bulan-pajak').val());
		es.addEventListener('message', function(e) {
        	var result = JSON.parse( e.data );
			if(e.lastEventId == 'SELESAI') {
				es.close();
            	$('#modal-cnt').html("<object type='application/pdf' data='"+result.message+"' width='100%' height='500'>this is not working as expected</object>");				
        	} else {
				$('#modal-cnt').html(result.message+"<center><img src='images/loading.gif' /><br>Loading</center>");
			}
		});
		
	});
	//$("#bulan-pajak").select2("val", "");
	$("#tanda-tangan").select2("val", "");
	$("#tanggal-penetapan").inputmask();
	$("#cetak").attr("disabled", "disabled");
});
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">SKPD</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>	
<form id="frm-restoran-skp-mas" name="frm-restoran-skp-mas" data-parsley-validate class="form-horizontal form-label-left">
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
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Pejabat </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="tanda-tangan" id="tanda-tangan" class="select2_single">
			<?php
			include_once $_SESSION['base_dir']."inc/db.inc.php";
			$sql = "SELECT A.nip,B.jabatan FROM public.tt A INNER JOIN public.user B ON B.nip=A.nip";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
				echo "<option value=\"".$row['nip']."\">".$row['jabatan']."</option>";				
			}
			pg_free_result($tampil);
			pg_close($dbconn);
			?>
			</select>
    </div>
</div>
<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal Penetapan</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tanggal-penetapan" name="tanggal-penetapan" class="form-control has-feedback-left" placeholder="Tanggal Penetapan" aria-describedby="inputSuccess2Status" data-inputmask="'mask': '99-99-9999'">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	
</div>
<div class="ln_solid"></div>
<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
			<button id="proses-penetapan" type="button" class="btn btn-success" onClick="start_penetapan();">Proses</button>
			<button id="cetak" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak SKP</button>
		</div>
</div>
</form>
<div class="">
	<div class="progress">
		<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="10" style="width:0s%">0%</div>										
	</div>
	<div>
	Status : <pre id="proc-status"></pre>
	</div>
</div>