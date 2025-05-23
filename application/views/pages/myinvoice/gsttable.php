<h5 class="fw-bold"> Products </h5>
<?php
$is_interstate = ($state_name !== 'Tamil Nadu');

?>
<input type="hidden" class="is_interstate" name="is_interstate" value="<?php if($state_name !== 'Tamil Nadu' && $gstornongst != "Non-GST"){ echo "yes"; }else{ echo "no"; } ?>">
<table class="table table-bordered text-nowrap w-100 service_1" id="responsiveDataTable">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Product Name <span class="text-danger">*</span></th>
                                            
                                            <th>Rate (₹)<span class="text-danger">*</span></th>
                                            <th>Unit</th>
                                            <th>Qty <span class="text-danger">*</span></th>
                                            
                                            <th>Amount (₹)<span class="text-danger">*</span></th>
                                            <?php if($is_interstate && $gstornongst != "Non-GST"){ ?>
                                            <th>IGST (%)<span class="text-danger">*</span></th>
                                            <th>IGST (₹)<span class="text-danger">*</span></th>
                                            <?php }else if($gstornongst != "Non-GST"){ ?>
                                            <th>GST (%)<span class="text-danger">*</span></th>
                                            <th>SGST (₹)<span class="text-danger">*</span></th>
                                            <th>CGST (₹)<span class="text-danger">*</span></th>
                                            <?php } ?>
                                            <th>Subtotal (₹)</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <!-- Check the value of formtype -->
                                    <?php  if ($formtype == 'add'){ ?>
                                    <tbody class="invoice_body">
                                        <tr class="text-center">
                                            <td class="p_name">
                                                <input type="text" class="form-control mobile_align" name="product_name[]" id="product_name">
                                                <input type="hidden" class="form-control mobile_align" name="description[]" id="HSN">
                                            </td>
                                               <td>
                                                <input type="text" class="form-control invoice_rate_exclusive mobile_align onlynumbersnoname " name="rate[]" id="rate" maxlength="10">
                                            </td>
                                            <td>
                                                <select style="width: 90px;" name="unit[]" id="unit" class="form-select mobile_align" required>
                                                       <!-- Length -->
                                                      <option value="Sq.Ft">Sq.Ft</option>
                                                      <option value="R.Ft">R.Ft</option>
                                                      <option value="Nos">Nos</option>
                                                      <option value="Sets">Sets</option>      
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control invoice_quantity_exclusive mobile_align" name="quantity[]" id="quantity" maxlength="6">
                                                <input type="hidden" class="form-control invoice_quantity_exclusive mobile_align" name="discount_exclusive[]" >
                                            </td>
                                            <td>
                                                <input type="text" class="form-control invoice_quantity_exclusive mobile_align" name="amount[]" >
                                            </td>
                                              <?php if($gstornongst != "Non-GST"){ ?>
                                            <td>
                                                <select style="width: 65px;" class="form-select invoice_quantity_exclusive mobile_align" name="GST[]">
                                                    <option value="0">0</option>
                                                  <option value="5">5</option>
                                                  <option value="12">12</option>
                                                  <option value="18"  <?php if ($formtype == 'add'){ echo "selected"; }  ?>>18</option>
                                                </select>
                                            </td>
                                              <?php } ?>
                                            <?php if($gstornongst != "Non-GST" && $is_interstate){ ?>
                                                <td>
                                                <input type="text" class="form-control  mobile_align" name="IGST[]" readonly>
                                            </td>

                                            <?php }else if($gstornongst != "Non-GST"){ ?>
                                            <td>
                                                <input type="text" class="form-control  mobile_align" name="SGST[]" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control  mobile_align" name="CGST[]" readonly>
                                            </td>
                                        <?php } ?>
                                            <td>
                                                <input type="text" class="form-control invoice_amount mobile_align" name="amount_subtotal[]" id="amount" value="₹0.00"  readonly>
                                            </td>
                                            <td>
                                                <button class="btn btn-primary btn-sm exclusivegst_addinvoiceButton" type="button" value="Add Row" title='Add'><i class="ri-add-line" aria-hidden="true"></i></button>
                                                <button class='delete delete_provider btn btn-danger btn-sm delete_rows delete_invoice' type='button' value='Delete' title='Delete'>
                                                    <i class="ri-delete-bin-line"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php }else{ ?>
                                      <?php if (!empty($invoicedetails) && is_array($invoicedetails)): ?>
                                        <tbody class="invoice_body">
    <?php foreach ($invoicedetails as $detail): ?>
        <tr class="text-center">
             <input type="hidden" name="invoice_details_id[]" value="<?= htmlspecialchars($detail['id']); ?>">
             <input type="hidden" name="invoice__id[]" value="<?= htmlspecialchars($detail['invoice_id']); ?>">
            <td class="p_name">
                <input type="text" class="form-control mobile_align onlytextnonumber" name="product_name[]" id="product_name" value="<?= htmlspecialchars($detail['product_name']); ?>" required>
            </td>
           
                <input type="hidden" class="form-control mobile_align" name="description[]" id="HSN" value="<?= htmlspecialchars($detail['product_description']); ?>">
           
            <td>
                <input type="text" class="form-control invoice_rate_exclusive mobile_align onlynumbersnoname" name="rate[]" id="rate" value="<?= htmlspecialchars($detail['rate']); ?>" maxlength="10" required>
            </td>
            <td>
                <select style="width: 90px;" name="unit[]" id="unit" class="form-select mobile_align" required>
                    <!-- Length -->
                    <option value="Sq.Ft" <?= ($detail['unit'] == 'Sq.Ft') ? 'selected' : ''; ?>>Sq.Ft</option>
                    <option value="R.Ft" <?= ($detail['unit'] == 'R.Ft') ? 'selected' : ''; ?>>R.Ft</option>
                    <option value="Nos" <?= ($detail['unit'] == 'Nos') ? 'selected' : ''; ?>>Nos</option>
                    <option value="Sets" <?= ($detail['unit'] == 'Sets') ? 'selected' : ''; ?>>Sets</option>
                </select>
            </td>
            <td>
                <input type="text" class="form-control invoice_quantity_exclusive mobile_align" name="quantity[]" id="quantity" value="<?= htmlspecialchars((int)$detail['quantity']); ?>" maxlength="6" required>
            </td>
           
                <input type="hidden" class="form-control invoice_quantity_exclusive mobile_align" name="discount_exclusive[]" value="<?= htmlspecialchars($detail['discount']); ?>">
           
            <td>
                <input type="text" class="form-control invoice_quantity_exclusive mobile_align" name="amount[]" value="<?= htmlspecialchars($detail['amount']); ?>" readonly>
            </td>
            <?php if($gstornongst != "Non-GST"){ ?>
            <td>
              <select style="padding: 10px;width:65px;" class="form-select invoice_quantity_exclusive mobile_align" name="GST[]">
                  <option value="0" <?= ($detail['gst_per'] == 0 || empty($detail['gst_per'])) ? 'selected' : ''; ?>>0</option>
                <option value="5" <?= ($detail['gst_per'] == 5 || empty($detail['gst_per'])) ? 'selected' : ''; ?>>5</option>
                <option value="12" <?= ($detail['gst_per'] == 12) ? 'selected' : ''; ?>>12</option>
                <option value="18" <?= ($detail['gst_per'] == 18) ? 'selected' : ''; ?>>18</option>
            </select>
            </td>
             <?php if($gstornongst != "Non-GST" && $is_interstate){ ?>
                                                <td>
                                                <input type="text" class="form-control  mobile_align" name="IGST[]" value="<?= htmlspecialchars($detail['igst']); ?>" readonly>
                                            </td>

                                            <?php }else if($gstornongst != "Non-GST"){ ?>
                                            <td>
                                                <input type="text" class="form-control  mobile_align" name="SGST[]" value="<?= htmlspecialchars($detail['sgst']); ?>" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control  mobile_align" name="CGST[]" value="<?= htmlspecialchars($detail['cgst']); ?>" readonly>
                                            </td>
                                        <?php } ?>
           
        <?php } ?>
            <td>
                <input type="text" class="form-control invoice_amount mobile_align" name="amount_subtotal[]" id="amount" value="₹<?= number_format((float)$detail['sub_total'], 2, '.', ''); ?>" readonly>
            </td>
            <td>
                <button class="btn btn-primary btn-sm exclusivegst_addinvoiceButton" type="button" value="Add Row">
                    <i class="ri-add-line" aria-hidden="true"></i>
                </button>
                <button class='delete delete_provider btn btn-danger btn-sm delete_rows delete_invoice' type='button' value='Delete'>
                    <i class="ri-delete-bin-line"></i>
                </button>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
    <?php endif; ?>
                                    <?php } ?>
                                    <tfoot>
                                        <tr>
                                            <td colspan="<?php if($gstornongst != "Non-GST" && $is_interstate){ echo "7"; }else if($gstornongst != "Non-GST"){ echo "8"; }else{ echo "5"; }?>" style="font-weight: bold; text-align: right;">SubTotal</td>
                                            <td><input type="text" class="form-control subtotal_input mobile_align" name="subtotal" id="subtotal" value="<?= (!empty($invoicedata['total_amount']) && $invoicedata['total_amount'] != 0) ? number_format($invoicedata['total_amount'], 2) : '0.00'; ?>" readonly></td>
                                            <td></td>
                                        </tr>                       
                                      
                                        <input type="hidden" class="form-control other-charges mobile_align" name="additional_cost" id="additional_cost" value="<?= (!empty($invoicedata['additional_cost']) && $invoicedata['additional_cost'] != 0) ? number_format($invoicedata['additional_cost'], 2) : '0.00'; ?>">
                                        <tr>
                                            <td colspan="<?php if($gstornongst != "Non-GST" && $is_interstate){ echo "7"; }else if($gstornongst != "Non-GST"){ echo "8"; }else{ echo "5"; }?>" style="font-weight: bold; text-align: right;">Grand Total</td>
                                            <td><input type="text" class="form-control grand-total mobile_align" name="grand_total" id="grand_total" value="<?= (!empty($invoicedata['grand_total']) && $invoicedata['grand_total'] != 0) ? number_format($invoicedata['grand_total'], 2) : '0.00'; ?>" readonly></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="<?php if($gstornongst != "Non-GST" && $is_interstate){ echo "7"; }else if($gstornongst != "Non-GST"){ echo "8"; }else{ echo "5"; }?>" style="font-weight: bold; text-align: right;">Paid Amount </td>
                                            <td><input type="text" class="form-control rev-charges mobile_align" name="paid_amount" id="rev_charges" value="<?= (!empty($invoicedata['paid_amount']) && $invoicedata['paid_amount'] != 0) ? number_format($invoicedata['paid_amount'], 2, '.', '') : '0.00'; ?>" readonly></td>
                                            <td></td>
                                        </tr>
                                         <tr>
                                            <td colspan="<?php if($gstornongst != "Non-GST" && $is_interstate){ echo "7"; }else if($gstornongst != "Non-GST"){ echo "8"; }else{ echo "5"; }?>" style="font-weight: bold; text-align: right;">Balance Amount </td>
                                            <td><input type="text" class="form-control balance-amount mobile_align" name="balance_amount" id="balance_amount" value="<?= (!empty($invoicedata['balance_amount']) && $invoicedata['balance_amount'] != 0) ? number_format($invoicedata['balance_amount'], 2) : '0.00'; ?>" readonly></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
