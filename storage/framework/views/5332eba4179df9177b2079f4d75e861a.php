<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Quick Aid Loan Limited'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0d3b66;
            --primary-deep: #082844;
            --secondary: #f4b942;
            --secondary-soft: #fff3d6;
            --accent: #1ea896;
            --text: #17324d;
            --muted: #64748b;
            --bg: #f5f7fb;
            --white: #ffffff;
            --border: rgba(15, 23, 42, 0.08);
            --shadow: 0 18px 45px rgba(15, 23, 42, 0.12);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(180deg, #0b1220 0%, #111b2b 28%, #f3f6fb 28%, #eef4fb 100%);
            color: var(--text);
            scroll-behavior: smooth;
        }

        a { text-decoration: none; }

        .animate-float {
            animation: floatUp 5s ease-in-out infinite;
        }

        @keyframes floatUp {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        header {
            background: rgba(8, 40, 68, 0.96);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .navbar-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            color: var(--white) !important;
            font-size: 1.4rem;
        }

        .brand-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.09);
            box-shadow: inset 0 0 0 2px rgba(255,255,255,0.08);
            flex-shrink: 0;
            overflow: hidden;
        }

        .brand-mark img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .nav-link {
            color: rgba(255,255,255,0.82) !important;
            font-weight: 500;
            margin-left: 0.25rem;
            transition: color 0.2s ease;
        }

        .nav-link:hover {
            color: var(--secondary) !important;
        }

        .hero {
            position: relative;
            background: linear-gradient(120deg, rgba(7, 16, 28, 0.92), rgba(13, 59, 102, 0.88)),
                        url('https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat;
            color: var(--white);
            padding: 110px 0 90px;
            overflow: hidden;
        }

        .hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top left, rgba(244, 185, 66, 0.18), transparent 32%);
        }

        .hero .container {
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 0.9rem;
            border-radius: 999px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.12);
            font-size: 0.8rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .hero h1 {
            font-size: clamp(2.8rem, 5.5vw, 5rem);
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -0.06em;
            margin: 1.4rem 0 1rem;
            max-width: 680px;
        }

        .hero p {
            font-size: 1.08rem;
            line-height: 1.8;
            color: rgba(255,255,255,0.86);
            max-width: 620px;
        }

        .hero-actions {
            margin-top: 2rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .btn-primary-custom,
        .btn-secondary-custom,
        .btn-light-custom {
            border-radius: 999px;
            padding: 0.9rem 1.6rem;
            font-weight: 700;
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--secondary), #ffcc66);
            color: var(--primary-deep);
            box-shadow: 0 12px 22px rgba(244, 185, 66, 0.35);
        }

        .btn-secondary-custom {
            background: rgba(255,255,255,0.12);
            color: var(--white);
            border: 1px solid rgba(255,255,255,0.28);
        }

        .btn-light-custom {
            background: var(--white);
            color: var(--primary);
            box-shadow: 0 12px 22px rgba(15, 23, 42, 0.12);
        }

        .btn-primary-custom:hover,
        .btn-secondary-custom:hover,
        .btn-light-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 28px rgba(15, 23, 42, 0.18);
        }

        .hero-card {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 1.25rem;
            box-shadow: var(--shadow);
        }

        .mini-stat {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1rem;
            padding: 0.7rem 0.9rem;
            border-radius: 14px;
            background: rgba(255,255,255,0.06);
        }

        .mini-stat strong {
            display: block;
            font-size: 1.2rem;
        }

        .section {
            padding: 90px 0;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            margin-bottom: 0.75rem;
            letter-spacing: -0.05em;
        }

        .section-lead {
            color: var(--muted);
            font-size: 1.06rem;
            max-width: 620px;
            margin: 0 auto 2rem;
            line-height: 1.8;
        }

        .stats-row {
            margin-top: 2rem;
        }

        .stat-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 22px;
            padding: 1.5rem;
            box-shadow: 0 8px 25px rgba(15, 23, 42, 0.04);
            height: 100%;
        }

        .stat-card .number {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -0.05em;
            color: var(--primary);
        }

        .stat-card .label {
            color: var(--muted);
            margin-top: 0.6rem;
            font-weight: 600;
        }

        .feature-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 22px;
            padding: 2rem 1.5rem;
            height: 100%;
            box-shadow: 0 10px 26px rgba(15, 23, 42, 0.04);
        }

        .feature-icon {
            width: 62px;
            height: 62px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--secondary-soft), rgba(30,168,150,0.12));
            color: var(--primary);
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .feature-card h4 {
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 0.7rem;
        }

        .feature-card p {
            color: var(--muted);
            line-height: 1.75;
            margin: 0;
        }

        .process-step {
            background: #fff;
            border-radius: 22px;
            padding: 2rem 1.4rem;
            border: 1px solid var(--border);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.04);
            height: 100%;
        }

        .step-number {
            display: inline-flex;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: var(--primary);
            color: var(--white);
            font-weight: 800;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .process-step h5 {
            font-weight: 700;
            margin-bottom: 0.6rem;
        }

        .process-step p {
            color: var(--muted);
            margin: 0;
            line-height: 1.75;
        }

        .about-panel {
            background: var(--white);
            border-radius: 30px;
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .about-panel img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            min-height: 380px;
        }

        .about-copy {
            padding: 2.5rem;
        }

        .about-copy p {
            color: var(--muted);
            line-height: 1.85;
            margin-bottom: 1.2rem;
        }

        .check-list {
            list-style: none;
            padding: 0;
            margin: 1.4rem 0 0;
        }

        .check-list li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            color: var(--text);
            font-weight: 600;
        }

        .check-list li::before {
            content: "✓";
            display: inline-flex;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: rgba(30,168,150,0.12);
            color: var(--accent);
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 800;
        }

        .cta-band {
            background: linear-gradient(135deg, var(--primary), var(--primary-deep));
            padding: 2.5rem 2rem;
            border-radius: 28px;
            color: var(--white);
            box-shadow: var(--shadow);
        }

        .cta-band h3 {
            font-size: clamp(2rem, 4vw, 2.7rem);
            font-weight: 800;
            letter-spacing: -0.05em;
            margin: 0 0 0.75rem;
        }

        .cta-band p {
            color: rgba(255,255,255,0.82);
            margin: 0;
            max-width: 620px;
            line-height: 1.7;
        }

        footer {
            background: #081d2d;
            color: rgba(255,255,255,0.8);
            padding: 36px 0 26px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.5rem;
            text-align: left;
        }

        .footer-title {
            display: block;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: rgba(255,255,255,0.6);
            margin-bottom: 0.75rem;
            font-weight: 700;
        }

        .footer-grid p,
        .footer-grid a {
            margin: 0;
            color: rgba(255,255,255,0.8);
            line-height: 1.8;
        }

        .footer-grid a:hover {
            color: #f4b942;
        }

        .footer-bottom {
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.08);
            text-align: center;
            color: rgba(255,255,255,0.7);
        }

        @media (max-width: 991.98px) {
            .hero { padding-top: 90px; }
            .hero-card { margin-top: 2rem; }
        }
    </style>
