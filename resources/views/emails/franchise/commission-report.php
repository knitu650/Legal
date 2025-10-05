<h2>Monthly Commission Report</h2>

<p>Dear <?= $franchiseName ?>,</p>

<p>Here is your commission report for <?= $month ?> <?= $year ?>.</p>

<div class="highlight">
    <strong>Commission Summary:</strong><br>
    Total Revenue Generated: ₹<?= number_format($totalRevenue, 2) ?><br>
    Commission Rate: <?= $commissionRate ?>%<br>
    Total Commission: ₹<?= number_format($totalCommission, 2) ?><br>
    Period: <?= $month ?> <?= $year ?>
</div>

<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
    <thead>
        <tr style="background-color: #f8f9fa;">
            <th style="padding: 10px; text-align: left; border: 1px solid #dee2e6;">Transaction Date</th>
            <th style="padding: 10px; text-align: left; border: 1px solid #dee2e6;">Type</th>
            <th style="padding: 10px; text-align: right; border: 1px solid #dee2e6;">Amount</th>
            <th style="padding: 10px; text-align: right; border: 1px solid #dee2e6;">Commission</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($transactions as $transaction): ?>
        <tr>
            <td style="padding: 10px; border: 1px solid #dee2e6;"><?= date('M d, Y', strtotime($transaction['date'])) ?></td>
            <td style="padding: 10px; border: 1px solid #dee2e6;"><?= $transaction['type'] ?></td>
            <td style="padding: 10px; text-align: right; border: 1px solid #dee2e6;">₹<?= number_format($transaction['amount'], 2) ?></td>
            <td style="padding: 10px; text-align: right; border: 1px solid #dee2e6;">₹<?= number_format($transaction['commission'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><strong>Payout Information:</strong></p>
<p>Your commission will be processed on <?= date('F d, Y', strtotime($payoutDate)) ?> and credited to your registered bank account.</p>

<p style="text-align: center;">
    <a href="<?= config('app.url') ?>/franchise/revenue/commissions" class="btn">View Detailed Report</a>
</p>

<p>
Best regards,<br>
Finance Team<br>
Legal Document Management System
</p>
