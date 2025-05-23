<!-- Select2 CSS -->
<link href="<?= base_url('assets/css/select2@4.1.0-select2.min.css'); ?>" rel="stylesheet" />
<!-- Select2 JS -->
<script src="<?= base_url('assets/js/select2@4.1.0-select2.min.js'); ?>"></script>
<style type="text/css">
    .select2.select2-container {
    width: 90% !important;
}
</style>
<!-- Start::app-content -->
<div class="main-content app-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">
                <?php if(!isset($invoicedata['customer_id'])){ echo "New Invoice"; }else{ echo "Edit Invoice"; }?></h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboards</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?php if(!isset($invoicedata['customer_id'])){ echo "New Invoice"; }else{ echo "Edit Invoice"; }?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <input type="hidden" id="additional_cost_total" name="additional_cost_total" value="0">
                        <form id="submit_invoicedata" method="post">
                            <input type="hidden"
                                value="<?= isset($latest_invoices['SM1']->invoice_number) ? $latest_invoices['SM2']->invoice_number : ''; ?>"><input
                                type="hidden"
                                value="<?= isset($latest_invoices['SM2']->invoice_number) ? $latest_invoices['SM2']->invoice_number : ''; ?>"><input
                                type="hidden"
                                value="<?= isset($latest_invoices['SM3']->invoice_number) ? $latest_invoices['SM2']->invoice_number : ''; ?>">
                            <?php if (!empty($company_records)): ?>
                            <?php foreach ($company_records as $record): ?>
                            <input type="hidden" class="companyinput<?= $record['id']; ?>"
                                value="<?= isset($latest_invoices[$record['shortterm']]->invoice_number) ? $latest_invoices[$record['shortterm']]->invoice_number : ''; ?>">
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="mb-3">
                                        <input type="hidden" id="invoiceid" name="invoiceid" value=<?= $invoiceid; ?>>
                                        <label for="company_id" class="form-label fs-14 text-dark">Select Company <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" name="company_id" id="company_id" required>
                                            <option value="" disabled selected>-- Select Company --</option>
                                            <?php if (!empty($company_records)): ?>
                                            <?php foreach ($company_records as $record): ?>
                                            <option value="<?= $record['id']; ?>"
                                                <?= (isset($invoicedata['company_id']) && $invoicedata['company_id'] == $record['id']) ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($record['companyname']); ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <?php
                            // Detect mode
                            $isEdit = isset($invoicedata['billtype']) && !empty($invoicedata['billtype']);
                            ?>
                            <div class="row">
                                <div class="col-md-4" id="billTypeSection"
                                    style="display:<?= ($isEdit && $invoicedata['billtype'] == 'GST') ? 'block' : 'none'; ?>">
                                    <div class="mb-3">
                                        <label class="form-label fs-14 text-dark d-block">Bill Type <span
                                                class="text-danger">*</span></label>
                                        <?php if ($isEdit): ?>
                                        <!-- Hidden input to POST value -->
                                        <input type="hidden" name="bill_type"
                                            value="<?= htmlspecialchars($invoicedata['billtype']); ?>">
                                        <!-- Disabled radio buttons (only visual) -->
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bill_type" id="gst_bill"
                                                value="GST" disabled
                                                <?= ($invoicedata['billtype'] == 'GST') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="gst_bill">GST Bill</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bill_type"
                                                id="non_gst_bill" value="Non-GST" disabled
                                                <?= ($invoicedata['billtype'] == 'Non-GST') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="non_gst_bill">Non-GST Bill</label>
                                        </div>
                                        <?php else: ?>
                                        <!-- Editable radio buttons -->
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bill_type" id="gst_bill"
                                                value="GST" required>
                                            <label class="form-check-label" for="gst_bill">GST Bill</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bill_type"
                                                id="non_gst_bill" value="Non-GST" required>
                                            <label class="form-check-label" for="non_gst_bill">Non-GST Bill</label>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="form-text1" class="form-label fs-14 text-dark">Invoice No <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="invoice_number"
                                            id="invoice_number" placeholder="" title="Not Editable"
                                            value="<?= (isset($invoicedata['invoice_number'])) ? $invoicedata['invoice_number'] : ''; ?>"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="form-text" class="form-label fs-14 text-dark">Invoice Date</label>
                                        <input type="date" class="form-control" name="invoice_date" id="form-text"
                                            value="<?php
