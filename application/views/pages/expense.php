<style>.send_emailclick{ display:none; }</style>
<!-- Start::app-content -->
<div class="main-content app-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Manage Expenses</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboards</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Expense</li>
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
                                    <a href="<?= base_url('add_expense') ?>" class="btn btn-primary" title="Add Expense"
                                        alt="Add Expense">
                                        <i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Add Expense
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                        <option value="<?= $record['id']; ?>"
                                            <?= ($record['id'] == urlencode(base64_decode($_GET['cmpyid']))) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($record['companyname']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <!-- Invoice Date -->
                                <div class="col-auto">
                                    <label for="datefilter" class="form-label">Expense Date</label>
                                    <div class="input-group">
                                        <input type="text" id="datefilter" name="datefilter"
                                            class="form-control datepicker" placeholder="Select date"
                                            value="<?= (isset($_GET['datefilter'])) ? str_replace(['_'], ' ', $_GET['datefilter']) : '' ?>" readonly>
                                        <button type="submit" class="btn btn-success" id="searchBtn" title="Search">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <a href="<?= base_url('expense'); ?>" title="Refresh" class="btn btn-primary">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="expense_list" class="table table-bordered  w-100">
                                <thead>
                                    <tr>
                                        <th>S.NO</th>
                                        <th>Expense No</th>
                                        <th> Expense Date</th>
                                        <th> Vendor Name</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Balance</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="fw-bold">Total:</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End::app-content -->
<!-- Mail Model Section -->
<div class="modal fade" id="sendEmailmodal" tabindex="-1" aria-labelledby="sendEmailmodal" data-bs-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title text-white" id="staticBackdropLabel2">Email</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendEmailMethod">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="form-text" class="form-label fs-14 text-dark">To <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="to_email" class="form-control to_EMAIL" id="to_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="form-text" class="form-label fs-14 text-dark">CC</label>
                        <input type="text" name="cc_email" class="form-control" id="cc_email">
                    </div>
                    <div class="mb-3">
                        <label for="form-text" class="form-label fs-14 text-dark">Subject</label>
                        <input type="text" name="subject" class="form-control SUBJECT" id="subject">
                    </div>
                    <div class="mb-3">
                        <label for="form-text" class="form-label fs-14 text-dark">Message</label>
                        <textarea class="form-control MESSAGE" name="message" id="message"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary-light load_para" type="button" disabled style="display: none;">
                        <span class="spinner-border spinner-border-sm align-middle" role="status"
                            aria-hidden="true"></span>
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
 $(document).on('click', '.delete_expenseid', function (e) {
    e.preventDefault();
    // Get CSRF token from meta
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>'; // get token name
    // Get customer ID
    var id = $(this).data('id');
   
    // Show confirmation dialog
    Swal.fire({
        title: 'Are you sure want to delete this expense?',
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
                url: '<?= base_url('Expense/delete_expense'); ?>',
                data: formData,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status === "success") {
                        Toastify({
                            text: "Successfully deleted expense",
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
                            text: "Failed to delete expense.",
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
        opens: 'left',
        <?php if(!isset($_GET['datefilter'])) { ?>
            startDate: start,
            endDate: end,
        <?php } ?>
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
$(document).ready(function() {
    var exp_table = $('#expense_list').DataTable({
        "responsive": false,
        "processing": true,
        "serverSide": true,
        "pageLength": 10,
        "lengthMenu": [ [5, 10, 50, 100, 500], [5, 10, 50, 100, 500] ],
        "ajax": {
            "url": 'getexpense',
            "type": "POST",
            "data": function(d) {
                d['<?= $this->security->get_csrf_token_name(); ?>'] =
                    '<?= $this->security->get_csrf_hash(); ?>';
                d.datefilter = $('#datefilter').val(); // Correctly appending the datefilter
                d.cmpyname = $('#company').val()
            },
            "dataSrc": function(json) {
                csrfHash = json['<?= $this->security->get_csrf_token_name(); ?>'];
                return json.data;
            }
        },
        "columns": [{
                "data": "exp_id"
            },
            {
                "data": "expense",
                "className": "style-column"
            },
            {
                "data": "expense_date",
                "className": "style-column"
            },
            {
                "data": "vendor_name"
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
            localStorage.setItem('expense', JSON.stringify(data));
        },
        "stateLoadCallback": function(settings) {
            var savedState = localStorage.getItem('expense');
            return savedState ? JSON.parse(savedState) : null;
        },
        "dom": "<'row'<'col-sm-4 mt-2'l><'col-sm-4 mt-2 d-flex justify-content-center align-items-center text-center'B><'col-sm-4 mt-2'f>>" +
            "<'row'<'col-sm-12 mt-2'tr>>" + "<'row'<'col-sm-6 mt-2'i><'col-sm-6 mt-2'p>>",
        "buttons": [{
            "extend": "excel",
            "title": function() {
                return "Expenses Report-" + $('#datefilter').val();
            },
            "className": "btn-sm",
            "exportOptions": {
                "columns": ':visible'
            }
        }],
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
            var total = api.column(4, { search: 'applied' }).data().reduce(function (a, b) { return parseAmount(a) + parseAmount(b);}, 0);
            var paid = api.column(5, { search: 'applied' }).data().reduce(function (a, b) { return parseAmount(a) + parseAmount(b);}, 0);
            var balance = api.column(6, { search: 'applied' }).data().reduce(function (a, b) { return parseAmount(a) + parseAmount(b);}, 0);

            var formattedTotal = '<b> ₹' + total.toLocaleString('en-IN', { minimumFractionDigits: 2 })+'</b>';
            var formattedPaidTotal = '<b> ₹' + paid.toLocaleString('en-IN', { minimumFractionDigits: 2 })+'</b>';
            var formattedBalance = '<b> ₹' + balance.toLocaleString('en-IN', { minimumFractionDigits: 2 })+'</b>';

            $(api.column(4).footer()).html(formattedTotal);
            $(api.column(5).footer()).html(formattedPaidTotal);
            $(api.column(6).footer()).html(formattedBalance);
        },
    });
     $('body').on('change', '.applyBtn, .datepicker', function() {
        if (exp_table) exp_table.draw(); // add guard
    });
    $('#searchBtn, #company').on('click change', function(e) {
        e.preventDefault();
        if (exp_table) exp_table.draw(); // add guard
    });
});
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