<script type="text/javascript">
$(document).ready(function () {
    // Trigger recalculation on input/change for relevant fields
    $(document).on('input change', '.invoice_rate_exclusive, .invoice_quantity_exclusive, [name="discount_exclusive[]"], [name="GST[]"], #is_interstate, input[name="gstbill_type"]', function () {
        const row = $(this).closest('tr');
        const rate = parseFloat(row.find('.invoice_rate_exclusive').val()) || 0;
        const qty = parseFloat(row.find('.invoice_quantity_exclusive').val()) || 0;
        const discount = parseFloat(row.find('[name="discount_exclusive[]"]').val()) || 0;
        const gstPercent = parseFloat(row.find('[name="GST[]"]').val()) || 0;
        const billType = $('input[name="gstbill_type"]:checked').val();
        const isInterstate = $('.is_interstate').val() === 'yes';

        let amount = rate * qty;
        let discountedAmount = amount - discount;
        if (discountedAmount < 0) discountedAmount = 0;

        if (billType === "Exclusive GST") {
            // Calculate GST amount
            const taxAmount = (discountedAmount * gstPercent) / 100;

            if (isInterstate) {
                // IGST applies
                row.find('input[name="CGST[]"]').val('0.00');
                row.find('input[name="SGST[]"]').val('0.00');
                row.find('input[name="IGST[]"]').val(taxAmount.toFixed(2));
                // Total including IGST
                var totalWithGST = discountedAmount + taxAmount;
            } else {
                // CGST + SGST split
                const halfGST = (taxAmount / 2).toFixed(2);
                row.find('input[name="CGST[]"]').val(halfGST);
                row.find('input[name="SGST[]"]').val(halfGST);
                row.find('input[name="IGST[]"]').val('0.00');
                var totalWithGST = discountedAmount + parseFloat(halfGST) * 2;
            }

            row.find('[name="amount[]"]').val((discountedAmount + taxAmount).toFixed(2));
            row.find('.invoice_amount').val(`₹${totalWithGST.toFixed(2)}`);
        }
        else if (billType === "Inclusive GST") {
            // Inclusive GST calculations
            calculateInclusiveGST(row);
        } else {
            // If no GST type selected or unknown, just show discounted amount
            row.find('[name="amount[]"]').val(discountedAmount.toFixed(2));
            row.find('.invoice_amount').val(`₹${discountedAmount.toFixed(2)}`);
            row.find('input[name="CGST[]"]').val('0.00');
            row.find('input[name="SGST[]"]').val('0.00');
            row.find('input[name="IGST[]"]').val('0.00');
        }

        // Update subtotals and grand total
        updateTotals(billType);
         setTimeout(function () {
        updatePaymentFields();
    }, 200);
    });

    // Initial totals update on page load
    const billTypeInit = $('input[name="gstbill_type"]:checked').val() || "Exclusive GST";
    updateTotals(billTypeInit);
});

