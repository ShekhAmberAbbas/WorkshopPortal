<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Quaere Tech Hub</title>
    <link rel="icon" href="{{ asset('images/qwaretech-hub-favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Karla:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root { --brand:#005495; --brand-dark:#003b68; --sidebar-width:250px; --font-display:'Fraunces',serif; --font-body:'Karla',system-ui,sans-serif; --font-mono:'JetBrains Mono',monospace; }
        body { background:#f6f9fc; font-family:var(--font-body); color:#243447; }
        h1,h2,h3,.brand-wordmark { font-family:var(--font-display); }
        code { font-family:var(--font-mono); }

        /* Desktop: static sidebar. offcanvas-md makes Bootstrap switch this
           same element to a slide-in panel below the md breakpoint — no
           separate mobile markup needed. */
        .sidebar {
            flex: 0 0 var(--sidebar-width);
            width: var(--sidebar-width) !important;
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            flex-shrink: 0;
            align-self: stretch;
            background: #0d1724;
        }
        @media (min-width: 768px) {
            .sidebar { min-height:100vh; height:100vh; position:sticky; top:0; }
            .sidebar .offcanvas-body { min-width:0; }
            .sidebar .nav-link { overflow-wrap:anywhere; }
        }

        .sidebar-brand { background:#fff; border-radius:.8rem; padding:.55rem .7rem; }
        .sidebar-brand img { display:block; width:100%; max-height:58px; object-fit:contain; }
        .sidebar .nav-link { position:relative; color:#a9b5c3; border-radius:.6rem; margin-bottom:.25rem; padding:.7rem .8rem; }
        .sidebar .nav-link.active,.sidebar .nav-link:hover { color:#fff; background:rgba(255,255,255,.07); }
        .sidebar .nav-link.active::before { content:""; position:absolute; left:-.8rem; top:20%; width:3px; height:60%; border-radius:0 4px 4px 0; background:#2da4e8; }
        .sidebar .nav-section { color:#667789; font-size:.68rem; text-transform:uppercase; letter-spacing:.08em; font-weight:700; margin:.75rem .8rem .4rem; }

        .stat-card { position:relative; border:none; border-radius:1rem; box-shadow:0 2px 8px rgba(16,42,67,.06); overflow:hidden; }
        .stat-card::before { content:""; position:absolute; top:0; left:0; right:0; height:3px; background:linear-gradient(90deg,var(--brand),#5fb6e7); }
        .stat-card .value { font-family:var(--font-display); font-size:1.85rem; font-weight:600; }
        .empty-state-icon { width:56px;height:56px;border-radius:50%;background:#eef6fb;color:var(--brand);display:flex;align-items:center;justify-content:center;font-size:24px;margin:0 auto;position:relative; }
        .empty-state-icon::after { content:"";position:absolute;inset:-6px;border-radius:50%;border:1px dashed #b8d8ea; }
        main { min-width:0; }

        @media (max-width: 767.98px) {
            .sidebar {
                flex: 0 0 min(280px, 85vw);
                width: min(280px, 85vw) !important;
                min-width: min(280px, 85vw);
                max-width: min(280px, 85vw);
                height: 100vh;
            }
            .mobile-topbar { display:flex; }
        }
        @media (min-width: 768px) {
            .mobile-topbar { display:none; }
        }
    </style>
    @stack('styles')
</head>
<body>

<nav class="mobile-topbar navbar bg-white border-bottom px-3 py-2 sticky-top align-items-center">
    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-label="Open menu">
        <i class="bi bi-list fs-4"></i>
    </button>
    <a href="{{ route('admin.dashboard') }}" class="ms-2">
        <img src="{{ asset('images/qwaretech-hub-logo.png') }}" alt="Quaere Tech Hub" style="height:32px;">
    </a>
</nav>

<div class="d-flex flex-column flex-md-row min-vh-100">
    <aside class="sidebar bg-dark h-100 offcanvas-md offcanvas-start d-flex flex-column" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
        <div class="offcanvas-header d-md-none">
            <span class="offcanvas-title text-white h6 mb-0" id="sidebarLabel">Menu</span>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#sidebar" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body d-flex flex-column p-3">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand mb-4 text-decoration-none d-none d-md-block">
                <img src="{{ asset('images/qwaretech-hub-logo.png') }}" alt="Quaere Tech Hub">
            </a>

            <div class="nav-section">Workspace</div>
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-grid me-2"></i> Dashboard</a>
                <a class="nav-link {{ request()->routeIs('admin.workshops.*') ? 'active' : '' }}" href="{{ route('admin.workshops.index') }}"><i class="bi bi-mortarboard me-2"></i> Workshops</a>
                <a class="nav-link {{ request()->routeIs('admin.participants.*') ? 'active' : '' }}" href="{{ route('admin.participants.index') }}"><i class="bi bi-patch-check me-2"></i> All Participants</a>
                <a class="nav-link {{ request()->routeIs('admin.academic.*') ? 'active' : '' }}" href="{{ route('admin.academic.index') }}"><i class="bi bi-diagram-3 me-2"></i> Academic Options</a>
                <a class="nav-link {{ request()->routeIs('admin.certificate-templates.*') ? 'active' : '' }}" href="{{ route('admin.certificate-templates.index') }}"><i class="bi bi-patch-check me-2"></i> Certificate Templates</a>
            </nav>

            <div class="mt-auto pt-4">
                <div class="small text-white-50 mb-2 px-2">Quaere Tech Hub</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light w-100"><i class="bi bi-box-arrow-left me-1"></i> Logout</button>
                </form>
            </div>
        </div>
    </aside>

    <main class="flex-grow-1 h-100  p-3 p-lg-4">
        @yield('content')
    </main>
</div>

@if (session('status'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="statusToast" class="toast align-items-center text-bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex"><div class="toast-body">{{ session('status') }}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>
        </div>
    </div>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const statusToastEl = document.getElementById('statusToast');
if (statusToastEl) new bootstrap.Toast(statusToastEl, { delay: 4000 }).show();

document.addEventListener('submit', (e) => {
    const form = e.target;

    // File-download forms (Excel/PDF exports) do not navigate away from the
    // current page, so their buttons must not be left in a permanent loading state.
    if (!(form instanceof HTMLFormElement)) return;
    if (form.dataset.clientValidation === 'true') return;
    if (form.dataset.noLoading === 'true') return;
    if (e.defaultPrevented) return;

    const btn = form.querySelector('button[type="submit"]');
    if (btn && !btn.disabled) {
        btn.dataset.originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Please wait…';
        btn.disabled = true;
    }
});

// Auto-close the mobile sidebar after navigating, so it doesn't stay open
// when the next page loads.
document.querySelectorAll('#sidebar .nav-link').forEach(link => {
    link.addEventListener('click', () => {
        const offcanvasEl = document.getElementById('sidebar');
        const instance = bootstrap.Offcanvas.getInstance(offcanvasEl);
        instance?.hide();
    });
});
</script>
@stack('scripts')
</body>
</html>
