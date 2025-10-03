<?php ob_start(); ?>

<!-- Hero Section -->
<section class="hero bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Create Legal Documents in Minutes</h1>
                <p class="lead mb-4">Professional legal document templates, e-signature, and expert consultation - all in one platform.</p>
                <div class="d-flex gap-3">
                    <a href="/register" class="btn btn-light btn-lg">Get Started Free</a>
                    <a href="/templates" class="btn btn-outline-light btn-lg">Browse Templates</a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-file-contract" style="font-size: 15rem; opacity: 0.2;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Why Choose Legal Docs?</h2>
            <p class="text-muted">Everything you need for legal document management</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-file-alt text-primary fa-3x mb-3"></i>
                        <h5>500+ Templates</h5>
                        <p class="text-muted">Professionally drafted legal templates for all your needs</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-signature text-primary fa-3x mb-3"></i>
                        <h5>E-Signature</h5>
                        <p class="text-muted">Legally binding electronic signatures on all documents</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-user-tie text-primary fa-3x mb-3"></i>
                        <h5>Expert Lawyers</h5>
                        <p class="text-muted">Consult with verified legal professionals</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-shield-alt text-primary fa-3x mb-3"></i>
                        <h5>Secure & Private</h5>
                        <p class="text-muted">Bank-level encryption for all your documents</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-mobile-alt text-primary fa-3x mb-3"></i>
                        <h5>Mobile Friendly</h5>
                        <p class="text-muted">Access your documents anywhere, anytime</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-headset text-primary fa-3x mb-3"></i>
                        <h5>24/7 Support</h5>
                        <p class="text-muted">Round-the-clock customer support</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Templates -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Popular Document Templates</h2>
            <p class="text-muted">Ready-to-use templates created by legal experts</p>
        </div>
        <div class="row g-4">
            <?php foreach ($featuredTemplates ?? [] as $template): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= sanitize($template->name) ?></h5>
                        <p class="card-text text-muted"><?= sanitize($template->description) ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-primary fw-bold"><?= currency($template->price) ?></span>
                            <a href="/templates/<?= $template->id ?>" class="btn btn-sm btn-primary">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="/templates" class="btn btn-primary">View All Templates</a>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">How It Works</h2>
            <p class="text-muted">Create your legal document in 3 simple steps</p>
        </div>
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 60px; height: 60px; font-size: 24px; font-weight: bold;">1</div>
                <h5>Choose Template</h5>
                <p class="text-muted">Select from 500+ professionally drafted templates</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 60px; height: 60px; font-size: 24px; font-weight: bold;">2</div>
                <h5>Customize</h5>
                <p class="text-muted">Fill in your details using our easy editor</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 60px; height: 60px; font-size: 24px; font-weight: bold;">3</div>
                <h5>Download & Sign</h5>
                <p class="text-muted">Download PDF or sign electronically</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-4">Ready to Get Started?</h2>
        <p class="lead mb-4">Join thousands of users who trust Legal Docs for their legal documentation needs</p>
        <a href="/register" class="btn btn-light btn-lg">Create Free Account</a>
    </div>
</section>

<?php 
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>
