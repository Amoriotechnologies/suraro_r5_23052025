<!-- Start::app-content -->
<div class="main-content app-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Manage Customers</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboards</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Customers</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <a href="<?= base_url('add_customer') ?>" class="btn btn-primary" title="Add Customer"
                                alt="Add Customer">
                                <i class="ri-add-line" aria-hidden="true"></i>&nbsp;Add Customer
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="customer_table" class="table table-bordered text-nowrap w-100 ">
                                <thead>
                                    <tr>
                                        <th>S.NO</th>
                                        <th>Name</th>
                                        <th>Email Address</th>
                                        <th>Mobile Number</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Balance Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Customer Detail Modal -->
<div class="modal fade" id="customerDetailModal" tabindex="-1" aria-labelledby="customerDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="customerDetailModalLabel">Customer Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body mb-3" id="customerDetailContent" style="max-height: 70vh; overflow-y: auto;">
        <!-- Content from view will be loaded here -->
        <div class="text-center">
          <span class="spinner-border" role="status"></span> Loading...
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<!-- End::app-content -->
<script type="text/javascript">
    $(document).on('click', '.delete_customerid', function (e) {
    e.preventDefault();
    // Get CSRF token from meta
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>'; // get token name
    // Get customer ID
    var id = $(this).data('id');
    
    // Show confirmation dialog
    Swal.fire({
        title: 'Are you sure want to delete this customer?',
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
                url: '<?= base_url('Customer/delete_customer'); ?>',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response === "success") {
                        Toastify({
                            text: "Successfully deleted customer details.",
                            backgroundColor: "linear-gradient(to right, #845adf, #845adf)",
                            gravity: "top",
                            position: 'right',
                            duration: 1000,
                            close: true
                        }).showToast();
                        location.reload();
                    } else {
                        Toastify({
                            text: "Failed to delete customer details.",
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
                        text: "An error occurred while deleting the customer.",
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
    $(function () {
        var start = moment().subtract(29, 'days');
        var end = moment();
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
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment()
                    .subtract(1, 'month').endOf('month')
                ]
            }
        }, customrange);
        customrange(start, end)
    });
    var inv_table;
    $(document).ready(function () {
        inv_table = $('#customer_table').DataTable({
            "responsive": false,
            "processing": true,
            "serverSide": true,
            "pageLength": 10,
            "lengthMenu": [ [5, 10, 50, 100, 500], [5, 10, 50, 100, 500] ],
            "ajax": {
                "url": 'customer_table',
                "type": "POST",
                "data": function (d) {
                    d['<?= $this->security->get_csrf_token_name(); ?>'] =
                        '<?= $this->security->get_csrf_hash(); ?>';
                    d.datefilter = $('#datefilter').val(); // Correctly appending the datefilter
                },
                "dataSrc": function (json) {
                    csrfHash = json['<?= $this->security->get_csrf_token_name(); ?>'];
                    return json.data;
                }
            },
            "columns": [{
                "data": "cust_id"
            },
            {
                "data": "custname",
                "className": "style-column"
            },
            {
                "data": "vendor_name"
            },
            {
                "data": "mobile_number"
            },{
                "data": "grand_total"
            },{
                "data": "paid"
            },{
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
            "stateSaveCallback": function (settings, data) {
                localStorage.setItem('customer_table', JSON.stringify(data));
            },
            "stateLoadCallback": function (settings) {
                var savedState = localStorage.getItem('customer_table');
                return savedState ? JSON.parse(savedState) : null;
            },
            "dom": "<'row'<'col-sm-4 mt-2'l><'col-sm-4 mt-2 d-flex justify-content-center align-items-center text-center'B><'col-sm-4 mt-2'f>>" +
                "<'row'<'col-sm-12 mt-2'tr>>" + "<'row'<'col-sm-6 mt-2'i><'col-sm-6 mt-2'p>>",
            "buttons": [{
                "extend": "excel",
                "title": function () {
                    return "Customers Report-" + $('#datefilter').val();
                },
                "className": "btn-sm",
                "exportOptions": {
                    "columns": ':visible'
                }
            }],
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api();
                var parseAmount = function (i) {
                    return typeof i === 'string' ?
                        parseFloat(i.replace(/[^0-9.-]+/g, '')) || 0 :
                        typeof i === 'number' ?
                            i :
                            0;
                };
                var total = api.column(4, {
                    search: 'applied'
                }).data()
                    .reduce(function (a, b) {
                        return parseAmount(a) + parseAmount(b);
                    }, 0);
                var formattedTotal = '<b> ₹' + total.toLocaleString('en-IN', {
                    minimumFractionDigits: 2
                }) + '</b>';
                $(api.column(4).footer()).html(formattedTotal);
            }
        });
    });
    $('body').on('click', '.applyBtn', function () {
        inv_table.draw();
    });
    $('#searchBtn').on('click', function (e) {
        e.preventDefault();
        inv_table.draw();
    });
  $(document).on('click', '.viewCustomerDetail', function () {
    var customerId = $(this).data('id');
    var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Make sure this is present
    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
    $('#customerDetailModal').modal('show');
    $('#customerDetailContent').html('<div class="text-center"><span class="spinner-border" role="status"></span> Loading...</div>');
    // Create a blank FormData object
    var formData = new FormData();
    formData.append('id', customerId);
    formData.append(csrfName, csrfToken); // Append CSRF token
   $.ajax({
    url: '<?= base_url("Customer/view_customer_details") ?>',
    type: 'POST',
    data: formData,
    dataType: 'json',
    processData: false,        // Important!
    contentType: false,        // Important!
    success: function (response) {
        
        $('#customerDetailContent').html(response.html); 
    },
    error: function (xhr) {
        $('#customerDetailContent').html('<div class="alert alert-danger">Failed to load customer details (Error ' + xhr.status + ').</div>');
    }
});
});
</script>