<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
$thn_pkb=date('Y');
$sql = "SELECT COUNT(no_reg) AS jlh FROM public.wp WHERE status <> '0'";
$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
$row = pg_fetch_array($result);
$jlh_wp = $row['jlh'];
$sql = "SELECT COUNT(no_reg) AS jlh FROM air_tanah.dat_obj_pajak WHERE status_pajak = '1'";
$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
$row = pg_fetch_array($result);
$jlh_abt = $row['jlh'];
$sql = "SELECT COUNT(no_reg) AS jlh FROM hiburan.dat_obj_pajak WHERE status_pajak = '1'";
$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
$row = pg_fetch_array($result);
$jlh_hiburan = $row['jlh'];
$sql = "SELECT COUNT(no_reg) AS jlh FROM hotel.dat_obj_pajak WHERE status_pajak = '1'";
$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
$row = pg_fetch_array($result);
$jlh_hotel = $row['jlh'];
$sql = "SELECT COUNT(no_reg) AS jlh FROM ppj.dat_obj_pajak WHERE status_pajak = '1'";
$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
$row = pg_fetch_array($result);
$jlh_ppj = $row['jlh'];
$sql = "SELECT COUNT(no_reg) AS jlh FROM restoran.dat_obj_pajak WHERE status_pajak = '1' AND jenis_penetapan='02'";
$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
$row = pg_fetch_array($result);
$jlh_restoran = $row['jlh'];
$sql = "SELECT COUNT(no_reg) AS jlh FROM reklame.dat_obj_pajak WHERE status_pajak = '1'";
$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
$row = pg_fetch_array($result);
$jlh_reklame = $row['jlh'];
$sql = "SELECT COUNT(nik) AS jlh FROM bphtb.sspd WHERE status_pembayaran <> '2'";
$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
$row = pg_fetch_array($result);
$jlh_bphtb= $row['jlh'];
$sql = "SELECT SUM(unit) AS jlh FROM pkb.pembayaran_opsen WHERE thn_pajak='$thn_pkb'";
$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
$row = pg_fetch_array($result);
$jlh_pkb= $row['jlh'];
$sql = "SELECT SUM(unit) AS jlh FROM bbnkb.pembayaran_opsen WHERE thn_pajak='$thn_pkb'";
$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
$row = pg_fetch_array($result);
$jlh_bbnkb= $row['jlh'];
pg_free_result($result);
pg_close($dbconn);
?>

<div class="row tile_count">
<div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
	<div class="left"></div>
	<div class="right">
		<span class="count_top"><i class="fa fa-user"></i> Total NPWPD</span>
		<div class="count green"><?php echo $jlh_wp; ?></div>
		<span class="count_bottom">WAJIB PAJAK</span>	</div>
</div>
<div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
	<div class="left"></div>
	<div class="right">
		<span class="count_top"><i class="fa fa-user"></i> Total Objek Pajak</span>
		<div class="count green"><?php echo $jlh_abt; ?></div>
		<span class="count_bottom">AIR TANAH</span>	</div>
</div>
<div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
	<div class="left"></div>
	<div class="right">
		<span class="count_top"><i class="fa fa-user"></i> Total Objek Pajak</span>
		<div class="count green"><?php echo $jlh_hiburan; ?></div>
		<span class="count_bottom">PBJT HIBURAN</span>	</div>
</div>
<div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
	<div class="left"></div>
	<div class="right">
		<span class="count_top"><i class="fa fa-user"></i> Total Objek Pajak</span>
		<div class="count green"><?php echo $jlh_hotel; ?></div>
		<span class="count_bottom">PBJT HOTEL</span>	</div>
</div>
<div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
	<div class="left"></div>
	<div class="right">
		<span class="count_top"><i class="fa fa-user"></i> Total Objek Pajak</span>
		<div class="count green"><?php echo $jlh_ppj; ?></div>
		<span class="count_bottom">PBJT TENAGA LISTRIK</span>	</div>
</div>
<div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
	<div class="left"></div>
	<div class="right">
		<span class="count_top"><i class="fa fa-user"></i> Total Objek Pajak</span>
		<div class="count green"><?php echo $jlh_restoran; ?></div>
		<span class="count_bottom">PBJT MAKAN MINUM</span>	</div>
</div>
</div>

<div class="row tile_count">
<div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
	<div class="left"></div>
	<div class="right">
		<span class="count_top"><i class="fa fa-user"></i> Total Objek Pajak</span>
		<div class="count green"><?php echo $jlh_reklame; ?></div>
		<span class="count_bottom">REKLAME</span>	</div>
</div>
<div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
	<div class="left"></div>
	<div class="right">
		<span class="count_top"><i class="fa fa-user"></i> Total Objek Pajak</span>
		<div class="count green"><?php echo $jlh_bphtb; ?></div>
		<span class="count_bottom">BPHTB</span>	</div>
</div>
<div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
	<div class="left"></div>
	<div class="right">
		<span class="count_top"><i class="fa fa-user"></i> Total Objek Pajak</span>
		<div class="count green"><?php echo $jlh_pkb; ?></div>
		<span class="count_bottom">OPSEN PKB</span>	</div>
</div>
<div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
	<div class="left"></div>
	<div class="right">
		<span class="count_top"><i class="fa fa-user"></i> Total Objek Pajak</span>
		<div class="count green"><?php echo $jlh_bbnkb; ?></div>
		<span class="count_bottom">OPSEN BBNKB</span>	</div>
</div>
</div>

<div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="dashboard_graph">

                            <div class="row x_title">
                                <div class="col-md-6">
                                    <h3>Realisasi <?php echo date('Y'); ?></h3>
                                </div>
                                
                            </div>

                            
                            <div class="col-md-12 col-sm-3 col-xs-12 bg-">		
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
							<tr><td>PBJT MAKAN MINUM</td><td>					
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div id="r-restoran">
                                        <!-- realisasi resto -->
                                    </div>
                                </div>
							</td></tr>
							<tr><td>PBJT HIBURAN</td><td>					
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div id="r-hiburan">
                                        <!-- realisasi hiburan -->
                                    </div>
                                </div>
							</td></tr>
							<tr><td>PBJT PERHOTELAN</td><td>					
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
							<tr><td>PBJT PARKIR</td><td>					
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div id="r-parkir">
                                        
                                    </div>
                                </div>
							</td></tr>
							<tr><td>PBJT TENAGA LISTRIK</td><td>					
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div id="r-ppj">
                                       
                                    </div>
                                </div>
							</td></tr>
							<tr><td>GALIAN C</td><td>					
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div id="r-galianc">
                                       
                                    </div>
                                </div>
							</td></tr>
							<tr><td>OPSEN PKB</td><td>					
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div id="r-pkb">
                                       
                                    </div>
                                </div>
							</td></tr>
							<tr><td>OPSEN BBNKB</td><td>					
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div id="r-bbnkb">
                                       
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
                	url: 'inc/r_pbb.php',
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
                	url: 'inc/r_reklame.php',
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
                	url: 'inc/r_restoran.php',
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
                	url: 'inc/r_air_tanah.php',
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
                	url: 'inc/r_hiburan.php',
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
                	url: 'inc/r_hotel.php',
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
                	url: 'inc/r_bphtb.php',
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
                	url: 'inc/r_parkir.php',
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
                	url: 'inc/r_ppj.php',
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
                	url: 'inc/r_galianc.php',
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
                	url: 'inc/r_pkb.php',
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
                	url: 'inc/r_bbnkb.php',
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