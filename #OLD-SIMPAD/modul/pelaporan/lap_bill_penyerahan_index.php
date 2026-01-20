<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	die('silahkan login');
	exit;
}
?>
<script src="js/input_mask/jquery.inputmask.js"></script>
<script type="text/javascript">
function list_data(pg) {
	$('#list-datbil').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");	
	var inputs = $('#filter-data').serializeArray();	
			$.post( "modul/pelaporan/list_penyerahan_bill.php", { postdata: inputs, page: pg })
  						.done(function( data1 ) {
							$('#list-datbil').html(data1);
  					});
};
$(document).ready(function () {
	$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });
				
	$('#resetfrm').click(function(event) {
		event.preventDefault();
		$('#filter-data')[0].reset();		
	});
	
	$('#tampilkan').click(function() {		
			list_data(0);							
	});
	
	$("#nop").inputmask();
	$("#npwpd").inputmask();		
});
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">DATA BILL</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<div class="x_content">
<form id="filter-data" name="filter-data">
	<table width="100%" border="0" class="table table-striped">
	<tr>
	<td colspan="6">Filter Data</td>
	</tr>
  <tr>
    <td style="vertical-align:middle" align="right">NPWPD</td>
    <td><input type="text" id="npwpd" name="npwpd" class="form-control col-md-7 col-xs-12" placeholder="Nomor Pokok Wajib Pajak Daerah" data-inputmask="'mask': '99.99.99.999999'"></td>
    <td style="vertical-align:middle" align="right">NOP</td>
    <td><input type="text" id="nop" name="nop" class="form-control col-md-7 col-xs-12" placeholder="Nomor Objek Pajak" data-inputmask="'mask': '99.99.99.99.9999'"></td>
    <td style="vertical-align:middle" align="right">Nama Objek </td>
    <td><input type="text" id="nm-usaha" name="nm-usaha" class="form-control col-md-7 col-xs-12" placeholder="Nama Usaha"></td>
  </tr>
  <tr>
    <td style="vertical-align:middle" align="right">Seri Bill</td>
    <td>
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
    </td>    
    <td style="vertical-align:middle" align="right">Nama WP</td>
    <td>
	<input type="text" id="nm-wp" name="nm-wp" class="form-control col-md-7 col-xs-12" placeholder="Nama WP">
	</td>
    <td style="vertical-align:middle" align="right"> </td>
    <td></td>
  </tr>  
  <tr>
    <td colspan="6" align="center" style="vertical-align:middle">
	<button id="tampilkan" type="button" class="btn btn-success">Tampilkan</button>
	<button id="resetfrm" type="reset" class="btn btn-primary">Reset</button>
	</td>
    </tr>
</table>
</form>
</div>
<div id="list-datbil">

</div>