</head>
<body>
    <?php if(($showPublicNavbar ?? false)): ?>
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="container">
                    <a class="navbar-brand" href="<?php echo e(route('landing')); ?>">
                        <span class="brand-mark" aria-label="Quick Aid Loan Limited logo">
                            <img src="<?php echo e(asset('images/quick-aid-logo.jpeg')); ?>" alt="Quick Aid Loan Limited" />
                        </span>
                        <span>Quick Aid Loans Limited</span>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="mainNav">
                        <ul class="navbar-nav ms-auto align-items-lg-center">
                            <li class="nav-item"><a class="nav-link" href="<?php echo e(route('landing')); ?>">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo e(route('loan.apply')); ?>">Apply</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo e(route('loan.calculator')); ?>">Loan Calculator</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo e(route('landing')); ?>#about">About Us</a></li>
                            <li class="nav-item ms-lg-3">
                                <a class="btn btn-primary-custom" href="<?php echo e(route('loan.apply')); ?>">Get Started</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
    <?php endif; ?>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer>
        <div class="container">
            <div class="footer-grid">
                <div>
                    <span class="footer-title">Quick Aid Loan Limited</span>
                    <p>Practical financial support for people and businesses ready to move forward with confidence.</p>
                </div>
                <div>
                    <span class="footer-title">Contact</span>
                    <p>Mobile No: +26076076776</p>
                    <p>Email: Info@mahafu.com</p>
                </div>
                <div>
                    <span class="footer-title">Office</span>
                    <p>Plot 8E Nsunzu Road, Lusaka</p>
                    <p>Zambia</p>
                </div>
            </div>
            <div class="footer-bottom">
                © <?php echo e(date('Y')); ?> Quick Aid Loan Limited. Built for practical financial support.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\laravel-app\resources\views/layouts/app.blade.php ENDPATH**/ ?>