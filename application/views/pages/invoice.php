<style>.send_emailclick{ display:none; }</style>
<!-- Start::app-content -->
<div class="main-content app-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Manage Invoice</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboards</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Invoice</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="container-fluid row">
                            <div class="col-9">
                                <div class="card-title">
                                    <a href="<?= base_url('add_invoice') ?>" class="btn btn-primary" title="Add Invoice">
                                        <i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Add Invoice
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Filter Form -->
                    <div class="container-fluid my-3">
                        <form method="GET" action="<?= base_url('invoice_list'); ?>">
                            <div class="row g-2 align-items-end">
                                <!-- Select Company -->
                                <div class="col-auto">
                                    <label for="company" class="form-label">Select Company</label>
                                    <select name="company_id" id="company" class="form-select">
                                        <option value="" disabled selected>-- Select Company --</option>
                                        <?php if (!empty($company_records)): ?>
                                            <?php foreach ($company_records as $record): ?>
                                                <option value="<?= $record['id']; ?>" <?= ($record['id'] == urlencode(base64_decode($_GET['cmpyid'] ?? ''))) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($record['companyname']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <!-- GST Type -->
                                <div class="col-auto">
                                    <label for="GST" class="form-label">GST Type</label>
                                    <select id="gst_filter" name="gst_type" class="form-select">
                                        <option value="All">All Bills</option>
                                        <option value="GST">GST</option>
                                        <option value="NON-GST">NON-GST</option>
                                    </select>
                                </div>
                                <!-- Invoice Date -->
                                <div class="col-auto">
                                    <label for="datefilter" class="form-label">Expense Date</label>
                                    <div class="input-group">
                                        <input type="text" id="datefilter" name="datefilter" class="form-control datepicker" placeholder="Select date"
                                            value="<?= isset($_GET['datefilter']) ? str_replace('_', ' ', $_GET['datefilter']) : '' ?>" readonly>
                                        <button type="submit" class="btn btn-success" id="searchBtn" title="Search">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <a href="<?= base_url('invoice'); ?>" title="Refresh" class="btn btn-primary">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- End Filter Form -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="invoice_list" class="table table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th>S.NO</th>
                                        <th>Invoice No</th>
                                        <th>Invoice Date</th>
                                        <th>Company Name</th>
                                        <th>Customer Name</th>
                                        <th>GST</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Balance</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <td colspan="5"></td>
                                        <td>Total:</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div> <!-- End Card -->
            </div>
        </div>
    </div>
</div>
<!-- End::app-content -->
<!-- Mail Modal Section -->
<div class="modal fade" id="sendEmailmodal" tabindex="-1" aria-labelledby="sendEmailmodal" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title text-white" id="staticBackdropLabel2">Email</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendEmailMethod">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="to_email" class="form-label fs-14 text-dark">To <span class="text-danger">*</span></label>
                        <input type="email" name="to_email" class="form-control to_EMAIL" id="to_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="cc_email" class="form-label fs-14 text-dark">CC</label>
                        <input type="text" name="cc_email" class="form-control" id="cc_email">
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label fs-14 text-dark">Subject</label>
                        <input type="text" name="subject" class="form-control SUBJECT" id="subject">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label fs-14 text-dark">Message</label>
                        <textarea class="form-control MESSAGE" name="message" id="message"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary-light load_para" type="button" disabled style="display: none;">
                        <span class="spinner-border spinner-border-sm align-middle" role="status" aria-hidden="true"></span>
                        Loading...
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- SheetJS Library to export -->
<script src="<?= base_url('assets/js/package/xlsx.full.min.js'); ?>"></script>
<script type="text/javascript">
 $(document).on('click', '.delete_invoiceid', function (e) {
    e.preventDefault();
    // Get CSRF token from meta
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>'; // get token name
    // Get customer ID
    var id = $(this).data('id');
   
    // Show confirmation dialog
    Swal.fire({
        title: 'Are you sure want to delete this invoice?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            var formData = new FormData();
            formData.append('id', id);
            formData.append(csrfName, csrfToken);
            $.ajax({
                type: 'POST',
                url: '<?= base_url('Invoice/delete_invoice'); ?>',
                data: formData,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status === "success") {
                        Toastify({
                            text: "Successfully deleted Invoice",
                            backgroundColor: "linear-gradient(to right, #28a745, #28a745)",
                            gravity: "top",
                            position: 'right',
                            duration: 1000,
                            close: true
                        }).showToast();
                         setTimeout(function() {
                            window.location.href = '<?= base_url('invoice') ?>';
                        }, 1000);
                    } else {
                        Toastify({
                            text: "Failed to delete invoice.",
                            backgroundColor: "linear-gradient(to right, #ff4d4d, #ff0000)",
                            gravity: "top",
                            position: 'right',
                            duration: 1000,
                            close: true
                        }).showToast();
                    }
                },
                error: function () {
                    Toastify({
                        text: "An error occurred while deleting the invoice.",
                        backgroundColor: "linear-gradient(to right, #ff4d4d, #ff0000)",
                        gravity: "top",
                        position: 'right',
                        duration: 1000,
                        close: true
                    }).showToast();
                }
            });
        }
    });
});
$(function() {
    var start = moment().startOf('month');
    var end = moment().endOf('month');
    function customrange(start, end) {
        $('#reportrange span').html(start.format('DD-MM-YYYY') + ' to ' + end.format('DD-MM-YYYY'));
    }
    $('.datepicker').daterangepicker({
        locale: {
            format: 'DD-MM-YYYY',
            separator: ' to ',
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July',
                'August', 'September', 'October', 'November', 'December'
            ],
        },
        <?php if(!isset($_GET['datefilter'])) { ?>
            startDate: start,
            endDate: end,
        <?php } ?>
        opens: 'left',
        ranges: {
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment()
                .subtract(1, 'month').endOf('month')
            ]
        }
    }, customrange);
    customrange(start, end)
});
var inv_table;
$(document).ready(function() {
    inv_table = $('#invoice_list').DataTable({
        "responsive": false,
        "processing": true,
        "serverSide": true,
        "pageLength": 10,
        "lengthMenu": [ [5, 10, 50, 100, 500], [5, 10, 50, 100, 500] ],
        "ajax": {
            "url": 'getinvoicedata',
            "type": "POST",
            "data": function(d) {
                d['<?= $this->security->get_csrf_token_name(); ?>'] =
                    '<?= $this->security->get_csrf_hash(); ?>';
                d.datefilter = $('#datefilter').val();
                d.gst_type = $('#gst_filter').val();
                d.cmpyname = $('#company').val();
            },
            "dataSrc": function(json) {
                csrfHash = json['<?= $this->security->get_csrf_token_name(); ?>'];
                return json.data;
            }
        },
        "columns": [{
                "data": "inv_id"
            },
            {
                "data": "invoice",
                "className": "style-column"
            },
            {
                "data": "invoice_date",
                "className": "style-column"
            },
            {
                "data": "company_name"
            },
            {
                "data": "customer_name"
            },
            {
                "data": "gst"
            },
            {
                "data": "grand_total"
            },
            {
                "data": "paid"
            },
            {
                "data": "balance"
            },
            {
                "data": "action"
            }
        ],
        "order": [
            [0, "desc"]
        ],
        "columnDefs": [{
            "orderable": false,
            "targets": "_all"
        }],
        "colReorder": true,
        "stateSave": true,
        "stateSaveCallback": function(settings, data) {
            localStorage.setItem('invoice', JSON.stringify(data));
        },
        "stateLoadCallback": function(settings) {
            var savedState = localStorage.getItem('invoice');
            return savedState ? JSON.parse(savedState) : null;
        },
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            // Remove ₹ and convert to float
            var parseAmount = function (i) {
              return typeof i === 'string'
                ? parseFloat(i.replace(/[^0-9.-]+/g, '')) || 0
                : typeof i === 'number'
                ? i
                : 0;
            };
            // Total over all pages
            var total = api.column(6, { search: 'applied' }).data().reduce(function (a, b) { return parseAmount(a) + parseAmount(b);}, 0);
            var paid = api.column(7, { search: 'applied' }).data().reduce(function (a, b) { return parseAmount(a) + parseAmount(b);}, 0);
            var balance = api.column(8, { search: 'applied' }).data().reduce(function (a, b) { return parseAmount(a) + parseAmount(b);}, 0);

            var formattedTotal = '<b> ₹' + total.toLocaleString('en-IN', { minimumFractionDigits: 2 })+'</b>';
            var formattedPaidTotal = '<b> ₹' + paid.toLocaleString('en-IN', { minimumFractionDigits: 2 })+'</b>';
            var formattedBalance = '<b> ₹' + balance.toLocaleString('en-IN', { minimumFractionDigits: 2 })+'</b>';

            $(api.column(6).footer()).html(formattedTotal);
            $(api.column(7).footer()).html(formattedPaidTotal);
            $(api.column(8).footer()).html(formattedBalance);
        },
        "dom": "<'row'<'col-sm-4 mt-2'l><'col-sm-4 mt-2 d-flex justify-content-center align-items-center text-center'B><'col-sm-4 mt-2'f>>" +
            "<'row'<'col-sm-12 mt-2'tr>>" + "<'row'<'col-sm-6 mt-2'i><'col-sm-6 mt-2'p>>",
        buttons: [{
                extend: 'excel',
                className: 'btn btn-sm btn-outline-primary',
                text: 'Excel'
            },
            {
                extend: 'print',
                className: 'btn btn-sm btn-outline-secondary',
                text: 'Print'
            }
        ]
    });
});
$('body').on('click', '.applyBtn', function() {
    inv_table.draw();
});
$('#searchBtn').on('click', function(e) {
    e.preventDefault();
    inv_table.draw();
});
$('#gst_filter, #company').on('change', function() {
    inv_table.draw();
});
$invoice_id = <?php echo isset($_POST['invoice_list']) ? base64_decode(urldecode($_POST['invoice_list'])) : ''; ?>
document.getElementById('company').addEventListener('change', function() {
    var selectedCompany = this.value;
    var rows = document.querySelectorAll('#billsTable tbody tr');
    rows.forEach(function(row) {
        if (selectedCompany === 'All' || row.getAttribute('data-company') ===
            selectedCompany) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
