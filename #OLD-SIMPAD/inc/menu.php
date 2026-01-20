<?php
									if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
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
													echo "<li><a href=\"#".$row['menu_id'].".".$row['sub_id'].$row['nm_menu']."\" onClick=\"load_content('".$row['link']."');\">".$row['nm_menu']."</a></li>".PHP_EOL;
													$tmp_smid++;
												} else {
													echo "<li><a href=\"#".$row['menu_id'].".".$row['sub_id'].$row['nm_menu']."\" onClick=\"load_content('".$row['link']."');\">".$row['nm_menu']."</a></li>".PHP_EOL;
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