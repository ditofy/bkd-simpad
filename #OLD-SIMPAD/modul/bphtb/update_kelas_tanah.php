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
var jlhobj;
var persenp;

function start_update() {
	if( $('#kd-kecamatan').val() == null || $('#kd-kelurahan').val() == null || $('#kelas').val() == '') {
		notif('Notifikasi','Isi Semua Data','error');
		return;
	}
	$("#proses-update").attr("disabled", "disabled");
	$("#kd-kecamatan").attr("disabled", "disabled");
	$("#kd-kelurahan").attr("disabled", "disabled");
	$("#kelas").attr("disabled", "disabled");
    es = new EventSource('/modul/bphtb/update_njop.php?kecamatan='+$('#kd-kecamatan').val()+'&kelurahan='+$('#kd-kelurahan').val()+'&kelas='+$('#kelas').val());
      
    //a message is received
    es.addEventListener('message', function(e) {
        var result = JSON.parse( e.data );
          
       // addLog(result.message);       
          
        if(e.lastEventId == 'SELESAI') {
			es.close();
            addLog('Selesai Update Kelas Tanah '+result.progress+' Objek');
			notif('Notifikasi','Update Kelas Tanah Selesai','success');
			$(".progress-bar").removeClass("progress-bar-striped active");
            $('.progress-bar').css('width', persenp+'%').attr('aria-valuenow', jlhobj);
	  		$('.progress-bar').html(persenp+'%');
        } else {
			if(e.lastEventId == 'INFO') {
				jlhobj = result.progress;
				$('.progress-bar').attr('aria-valuemax', jlhobj);
				$('.progress-bar').css('width', '0%').attr('aria-valuemax', jlhobj);
				$(".progress-bar").addClass("progress-bar-striped active");
				addLog(result.message);
			} else {
				if(e.lastEventId == 'ERROR') {
					es.close();
					addLog(result.message);
					$("#proses-update").removeAttr('disabled');
					$("#kd-kecamatan").removeAttr('disabled');
					$("#kd-kelurahan").removeAttr('disabled');
					$("#kelas").removeAttr('disabled');
				} else {
					addLog(result.message);
					persenp = (result.progress/jlhobj)*100;
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
	//$("#bulan-pajak").select2("val", "");
	
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
<form id="frm-updte-kelas-tanah" name="frm-updte-kelas-tanah" data-parsley-validate class="form-horizontal form-label-left">
<div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kecamatan</label>
 <div class="col-md-6 col-sm-6 col-xs-12">
 <select name="kd-kecamatan" id="kd-kecamatan" class="select2_single" style="width:300px;" onchange="get_kel($('#kd-kecamatan').val());">
<option value="010">PAYAKUMBUH BARAT</option>
<option value="020">PAYAKUMBUH TIMUR</option>
<option value="030">PAYAKUMBUH UTARA</option>
<option value="040">PAYAKUMBUH SELATAN</option>
<option value="050">LAMPOSI TIGO NAGARI</option>
</select>
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kelurahan</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
  <select name="kd-kelurahan" id="kd-kelurahan" class="select2_single" style="width:300px;">
</select>
    </div>
</div>

<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kenaikan Kelas</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
      <input type="text" id="kelas" name="kelas" class="form-control col-md-5 col-xs-5" placeholder="Input Kenaikan Kelas">
           
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	
</div>
<div class="ln_solid"></div>
<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
			<button id="proses-update" type="button" class="btn btn-success" onClick="start_update();">Proses Update Kelas Tanah</button>
			
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