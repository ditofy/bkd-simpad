
<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
header('Cache-Control: no-cache'); 
header('Content-Type: text/event-stream;');
include_once $_SESSION['base_dir']."inc/function.php";
include_once $_SESSION['base_dir']."inc/conn.php";

$conn = conn();
$date_start=date("Y-m-d",strtotime($_POST['tgl_awal']));
$date_end = date("Y-m-d",strtotime($_POST['tgl_akhir']));

?>

<div class="x_content">
<table id="t-detail-pembayaran" class="table table-striped responsive-utilities jambo_table" style="font-size:12px;">										
                                        <thead>
											<tr class="headings">
											 <td colspan="9" align="center"><h3>DATA PEMBAYARAN PAJAK DAERAH  <?php echo $row_obj['nm_obj_pajak']; ?> TANGGAL: <?php echo $date_start; ?> s.d <?php echo $date_end; ?></h3></td>
											 </tr>
                                            <tr>
                                <td class="text-center">Tax Type</td>
                                <td class="text-center">Nomor Bayar</td>
                                <td class="text-center">Outlet ID</td>
                                <td class="text-center">Billing Number</td>
                                <td class="text-center">Amount</td>
                                <td class="text-center">Reference Number</td>
                                <td class="text-center">Billing Date</td>
                                <td class="text-center">PJSP</td>
                                <td class="text-center">Customer Name</td>
                              
                            </tr>
                            </thead><tbody>
	<?php  if (! validateDate($date_start) || ! validateDate($date_end)) {
        echo "Format tanggal tidak valid.<br>";
    } else {
        $rows = select($conn, "SELECT t.*, 
       		(SELECT CASE 
                   WHEN COUNT(*) > 1 THEN 'D'
                   ELSE 'U' 
                END
        	FROM qris.trx 
        	WHERE nop = t.nop) AS mark
		FROM qris.trx AS t
		WHERE DATE(t.billing_date) >= ? 
  			AND DATE(t.billing_date) <= ?
		ORDER BY t.billing_date DESC",
        [
            $date_start,
            $date_end,
        ]);
		$data = '';
    	$double = false;
		
		   foreach ($rows as $row) {
		   if ($row['mark'] == 'D') {
            	$double = true;
            	$mark = 'bg-info';
            } else {
            	$mark = '';
            }	
				$data .= '		

<tr>
                <td class="' . $mark . ' text-center">' . $row['tax_type'] . '</td>
                <td class="' . $mark . '">' . $row['nop'] . '</td>
                <td class="' . $mark . ' text-center">' . $row['outlet_id'] . '</td>
                <td class="' . $mark . ' text-center">' . $row['billing_number'] . '</td>
                <td class="' . $mark . ' text-end">' . number_format($row['amount'], 2, ',', '.') . '</td>
                <td class="' . $mark . ' text-center">' . $row['reference_number'] . '</td>
                <td class="' . $mark . ' text-center">' . $row['billing_date'] . '</td>
                <td class="' . $mark . '">' . $row['pjsp'] . '</td>
                <td class="' . $mark . '">' . $row['customer_name'] . '</td>
                <td class="' . $mark . ' text-end">' . $row['val_time_out'] . '</td>
            </tr>';
        } 
		
		echo json_encode([
            'rows' => $data,
            'double' => $double
        ]);

}


?>

</tbody>

                                    </table>
									
                                </div>
