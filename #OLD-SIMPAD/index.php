<?php
date_default_timezone_set('Asia/Jakarta');
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SIMPAD</title>

<!------>
 <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="css/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/maps/jquery-jvectormap-2.0.1.css" />
    <link href="css/icheck/flat/green.css" rel="stylesheet" />
    <link href="css/floatexamples.css" rel="stylesheet" type="text/css" />
	<link href="css/select/select2.min.css" rel="stylesheet">
 <!--  <script src="js/jquery.min.js"></script>-->
   
	<script src="js/select/select2.full.js"></script>
<!------>


    <!-- Bootstrap core CSS -->

 <!-- <link href="css/bootstrap.min.css" rel="stylesheet"> 
  <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
  <link href="css/animate.min.css" rel="stylesheet">
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">-->
    <!-- Custom styling plus plugins 
    <link href="css/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/maps/jquery-jvectormap-2.0.1.css" />
    <link href="css/icheck/flat/green.css" rel="stylesheet" />
    <link href="css/floatexamples.css" rel="stylesheet" type="text/css" />
	<link href="css/select/select2.min.css" rel="stylesheet">-->
<!--	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">-->
<script src="js/jquery.baru.js" type="text/javascript"></script>
<script src="js/select/select2.full.js"></script>
    <script>
        NProgress.start();
		function load_content(page,hrefli){
				$('#maincont').html("<center><br><br><img src='images/ajax-loader.gif' /></center>");
				$.ajax({
                	type: "GET",
                	url: page,
                	data: '',
               		success: function(data){
						
						$('#maincont').html(data);	
					
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#maincont').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						
      				}
            	}); //ajax 
				var url = hrefli;
				// $('#sidebar-menu a[href="' + url1 + '"]').parent('li').removeClass('current-page');
   				 
    			
				$('#sidebar-menu a').filter(function () {
        			return this.href != url;
    			}).parent('li').removeClass('current-page').parent('ul').parent().removeClass('active');
				
				$('#sidebar-menu a').filter(function () {
        			return this.href == url;
    			}).parent('li').addClass('current-page').parent('ul').slideDown().parent().addClass('active');
				$('#sidebar-menu a[href="' + url + '"]').parent('li').addClass('current-page');
}

		
		function requestFullScreen() {
		var el = document.documentElement
    , rfs = // for newer Webkit and Firefox
           el.requestFullScreen
        || el.webkitRequestFullScreen
        || el.mozRequestFullScreen
        || el.msRequestFullscreen
;
if(typeof rfs!="undefined" && rfs){
  rfs.call(el);
} else if(typeof window.ActiveXObject!="undefined"){
  // for Internet Explorer
  var wscript = new ActiveXObject("WScript.Shell");
  if (wscript!=null) {
     wscript.SendKeys("{F11}");
  }
}
  		}
		
function notif(title,text,type){
        $(function () {
        	new PNotify({
                                title: title,
                                text: text,
                                type: type
                            });
        });
		};
    </script>
    
    <!--[if lt IE 9]>
        <script src="../assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

</head>


