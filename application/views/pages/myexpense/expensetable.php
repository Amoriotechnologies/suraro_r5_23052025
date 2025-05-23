<h5 class="fw-bold"> Expenses </h5>

<table class="table table-bordered text-nowrap w-100 service_1" id="responsiveDataTable">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Name <span class="text-danger">*</span></th>
                                            
                                            <th>Rate (₹)</th>
                                            <th>Unit</th>
                                            <th>Qty </th>
                                            
                                            <th>Amount (₹)<span class="text-danger">*</span></th>
                                            <?php if($gstornongst != "Non-GST"){ ?>
                                            <th>GST (%)<span class="text-danger">*</span></th>
                                            <th>SGST (%)<span class="text-danger">*</span></th>
                                            <th>CGST (%)<span class="text-danger">*</span></th>
                                            <?php } ?>
                                            <th>Subtotal (₹)</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <!-- Check the value of formtype -->
                                    <?php  if ($formtype == 'add'){ ?>
                                    <tbody class="expense_body">
                                        <tr class="text-center">
                                            <td class="p_name">
                                                <input type="text" class="form-control mobile_align onlytextnonumber" name="product_name[]" id="product_name">
                                                 <input type="hidden" class="form-control mobile_align" name="description[]" id="HSN">
                                            </td>
                                               <td>
                                                <input type="text" class="form-control expense_rate_exclusive mobile_align onlynumbersnoname" name="rate[]" id="rate" maxlength="10" >
                                            </td>
                                            <td>
                                                <select name="unit[]" id="unit" class="form-select mobile_align" required>
                                                       <!-- Length -->
                                                      <option value="Sq.Ft">Sq.Ft</option>
                                                      <option value="R.Ft">R.Ft</option>
                                                      <option value="Nos">Nos</option>
                                                      <option value="Sets">Sets</option>  
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control expense_quantity_exclusive mobile_align" name="quantity[]" id="quantity" maxlength="6">
                                                <input type="hidden" class="form-control expense_quantity_exclusive mobile_align" name="discount_exclusive[]" >
                                            </td>
                                            
                                            <td>
                                                <input type="text" class="form-control expense_quantity_exclusive mobile_align" name="amount[]" >
                                            </td>
                                              <?php if($gstornongst != "Non-GST"){ ?>
                                            <td>
                                                <select class="form-select expense_quantity_exclusive mobile_align" name="GST[]">
                                                  <option value="5">5</option>
                                                  <option value="12">12</option>
                                                  <option value="18">18</option>
                                                </select>
                                            </td>
                                         <td>
                                                <input type="disabled" class="form-control  mobile_align" name="SGST[]" disabled>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control  mobile_align" name="CGST[]" disabled>
                                            </td>
                                        <?php } ?>
                                            <td>
                                                <input type="text" class="form-control expense_amount mobile_align" name="amount_subtotal[]" id="amount" value="₹0.00"  readonly>
                                            </td>
                                            <td>
                                                <button class="btn btn-primary btn-sm exclusivegst_addexpenseButton" type="button" value="Add Row" title='Add'><i class="ri-add-line" aria-hidden="true"></i></button>
                                                <button class='delete delete_provider btn btn-danger btn-sm delete_rows delete_expense' type='button' value='Delete' title='Delete'>
                                                    <i class="ri-delete-bin-line"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php }else{ ?>
                                      <?php if (!empty($expensedetails) && is_array($expensedetails)): ?>
                                        <tbody class="expense_body">
    <?php foreach ($expensedetails as $detail): ?>
        <tr class="text-center">
             <input type="hidden" name="expense_details_id[]" value="<?= htmlspecialchars($detail['id']); ?>">
             <input type="hidden" name="expense__id[]" value="<?= htmlspecialchars($detail['expense_id']); ?>">
            <td class="p_name">
                <input type="text" class="form-control mobile_align onlytextnonumber" name="product_name[]" id="product_name" value="<?= htmlspecialchars($detail['product_name']); ?>" required>
            </td>
            
                <input type="hidden" class="form-control mobile_align" name="description[]" id="HSN" value="<?= htmlspecialchars($detail['product_description']); ?>">
           
            <td>
                <input type="text" class="form-control expense_rate_exclusive mobile_align onlynumbersnoname" name="rate[]" id="rate" value="<?= htmlspecialchars($detail['rate']); ?>" maxlength="10" required>
            </td>
            <td>
                <select name="unit[]" id="unit" class="form-select mobile_align" required>
                    <!-- Length -->
                   <option value="Sq.Ft" <?= ($detail['unit'] == 'Sq.Ft') ? 'selected' : ''; ?>>Sq.Ft</option>
                    <option value="R.Ft" <?= ($detail['unit'] == 'R.Ft') ? 'selected' : ''; ?>>R.Ft</option>
                    <option value="Nos" <?= ($detail['unit'] == 'Nos') ? 'selected' : ''; ?>>Nos</option>
                    <option value="Sets" <?= ($detail['unit'] == 'Sets') ? 'selected' : ''; ?>>Sets</option>
                </select>
            </td>
            <td>
                <input type="number" class="form-control expense_quantity_exclusive mobile_align" name="quantity[]" id="quantity" value="<?= (int)$detail['quantity']; ?>" step="1" maxlength="6" required>
            </td>
        
                <input type="hidden" class="form-control expense_quantity_exclusive mobile_align" name="discount_exclusive[]" value="<?= htmlspecialchars($detail['discount']); ?>">
           
            <td>
                <input type="text" class="form-control expense_quantity_exclusive mobile_align" name="amount[]" value="<?= htmlspecialchars($detail['amount']); ?>" readonly>
            </td>
            <?php if($gstornongst != "Non-GST"){ ?>
            <td>
              <select style="padding: 10px;" class="form-select expense_quantity_exclusive mobile_align" name="GST[]">
                <option value="5" <?= ($detail['gst_per'] == 5 || empty($detail['gst_per'])) ? 'selected' : ''; ?>>5</option>
                <option value="12" <?= ($detail['gst_per'] == 12) ? 'selected' : ''; ?>>12</option>
                <option value="18" <?= ($detail['gst_per'] == 18) ? 'selected' : ''; ?>>18</option>
            </select>
            </td>
            <td>
                <input type="text" class="form-control mobile_align" name="SGST[]" value="<?= htmlspecialchars($detail['sgst']); ?>" readonly>
            </td>
            <td>
                <input type="text" class="form-control mobile_align" name="CGST[]" value="<?= htmlspecialchars($detail['cgst']); ?>" readonly>
            </td>
        <?php } ?>
            <td>
                <input type="text" class="form-control expense_amount mobile_align" name="amount_subtotal[]" id="amount" value="₹<?= number_format((float)$detail['sub_total'], 2, '.', ''); ?>" readonly>
            </td>
            <td>
                <button class="btn btn-primary btn-sm exclusivegst_addexpenseButton" type="button" value="Add Row">
                    <i class="ri-add-line" aria-hidden="true"></i>
                </button>
                <button class='delete delete_provider btn btn-danger btn-sm delete_rows delete_expense' type='button' value='Delete'>
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
                                            <td colspan="<?php if($gstornongst != "Non-GST"){ echo "8"; }else{ echo "5"; }?>" style="font-weight: bold; text-align: right;">SubTotal</td>
                                            <td><input type="text" class="form-control subtotal_input mobile_align" name="subtotal" id="subtotal" value="<?= (!empty($expensedata['total_amount']) && $expensedata['total_amount'] != 0) ? number_format($expensedata['total_amount'], 2) : '0.00'; ?>" readonly></td>
                                            <td></td>
                                        </tr>                       
                                        <tr>
                                            <td colspan="<?php if($gstornongst != "Non-GST"){ echo "8"; }else{ echo "5"; }?>" style="font-weight: bold; text-align: right;">Grand Total</td>
                                            <td><input type="text" class="form-control grand-total mobile_align" name="grand_total" id="grand_total" value="<?= (!empty($expensedata['grand_total']) && $expensedata['grand_total'] != 0) ? number_format($expensedata['grand_total'], 2) : '0.00'; ?>" readonly></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="<?php if($gstornongst != "Non-GST"){ echo "8"; }else{ echo "5"; }?>" style="font-weight: bold; text-align: right;">Paid Amount </td>
                                            <td><input type="text" class="form-control rev-charges mobile_align" name="paid_amount" id="rev_charges" value="<?= (!empty($expensedata['paid_amount']) && $expensedata['paid_amount'] != 0) ? number_format($expensedata['paid_amount'], 2) : '0.00'; ?>" readonly></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="<?php if($gstornongst != "Non-GST"){ echo "8"; }else{ echo "5"; }?>" style="font-weight: bold; text-align: right;">Balance Amount </td>
                                            <td><input type="text" class="form-control balance-amount mobile_align" name="balance_amount" id="balance_amount" value="<?= (!empty($expensedata['balance_amount']) && $expensedata['balance_amount'] != 0) ? number_format($expensedata['balance_amount'], 2, '.', '') : '0.00'; ?>" readonly></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
<script type="text/javascript">
$(document).ready(function () {
    // Add new row on button click
$(document).on('click', '.exclusivegst_addexpenseButton', function () {
    // Clone the first row
    var newRow = $('.expense_body tr:first').clone();
    // Remove the hidden expense_details_id field
    newRow.find('input[name="expense_details_id[]"]').remove();
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
    newRow.find('select[name="GST[]"]').val('5');
    newRow.find('select[name="unit[]"]').val('Sq.Ft');
    // Append new row to the table body
    $('.expense_body').append(newRow);
});
    $(document).on('input change', '.expense_rate_exclusive, .expense_quantity_exclusive, [name="discount_exclusive[]"], [name="SGST[]"]', function () {
    const row = $(this).closest('tr');
    const rate = parseFloat(row.find('.expense_rate_exclusive').val()) || 0;
    let qtyVal = row.find('.expense_quantity_exclusive').val();
    const qty = (qtyVal === "" || isNaN(qtyVal)) ? 1 : parseFloat(qtyVal);

    const discount = parseFloat(row.find('[name="discount_exclusive[]"]').val()) || 0;
    const gstPercent = parseFloat(row.find('[name="GST[]"]').val()) || 0; // get selected GST
    let billType = $('input[name="gstbill_type"]:checked').val(); // Get the selected value
    let actualamount = parseFloat(row.find('[name="amount[]"]').val()) || 0; // Get the selected value
    
    let amount = 0;

    if(rate == '' && amount > 0 ){
        amount = actualamount;
    }else{
       amount = rate * qty; 
    }
    let discountedAmount = amount - discount;
    if (discountedAmount < 0) discountedAmount = 0;
    // Calculate GST amount (divided equally for SGST and CGST)
    const taxAmount = (discountedAmount * gstPercent) / 100;
    const halfGST = (taxAmount / 2).toFixed(2);
    // Update CGST/SGST input values
    row.find('input[name="CGST[]"]').eq(0).val(halfGST); // SGST
    row.find('input[name="SGST[]"]').eq(0).val(halfGST); // CGST
    // Total with GST
    const totalWithGST = discountedAmount + parseFloat(halfGST) * 2;
    const totalWithout_GST = discountedAmount;//for inclusive
  
    // Update hidden amount and display subtotal
     //row.find('[name="amount[]"]').val(discountedAmount.toFixed(2) + taxAmount);

    // console.log('3')
    if(billType == "Exclusive GST"){
        row.find('.expense_amount').val(`₹${totalWithGST.toFixed(2)}`);
    }else{
         row.find('.expense_amount').val(`₹${totalWithout_GST.toFixed(2)}`);
    }
     if (billType === "Inclusive GST") {  
        calculateInclusiveGST(row);
            var subtotalamt = 0;
            $('input[name="amount_subtotal[]"]').each(function() {
                var val = $(this).val().replace(/[^\d.-]/g, ''); // Remove ₹ and anything not a number/dot/negative
                subtotalamt += parseFloat(val) || 0;
            });
           
            $('#subtotal').val('₹' + subtotalamt.toFixed(2)); // Update Subtotal field
            let finaladdcost = subtotalamt;
            $('#grand_total').val('₹' + finaladdcost.toFixed(2));
            // Update Subtotal field
    } else {
       
        updateTotalAmountExclusive(); // optional: move your existing logic into this function
    }
});
    // Delete row functionality
    $(document).on('click', '.delete_expense', function () {
    // Ask for confirmation before removing the row
    var confirmDelete = confirm("Are you sure you want to delete this expense row?");
    
    if (!confirmDelete) {
        return;  // If the user clicks "Cancel", stop the deletion
    }

    // Only remove if more than one row exists
    if ($('.expense_body tr').length > 1) {
        $(this).closest('tr').remove();
    } else {
        showToast("At least one expense row is required.", "error");
    }
});

});
function calculateInclusiveGST(row) {
    const rate = parseFloat(row.find('.expense_rate_exclusive').val()) || 0;
    let qtyVal = row.find('.expense_quantity_exclusive').val();
    const qty = (qtyVal === "" || isNaN(qtyVal)) ? 1 : parseFloat(qtyVal);
    const discount = parseFloat(row.find('[name="discount_exclusive[]"]').val()) || 0;
    const gstPercent = parseFloat(row.find('[name="GST[]"]').val()) || 0;
     let actualamount = parseFloat(row.find('[name="amount[]"]').val()) || 0; // Get the selected value
    
    if(rate == '' && amount > 0 ){
        let amount = actualamount;
    }else{
       let amount = rate * qty; 
    }
    let discountedAmount = amount - discount;
    if (discountedAmount < 0) discountedAmount = 0;
    // Inclusive GST: amount includes GST already
    const divisor = 1 + gstPercent / 100;
    const baseAmount = discountedAmount / divisor;
    const gstAmount = discountedAmount - baseAmount;
    const halfGST = (gstAmount / 2).toFixed(2);
    // Update values in the row
    row.find('input[name="CGST[]"]').val(halfGST);
    row.find('input[name="SGST[]"]').val(halfGST);
    row.find('[name="amount[]"]').val(baseAmount.toFixed(2));
    row.find('.expense_amount').val(`₹${discountedAmount.toFixed(2)}`);
    return baseAmount;
}
function updateTotalAmountExclusive(selectedState, taxOptionState) {
    const currency = '₹';
    let subtotal = 0;
    // read additional/reverse charges
    const otherCharges = parseFloat($('.other-charges').val()) || 0;
    const reverseCharges = parseFloat($('.rev-charges').val()) || 0;
    // default tax percentages
    const CGST_RATE = 9;
    const SGST_RATE = 9;
    const IGST_RATE = 18;
    const CGST_ALT = 2.5;  // if taxOptionState applies
    const SGST_ALT = 2.5;
    const IGST_ALT = 5;
    // zero out tax displays
    $('.totalcgst_input, .totalsgst_input, .totaligst_input').val(currency + '0.00');
    // 1) loop rows, calc discounted row amount
    $('.expense_body tr').each(function() {
        const $row = $(this);
        const rate = parseFloat($row.find('.expense_rate_exclusive').val()) || 0;
        const qty  = parseFloat($row.find('.expense_quantity_exclusive').val()) || 0;
        const disc = parseFloat($row.find('[name="discount_exclusive[]"]').val()) || 0;
        const cgst = parseFloat($row.find('[name="CGST[]"]').val()) || 0;
        const sgst = parseFloat($row.find('[name="SGST[]"]').val()) || 0;
        let billType = $('input[name="gstbill_type"]:checked').val(); // Get the selected value
        
         let actualamount = parseFloat($row.find('[name="amount[]"]').val()) || 0; // Get the selected value
    
        let rowTotal_withoutgst = 0

            if(rate == '' && actualamount > 0 ){
                rowTotal_withoutgst = actualamount - disc;
            }else{
               rowTotal_withoutgst = rate * qty - disc;
            }
        if (rowTotal_withoutgst < 0) rowTotal_withoutgst = 0;
        
            let rowTotal = 0;
         if(rate == '' && actualamount > 0 ){
                rowTotal = actualamount - disc + (cgst * 2);
            }else{
               rowTotal = rate * qty - disc + (cgst * 2);
            }
        if (rowTotal < 0) rowTotal = 0;
      // update fields

        let qtyVal = $row.find('.expense_quantity_exclusive').val();

        if(qtyVal !== ''){
             $row.find('[name="amount[]"]').val(rowTotal_withoutgst.toFixed(2));
        }
    
        if(billType == "Exclusive GST"){
        $row.find('.expense_amount').val(currency + rowTotal.toFixed(2));
        }else{
        $row.find('.expense_amount').val(rowTotal_withoutgst.toFixed(2));
        }
        subtotal += rowTotal;
    });
    var subtotalamt = 0;
    $('input[name="amount_subtotal[]"]').each(function() {
        var val = $(this).val().replace(/[^\d.-]/g, ''); // Remove ₹ and anything not a number/dot/negative
        subtotalamt += parseFloat(val) || 0;
    });
    $('#subtotal').val('₹' + subtotalamt.toFixed(2)); // Update Subtotal field
    let finaladdcost = subtotalamt ;
    $('#grand_total').val('₹' + finaladdcost.toFixed(2));
}
 <?php if (!empty($expensedetails) && is_array($expensedetails)): ?>
    var subtotalamt = 0;
    $('input[name="amount_subtotal[]"]').each(function() {
        var val = $(this).val().replace(/[^\d.-]/g, ''); // Remove ₹ and anything not a number/dot/negative
        subtotalamt += parseFloat(val) || 0;
    });
    $('#subtotal').val('₹' + subtotalamt.toFixed(2)); // Update Subtotal field
    let finaladdcost = subtotalamt;
    $('#grand_total').val('₹' + finaladdcost.toFixed(2)); // Update Subtotal field
    <?php endif; ?>
</script>
