<p><strong>Name:</strong> <?= ucwords($name) ?? '-' ?></p>
<h5 class="mt-3">Invoices</h5>
<?php if (!empty($invoices)) : ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Invoice Number</th>
                <th>Company Name</th>
                <th>Total Amount</th>
                <th>Paid Amount</th>
                <th>Balance Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoices as $inv) : ?>
                <tr>
                    <td><?= $inv['invoice_number'] ?></td>
                    <td><?= $inv['companyname'] ?></td>
                    <td><?= number_format($inv['grand_total'], 2) ?></td>
                    <td><?= number_format($inv['paid_amount'], 2) ?></td>
                    <td><?= number_format($inv['balance_amount'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>No invoices found for this customer.</p>
<?php endif; ?>