<body class="nav-md">

    <div class="container body">


        <div class="main_container">

            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">

                    <div class="navbar nav_title" style="border: 0;">
                        <a href="index.php" class="site_title"><img src="images/payakumbuh.png" height="40"> <span>SIMPAD</span></a>                    </div>
                    <div class="clearfix"></div>

                    <!-- menu prile quick info -->
                    <div class="profile">
                        <div class="profile_pic">
                            <img src=<?php if (file_exists($_SESSION['base_dir']."images/".$_SESSION['user'].".jpg")) { echo "images/".$_SESSION['user'].".jpg"; } else { echo "images/user.png"; } ?> alt="..." class="img-circle profile_img">                        </div>
                        <div class="profile_info">
                            <span><font size="1px"><?php echo $_SESSION['nama']; ?></font></span>
                            <h2><font size="1px"><?php echo $_SESSION['nip']; ?></font></h2>
                        </div>
                    </div>
                    <!-- /menu prile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                        <div class="menu_section">
                            <h3><?php echo $_SESSION['jabatan']; ?></h3>
                            <ul class="nav side-menu">
								<?php
									include_once $_SESSION['base_dir']."inc/db.inc.php";
									$user = $_SESSION['user'];
									$query = "SELECT menu FROM public.user WHERE username='$user'";
									$result = pg_query($query) or die('Query failed: ' . pg_last_error());
									$row = pg_fetch_array($result);
									$menu_user = $row['menu'];					
									$query = "SELECT * FROM public.menu ORDER BY menu_id,sub_id";
									$result = pg_query($query) or die('Query failed: ' . pg_last_error());
									if($menu_user != "") {
									$tmp_mid = 1;		
									$tmp_smid = 1;							
									while ($row = pg_fetch_array($result)){										
										if (strpos($menu_user, $row['menu_id'].".".$row['sub_id']) !== false) {										
											if($row['sub_id'] == 0) {
												if($tmp_mid == 1) {
													echo "<li><a><i class=\"".$row['class']."\"></i> ".$row['nm_menu']." <span class=\"fa fa-chevron-down\"></span></a>".PHP_EOL;
													$tmp_mid++;
													$tmp_smid = 1;
												} else {
													echo "</ul></li>".PHP_EOL;
													echo "<li><a><i class=\"".$row['class']."\"></i> ".$row['nm_menu']." <span class=\"fa fa-chevron-down\"></span></a>".PHP_EOL;
													$tmp_smid = 1;
												}
											} else {
												if($tmp_smid == 1) {											
													echo "<ul class=\"nav child_menu\" style=\"display: none\">".PHP_EOL;
													echo "<li><a href=\"#".$row['menu_id'].".".$row['sub_id'].$row['nm_menu']."\" onClick=\"load_content('".$row['link']."',$(this).attr('href'));\">".$row['nm_menu']."</a></li>".PHP_EOL;
													$tmp_smid++;
												} else {
													echo "<li><a href=\"#".$row['menu_id'].".".$row['sub_id'].$row['nm_menu']."\" onClick=\"load_content('".$row['link']."',$(this).attr('href'));\">".$row['nm_menu']."</a></li>".PHP_EOL;
												}
												
											}																					
										}																													
									}
									echo "</ul></li>".PHP_EOL;
								} else {
								echo "Hubungi Admin Untuk Setting Menu";
								}
								pg_free_result($result);
								pg_close($dbconn);
								?>
                            </ul>
                        </div>
                    </div>
                    <!-- /sidebar menu -->

                    <!-- /menu footer buttons -->
                    <div class="sidebar-footer hidden-small" align="right">
						<a onClick="requestFullScreen();" data-toggle="tooltip" data-placement="top" title="FullScreen">
                            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>                        </a>
                        <a href="login.php?logout=true" data-toggle="tooltip" data-placement="top" title="Logout">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>                        </a>                    </div>
                    <!-- /menu footer buttons -->
                </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav">

                <div class="nav_menu">
                    <nav class="" role="navigation">
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        <ul class="nav navbar-nav navbar-right">
							
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <img src=<?php if (file_exists($_SESSION['base_dir']."images/".$_SESSION['user'].".jpg")) { echo "images/".$_SESSION['user'].".jpg"; } else { echo "images/user.png"; } ?> alt=""><?php echo $_SESSION['user']; ?>
                                    <span class=" fa fa-angle-down"></span>                                </a>
                                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
								<?php
								if($_SESSION['level'] == '0'){
								?>
								<li>
                                        <a href="#" onClick="load_content('modul/referensi/index.php');"><i class="fa fa fa-cog pull-right"></i> Referensi</a>                                    </li>
								<li>
                                        <a href="#" onClick="load_content('modul/user/manage.php');"><i class="fa fa fa-user pull-right"></i> Management User</a>                                    </li>
								<?php
								}
								?>
                                    <li>
                                        <a href="#" onClick="load_content('modul/user/ganti_pass.php');"><i class="fa fa fa-lock pull-right"></i> Ganti Password</a>                                    </li>
                                    <li><a href="login.php?logout=true"><i class="fa fa-sign-out pull-right"></i> Log Out</a>                                    </li>
                                </ul>
                            </li>

                            

                        </ul>
                    </nav>
                </div>

            </div>
            <!-- /top navigation -->


            <!-- page content -->
            <div id="maincont" class="right_col" role="main">
            <!-- footer content -->

                
                <!-- /footer content -->
            </div>
            <!-- /page content -->

        </div>

    </div>

    <div id="custom_notifications" class="custom-notifications dsp_none">
        <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
        </ul>
        <div class="clearfix"></div>
        <div id="notif-group" class="tabbed_notifications"></div>
    </div>

    <script src="js/bootstrap.min.js"></script>

    
    <!-- chart js -->
    <script src="js/chartjs/chart.min.js"></script>
    <!-- bootstrap progress js -->
    <script src="js/progressbar/bootstrap-progressbar.min.js"></script>
    <script src="js/nicescroll/jquery.nicescroll.min.js"></script>
    <!-- icheck -->
    <script src="js/icheck/icheck.min.js"></script>
    <!-- daterangepicker -->
    <script type="text/javascript" src="js/moment.min.js"></script>
    <script type="text/javascript" src="js/datepicker/daterangepicker.js"></script>

    <script src="js/custom.js"></script>
	
	<!-- PNotify -->
    <script type="text/javascript" src="js/notify/pnotify.core.js"></script>
    <script type="text/javascript" src="js/notify/pnotify.buttons.js"></script>
    <script type="text/javascript" src="js/notify/pnotify.nonblock.js"></script>

    <!-- flot js -->
    <!--[if lte IE 8]><script type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->

    <!-- worldmap -->
  
    <!-- skycons -->
   

    <!-- dashbord linegraph -->
   
    <!-- /dashbord linegraph -->
    <!-- datepicker -->
   
	<script>
	load_content('home.php');
	</script>
    <script>
        NProgress.done();
    </script>
    <!-- /datepicker -->
    <!-- /footer content -->
</body>

</html>