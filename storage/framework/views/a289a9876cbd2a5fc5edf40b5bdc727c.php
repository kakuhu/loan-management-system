

<?php $showPublicNavbar = true; ?>

<?php $__env->startSection('title', 'Quick Aid Loans'); ?>

<?php $__env->startSection('content'); ?>
    <section class="hero">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <span class="hero-badge">Smart financing for growth</span>
                    <h1>Helping your next step feel possible.</h1>
                    <p>Quick Aid Loan Limited gives individuals and businesses access to practical, transparent financing designed around real goals, steady planning, and a smoother path forward.</p>
                    <div class="hero-actions">
                        <a class="btn-primary-custom" href="<?php echo e(route('loan.apply')); ?>">Apply for Loan</a>
                        <a class="btn-secondary-custom" href="<?php echo e(route('loan.calculator')); ?>">Use Loan Calculator</a>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="hero-card">
                        <div class="small text-uppercase fw-bold text-white-50 mb-2">Loan Snapshot</div>
                        <div class="alert alert-warning border-0 mb-3 small text-dark">
                            <strong>Note:</strong> We Currently only offer Monthly Salary-backed loans. All Loans must be Repaid back on your "Payday".
                        </div>
                        <div class="stat-card p-3 border-0 bg-white bg-opacity-10 text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-white-50 small">Estimated monthly</div>
                                    <strong class="fs-4">K2,650</strong>
                                </div>
                                <span class="badge bg-warning text-dark px-3 py-2">12 months</span>
                            </div>
                        </div>

                        <div class="mini-stat">
                            <div>
                                <span class="text-white-50 small">Approved</span>
                                <strong>96%</strong>
                            </div>
                            <div>
                                <span class="text-white-50 small">Average time</span>
                                <strong>48 hrs</strong>
                            </div>
                        </div>

                        <div class="mini-stat">
                            <div>
                                <span class="text-white-50 small">Funds available</span>
                                <strong>K500k+</strong>
                            </div>
                            <div>
                                <span class="text-white-50 small">Support</span>
                                <strong>24/7</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if(session('success')): ?>
        <div class="container mt-4">
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        </div>
    <?php endif; ?>

    <section class="section">
        <div class="container text-center">
            <h2 class="section-title">Built around your goals</h2>
            <p class="section-lead">Whether you need extra working capital, a smoother repayment plan, or a timely boost for a personal milestone, we give you room to move forward with confidence.</p>

            <div class="row g-4 stats-row">
                <div class="col-md-3 col-6">
                    <div class="stat-card animate-float">
                        <div class="number">K1.8M</div>
                        <div class="label">Funds supported</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card animate-float">
                        <div class="number">2,400+</div>
                        <div class="label">Clients helped</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card animate-float">
                        <div class="number">48h</div>
                        <div class="label">Average review time</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card animate-float">
                        <div class="number">4.9/5</div>
                        <div class="label">Customer rating</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-light">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">Why choose Quick Aid Loan Limited?</h2>
                <p class="section-lead">We keep the process simple, practical, and human-focused so you can move with less friction and more clarity.</p>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">⚡</div>
                        <h4>Fast approvals</h4>
                        <p>We speed up application review so your plans do not stall while you are waiting on finances.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">💬</div>
                        <h4>Transparent support</h4>
                        <p>Clear guidance, realistic structures, and honest repayment conversations from start to finish.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">📈</div>
                        <h4>Growth-focused loans</h4>
                        <p>Flexible amounts and repayment terms designed for business growth, personal goals, and everyday momentum.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <div class="feature-card h-100 bg-dark text-white border-0 shadow-lg">
                        <div class="feature-icon bg-white text-dark">🏆</div>
                        <h4 class="text-white">Tailored for ambition</h4>
                        <p class="text-white-50">Our product is designed for people with real plans, whether you are expanding a business, managing a personal goal, or securing a clean financial reset.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="feature-card h-100">
                        <div class="feature-icon">🔒</div>
                        <h4>Reliable and respectful</h4>
                        <p>Every step is handled with clarity, professionalism, and respect for your time, your priorities, and your repayment comfort.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">How it works</h2>
                <p class="section-lead">A clear and simple process built to keep your journey easy to understand.</p>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <div class="process-step">
                        <div class="step-number">1</div>
                        <h5>Apply online</h5>
                        <p>Complete a quick application with your details, loan amount, and preferred repayment needs.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="process-step">
                        <div class="step-number">2</div>
                        <h5>Review and assess</h5>
                        <p>Our team reviews your request and checks the details to match you with the right loan solution.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="process-step">
                        <div class="step-number">3</div>
                        <h5>Get funded</h5>
                        <p>Once approved, you receive support to move your plans forward with confidence and clarity.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-light" id="about">
        <div class="container">
            <div class="about-panel row g-0 align-items-center">
                <div class="col-lg-6 p-0">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=900&q=80" alt="Finance team discussion">
                </div>
                <div class="col-lg-6">
                    <div class="about-copy">
                        <span class="hero-badge text-dark bg-light border-0">About us</span>
                        <h2 class="section-title mt-3">A supportive partner in your financial journey.</h2>
                        <p>Quick Aid Loan Limited was created to provide practical financing support for people and businesses who want to move ahead without unnecessary delays or confusion.</p>
                        <p>We combine clear communication, compassionate service, and structured loan solutions to help clients make better decisions and take the next step with confidence.</p>

                        <ul class="check-list">
                            <li>Flexible loan options</li>
                            <li>Clear repayment planning</li>
                            <li>Helpful support from review to approval</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="cta-band d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
                <div>
                    <h3>Ready to take the next step?</h3>
                    <p>Apply today or estimate your repayment with our loan calculator before you begin.</p>
                </div>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?php echo e(route('loan.apply')); ?>" class="btn-light-custom">Apply now</a>
                    <a href="<?php echo e(route('loan.calculator')); ?>" class="btn-secondary-custom">Calculate loan</a>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel-app\resources\views/site/landing.blade.php ENDPATH**/ ?>