function calculateInclusiveGST(row) {
    const rate = parseFloat(row.find('.invoice_rate_exclusive').val()) || 0;
    const qty = parseFloat(row.find('.invoice_quantity_exclusive').val()) || 0;
    const discount = parseFloat(row.find('[name="discount_exclusive[]"]').val()) || 0;
    const gstPercent = parseFloat(row.find('[name="GST[]"]').val()) || 0;
    const isInterstate = $('.is_interstate').val() === 'yes';

    let amount = rate * qty;
    let discountedAmount = amount - discount;
    if (discountedAmount < 0) discountedAmount = 0;

    const divisor = 1 + gstPercent / 100;
    const baseAmount = discountedAmount / divisor;
    const gstAmount = discountedAmount - baseAmount;
    const halfGST = (gstAmount / 2).toFixed(2);

    if (isInterstate) {
        row.find('input[name="CGST[]"]').val('0.00');
        row.find('input[name="SGST[]"]').val('0.00');
        row.find('input[name="IGST[]"]').val(gstAmount.toFixed(2));
    } else {
        row.find('input[name="CGST[]"]').val(halfGST);
        row.find('input[name="SGST[]"]').val(halfGST);
        row.find('input[name="IGST[]"]').val('0.00');
    }

    row.find('[name="amount[]"]').val(baseAmount.toFixed(2));
    row.find('.invoice_amount').val(`₹${discountedAmount.toFixed(2)}`);

    return baseAmount;
}

