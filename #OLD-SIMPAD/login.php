<?php
date_default_timezone_set('Asia/Jakarta');
$ketemu = "y";
if(isset($_GET['logout']))
{
	if (!isset($_SESSION)) { session_start(); }
	session_unset();
	session_destroy();
}
if (!isset($_SESSION)) { session_start(); }
if(isset($_SESSION['base_dir']) && isset($_SESSION['user'])) {
	header('Location: /index.php');
	exit;
}
if(isset($_POST["commit"]))
{
	include_once "inc/db.inc.php";
	$user = pg_escape_string($_POST['login']);
	$pass = strtoupper(md5(pg_escape_string($_POST['password'])));
	$query = "SELECT * FROM public.user WHERE username = '$user' AND password = '$pass'";
	$result = pg_query($query) or die('Query failed: ' . pg_last_error());
	if(pg_num_rows($result) == '0')
	{ 
		$ketemu = "f";		
	} else {
		$data = pg_fetch_row($result);
		if( ($data[0] == $user) and ($data[1] == $pass) and ($data[5] == '1'))
		{
			session_start();
			if (!isset($_SESSION['user'])) {
  				$_SESSION['user'] = $data[0];
				$_SESSION['level'] = $data[2];
				$_SESSION['nip'] = $data[3];
				$_SESSION['nama'] = $data[4];
				$_SESSION['jabatan'] = $data[6];
				$_SESSION['pemungut']=$data[8];
				
				
				//linux
				$_SESSION['base_dir'] = "/home/simpad/public_html/";
				$_SESSION['PDF_GEN'] = "/usr/local/bin/wkhtmltopdf";
				$_SESSION['base_url'] =  "http://192.168.10.90/";
				$_SESSION['TMP_FOLDER'] = "/home/simpad/public_html/tmp/".$_SESSION['user']."/";
				
				//windows
				//$_SESSION['base_dir'] = "D:/wamp/www/htdocs/simpad_dev/";
				//$_SESSION['base_url'] =  "http://localhost/htdocs/simpad_dev/";				
				//$_SESSION['PDF_GEN'] = "C:\progra~1\wkhtmltopdf\bin\wkhtmltopdf.exe";
				//$_SESSION['TMP_FOLDER'] = "D:/wamp/www/htdocs/simpad_dev/tmp/".$_SESSION['user']."/";
				
				//$_SESSION['LAST_ACTIVITY'] = time();
				pg_free_result($result);
				pg_close($dbconn);
				header('Location: index.php');
			} else {
				pg_free_result($result);
				pg_close($dbconn);
				header('Location: index.php');
			}
		} else {
			$ketemu = "n";
		}
	}
	pg_free_result($result);
	pg_close($dbconn);	
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

    <title>SIMPAD - Login</title>

    <!-- Bootstrap core CSS -->

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/icheck/flat/green.css" rel="stylesheet">


    <script src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/notify/pnotify.core.js"></script>
    <script type="text/javascript" src="js/notify/pnotify.buttons.js"></script>
    <script type="text/javascript" src="js/notify/pnotify.nonblock.js"></script>
	<script type="text/javascript">
        function notif(text){
        $(function () {
        	new PNotify({
                                title: 'Error',
                                text: text,
                                type: 'error'
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

<body style="background:#F7F7F7;">
    
    <div class="">
        <a class="hiddenanchor" id="toregister"></a>
        <a class="hiddenanchor" id="tologin"></a>
        <div id="wrapper">
            <div id="login" class="animate form">
				<div style="margin-left:-10px;"><img src="images/logo.png"></div>
                <section class="login_content">
                    <form id="frm-login" name="frm-login" method="post">
                        <h1>LOGIN</h1>
                        <div>
                            <input name="login" type="text" class="form-control" placeholder="Username" required="required" />
                        </div>
                        <div>
                            <input name="password" type="password" class="form-control" placeholder="Password" required="required" autocomplete="off" />
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary" name="commit" >Login</button>
                        </div>
                        <div class="clearfix"></div>
                        <div class="separator">
                            <div class="clearfix"></div>
                            <br />
                            <div>
                                <p>©2015 All Rights Reserved.</p>
                            </div>
                        </div>
                    </form>
                    <!-- form -->
                </section>
                <!-- content -->
            </div>
        </div>
    </div>

</body>
 		<?php
			if($ketemu != "y")
			{
				if($ketemu == "n")
				{
					?>
					<script type="text/javascript">
        			notif('Username Anda Sudah Tidak Aktif');
					</script>
					<?php
				} else {
					?>
					<script type="text/javascript">
        			notif('Username / Password Anda Salah !!!');
					</script>
					<?php
				}
			} 
		?>
</html>