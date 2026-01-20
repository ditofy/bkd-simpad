<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";

?>



<div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="dashboard_graph">

                            <div class="row x_title">
                                <div class="col-md-6">
                                    <h3>Realisasi Pajak Daerah <?php 
									$tanggal= mktime(date("m"),date("d"),date("Y"));
									echo "Tanggal : <b>".date("d-M-Y", $tanggal)."</b> "; date_default_timezone_set('Asia/Jakarta');$jam=date("H:i:s");
									echo "| Pukul : <b>". $jam." "."</b>";
$a = date ("H"); ?></h3>
                                </div>
                                
                            </div>

                            
                            <div class="col-md-12 col-sm-3 col-xs-12 bg-white">		
							 <table class="table table-hover">
							 <thead>
      							<tr>
        							<th>Pajak Daerah</th>
        							<th>Realisasi</th>
      							</tr>
    						</thead>
							 <tr><td>PBB</td><td>					
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div id="r-pbb">
                                        <!-- realisasi PBB -->
                                    </div>
                                </div>
							</td></tr>
							<tr><td>AIR BAWAH TANAH</td><td>					
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div id="r-abt">
                                         <!-- realisasi ABT -->
                                    </div>
                                </div>
							</td></tr>
							 <tr><td>REKLAME</td><td>					
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div id="r-reklame">
                                        <!-- realisasi reklame -->
                                    </div>
                                </div>
							</td></tr>
							<tr><td>RESTORAN</td><td>					
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div id="r-restoran">
                                        <!-- realisasi resto -->
                                    </div>
                                </div>
							</td></tr>
							<tr><td>HIBURAN</td><td>					
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div id="r-hiburan">
                                        <!-- realisasi hiburan -->
                                    </div>
                                </div>
							</td></tr>
							<tr><td>HOTEL</td><td>					
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div id="r-hotel">
                                        <!-- realisasi hotel -->
                                    </div>
                                </div>
							</td></tr>
							<tr><td>BPHTB</td><td>					
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div id="r-bphtb">
                                       
                                    </div>
                                </div>
							</td></tr>
							<tr><td>PARKIR</td><td>					
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div id="r-parkir">
                                         realisasi Parkir
                                    </div>
                                </div>
							</td></tr>
							<tr><td>PENERANGAN JALAN</td><td>					
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div id="r-ppj">
                                         realisasi PPJ
                                    </div>
                                </div>
							</td></tr>
							<tr><td>GALIAN C</td><td>					
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div id="r-galianc">
                                         realisasi galian c
                                    </div>
                                </div>
							</td></tr>
							<tr><td>OPSEN PKB</td><td>					
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div id="r-pkb">
                                        realisasi Opsen PKB
                                    </div>
                                </div>
							</td></tr>
							<tr><td>OPSEN BBNKB</td><td>					
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div id="r-bbnkb">
                                        realisasi Opsen BBNKB
                                    </div>
                                </div>
							</td></tr>
							</table>
                            </div>
							
                            <div class="clearfix"></div>
                        </div>
                    </div>

                </div>
				<br />
<script>
$(document).ready(function () {
				$('#r-pbb').html("<img src='images/loading.gif' />");
				$('#r-reklame').html("<img src='images/loading.gif' />");
				$('#r-restoran').html("<img src='images/loading.gif' />");
				$('#r-abt').html("<img src='images/loading.gif' />");
				$('#r-hiburan').html("<img src='images/loading.gif' />");
				$('#r-hotel').html("<img src='images/loading.gif' />");
				$('#r-bphtb').html("<img src='images/loading.gif' />");
				$('#r-parkir').html("<img src='images/loading.gif' />");
				$('#r-ppj').html("<img src='images/loading.gif' />");
				$('#r-galianc').html("<img src='images/loading.gif' />");
				$('#r-pkb').html("<img src='images/loading.gif' />");
				$('#r-bbnkb').html("<img src='images/loading.gif' />");
				$.ajax({
                	type: "GET",
                	url: 'modul/realisasi/r_pbb.php',
                	data: '',
               		success: function(data){
						
						$('#r-pbb').html(data);	
					
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#r-pbb').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						
      				}
            	});				
				$.ajax({
                	type: "GET",
                	url: 'modul/realisasi/r_reklame.php',
                	data: '',
               		success: function(data){
						
						$('#r-reklame').html(data);	
					
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#r-reklame').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						
      				}
            	});
				$.ajax({
                	type: "GET",
                	url: 'modul/realisasi/r_restoran.php',
                	data: '',
               		success: function(data){
						
						$('#r-restoran').html(data);	
					
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#r-restoran').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						
      				}
            	});
				$.ajax({
                	type: "GET",
                	url: 'modul/realisasi/r_air_tanah.php',
                	data: '',
               		success: function(data){
						
						$('#r-abt').html(data);	
					
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#r-abt').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						
      				}
            	});
				$.ajax({
                	type: "GET",
                	url: 'modul/realisasi/r_hiburan.php',
                	data: '',
               		success: function(data){
						
						$('#r-hiburan').html(data);	
					
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#r-hiburan').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						
      				}
            	});
				$.ajax({
                	type: "GET",
                	url: 'modul/realisasi/r_hotel.php',
                	data: '',
               		success: function(data){
						
						$('#r-hotel').html(data);	
					
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#r-hotel').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						
      				}
            	});
				$.ajax({
                	type: "GET",
                	url: 'modul/realisasi/r_bphtb.php',
                	data: '',
               		success: function(data){
						
						$('#r-bphtb').html(data);	
					
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#r-bphtb').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						
      				}
            	});
				$.ajax({
                	type: "GET",
                	url: 'modul/realisasi/r_parkir.php',
                	data: '',
               		success: function(data){
						
						$('#r-parkir').html(data);	
					
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#r-parkir').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						
      				}
            	});
				$.ajax({
                	type: "GET",
                	url: 'modul/realisasi/r_ppj.php',
                	data: '',
               		success: function(data){
						
						$('#r-ppj').html(data);	
					
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#r-ppj').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						
      				}
            	});
				$.ajax({
                	type: "GET",
                	url: 'modul/realisasi/r_galianc.php',
                	data: '',
               		success: function(data){
						
						$('#r-galianc').html(data);	
					
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#r-galianc').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						
      				}
            	});
				$.ajax({
                	type: "GET",
                	url: 'modul/realisasi/r_pkb.php',
                	data: '',
               		success: function(data){
						
						$('#r-pkb').html(data);	
					
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#r-pkb').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						
      				}
            	});
				$.ajax({
                	type: "GET",
                	url: 'modul/realisasi/r_bbnkb.php',
                	data: '',
               		success: function(data){
						
						$('#r-bbnkb').html(data);	
					
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#r-bbnkb').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						
      				}
            	});
});
</script>
<?php
include_once $_SESSION['base_dir']."footer.php";
?>