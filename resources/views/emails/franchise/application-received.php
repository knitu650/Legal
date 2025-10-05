<h2>Franchise Application Received</h2>

<p>Dear <?= $applicantName ?>,</p>

<p>Thank you for your interest in becoming a franchise partner with Legal Document Management System!</p>

<p>We have successfully received your franchise application and our team is currently reviewing it.</p>

<div class="highlight">
    <strong>Application Details:</strong><br>
    Application ID: <?= $applicationId ?><br>
    Submitted On: <?= date('F d, Y', strtotime($submittedDate)) ?><br>
    Preferred Location: <?= $preferredLocation ?><br>
    Status: Under Review
</div>

<p><strong>Next Steps:</strong></p>
<ol>
    <li>Our team will review your application (typically 3-5 business days)</li>
    <li>We may contact you for additional information or clarification</li>
    <li>You will be notified of our decision via email</li>
    <li>If approved, we will schedule an onboarding call</li>
</ol>

<p>You can track your application status anytime from your dashboard.</p>

<p style="text-align: center;">
    <a href="<?= config('app.url') ?>/franchise/application/<?= $applicationId ?>" class="btn">Track Application</a>
</p>

<p>If you have any questions, please contact our franchise team at franchise@legaldocs.com or call +91 9876543210.</p>

<p>
Best regards,<br>
Franchise Team<br>
Legal Document Management System
</p>