function updateTotals(billType) {
    let subtotal = 0;

    $('.invoice_body tr').each(function () {
        const $row = $(this);
        const rate = parseFloat($row.find('.invoice_rate_exclusive').val()) || 0;
        const qty = parseFloat($row.find('.invoice_quantity_exclusive').val()) || 0;
        const discount = parseFloat($row.find('[name="discount_exclusive[]"]').val()) || 0;
        const cgst = parseFloat($row.find('[name="CGST[]"]').val()) || 0;
        const sgst = parseFloat($row.find('[name="SGST[]"]').val()) || 0;
        const igst = parseFloat($row.find('[name="IGST[]"]').val()) || 0;

        let rowAmountWithoutGST = rate * qty - discount;
        if (rowAmountWithoutGST < 0) rowAmountWithoutGST = 0;

        let rowTotal = 0;

        if (billType === "Exclusive GST") {
            // Add either IGST or CGST+SGST for total
            if (igst > 0) {
                rowTotal = rowAmountWithoutGST + igst;
            } else {
                rowTotal = rowAmountWithoutGST + cgst + sgst;
            }
        } else if (billType === "Inclusive GST") {
            rowTotal = rowAmountWithoutGST; // Inclusive GST row total is base amount
        } else {
            rowTotal = rowAmountWithoutGST;
        }

        subtotal += rowTotal;

        // Update amount and invoice amount display
        if (billType !== "Inclusive GST") {
            $row.find('[name="amount[]"]').val(rowAmountWithoutGST.toFixed(2));
        }
        else if (billType === "Exclusive GST") {
            $row.find('.invoice_amount').val(`₹${rowTotal.toFixed(2)}`);
        } else {
            $row.find('.invoice_amount').val(`₹${rowAmountWithoutGST.toFixed(2)}`);
        }
    });

    $('#subtotal').val(`₹${subtotal.toFixed(2)}`);

    const addCost = parseFloat($('#additional_cost').val().replace(/[^\d.-]/g, '')) || 0;
    const finalTotal = subtotal + addCost;
    $('#grand_total').val(`₹${finalTotal.toFixed(2)}`);
}

