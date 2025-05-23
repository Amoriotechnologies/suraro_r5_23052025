<?php
$isViewMode = strpos($_SERVER['REQUEST_URI'], 'view_expense') !== false;
?>
<!-- Start::app-content -->
<div class="main-content app-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">
                <?php if(!isset($expensedata['vendor_name'])){ echo "New Expense"; }else if(!$isViewMode){ echo "Edit Expense"; } else if($isViewMode){ echo "View Expense";  } ?> 
            </h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboards</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?php if(!isset($expensedata['vendor_name'])){ echo "New Expense"; }else if(!$isViewMode){ echo "Edit Expense"; }else if($isViewMode){ echo "View Expense";  } ?>
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
                        <form id="submit_expensedata" method="post">
                            <input type="hidden"
                                value="<?= isset($latest_expenses['SM1']->expense_number) ? $latest_expenses['SM2']->expense_number : ''; ?>"><input
                                type="hidden"
                                value="<?= isset($latest_expenses['SM2']->expense_number) ? $latest_expenses['SM2']->expense_number : ''; ?>"><input
                                type="hidden"
                                value="<?= isset($latest_expenses['SM3']->expense_number) ? $latest_expenses['SM2']->expense_number : ''; ?>">
                            <?php if (!empty($company_records)): ?>
                            <?php foreach ($company_records as $record): ?>
                            <input type="hidden" class="companyinput<?= $record['id']; ?>"
                                value="<?= isset($latest_expenses[$record['shortterm']]->expense_number) ? $latest_expenses[$record['shortterm']]->expense_number : ''; ?>">
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="hidden" id="expenseid" name="expenseid" value=<?= $expenseid; ?>>
                                        <label for="company_id" class="form-label fs-14 text-dark">Select Company <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" name="company_id" id="company_id" required>
                                            <option value="" disabled selected>-- Select Company --</option>
                                            <?php if (!empty($company_records)): ?>
                                            <?php foreach ($company_records as $record): ?>
                                            <option value="<?= $record['id']; ?>"
                                                <?= (isset($expensedata['company_id']) && $expensedata['company_id'] == $record['id']) ? 'selected' : ''; ?>>
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
                            $isEdit = isset($expensedata['billtype']) && !empty($expensedata['billtype']);
                            ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="form-text1" class="form-label fs-14 text-dark">Expense No <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="expense_number"
                                            id="expense_number" placeholder="" title="Not Editable"
                                            value="<?= (isset($expensedata['expense_number'])) ? $expensedata['expense_number'] : ''; ?>"
                                            >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="form-text" class="form-label fs-14 text-dark">Expense Date</label>
                                      <input type="date" class="form-control" name="expense_date" id="expense_date"
    value="<?php
        date_default_timezone_set('Asia/Kolkata');
        echo date('Y-m-d');
    ?>" title="Not Editable" onkeydown="return false;" onclick="this.showPicker()">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="customer_id" class="form-label fs-14 text-dark">
                                            Vendor Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="vendor_name" id="customer_id"
                                                    class="form-control custmobile onlytextnonumber" value="<?= (isset($expensedata['vendor_name'])) ? $expensedata['vendor_name'] : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <?php
                            // Detect mode for GST Bill Type
                            $isGstBillEdit = isset($expensedata['gstbilltype']) && !empty($expensedata['gstbilltype']);
                            ?>
                                <div class="col-xl-12 scrollable-table table-responsive loadproducttable">
                                </div>
                                <div class="col-md-6">
                                    <?php if (!$isViewMode): ?>
                                    <div class="row border p-3 rounded">
                                        <h6 class="text-center mb-3 fw-bold">Payment</h6>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="">
                                                <div class="mb-3">
                                                    <label for="paymentMethod" class="form-label">Payment Method</label>
                                                    <select class="form-select" id="paymentMethod">
                                                        <option selected disabled>Select</option>
                                                        <option value="Cash">Cash</option>
                                                        <option value="UPI">UPI</option>
                                                        <option value="cheque">Cheque</option>
                                                        <option value="Bank Transfer">Bank Transfer</option>
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
                                                <input type="number" class="form-control" id="paidAmount">
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
                                            value="<?= (isset($expensedata['payment_history'])) ? $expensedata['payment_history'] : ''; ?>">
                                    </div>
                                     <?php endif; ?>
                                           <?php