// Set default timezone if needed
date_default_timezone_set('Asia/Kolkata');
// Get current date in YYYY-MM-DD format
$current_date = date('Y-m-d');  // Change the format to YYYY-MM-DD
// Output the current date
echo $current_date;
?>" title="Not Editable" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="customer_id" class="form-label fs-14 text-dark">
                                            Customer Name <span class="text-danger">*</span>
                                            <input type="hidden" id="load_cust_state">
                                        </label>
                                       <?php if(!isset($invoicedata['customer_id'])){ ?> 
                                        <div style="flex-wrap: nowrap !important;"class="input-group"> <?php } ?>
                                            <select style="" class="form-select" name="customer_id" id="customer_id">
                                                <option value="" disabled selected>-- Select Customer --</option>
                                                <?php if (!empty($customers)): ?>
                                                <?php foreach ($customers as $customer): ?>
                                                <option value="<?= $customer['id']; ?>"
                                                    <?= (isset($invoicedata['customer_id']) && $invoicedata['customer_id'] == $customer['id']) ? 'selected' : ''; ?> >
                                                    <?= htmlspecialchars(ucwords($customer['customer_name'])) . ' - ' . htmlspecialchars(ucwords($customer['customer_mobilenumber'])) . ''; ?>
                                                </option>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <?php if(!isset($invoicedata['customer_id'])){ ?>
                                            <button class="btn btn-outline-primary addCustomerBtn" type="button"
                                                id="addCustomerBtn">
                                                <i class="ri-add-line"></i>
                                            </button>
                                            <?php } ?>
                                        <?php if(!isset($invoicedata['customer_id'])){ ?> </div><?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="form-text" class="form-label fs-14 text-dark">Mobile Number</label>
                                        <input type="number" name="mobile_number" class="form-control custmobile"
                                            id="mobile" title="Non Editable" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="form-text" class="form-label fs-14 text-dark">Email Address</label>
                                        <input type="email" name="email_address" class="form-control custemail"
                                            id="email" title="Non Editable" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="form-text" class="form-label fs-14 text-dark">Address</label>
                                        <textarea class="form-control custaddress" name="address" id="address"
                                            title="Non Editable" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                            <?php
                            // Detect mode for GST Bill Type
                            $isGstBillEdit = isset($invoicedata['gstbilltype']) && !empty($invoicedata['gstbilltype']);
                            ?>
                                <div class="col-md-12" id="billTypeIncExcSection" style="display: none;">
                                    <div class="mb-3">
                                        <label class="form-label fs-14 text-dark d-block">GST Bill Type <span
                                                class="text-danger">*</span></label>
                                        <?php if ($isGstBillEdit): ?>
                                        <!-- Hidden input to POST value -->
                                        <input type="hidden" name="gstbill_type"
                                            value="<?= htmlspecialchars($invoicedata['gstbilltype']); ?>">
                                        <!-- Disabled radio buttons -->
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input larger-radio" type="radio"
                                                name="gstbill_type" id="exclusive_gst" value="Exclusive GST" disabled
                                                <?= ($invoicedata['gstbilltype'] == 'Exclusive GST') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="exclusive_gst">Exclusive GST
                                                Bill</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input larger-radio" type="radio"
                                                name="gstbill_type" id="inclusive_gst" value="Inclusive GST" disabled
                                                <?= ($invoicedata['gstbilltype'] == 'Inclusive GST') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="inclusive_gst">Inclusive GST
                                                Bill</label>
                                        </div>
                                        <?php else: ?>
                                        <!-- Editable radio buttons -->
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input " type="radio" name="gstbill_type"
                                                id="exclusive_gst" value="Exclusive GST">
                                            <label class="form-check-label" for="exclusive_gst">Exclusive GST
                                                Bill</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gstbill_type"
                                                id="inclusive_gst" value="Inclusive GST">
                                            <label class="form-check-label" for="inclusive_gst">Inclusive GST
                                                Bill</label>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-xl-12 scrollable-table table-responsive loadproducttable">
                                </div>
<?php
$selectedMethod = $invoicedata['paymentmethod'] ?? '';
$billType = $invoicedata['billtype'] ?? '';
$paymentOptions = [];
if (!empty($invoicedata)) {
    if ($billType === 'Non-GST') {
        $paymentOptions = ['Cash', 'UPI'];
    } elseif ($billType === 'GST') {
        $paymentOptions = ['Cash', 'cheque', 'Bank Transfer'];
    }
} else {
    // Add page default: show all options
    $paymentOptions = ['Cash', 'UPI', 'cheque', 'Bank Transfer'];
}
?>
                                <div style="<?php if(empty($invoicedata)){ echo "display:none;"; }else{ echo "display:block;"; } ?>" class="col-md-6 Paymentsection">
                                    <div class="row border p-3 rounded">
                                        <h6 class="text-center mb-3">Payment</h6>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="">
                                                <div class="mb-3">
                                                    <label for="paymentMethod" class="form-label">Payment Method</label>
                                                    <select class="form-select" id="paymentMethod" name="paymentMethod">
                                                        <option disabled <?= $selectedMethod === '' ? 'selected' : '' ?>>Select</option>
                                                        <?php foreach ($paymentOptions as $method): ?>
                                                            <option value="<?= $method ?>" <?= $selectedMethod === $method ? 'selected' : '' ?>>
                                                                <?= $method ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                <label for="paidDate" class="form-label">Paid Date</label>
                                                <input type="date" class="form-control" id="paidDate"  onkeydown="return false;" onclick="this.showPicker()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="mb-3">
                                                <label for="paidAmount" class="form-label">Paid Amount</label>
                                                <input type="number" class="form-control" id="paidAmount" maxlength="10">
                                            </div>
                                             <div class="mb-3">
                                                <label for="remarks" class="form-label">Notes</label>
                                                <textarea class="form-control text-number-only" id="remarks" rows="3" placeholder="Enter Notes here..." maxlength="150"></textarea>
                                            </div>
                                        </div>
                                        <div style="text-align:center;" class="col-md-4 col-sm-12 text-center">
                                        </div>
                                        <div style="text-align:center;" class="col-md-4 col-sm-12 text-center">
                                            <button type="button" class="btn btn-primary w-100 clicksavepayment">Add
                                                Payment</button>
                                        </div>
                                        <input type="hidden" id="historyData" name="historyData"
                                            value="<?= (isset($invoicedata['payment_history'])) ? $invoicedata['payment_history'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div style="display: none;" class="row border p-3 rounded">
                                        <h6 class="text-center mb-3">Additional Cost</h6>
                                        <div class="col-md-12">
                                            <div class="additional-cost-section">
                                                <table class="table table-bordered table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Service Provider</th>
                                                            <th>Description</th>
                                                            <th>QTY</th>
                                                            <th>Rate</th>
                                                            <th>Total</th>
                                                            <th style="width: 87px;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="additionalCostBody">
                                                        <?php if (!empty($additionalcosts)) : ?>
                                                        <?php foreach ($additionalcosts as $item) : ?>
                                                        <tr>
                                                            <input type="hidden" name="additional_costid[]"
                                                                value="<?= (int)$item['id'] ?>">
                                                            <td><input type="text" name="service_provider[]"
                                                                    class="form-control"
                                                                    value="<?= htmlspecialchars($item['service_provider']) ?>">
                                                            </td>
                                                            <td><input type="text" name="service_description[]"
                                                                    class="form-control"
                                                                    value="<?= htmlspecialchars($item['description']) ?>">
                                                            </td>
                                                            <td><input type="number" name="service_qty[]"
                                                                    class="form-control qty"
                                                                    value="<?= (int)$item['qty'] ?>"></td>
                                                            <td><input type="number" name="service_rate[]"
                                                                    class="form-control rate"
                                                                    value="<?= (float)$item['rate'] ?>"></td>
                                                            <td><input type="text" name="service_total[]"
                                                                    class="form-control total"
                                                                    value="<?= (float)$item['total'] ?>" readonly></td>
                                                            <td>
                                                                <button class="btn btn-primary btn-sm addCostRow"
                                                                    type="button" title="Add Row"><i class="ri-add-line"
                                                                        aria-hidden="true"></i></button>
                                                                &nbsp;
                                                                <button class="btn btn-danger btn-sm removeRow"
                                                                    type="button" value="Delete" title="Delete"><i
                                                                        class="ri-delete-bin-line"></i></button>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                        <?php else : ?>
                                                        <tr>
                                                            <input type="hidden" name="additional_costid[]" value="">
                                                            <td><input type="text" name="service_provider[]"
                                                                    class="form-control"></td>
                                                            <td><input type="text" name="service_description[]"
                                                                    class="form-control"></td>
                                                            <td><input type="number" name="service_qty[]"
                                                                    class="form-control qty" value="1"></td>
                                                            <td><input type="number" name="service_rate[]"
                                                                    class="form-control rate" value="0"></td>
                                                            <td><input type="text" name="service_total[]"
                                                                    class="form-control total" readonly></td>
                                                            <td>
                                                                <button class="btn btn-primary btn-sm addCostRow"
                                                                    type="button" id="addCostRow" title="Add"><i class="ri-add-line"
                                                                        aria-hidden="true"></i></button>
                                                                &nbsp;
                                                                <button class="btn btn-danger btn-sm removeRow"
                                                                    type="button" value="Delete" title="Delete"><i
                                                                        class="ri-delete-bin-line"></i></button>
                                                            </td>
                                                        </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-3 col-sm-12 text-center"></div>
                                                    <div class="col-md-6 col-sm-12 text-center">
                                                        <button type="button" id="submitAdditionalCost"
                                                            class="btn btn-success">Submit Additional Cost</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <button class="btn btn-success" id="submit_btn" type="submit">SUBMIT</button>
                            <a href="<?= base_url('invoice'); ?>" class="btn btn-danger">CANCEL</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Large Modal -->
        <div class="modal fade" id="largeModalcustomeradd" tabindex="-1" aria-labelledby="largeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <!-- Use modal-xl for large modal -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="largeModalLabel">Add Customer </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="CustomerModalForm">
                        <div class="modal-body loadcustomeradd">
                            <!-- Modal body content here -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary clickcancelmodal"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End::app-content -->
<script type="text/javascript">
$(document).on('click', '.addCustomerBtn', function() {
    //var id = $(this).data('id');
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    // Prepare form data
    var formData = new FormData();
    formData.append('<?= $this->security->get_csrf_token_name(); ?>', csrfToken); // Add CSRF token to form data
    $.ajax({
        url: "<?= base_url('Customer/load_customeraddmodal'); ?>",
        type: "POST",
        data: formData,
        dataType: "json",
        processData: false, // Don't process the files
        contentType: false, // Let jQuery set it automatically
        success: function(response) {
            $('.loadcustomeradd').html(response.content);
            $('#largeModalcustomeradd').modal('show');
        },
        error: function() {
            alert("Failed to load modal content.");
        }
    });
});
$(document).ready(function() {
    function updateTotalAmount123(selectedValue, selectOptionpercentage) {
        var currency = '₹';
        var totalAmount = 0;
        var defaultCGSTPercentage = 9;
        var defaultSGSTPercentage = 9;
        var defaultIGSTPercentage = 18;
        var defaultCGSTPercentageTax = 2.5;
        var defaultSGSTPercentageTax = 2.5;
        var defaultIGSTPercentageTax = 5;
        var o_charges = Number($('.other-charges').val());
        var lu_charges = Number($('.lu-charges').val());
        var tax_percentagecheck = $('.inserttax_percentage').val();
        $('.totalcgst_input, .totalsgst_input, .totaligst_input').val(currency + '0.00');
        $('.invoice_body tr').each(function() {
            var quantity = $(this).find('.invoice_quantity').val();
            var rate = $(this).find('.invoice_rate').val();
            var parsedQuantity = parseFloat(quantity);
            var parsedRate = parseFloat(rate);
            if (!isNaN(parsedQuantity) && !isNaN(parsedRate)) {
                var rowTotal = parsedQuantity * parsedRate;
                totalAmount += rowTotal;
                $(this).find('.invoice_amount').val(currency + rowTotal.toFixed(2));
            } else {
                $(this).find('.invoice_amount').val(currency + '0.00');
            }
        });
        var totalWithTaxes;
        var totalAmountadditional;
        if (selectedValue === 'Tamil Nadu') {
            var defaultCGSTAmount = ((totalAmount + lu_charges) * defaultCGSTPercentage) / 100;
            var defaultSGSTAmount = ((totalAmount + lu_charges) * defaultSGSTPercentage) / 100;
            totalWithTaxes = totalAmount + defaultCGSTAmount + defaultSGSTAmount;
            $('.totalcgst_input').val(currency + defaultCGSTAmount.toFixed(2));
            $('.totalsgst_input').val(currency + defaultSGSTAmount.toFixed(2));
        } else {
            var defaultIGSTAmount = ((totalAmount + lu_charges) * defaultIGSTPercentage) / 100;
            totalWithTaxes = totalAmount + defaultIGSTAmount;
            $('.totaligst_input').val(currency + defaultIGSTAmount.toFixed(2));
        }
        if (tax_percentagecheck == 'true') {
            // Tax 5%
            if (selectOptionpercentage === 'Tamil Nadu') {
                var defaultCGSTAmounttax = ((totalAmount + lu_charges) * defaultCGSTPercentageTax) / 100;
                var defaultSGSTAmounttax = ((totalAmount + lu_charges) * defaultSGSTPercentageTax) / 100;
                totalWithTaxes = totalAmount + defaultCGSTAmounttax + defaultSGSTAmounttax;
                $('.totalcgst_input').val(currency + defaultCGSTAmounttax.toFixed(2));
                $('.totalsgst_input').val(currency + defaultSGSTAmounttax.toFixed(2));
            } else {
                var defaultIGSTAmounttax = ((totalAmount + lu_charges) * defaultIGSTPercentageTax) / 100;
                totalWithTaxes = totalAmount + defaultIGSTAmounttax;
                $('.totaligst_input').val(currency + defaultIGSTAmounttax.toFixed(2));
            }
        } else {
            if (selectOptionpercentage === 'Tamil Nadu') {
                var defaultCGSTAmount = ((totalAmount + lu_charges) * defaultCGSTPercentage) / 100;
                var defaultSGSTAmount = ((totalAmount + lu_charges) * defaultSGSTPercentage) / 100;
                totalWithTaxes = totalAmount + defaultCGSTAmount + defaultSGSTAmount;
                $('.totalcgst_input').val(currency + defaultCGSTAmount.toFixed(2));
                $('.totalsgst_input').val(currency + defaultSGSTAmount.toFixed(2));
            } else {
                var defaultIGSTAmount = ((totalAmount + lu_charges) * defaultIGSTPercentage) / 100;
                totalWithTaxes = totalAmount + defaultIGSTAmount;
                $('.totaligst_input').val(currency + defaultIGSTAmount.toFixed(2));
            }
        }
        if (!isNaN(o_charges)) {
            totalWithTaxes += o_charges;
        }
        if (!isNaN(lu_charges)) {
            totalWithTaxes += lu_charges;
        }
        totalAmountadditional = currency + totalWithTaxes.toFixed(2);
        if (!isNaN(totalAmount)) {
            $('.subtotal_input').val(currency + totalAmount.toFixed(2));
            $('.grand-total').val(totalAmountadditional);
        } else {
            console.error("Invalid totalAmount:", totalAmount);
        }
    }
    // Add Row
    $(".service_1").on("click", ".addinvoiceButton", function() {
        var newRowservice = $(this).closest("tr").clone();
        var tbodyRowCount = $(".service_1 tbody tr").length;
        newRowservice.find("input").val("");
        newRowservice.find("textarea").val("");
        newRowservice.find('.delete').removeClass('delete').addClass('delete_invoice').html(
            '<i class="ri-delete-bin-line" aria-hidden="true"></i>');
        $(this).closest("tr").after(newRowservice);
    });
    // Delete Row
    $(".service_1").on("click", ".delete_invoice", function(event) {
        var tbodyRowCount = $(".service_1 tbody tr").length;
        if (tbodyRowCount > 1) {
            $(this).closest("tr").remove();
            var selectedValue = $('#customer_state').val();
            var selectOptionpercentage = $('#tax_percentagecheckValue').val();
            updateTotalAmount(selectedValue, selectOptionpercentage);
        } else {
            Toastify({
                text: "Cannot Remove First Row",
                backgroundColor: "linear-gradient(to right, #ff4d4d, #ff0000)",
                gravity: "top",
                position: 'right',
                duration: 1000,
                close: true
            }).showToast();
        }
    });
    // Calculate Total on Input Change
    $(document).on('input',
        '.invoice_quantity, .invoice_rate , .other-charges, .lu-charges, .rev-charges, .inserttax_percentage',
        function() {
            var selectedValue = $('#customer_state').val();
            var selectOptionpercentage = $('#tax_percentagecheckValue').val();
            updateTotalAmount(selectedValue, selectOptionpercentage);
        });
    /* Form Submission */
    let formSubmitted = false;
    $("#submit_invoicedata").validate({
        rules: {
            company_id: {
                required: true
            }
        },
        messages: {
            company_id: "Please Select Company"
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            const errors = []; // to collect messages
            const toHighlight = []; // to collect inputs to mark red
            // 1) validate each row
            $('.invoice_body tr').each(function(i) {
                const $row = $(this);
                const $prod = $row.find('[name="product_name[]"]');
                const $rate = $row.find('[name="rate[]"]');
                const $qty = $row.find('[name="quantity[]"]');
                const $gst = $row.find('[name="GST[]"]');
                const prod = $prod.val().trim();
                const rate = parseFloat($rate.val().trim());
                const qty = parseFloat($qty.val().trim());
                const gst = parseFloat(($gst.val() || '0').trim()) || 0;
                let billType = $('input[name="gstbill_type"]:checked')
                    .val(); // Get the selected value
                // clear any previous style
                $prod.removeClass('is-invalid');
                $rate.removeClass('is-invalid');
                $qty.removeClass('is-invalid');
                $gst.removeClass('is-invalid');
                if (!prod) {
                    errors.push(`Row ${i+1}: Please enter Product Name`);
                    toHighlight.push($prod);
                }
                if (isNaN(rate) || rate <= 0) {
                    errors.push(`Row ${i+1}: Rate must be greater than 0`);
                    toHighlight.push($rate);
                }
                if (isNaN(qty) || qty <= 0) {
                    errors.push(`Row ${i+1}: Quantity must be greater than 0`);
                    toHighlight.push($qty);
                }
                let gstornongst = $('input[name="bill_type"]:checked').val();
                if (gstornongst !== "Non-GST" && (gst <= 0) && (billType == "Exclusive GST" || billType ==
                        "Inclusive GST")) {
                    errors.push(`Row ${i+1}: GST must be greater than 0`);
                    toHighlight.push($gst);
                }
            });
            // 2) if any errors, show all toasts then prevent submit
            if (errors.length) {
                // highlight fields
                toHighlight.forEach($el => $el.addClass('is-invalid'));
                // show all toasts
                errors.forEach(msg => {
                    Toastify({
                        text: msg,
                        backgroundColor: "linear-gradient(to right, #ff4d4d, #ff0000)",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right"
                    }).showToast();
                });
                return false;
            }
            let custname = $('#customer_id').val(); // Assuming the select field has id="customer_id"
            if (!custname) {
                showToast("Please select a customer", "error");
                return;
            }
            let gstornongst = $('input[name="bill_type"]:checked').val();
            let billType = $('input[name="gstbill_type"]:checked').val();
            if (gstornongst !== "Non-GST") {
                if (!billType) {
                    showToast("Please choose a GST Bill Type.", "error");
                    return;
                }
            }
            if (formSubmitted) {
            return;
            }
            formSubmitted = true; // Prevent further submissions
            $('#submit_btn').prop('disabled', true);
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var formData = new FormData(form);
            formData.append('<?= $this->security->get_csrf_token_name(); ?>', csrfToken);
            var inv_id = $('#invoiceid').val();
            <?php if(!isset($invoicedata['billtype'])){ ?>
            $.ajax({
                type: 'POST',
                url: '<?= base_url('Invoice/newinvoice') ?>',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    formSubmitted = true;
                    if (response.status === "success") {
                        Toastify({
                            text: "Invoice Details Saved Successfully",
                            backgroundColor: "linear-gradient(to right, #28a745, #28a745)", // Green color
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
                            text: "Failed to Save Invoice Details.",
                            backgroundColor: "linear-gradient(to right, #28a745, #28a745)",
                            gravity: "top",
                            position: 'right',
                            duration: 1000,
                            close: true
                        }).showToast();
                    }
                }
            });
            <?php } else{ ?>
            $.ajax({
                type: 'POST',
                url: '<?= base_url('Invoice/updateinvoice') ?>',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status === "success") {
                        Toastify({
                            text: "Invoice Details Saved Successfully",
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
                            text: "Failed to Save Invoice Details.",
                            backgroundColor: "linear-gradient(to right, #ff4d4d, #ff0000)",
                            gravity: "top",
                            position: 'right',
                            duration: 1000,
                            close: true
                        }).showToast();
                    }
                }
            });
            <?php } ?>
            return false;
        }
    });
});
$(document).ready(function() {
    var thisurl = "<?= base_url('Customer/insert_customer_formodal'); ?>";
    $("#CustomerModalForm").validate({
        rules: {
            customer_name: {
                required: true,
                validName: true
            },
            customer_email: {
                required: true,
                email: true
            },
            customer_mobilenumber: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            customer_address: {
                required: true,
                minlength: 5
            },
            customer_city: {
                required: true
            },
            customer_state: {
                required: true
            }
        },
        messages: {
            customer_name: {
                required: "Name is required"
            },
            customer_email: {
                required: "Email is required",
                email: "Enter a valid email"
            },
            customer_mobilenumber: {
                required: "Mobile number is required",
                digits: "Only numbers are allowed",
                minlength: "Mobile must be 10 digits",
                maxlength: "Mobile must be 10 digits"
            },
            customer_address: {
                required: "Address is required",
                minlength: "Address must be at least 5 characters"
            },
            customer_city: {
                required: "Please enter city"
            },
            customer_state: {
                required: "Please select state"
            }
        },
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            $('#submit_btn').prop('disabled', false);
            var formData = $(form).serialize();
            $.ajax({
                type: 'POST',
                url: thisurl,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        showToast("Customer Details Saved Successfully", "success");
                        // Rebuild the customer dropdown
                        if (response.customers && Array.isArray(response.customers)) {
                            var $dropdown = $('#customer_id');
                            $dropdown.empty().append(
                                '<option value="" disabled selected>-- Select Customer --</option>'
                            );
                            $.each(response.customers, function(index, customer) {
                                $dropdown.append(
                                    $('<option></option>')
                                    .attr('value', customer.id)
                                    .text(customer.customer_name.replace(
                                            /\b\w/g, c => c.toUpperCase()) +
                                        ' - ' + customer
                                        .customer_mobilenumber + '')
                                );
                            });
                            // Optionally auto-select the newly added customer
                            const newId = response.customers[0]?.id;
                            if (newId) {
                                $dropdown.val(newId).trigger('change');
                            }
                        }
                        $('#CustomerModal').toggle(); // close modal if used
                        $('.clickcancelmodal').click(); // close modal if used
                        form.reset(); // reset form
                        getcustomer();
                    } else {
                        showToast("Failed to Save Customer Details.", "error");
                    }
                }
            });
            return false;
        }
    });
    $('input[name="gstbill_type"]').on('change', function() {
        //gstbillblock();
    });
    <?php if(isset($invoicedata['billtype'])){ ?>
    let thisid = $('#invoiceid').val();
    setTimeout(function() {
        gstbillblock(thisid,null);
    }, 1000);
    <?php } ?>
    function gstbillblock(invoiceid = 0,customerstate=null) {  
        let billType = $('input[name="gstbill_type"]:checked').val(); // Get the selected value
        let gstornongst = $('input[name="bill_type"]:checked').val(); // Get the selected value
        // Show sections
        $('.Paymentsection').show();
        $('#responsiveDataTable').slideDown();
        if (gstornongst == "Non-GST") {
        } else {
            $('#billTypeIncExcSection').slideDown();
        }
        // Get CSRF token from meta
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        var csrfName = '<?= $this->security->get_csrf_token_name(); ?>'; // get token name
        var formData = new FormData();
        formData.append('gstbill_type', billType);
        formData.append('gstornongst', gstornongst);
        formData.append(csrfName, csrfToken); // token name as key
        formData.append('invoiceid', invoiceid); // token name as key
        formData.append('customerstate', customerstate); // token name as key
        // Send via AJAX
        $.ajax({
            url: '<?= base_url('Invoice/Getinvoiceformtable')?>', // Replace with your actual URL
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                $('.loadproducttable').html(response.content)
                // You can show a toast or update a section if needed
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    }
    <?php if(isset($invoicedata['billtype'])){ ?>
    getcustomer();
    <?php } ?>
    $('#customer_id').on('change', function () {
         getcustomer();
    });
    function getcustomer(addoredit="<?php if(isset($invoicedata['billtype'])){ echo "edit"; }else{ echo "add"; }  ?>") {
        let custid = $('#customer_id').val();
        //var id = $(this).data('id');
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        // Prepare form data
        var formData = new FormData();
        formData.append('<?= $this->security->get_csrf_token_name(); ?>',
            csrfToken); // Add CSRF token to form data
        formData.append('custid', custid); // Add CSRF token to form data
        $.ajax({
            url: "<?= base_url('Customer/getcustomer'); ?>",
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false, // Don't process the files
            contentType: false, // Let jQuery set it automatically
            success: function(response) {
                if (response.customer_data) {
                    $('.custmobile').val(response.customer_data.customer_mobilenumber)
                    $('.custemail').val(response.customer_data.customer_email)
                    $('.custaddress').val(response.customer_data.customer_address)
                        <?php if(!isset($invoicedata['billtype'])){ ?>
                    gstbillblock(invoiceid = 0,customerstate=response.customer_data.customer_state)
                    $('#load_cust_state').val(response.customer_data.customer_state)
                        <?php } ?>
                } else {
                }
            },
            error: function() {
                alert("Failed to load modal content.");
            }
        });
    }
   $('input[name="bill_type"]').on('change', function () {
    let gstornongst = $('input[name="bill_type"]:checked').val();
    let invoiceInput = $('#invoice_number');
    let currentValue = invoiceInput.val().trim();
    let parts = currentValue.split('-');
    if (gstornongst === "Non-GST") {
        if (parts.length === 3) {
            invoiceInput.val(`${parts[0]}-${parts[2]}`);
        }
        gstbillblock();
        setTimeout(() => $('#billTypeIncExcSection').hide(), 400);
        // Only allow UPI and Cash for Non-GST
        setPaymentOptions(['Cash', 'UPI']);
    } else {
        <?php if (!isset($invoicedata['billtype'])) { ?>
            $('#exclusive_gst').prop('checked', true).trigger('change');
        <?php } ?>
        if (parts.length === 2) {
            let yearCode = "2526"; // Make dynamic if needed
            invoiceInput.val(`${parts[0]}-${yearCode}-${parts[1]}`);
        }
        let cust__state = $('#load_cust_state').val()
        if(cust__state != ''){
            gstbillblock(0,cust__state);  
        }
        setTimeout(() => $('#billTypeIncExcSection').show(), 400);
        // Allow Bank Transfer, Cheque, and Cash for GST
        setPaymentOptions(['Cash', 'cheque', 'Bank Transfer']);
    }
});
function setPaymentOptions(options) {
    let paymentSelect = $('#paymentMethod');
    paymentSelect.empty();
    // Add default disabled "Select" option
    paymentSelect.append('<option selected disabled>Select</option>');
    // Add dynamic options
    options.forEach(method => {
        paymentSelect.append(`<option value="${method}">${method}</option>`);
    });
}
    // Show table when GST or Non-GST radio is selected
    $('input[name="bill_type"]').on('change', function() {
        if ($(this).is(':checked')) {
            $('#responsiveDataTable').slideDown();
            $('#billTypeIncExcSection').slideDown();
        }
    });
    $('#responsiveDataTable').slideDown(); //to remove
    $('#company_id').on('change', function() {
        const selectedCompany = $(this).val();
        if (selectedCompany) {
            $('#billTypeSection').slideDown();
        } else {
            $('#billTypeSection').slideUp();
            $('input[name="bill_type"]').prop('checked', false); // uncheck radios
        }
    });
});
</script>
<script>
$(document).ready(function() {
   function updateInvoiceNumber() {
    var companyId = $('#company_id').val();
    var gstChosen = $('input[name="bill_type"]:checked').val();
    // Only proceed if both are selected
    if (!companyId || !gstChosen) {
        $('#invoice_number').val('');
        return;
    }
    var useGST = gstChosen === "GST";
    var latestInvoice = $('.companyinput' + companyId).val()?.trim() || '';
    const today = new Date();
    const year = today.getFullYear();
    const month = today.getMonth() + 1;
    const finYear = month >= 4 
        ? `${year.toString().slice(2)}${(year + 1).toString().slice(2)}`
        : `${(year - 1).toString().slice(2)}${year.toString().slice(2)}`;
    if (latestInvoice) {
        var parts = latestInvoice.split('-');
        var shortname = parts[0];
        var suffix = parseInt(parts[parts.length - 1], 10) + 1;
        var newSuffix = suffix.toString().padStart(3, '0');
        var newInvoice = useGST 
            ? `${shortname}-${finYear}-${newSuffix}` 
            : `${shortname}-${newSuffix}`;
        $('#invoice_number').val(newInvoice);
    } else {
        <?php if (!empty($company_records)): ?>
        var companies = <?php echo json_encode(array_column($company_records, 'shortterm', 'id')); ?>;
        var companyShortTerm = companies[companyId] || '';
        var newInvoice = useGST 
            ? `${companyShortTerm}-${finYear}-001` 
            : `${companyShortTerm}-001`;
        $('#invoice_number').val(newInvoice);
        <?php endif; ?>
    }
    }
    // Only regenerate when bill_type is changed
    $('input[name="bill_type"]').on('change', function () {
        updateInvoiceNumber();
    });
    // Also regenerate if company is changed and bill_type is already selected
    $('#company_id').on('change', function () {
        if ($('input[name="bill_type"]:checked').length > 0) {
            updateInvoiceNumber();
        } else {
            $('#invoice_number').val('');
        }
    });
   $('.clicksavepayment').on('click', function() {
    // Get values from the form
    var paymentMethod = $('#paymentMethod').val();
    var paidAmount = parseFloat($('#paidAmount').val());
    var paidDate = $('#paidDate').val();
    var remarks = $('#remarks').val().trim();
    // Check if the payment method, paid amount, and date are selected/entered
    if (!paymentMethod || isNaN(paidAmount) || paidAmount <= 0 || !paidDate) {
        showToast("Please select a payment method, enter a valid paid amount, and choose a date.", "error");
        return;
    }
    // Get current reverse charges and grand total
    var currentCharges = parseFloat($('#rev_charges').val()) || 0;
    var grandTotal = parseFloat($('#grand_total').val().replace('₹', '').replace(',', '').trim()) || 0;
    // Calculate new reverse charges
    var newCharges = currentCharges + paidAmount;
    // Validate if new charges exceed grand total
    if (newCharges > grandTotal) {
        showToast("The total charges cannot exceed the grand total.", "error");
        return;
    }
    // Update reverse charges
    $('#rev_charges').val(newCharges.toFixed(2));
    // Calculate and update Balance Amount
    var balanceAmount = grandTotal - newCharges;
    $('#balance_amount').val(balanceAmount.toFixed(2));
    // Format history data entry
    var newEntry = `${paymentMethod}|${paidAmount}|${paidDate}|${remarks}`;
    var historyData = $('#historyData').val();
    historyData = historyData ? `${historyData};${newEntry}` : newEntry;
    $('#historyData').val(historyData);
    // Show success message
    showToast("Payment details saved.");
    // Clear input fields
    $('#paidAmount').val('');
    $('#remarks').val('');
    $('#paymentMethod').val('');
    $('#paidDate').val('');
});
});
// Add new row on button click
$('.addCostRow').on('click', function() {
    let newRow = `
        <tr>
          <input type="hidden" name="additional_costid[]" value="">
            <td><input type="text" name="service_provider[]" class="form-control provider"></td>
            <td><input type="text" name="service_description[]" class="form-control description"></td>
            <td><input type="number" name="service_qty[]" class="form-control qty" value="1" min="1"></td>
            <td><input type="number" name="service_rate[]" class="form-control rate" value="0" min="0"></td>
            <td><input type="text" name="service_total[]" class="form-control total" readonly></td>
            <td><button class="btn btn-primary btn-sm addCostRow" type="button" id="addCostRow" title="Add"><i class="ri-add-line" aria-hidden="true"></i></button> &nbsp; <button class="btn btn-danger btn-sm removeRow" type="button" value="Delete" title="Delete">
                                                    <i class="ri-delete-bin-line"></i></button></td>
        </tr>
    `;
    $('#additionalCostBody').append(newRow);
});
// Remove a row
$(document).on('click', '.removeRow', function() {
    // Prompt the user with a confirmation dialog
    var confirmDelete = confirm("Are you sure you want to delete this row?");
    if (!confirmDelete) {
        return;  // If the user cancels, do nothing
    }
    let rowCount = $('#additionalCostBody tr').length;
    if (rowCount <= 1) {
        showToast("At least one row is required.", "error");
        return;
    }
    $(this).closest('tr').remove();  // Remove the row
    updateAdditionalCostTotal();  // Update the total after removal
});
// Calculate total for each row on quantity or rate input
$(document).on('input', '.qty, .rate', function() {
    let row = $(this).closest('tr');
    let qty = parseFloat(row.find('.qty').val()) || 0;
    let rate = parseFloat(row.find('.rate').val()) || 0;
    let total = qty * rate;
    row.find('.total').val(total.toFixed(2));
});
// Calculate overall additional cost
function updateAdditionalCostTotal() {
    let totalSum = 0;
    $('#additionalCostBody .total').each(function() {
        let val = parseFloat($(this).val()) || 0;
        totalSum += val;
    });
    // Update hidden field or a visible field if needed
    $('#additional_cost_total').val(totalSum.toFixed(2));
    $('input[name="additional_cost"]').val(totalSum.toFixed(2));
    $('input[name="additional_cost"]').val('₹' + totalSum.toFixed(2));
    var subtotalamt = 0;
    $('input[name="amount_subtotal[]"]').each(function() {
        var val = $(this).val().replace(/[^\d.-]/g, ''); 
        subtotalamt += parseFloat(val) || 0;
    });
    let subtotalamtandadditionalcost = subtotalamt + parseFloat(totalSum);
    // Optional: also update a visible text span if present
    $('#display_additional_cost_total').text(totalSum.toFixed(2));
    $('#grand_total').val('₹' + subtotalamtandadditionalcost.toFixed(2)); // Update Subtotal field
    showToast("Additional cost updated.")
}
$('#submitAdditionalCost').on('click', function() {
    let isValid = true;
    let missingFields = [];
    $('#additionalCostBody tr').each(function(index) {
        let rowIndex = index + 1;
        let row = $(this);
        let provider = row.find('input[name="service_provider[]"]').val().trim();
        let serviceqty = row.find('input[name="service_qty[]"]').val();
        let servicerate = row.find('input[name="service_rate[]"]').val();
        row.find('input').removeClass('is-invalid');
        if (provider === '') {
            isValid = false;
            row.find('input[name="service_provider[]"]').addClass('is-invalid');
            missingFields.push(`Row ${rowIndex}: Service Provider`);
        }
        if (serviceqty === '' || parseFloat(serviceqty) <= 0) {
            isValid = false;
            row.find('input[name="service_qty[]"]').addClass('is-invalid');
            missingFields.push(`Row ${rowIndex}: Quantity`);
        }
        if (servicerate === '' || parseFloat(servicerate) <= 0) {
            isValid = false;
            row.find('input[name="service_rate[]"]').addClass('is-invalid');
            missingFields.push(`Row ${rowIndex}: Rate`);
        }
    });
    if (!isValid) {
        showToast("Missing fields:\n" + missingFields.join('\n'), "error");
        return;
    }
    updateAdditionalCostTotal();
});
$(document).on('input', 'input[type="text"]', function() {
    // Replace multiple spaces with a single space
    this.value = this.value.replace(/\s{2,}/g, ' ');
});
$(document).ready(function() {
    $('#customer_id').select2({
        placeholder: "-- Select Customer --",
        allowClear: true
    });
    <?php if(isset($invoicedata['billtype'])){  ?>
    $('#customer_id').prop('disabled', true);
    <?php } ?>
    $(document).on('click', '.exclusivegst_addinvoiceButton', function () {
    // Clone the first row
    var newRow = $('.invoice_body tr:first').clone();
    // Remove the hidden invoice_details_id field
    newRow.find('input[name="invoice_details_id[]"]').remove();
    // Clear all input values in the cloned row
    newRow.find('input').each(function () {
        var input = $(this);
        if (input.attr('type') !== 'hidden') {
            // Reset value
            input.val('');
            // Set ₹0.00 if it's the amount field
            if (input.attr('id') === 'amount') {
                input.val('₹0.00');
            }
        }
    });
    // Clear selected options (if needed) and set GST default to 5
    newRow.find('select').each(function () {
        $(this).val('');
    });
    // Set GST to 5 by default
    newRow.find('select[name="GST[]"]').val('18');
    
    newRow.find('select[name="unit[]"]').val('Sq.Ft');
    
    // Append new row to the table body
    $('.invoice_body').append(newRow);
});
    // Delete row functionality
    $(document).on('click', '.delete_invoice', function () {
    // Ask for confirmation before removing the row
    var confirmDelete = confirm("Are you sure you want to delete this row?");
    if (!confirmDelete) {
        return;  // If the user clicks "Cancel", stop the deletion
    }
    // Only remove if more than one row exists
    if ($('.invoice_body tr').length > 1) {
        $(this).closest('tr').remove();
    } else {
        showToast("At least one product row is required.", "error");
    }
});
});
</script>