// Optional: payment fields update function (unchanged)
function updatePaymentFields() {
    var paymentMethod = $('#paymentMethod').val();
    var paidAmount = parseFloat($('#paidAmount').val());
    var balamount = parseFloat($('#balance_amount').val());

    if (isNaN(paidAmount) || paidAmount < 0) paidAmount = 0;
    if (isNaN(balamount) || balamount < 0) balamount = 0;

    var currentCharges = parseFloat($('#rev_charges').val());
    if (isNaN(currentCharges) || currentCharges < 0) currentCharges = 0;

    var grandTotalRaw = $('#grand_total').val().replace(/[₹,]/g, '').trim();
    var grandTotal = parseFloat(grandTotalRaw);
    if (isNaN(grandTotal) || grandTotal < 0) grandTotal = 0;

    // ✅ Only update if paidAmount >= 0
    if (paidAmount >= 0) {
        var balanceAmount = grandTotal - paidAmount - currentCharges;

        // ✅ Prevent negative balance
        if (balanceAmount < 0) {
            balanceAmount = 0;
        }

        $('#balance_amount').val(isNaN(balanceAmount) ? '' : balanceAmount.toFixed(2));
    }

}

$('input[name="rate[]"]').on('keyup', function () {
    setTimeout(function () {
        updatePaymentFields();
    }, 200);
});

$('input[name="quantity[]"]').on('keyup', function () {
    setTimeout(function () {
        updatePaymentFields();
    }, 200);
});
</script>
