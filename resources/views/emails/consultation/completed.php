<h2>Consultation Completed</h2>

<p>Dear <?= $clientName ?>,</p>

<p>Thank you for using our legal consultation service. Your consultation with <?= $lawyerName ?> has been completed.</p>

<div class="highlight">
    <strong>Consultation Details:</strong><br>
    Lawyer: <?= $lawyerName ?><br>
    Date: <?= date('F d, Y', strtotime($consultationDate)) ?><br>
    Duration: <?= $duration ?> minutes<br>
    Booking ID: <?= $bookingId ?>
</div>

<p>We hope you found the consultation helpful. Please take a moment to rate your experience and provide feedback.</p>

<p style="text-align: center;">
    <a href="<?= config('app.url') ?>/user/consultations/<?= $bookingId ?>/review" class="btn">Leave a Review</a>
</p>

<?php if (!empty($followUpNotes)): ?>
<p><strong>Follow-up Notes:</strong></p>
<p style="padding: 15px; background-color: #f8f9fa; border-left: 4px solid #3498db;">
    <?= nl2br(htmlspecialchars($followUpNotes)) ?>
</p>
<?php endif; ?>

<p>If you need additional assistance, feel free to book another consultation or contact our support team.</p>

<p>
Best regards,<br>
The Legal Docs Team
</p>
