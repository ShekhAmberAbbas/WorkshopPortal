<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Certificate Form Unavailable | Quaere Tech Hub</title>
    <meta name="theme-color" content="#005495">
    <meta name="description" content="Feedback cum Certificate Form information from Quaere Tech Hub.">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand: #005495;
            --brand-dark: #00385f;
            --text: #172b3a;
            --muted: #718196;
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            color: var(--text);
            font-family: 'Inter', system-ui, sans-serif;
            background:
                radial-gradient(circle at 8% 12%, rgba(0,84,149,.10), transparent 28%),
                radial-gradient(circle at 92% 88%, rgba(54,174,235,.12), transparent 28%),
                #f5f8fc;
        }

        .page-shell { width: 100%; max-width: 1080px; padding: 30px 22px; margin: auto; }
        .topbar { display: flex; justify-content: space-between; align-items: center; gap: 18px; margin-bottom: 30px; }
        .brand { display: flex; align-items: center; gap: 12px; min-width: 0; }
        .brand-logo { max-width: 190px; max-height: 56px; object-fit: contain; }
        .brand-divider { width: 1px; height: 34px; flex: 0 0 1px; background: #d7e0ea; }
        .brand-caption { color: #718196; font-size: 11px; font-weight: 800; letter-spacing: 1.1px; line-height: 1.5; text-transform: uppercase; }
        .secure-label { display: inline-flex; align-items: center; gap: 8px; padding: 9px 13px; border: 1px solid #dce8f2; border-radius: 50px; background: rgba(255,255,255,.85); color: #47647d; font-size: 12px; font-weight: 600; white-space: nowrap; }
        .secure-label i { color: #16875e; font-size: 15px; }

        .status-card {
            position: relative;
            max-width: 720px;
            margin: 0 auto;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.8);
            border-radius: 25px;
            background: #fff;
            box-shadow: 0 24px 70px rgba(26,54,83,.09), 0 3px 12px rgba(26,54,83,.035);
            animation: cardEntry .65s ease both;
        }
        .card-accent { height: 7px; background: linear-gradient(90deg, #005495, #168fcb, #65c7ed); }
        .card-content { padding: 43px 48px 35px; text-align: center; }
        .status-icon-wrap {
            position: relative;
            display: flex;
            width: 94px;
            height: 94px;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            border-radius: 28px;
            background: linear-gradient(145deg, #edf7ff, #dceefa);
            color: var(--brand);
            box-shadow: 0 10px 24px rgba(0,84,149,.10);
            transform: rotate(-4deg);
        }
        .status-icon-wrap > i { font-size: 43px; transform: rotate(4deg); }
        .status-dot { position: absolute; right: -5px; bottom: -5px; display: flex; width: 25px; height: 25px; align-items: center; justify-content: center; border: 4px solid #fff; border-radius: 50%; background: #e7a23b; color: #fff; font-size: 11px; transform: rotate(4deg); }
        .eyebrow { display: inline-flex; align-items: center; gap: 7px; padding: 7px 12px; margin-bottom: 17px; border: 1px solid #f2e6cf; border-radius: 50px; background: #fff9ed; color: #94631e; font-size: 11px; font-weight: 800; letter-spacing: .8px; text-transform: uppercase; }
        .page-title { margin: 0 0 13px; color: #172b3a; font-size: clamp(25px, 4vw, 34px); font-weight: 800; letter-spacing: -1.1px; line-height: 1.25; }
        .description { max-width: 520px; margin: 0 auto; color: #6c7d8f; font-size: 14px; line-height: 1.9; }

        .workshop-box { margin-top: 27px; padding: 19px; border: 1px solid #e3edf6; border-radius: 16px; background: linear-gradient(135deg, #f8fbff, #f0f7fd); text-align: left; }
        .workshop-label { display: flex; align-items: center; gap: 7px; margin-bottom: 9px; color: #718196; font-size: 11px; font-weight: 800; letter-spacing: .9px; text-transform: uppercase; }
        .workshop-label i { color: var(--brand); font-size: 15px; }
        .workshop-title { margin-bottom: 12px; color: #17364f; font-size: 16px; font-weight: 800; line-height: 1.55; overflow-wrap: anywhere; }
        .workshop-detail { display: flex; align-items: flex-start; gap: 10px; padding-top: 13px; border-top: 1px solid #e1eaf2; color: #526a7f; font-size: 13px; line-height: 1.7; }
        .workshop-detail + .workshop-detail { margin-top: 12px; }
        .workshop-detail > i { margin-top: 2px; color: var(--brand); font-size: 17px; }
        .workshop-detail strong { color: #17364f; }
        .date-emphasis { display: inline-block; margin-top: 4px; color: var(--brand); font-weight: 700; }

        .help-note { display: flex; align-items: flex-start; gap: 11px; max-width: 520px; margin: 23px auto 0; padding: 14px 16px; border-radius: 12px; background: #f8fafc; color: #718196; text-align: left; font-size: 12px; line-height: 1.8; }
        .help-note > i { margin-top: 2px; color: #63809b; font-size: 16px; }
        .help-note strong { color: #3c5368; }
        .card-footer-custom { padding: 17px 20px; border-top: 1px solid #edf1f5; background: #fcfdff; color: #8a98a8; font-size: 11px; text-align: center; }
        .card-footer-custom strong { color: #52677c; }
        .page-footer { padding-top: 26px; color: #8a98a8; font-size: 11px; line-height: 1.8; text-align: center; }
        .page-footer span { color: var(--brand); font-weight: 700; }

        @keyframes cardEntry { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }

        @media (max-width: 576px) {
            .page-shell { padding: 20px 14px; }
            .topbar { align-items: flex-start; flex-direction: column; margin-bottom: 22px; }
            .brand-logo { max-width: 160px; max-height: 48px; }
            .secure-label { font-size: 11px; }
            .status-card { border-radius: 19px; }
            .card-content { padding: 32px 20px 25px; }
            .status-icon-wrap { width: 80px; height: 80px; margin-bottom: 21px; border-radius: 24px; }
            .status-icon-wrap > i { font-size: 36px; }
            .page-title { letter-spacing: -.6px; }
            .description { font-size: 13px; }
            .workshop-box { padding: 15px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; scroll-behavior: auto !important; }
        }
    </style>
</head>
<body>
    @php
        $registrationOpensLater = $workshop->status === 'active'
            && $workshop->isBeforeStartDate();

        $workshopStartDate = $workshop->start_date;
        $workshopEndDate = $workshop->end_date;
    @endphp

    <main class="page-shell">
        <header class="topbar">
            <div class="brand">
                <img
                    src="{{ asset('images/qwaretech-hub-logo.png') }}"
                    alt="Quaere Tech Hub"
                    class="brand-logo"
                    onerror="this.style.display='none'"
                >
                <div class="brand-divider"></div>
                <div class="brand-caption">Feedback Cum<br>Certificate Form</div>
            </div>

            <div class="secure-label">
                <i class="bi bi-shield-check"></i>
                Official Certificate Portal
            </div>
        </header>

        <section class="status-card" aria-labelledby="page-title">
            <div class="card-accent"></div>

            <div class="card-content">
                <div class="status-icon-wrap" aria-hidden="true">
                    <i class="bi bi-award"></i>
                    <span class="status-dot"><i class="bi bi-clock-fill"></i></span>
                </div>

                <div class="eyebrow">
                    <i class="bi bi-patch-check-fill"></i>
                    Certificate Form Status
                </div>

                @if ($registrationOpensLater)
                    <h1 class="page-title" id="page-title">Your Certificate Form Opens Soon!</h1>
                    <p class="description">
                        The Feedback cum Certificate Form for this workshop is not available yet.
                        You will be able to complete the feedback process and access the certificate
                        form starting from the workshop date shown below.
                    </p>
                @elseif ($workshop->status === 'completed')
                    <h1 class="page-title" id="page-title">Certificate Form Is Closed</h1>
                    <p class="description">
                        The Feedback cum Certificate Form for this workshop is currently closed.
                        Please contact the workshop coordinator for assistance with your feedback
                        or certificate.
                    </p>
                @elseif ($workshop->status !== 'active')
                    <h1 class="page-title" id="page-title">Certificate Form Unavailable</h1>
                    <p class="description">
                        The Feedback cum Certificate Form is currently unavailable because this
                        workshop is not active. Please contact the workshop coordinator for more information.
                    </p>
                @else
                    <h1 class="page-title" id="page-title">Certificate Form Temporarily Unavailable</h1>
                    <p class="description">
                        We cannot open the Feedback cum Certificate Form right now.
                        @if (!empty($availabilityMessage))
                            {{ $availabilityMessage }}
                        @else
                            Please try again later or contact the workshop coordinator.
                        @endif
                    </p>
                @endif

                <div class="workshop-box">
                    <div class="workshop-label">
                        <i class="bi bi-mortarboard-fill"></i>
                        Workshop Information
                    </div>

                    <div class="workshop-title">{{ $workshop->title }}</div>

                    <div class="workshop-detail">
                        <i class="bi bi-calendar3"></i>
                        <div>
                            <strong>Workshop Date</strong><br>
                            @if ($workshopStartDate)
                                {{ $workshopStartDate->format('d M Y') }}
                                @if ($workshopEndDate && $workshopStartDate->toDateString() !== $workshopEndDate->toDateString())
                                    – {{ $workshopEndDate->format('d M Y') }}
                                @endif
                            @else
                                To be announced
                            @endif

                            @if ($registrationOpensLater)
                                <div class="date-emphasis">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    Certificate form opens on {{ $workshopStartDate->format('d M Y') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($workshop->college_name)
                        <div class="workshop-detail">
                            <i class="bi bi-building"></i>
                            <div>
                                <strong>Organized At</strong><br>
                                {{ $workshop->college_name }}
                            </div>
                        </div>
                    @endif
                </div>

                <div class="help-note">
                    <i class="bi bi-headset"></i>
                    <div>
                        <strong>Need help with your certificate?</strong><br>
                        For feedback form access, certificate generation, or certificate download
                        queries, please contact your workshop coordinator.
                    </div>
                </div>
            </div>

            <div class="card-footer-custom">
                Powered by <strong>Quaere Tech Hub</strong>
                <span class="mx-1">&middot;</span>
                Learn. Build. Innovate.
            </div>
        </section>

        <footer class="page-footer">
            &copy; {{ date('Y') }} <span>Quaere Etechnologies Pvt. Ltd.</span>
            All rights reserved.
        </footer>
    </main>
</body>
</html>
