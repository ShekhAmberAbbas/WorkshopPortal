<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print QR — {{ $workshop->title }}</title>
    <style>
        body {
            font-family: system-ui, sans-serif;
            text-align: center;
            padding: 60px 20px;
        }
        img { width: 320px; height: 320px; margin: 24px 0; }
        h1 { font-size: 22px; margin-bottom: 4px; }
        p { color: #555; margin: 2px 0; }
        code { font-size: 14px; color: #888; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <h1>{{ $workshop->title }}</h1>
    <p>{{ $workshop->college_name }}</p>
    <p>{{ $workshop->start_date?->format('d M Y') ?? '—' }} &middot; {{ $workshop->duration_label }}</p>

    <img
        src="{{ route('admin.workshops.qr.image', $workshop) }}"
        alt="Registration QR code"
    >

    <p><code>{{ $workshop->workshop_code }}</code></p>
    <p class="no-print"><button onclick="window.print()">Print</button></p>

    <script>window.onload = () => window.print();</script>
</body>
</html>
