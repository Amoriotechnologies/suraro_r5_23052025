<?php
$payment_parts = explode(',', $invoicedata['payment_history'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice PDF</title>
  <link href="<?= base_url('assets/css/bootstrap5.3.3.min.css') ?>" rel="stylesheet">
  <script src="<?= base_url('assets/js/pdf/html2pdf.bundle.min.js'); ?>"></script>
  <style>
    body { font-size: 12px; background-color: #f8f9fa; }
    .invoice-box { background: #fff; padding: 20px; margin: auto; max-width: 800px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,.1); }
    .custom-thead { background-color: rgb(227, 193, 245); }
    .tr-back { border-color: #000 !important; }
    .summary td { text-align: right; border: none; }
    .footer { font-size: 11px; text-align: center; margin-top: 20px; color: #6c757d; }
    @media print { .no-print { display: none; } }
    .table {
    width: 100%;
    border-collapse: collapse;
  }
  .table th, .table td {
    border: 1px solid #dee2e6;
    padding: 0.5rem;
    text-align: left;
  }
  .text-center { text-align: center; }
  .text-end { text-align: right; }
  .fw-bold { font-weight: bold; }
  .mb-3 { margin-bottom: 1rem; }
  .mt-4 { margin-top: 1.5rem; }
  .btn { padding: 0.375rem 0.75rem; font-size: 1rem; cursor: pointer; }
  .btn-danger { background-color: #dc3545; color: #fff; border: none; }
  </style>
</head>
<body>
  <div class="text-center my-3 no-print"><br><br><br>
    <button class="btn btn-danger" onclick="downloadPDF()"><i class="fas fa-file-pdf"></i> Download Invoice</button>
  </div>
  <div id="invoice" class="invoice-box">
    <div class="text-center mb-3">
      <?php
$logo = '';
if ($invoicedata['company_id'] == 1) {
    $logo = 'stonemetallogo.png';
} else if ($invoicedata['company_id'] == 2) {
    $logo = 'logo.png';
}
?>
<?php if(!empty($logo)){ ?><img src="<?= base_url('assets/images/' . $logo); ?>" alt="Logo" style="height: 110px; width: 110px;"><?php } ?>
      <h4 class="mb-4 fw-bold text-primary">Invoice</h4>
    </div>
    <div class="d-flex justify-content-between">
      <div class="col-md-6"><p class="mb-3 p-3 text-center">Invoice No: <strong><?= $invoicedata['invoice_number'] ?></strong></p></div>
      <div class="col-md-6"><p class="mb-3 p-3 text-center">Date: <strong><?= date('d/m/Y', strtotime($invoicedata['invoice_date'])) ?></strong></p></div>
    </div>
    <div class="d-flex justify-content-between gap-1 flex-nowrap">
      <div class="border rounded p-2" style="width: 50%; background-color: rgb(251, 219, 224);">
        <h6 class="text-center"><i class="fas fa-user-tie me-2 text-primary p-1"></i>Billed By</h6>
        <p class="mb-1"><strong>Company:</strong> <?php echo $companies[$invoicedata['company_id']-1]['companyname']; ?></p>
        <p class="mb-1"><strong>Name:</strong> Admin</p>
        <p class="mb-1"><strong>Address:</strong> Chennai</p>
      </div>
      <div class="border rounded p-2" style="width: 50%; background-color: rgb(251, 219, 224);">
        <h6 class="text-center"><i class="fas fa-user me-2 text-primary p-1"></i>Billed To</h6>
        <p class="mb-1"><strong>Name:</strong> <?= ucwords($customer_details['customer_name']) ?></p>
        <p class="mb-1"><strong>Address:</strong> <?= ucwords($customer_details['customer_address']) ?></p>
        <p class="mb-1"><strong>Mobile:</strong> <?= $customer_details['customer_mobilenumber'] ?></p>
      </div>
    </div>
    <div class="table-responsive mt-3 mb-3">
      <table style="min-width:600px" class="table table-bordered table-striped table-hover">
          <thead>
            <tr class="tr-back">
              <th class="custom-thead">S.No</th>
              <th class="custom-thead">Product</th>
              <th class="custom-thead">Rate</th>
              <th class="custom-thead">Unit</th>
              <th class="custom-thead">Qty</th>
              <th class="custom-thead">Amount</th>
              <?php if (!empty($invoicedata['billtype']) && $invoicedata['billtype'] !== "Non-GST") { ?>
                <th class="custom-thead">GST</th>
                <th class="custom-thead">SGST</th>
                <th class="custom-thead">CGST</th>
              <?php } ?>
              <th class="custom-thead">SubTotal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($invoicedetails as $i => $row): ?>
              <tr class="tr-back">
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td>₹<?= number_format((float)$row['rate'], 2) ?></td>
                <td><?= htmlspecialchars($row['unit']) ?></td>
                <td><?= (float)$row['quantity'] ?></td>
                <td>₹<?= number_format((float)$row['amount'], 2) ?></td>
                <?php if (!empty($invoicedata['billtype']) && $invoicedata['billtype'] !== "Non-GST") { ?>
                  <td><?= (float)$row['gst_per'] ?>%</td>
                  <td><?= (float)$row['sgst'] ?>%</td>
                  <td><?= (float)$row['cgst'] ?>%</td>
                <?php } ?>
                <td>
    ₹
    <?php
    // Format subtotal
    $sub_total = number_format((float)$row['sub_total'], 2);
    // Check if subtotal is 0 or 0.00 and billtype is Non-GST
    if (
        (float)$row['sub_total'] == 0
        && !empty($invoicedata['billtype'])
        && $invoicedata['billtype'] == "Non-GST"
    ) {
        // Show amount instead
        echo number_format((float)$row['amount'], 2);
    } else {
        // Show subtotal
        echo $sub_total;
    }
    ?>
</td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
    </div>
    <div class="row justify-content-end mt-4">
      <div class="table-responsive col-md-8">
<?php
$paymentData = $invoicedata['payment_history'];
if (strpos($paymentData, '|') !== false) {
    // ✅ Pipe-delimited format
    $entries = explode(';', trim($paymentData, ';')); // Split into each payment record
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
    if (!empty($payment_chunks)):
?>
    <table class="table table-bordered table-striped">
        <div class="text-center"><h5>Payment Details</h5></div>
        <thead>
            <tr class="tr-back">
                <th>S.NO</th>
                <th>Payment Type</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payment_chunks as $index => $payment): ?>
            <tr class="tr-back">
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($payment['method']) ?></td>
                <td>₹<?= number_format((float)$payment['amount'], 2) ?></td>
                <td><?= date('d/m/Y', strtotime($payment['date'])) ?></td>
                <td><?= htmlspecialchars($payment['description']) ?></td> <!-- Notes -->
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php
    endif;
} else {
    // Fallback to old comma-separated format
    $payments = explode(',', $paymentData);
    $payment_chunks = array_chunk($payments, 2);
    if (!empty($payment_chunks) && is_numeric($payment_chunks[0][1])):
?>
    <table class="table table-bordered table-striped">
        <div class="text-center"><h5>Payment Details</h5></div>
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
                <td><?= date('d/m/Y', strtotime($invoicedata['invoice_date'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php
    endif;
}
?>
      </div>
      <div class="col-md-4">
        <div class="row">
              <div class="col-md-12">
                <table class="table summary mt-2">
                <tr><td><strong>Total:</strong> ₹<?= number_format($invoicedata['total_amount'], 2) ?></td></tr>
                <tr><td><strong>Grand Total:</strong> ₹<?= number_format($invoicedata['grand_total'], 2) ?></td></tr>
                <tr><td><strong>Paid:</strong> ₹<?= number_format($invoicedata['paid_amount'], 2) ?></td></tr>
                <tr><td><strong>Balance:</strong> ₹<?= number_format($invoicedata['grand_total'] - $invoicedata['paid_amount'], 2) ?></td></tr>
              </table>
            </div>
        </div>
      </div>
    </div>
    <div class="footer text-end"><br><br><br>
      <strong>SURARO ENTERPRISES</strong><br>
      Authorised Signatory
    </div>
  </div>
  <script>
    function downloadPDF() {
      const invoice = document.querySelector('.invoice-box');
      const opt = {
        margin: 0.5,
        filename: '<?= $invoicedata['invoice_number'] ?>.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
      };
      html2pdf().set(opt).from(invoice).save();
    }
  </script>
</body>
</html>