$paymentData = isset($expensedata['payment_history']) ? $expensedata['payment_history'] : [];
if ($isViewMode && !empty($paymentData)) {
    if (strpos($paymentData, '|') !== false) {
        // Pipe-delimited format
        $entries = explode(';', trim($paymentData, ';'));
        $payment_chunks = [];
        foreach ($entries as $entry) {
            if (!empty($entry)) {
                $parts = explode('|', $entry);
                if (count($parts) >= 3) {
                    $payment_chunks[] = [
                        'method' => $parts[0],
                        'amount' => $parts[1],
                        'date' => $parts[2],
                        'description' => $parts[3] ?? ''
                    ];
                }
            }
        }
        if (!empty($payment_chunks)) {
?>
            <table class="table table-bordered table-striped">
                <div style="margin-top:40px;" class="text-center"><h5>Payment Details</h5></div>
                <thead>
                    <tr class="tr-back">
                        <th>S.NO</th>
                        <th>Payment Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payment_chunks as $index => $payment): ?>
                        <tr class="tr-back">
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($payment['method']) ?></td>
                            <td>₹<?= number_format((float)$payment['amount'], 2) ?></td>
                            <td><?= date('d/m/Y', strtotime($payment['date'])) ?></td>
                            <td><?= htmlspecialchars($payment['description']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
<?php
        }
    } else {  
        // Fallback: comma-separated format
        $payments = explode(',', $paymentData);
        $payment_chunks = array_chunk($payments, 2);
        if (!empty($payment_chunks) && is_numeric($payment_chunks[0][1])) {
?>
            <table class="table table-bordered table-striped">
                <div style="margin-top:40px;" class="text-center"><h5>Payment Details</h5></div>
                <thead>
                    <tr class="tr-back">
                        <th>S.NO</th>
                        <th>Payment Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payment_chunks as $index => $payment): ?>
                        <tr class="tr-back">
                            <td><?= $index + 1 ?></td>
                            <td><?= $payment[0] ?? '-' ?></td>
                            <td>₹<?= (isset($payment[1]) && is_numeric($payment[1])) ? number_format((float)$payment[1], 2) : '0.00' ?></td>
                            <td><?= date('d/m/Y', strtotime($expensedata['expense_date'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
<?php
        }
    }
}
?>
                                </div>
                                <div style="text-align: center;" class="p-3 col-md-6 col-sm-12 pd-t-50">
                                    <div class="row">
                                        <div class="col-md-2">
                                        </div>
                                         <div class="col-md-8">
                                            <div class="mb-3">
                                                <h6 style="text-align: center;" class="fw-bold text-center mb-3">Attachments</h6>
                                                <input type="hidden" name="existing_uploaded_files" value='<?= isset($expensedata['attachment_files']) ? htmlspecialchars(json_encode(json_decode($expensedata['attachment_files'], true)), ENT_QUOTES, 'UTF-8') : '' ?>'>
                                                <?php if(!$isViewMode): ?>
                                                <input type="file" class="form-control" id="fileupload" name="fileupload[]" multiple accept="image/*">
                                                <?php endif; ?>
                                               <div id="previewImages" class="mt-3 d-flex flex-wrap gap-2 justify-content-center">
                                                <?php
                                                $hasAttachments = false;
                                                if (!empty($expensedata['attachment_files'])) {
                                                    $uploadedFiles = json_decode($expensedata['attachment_files'], true);
                                                    if (!empty($uploadedFiles) && is_array($uploadedFiles)) {
                                                        $hasAttachments = true;
                                                        foreach ($uploadedFiles as $file) {
                                                            $fileUrl = base_url('uploads/expenses/' . $file);
                                                            ?>
                                                            <a target="_blank" href="<?= $fileUrl ?>">
                                                                <img src="<?= $fileUrl ?>" style="height: 100px; margin-right: 8px; border: 1px solid #ccc; padding: 5px; border-radius: 8px;">
                                                            </a>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                
                                                if(empty($expensedata['attachment_files']) || $expensedata['attachment_files'] == 'null' || $expensedata['attachment_files'] == null){ 
                                                     if(!isset($expensedata['vendor_name'])){ echo ""; }else if(!$isViewMode){ echo "<p style='text-align: center;'>No attachments found.</p>"; }else if($isViewMode){ echo "<p style='text-align: center;'>No attachments found.</p>";  }
                                                }
                                                ?>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="display:none;" class="col-md-6">
                                    <div class="row border p-3 rounded">
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
                                                                    type="button" title="Add"><i class="ri-add-line"
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
                                                                    type="button" id="addCostRow"><i class="ri-add-line"
                                                                        aria-hidden="true"></i></button>
                                                                &nbsp;
                                                                <button class="btn btn-danger btn-sm removeRow"
                                                                    type="button" value="Delete"><i
                                                                        class="ri-delete-bin-line"></i></button>
                                                            </td>
                                                        </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-4 col-sm-12 text-center"></div>
                                                    <div class="col-md-4 col-sm-12 text-center">
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
                            <?php if (!$isViewMode): ?>
                            <button class="btn btn-success" id="submit_btn" type="submit">SUBMIT</button>
                            <a href="<?= base_url('expense'); ?>" class="btn btn-danger">CANCEL</a>
                            <?php endif; ?>
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
    //formData.append('id', id);
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
        $('.expense_body tr').each(function() {
            var quantity = $(this).find('.expense_quantity').val();
            var rate = $(this).find('.expense_rate').val();
            var parsedQuantity = parseFloat(quantity);
            var parsedRate = parseFloat(rate);
            if (!isNaN(parsedQuantity) && !isNaN(parsedRate)) {
                var rowTotal = parsedQuantity * parsedRate;
                totalAmount += rowTotal;
                $(this).find('.expense_amount').val(currency + rowTotal.toFixed(2));
            } else {
                $(this).find('.expense_amount').val(currency + '0.00');
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
    $(".service_1").on("click", ".addexpenseButton", function() {
        var newRowservice = $(this).closest("tr").clone();
        var tbodyRowCount = $(".service_1 tbody tr").length;
        newRowservice.find("input").val("");
        newRowservice.find("textarea").val("");
        newRowservice.find('.delete').removeClass('delete').addClass('delete_expense').html(
            '<i class="ri-delete-bin-line" aria-hidden="true"></i>');
        $(this).closest("tr").after(newRowservice);
    });
    // Delete Row
    $(".service_1").on("click", ".delete_expense", function(event) {
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
        '.expense_quantity, .expense_rate , .other-charges, .lu-charges, .rev-charges, .inserttax_percentage',
        function() {
            var selectedValue = $('#customer_state').val();
            var selectOptionpercentage = $('#tax_percentagecheckValue').val();
            updateTotalAmount(selectedValue, selectOptionpercentage);
        });
        let formSubmitted = false;
    /* Form Submission */
    $("#submit_expensedata").validate({
        rules: {
            company_id: {
                required: true
            },
            vendor_name: {
                required: true
            }
        },
        messages: {
            company_id: "Please Select Company",
            vendor_name: "Enter Vendor Name"
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            const errors = []; // to collect messages
            const toHighlight = []; // to collect inputs to mark red
            // 1) validate each row
            $('.expense_body tr').each(function(i) {
                const $row = $(this);
                const $prod = $row.find('[name="product_name[]"]');
                const $rate = $row.find('[name="amount[]"]');
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
                    errors.push(`Row ${i+1}: Amount must be greater than 0`);
                    toHighlight.push($rate);
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
            if (formSubmitted) {
            return;
            }
            $('#submit_btn').prop('disabled', true);
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var formData = new FormData(form);
            formData.append('<?= $this->security->get_csrf_token_name(); ?>', csrfToken);
            var inv_id = $('#expenseid').val();
            <?php if(!isset($expensedata['vendor_name'])){ ?>
            $.ajax({
                type: 'POST',
                url: '<?= base_url('expense/newexpense') ?>',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    formSubmitted = false;
                    if (response.status === "success") {
                        Toastify({
                            text: "Expense Details Saved Successfully",
                            backgroundColor: "linear-gradient(to right, #28a745, #28a745)",
                            gravity: "top",
                            position: 'right',
                            duration: 1000,
                            close: true
                        }).showToast();
                        setTimeout(function() {
                            window.location.href = '<?= base_url('expense') ?>';
                        }, 1000);
                    } else {
                        Toastify({
                            text: "Failed to Save expense Details.",
                            backgroundColor: "linear-gradient(to right, #ff4d4d, #ff0000)",
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
                url: '<?= base_url('expense/updateexpense') ?>',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status === "success") {
                        Toastify({
                            text: "expense Details Saved Successfully",
                            backgroundColor: "linear-gradient(to right, #28a745, #28a745)",
                            gravity: "top",
                            position: 'right',
                            duration: 1000,
                            close: true
                        }).showToast();
                        setTimeout(function() {
                            window.location.href = '<?= base_url('expense') ?>';
                        }, 1000);
                    } else {
                        Toastify({
                            text: "Failed to Save expense Details.",
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
                    setTimeout(function() {
                        //window.location.href = 'customer_information.php';
                    }, 2500);
                }
            });
            return false;
        }
    });
    <?php if(isset($expensedata['billtype'])){ ?>
    getcustomer();
    <?php } ?>
    $(document).on('click', '#customer_id', function() {
        getcustomer();
    });
    function getcustomer() {
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
                } else {
                }
            },
            error: function() {
                alert("Failed to load modal content.");
            }
        });
    }
    $('input[name="gstbill_type"]').on('change', function() {
        gstbillblock();
    });
    gstbillblock();
    <?php if(isset($expensedata['billtype'])){ ?>
    let thisid = $('#expenseid').val();
    setTimeout(function() {
        gstbillblock(thisid);
    }, 1000);
    <?php } ?>
    function gstbillblock(expenseid = 0) {
        let billType = $('input[name="gstbill_type"]:checked').val(); // Get the selected value
        // Show sections
        $('#responsiveDataTable').slideDown();
        $('#billTypeIncExcSection').slideDown();
        // Get CSRF token from meta
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        var csrfName = '<?= $this->security->get_csrf_token_name(); ?>'; // get token name
        let expenseidmine = $('#expenseid').val();
        var formData = new FormData();
        formData.append('gstbill_type', billType);
        formData.append(csrfName, csrfToken); // token name as key
        formData.append('expenseid', expenseidmine); // token name as key
        // Send via AJAX
        $.ajax({
            url: '<?= base_url('Expense/Getexpensetable')?>', // Replace with your actual URL
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
    $('#company_id').on('change', function() {
        var companyId = $(this).val();
        var latestexpense = $('.companyinput' + companyId).val().trim();
        // Step 1: Calculate financial year (e.g., 2627)
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth() + 1; // JS months: 0 = Jan, so add 1
        const finYear = month >= 4 ? `${year.toString().slice(2)}${(year + 1).toString().slice(2)}` :
            `${(year - 1).toString().slice(2)}${year.toString().slice(2)}`;
        if (latestexpense) {
            var parts = latestexpense.split('-'); // e.g., ["SM1", "2526", "001"]
            if (parts.length === 3) {
                var shortname = parts[0]; // "SM1"
                var suffix = parseInt(parts[2], 10) + 1;
                var newSuffix = suffix.toString().padStart(3, '0');
                var newexpense = shortname + '-' + finYear + '-' + newSuffix;
                $('#expense_number').val(newexpense);
            } else {
                $('#expense_number').val('');
            }
        } else {
            // If no previous expense, start with 001
            var shortname = $('.companyinput' + companyId).attr('class').match(/companyinput(\d+)/);
            if (shortname) {
                var matchedCompanyId = shortname[1];
                var companyShortTerm = '';
                <?php if (!empty($company_records)): ?>
                var companies =
                    <?php echo json_encode(array_column($company_records, 'shortterm', 'id')); ?>;
                companyShortTerm = companies[companyId];
                <?php endif; ?>
                var newexpense = companyShortTerm + '-' + finYear + '-001';
                $('#expense_number').val(newexpense);
            } else {
                $('#expense_number').val('');
            }
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
    // Ask for confirmation before removing the row
    var confirmDelete = confirm("Are you sure you want to delete this row?");
    if (!confirmDelete) {
        return;  // If the user clicks "Cancel", stop the deletion
    }
    let rowCount = $('#additionalCostBody tr').length;
    // Ensure at least one row remains
    if (rowCount <= 1) {
        showToast("At least one row is required.", "error");
        return;
    }
    // Remove the row if confirmed
    $(this).closest('tr').remove();
    // Update the total cost after removal
    updateAdditionalCostTotal();
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
        var val = $(this).val().replace(/[^\d.-]/g, ''); // Remove ₹ and anything not a number/dot/negative
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
</script>
<?php if ($isViewMode): ?>
<script>
$(document).ready(function () {
    setTimeout(function () {
        const url = window.location.href;
        if (url.includes("view_")) {
            // Make all input fields readonly
            $('input').attr('readonly', true);
            // Make all textarea fields readonly
            $('textarea').attr('readonly', true);
            // Disable all select fields
            $('select').attr('disabled', true);
            // Hide all submit buttons
            $("button[type='submit'], input[type='submit']").hide();
        }
    }, 1000); // delay in milliseconds
});
</script>
<?php endif; ?>
<script>
document.getElementById('fileupload').addEventListener('change', function (e) {
    const files = e.target.files;
    const previewContainer = document.getElementById('previewImages');
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        if (!file.type.match('image.*')) {
            showToast("Only image files are allowed.", "error");
            continue;
        }
        // Check file size (in bytes), ensure it is less than 1 MB (1048576 bytes)
        if (file.size > 1048576) { // 1 MB
            showToast("The file size exceeds the 1 MB limit. Please upload a smaller file.", "error");
            this.value = '';
            continue; // Skip this file, but continue with the next one
        } else {
            displayImage(file); // Proceed to display the image if it is within the limit
        }
    }
    // Function to display the image (original file)
    function displayImage(file) {
        const reader = new FileReader();
        reader.onload = function (event) {
            const img = document.createElement('img');
            img.src = event.target.result;
            img.style.height = '100px';
            img.style.marginRight = '8px';
            img.style.border = '1px solid #ccc';
            img.style.padding = '5px';
            img.style.borderRadius = '8px';
            img.style.marginBottom = '8px';
            previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
});
</script>
