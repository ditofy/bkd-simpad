<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/function.php";
include_once $_SESSION['base_dir']."inc/conn.php";

$conn = conn();
$date_start = date("Y-m-d",strtotime($_POST['date-awal']));
$date_end = date("Y-m-d",strtotime($_POST['date-akhir']));


  //  $date_start = $_POST['date-start'];
  //  $date_end = $_POST['date-end'];

    if (! validateDate($date_start) || ! validateDate($date_end)) {
        echo "Format tanggal tidak valid.<br>";
    } else {
        $rows = select($conn, "SELECT t.*, 
       		(SELECT CASE WHEN COUNT(*) > 1 THEN 'D' ELSE 'U' END
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
