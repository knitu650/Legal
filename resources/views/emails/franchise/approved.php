<h2>Congratulations! Your Franchise Application is Approved</h2>

<p>Dear <?= $applicantName ?>,</p>

<p>We are delighted to inform you that your franchise application has been approved! Welcome to the Legal Document Management System family!</p>

<div class="highlight">
    <strong>Franchise Details:</strong><br>
    Franchise ID: <?= $franchiseId ?><br>
    Location: <?= $location ?><br>
    Territory: <?= $territory ?><br>
    Approved On: <?= date('F d, Y', strtotime($approvedDate)) ?><br>
    Start Date: <?= date('F d, Y', strtotime($startDate)) ?>
</div>

<p><strong>What's Next?</strong></p>
<ol>
    <li>Complete the onboarding process</li>
    <li>Sign the franchise agreement</li>
    <li>Complete initial training program</li>
    <li>Set up your franchise location</li>
    <li>Receive marketing materials and system access</li>
</ol>

<p style="text-align: center;">
    <a href="<?= config('app.url') ?>/franchise/onboarding" class="btn">Start Onboarding</a>
</p>

<p><strong>Your Benefits:</strong></p>
<ul>
    <li>Exclusive territory rights</li>
    <li>Complete training and support</li>
    <li>Marketing and promotional materials</li>
    <li>Ongoing business support</li>
    <li>Access to all system features</li>
</ul>

<p>Our franchise support team will contact you shortly to schedule your onboarding call.</p>

<p>We're excited to have you on board!</p>

<p>
Best regards,<br>
Franchise Team<br>
Legal Document Management System
</p>
