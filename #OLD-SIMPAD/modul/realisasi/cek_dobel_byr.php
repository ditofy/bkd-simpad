
    
<!--
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js" type="text/javascript"></script>
        <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js" type="text/javascript"></script>
        <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" type="text/javascript"></script>-->
<body>
        <div class="container">
            <div class="card mt-5">
                <div class="card-body">
                    <form id="form-auth">
                        <div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Pilih Tanggal Awal - Tanggal Akhir </label>
		<div class="col-md-3 col-sm-3 col-xs-6">
        	<input type="text" id="date-awal" name="date-awal" class="form-control has-feedback-left" placeholder="Tanggal Awal" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only"></span>
        </div>
	
	<div class="col-md-3 col-sm-3 col-xs-6">
        	<input type="text" id="date-akhir" name="date-akhir" class="form-control has-feedback-left" placeholder="Tanggal Akhir" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only"></span>	
        </div>
		
	</div>
                        <div class="row">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Tampilkan Data</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card my-3">
                <div class="card-body">
                    <table class="table table-sm table-bordered" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <td class="text-center">Tax Type</td>
                                <td class="text-center">Nomor</td>
                                <td class="text-center">Outlet ID</td>
                                <td class="text-center">Billing Number</td>
                                <td class="text-center">Amount</td>
                                <td class="text-center">Reference Number</td>
                                <td class="text-center">Billing Date</td>
                                <td class="text-center">PJSP</td>
                                <td class="text-center">Customer Name</td>
                                <td class="text-center">Val Time Out</td>
                            </tr>
                        </thead>
                        <tbody id="output"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Toast Container -->
		<div id="toast-container" class="toast-container top-0 end-0 p-3">
        	<div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      			<div id="toast-header" class="toast-header">
        			<strong class="me-auto">Info</strong>
        			<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      			</div>
      			<div class="toast-body" id="toastMessage"></div>
    		</div>
		</div>
  
        <script>
		$(document).ready(function () {
 
			$('#date-awal').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
			
			$('#date-akhir').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
		});	
			
            function showToast(message, bg) {
                document.getElementById('toast-header').classList.remove("bg-danger-subtle", "bg-success-subtle");
				document.getElementById('toast-header').classList.add(bg);
				
                // Set the message dynamically
				document.getElementById('toastMessage').innerText = message;

				// Get the toast element
				var toast = new bootstrap.Toast(document.getElementById('liveToast'), {
					delay: 180000  // Set the toast to stay for 3 minutes (180,000 milliseconds)
				}).show();
			}

            $(function() {
                var start = moment();
                var end = moment().add(1, 'months');

                document.getElementById('date-awal').value = start.format('YYYY-MM-DD');
                document.getElementById('date-akhir').value = end.format('YYYY-MM-DD');

                $('#daterange').daterangepicker({
                    startDate: start,
                    endDate: end,
                    locale: {
                        format: 'D MMMM YYYY'
                    }
                });

                $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('D MMMM YYYY') + ' - ' + picker.endDate.format('D MMMM YYYY'));
                    $('#date-awal').val(picker.startDate.format('YYYY-MM-DD'));
                    $('#date-akhir').val(picker.endDate.format('YYYY-MM-DD'));
                });
            });

            function scrollToBottom(element) {
                element.scrollTop = element.scrollHeight;
            }
            
            document.getElementById('form-auth').addEventListener('submit', async function (e) {
                e.preventDefault();

                let outputEl = document.getElementById('output');
                outputEl.innerHTML = ''; // Bersihkan output sebelumnya

                const formData = new URLSearchParams();
                formData.append('date-awal', document.getElementById('date-awal').value);
                formData.append('date-akhir', document.getElementById('date-akhir').value);

                try {
                    const response = await axios.post('modul/realisasi/load.php', formData);
                    outputEl.innerHTML = response.data.rows;
                	if (response.data.double == true) {
                    	msg = 'Ada Data Pembayaran Double';
                    	bg = 'bg-danger-subtle';
                    } else {
                    	msg = 'Tidak Ada Data Pembayaran Double';
                    	bg = 'bg-success-subtle'
                    }
                	showToast(msg, bg);
                } catch (error) {
                    outputEl.innerHTML = '<p style="color:red;">Terjadi kesalahan saat memuat data.</p>';
                    console.error(error);
                }
            });
        </script>
 </body>