<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NutriShare @hasSection('title')| @yield('title')@endif</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🌾</text></svg>">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Apply saved theme and demo mode immediately before render to prevent flicker
        (function() {
            const saved = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', saved);
            const savedDemo = localStorage.getItem('demo_mode') || 'on';
            document.documentElement.setAttribute('data-demo-mode', savedDemo);
        })();
    </script>

    <style>
        :root {
            --apple-bg: #000000;
            --apple-surface: #111111;
            --apple-border: #333333;
            --apple-text: #f5f5f7;
            --apple-text-muted: #a1a1a6;
            --apple-accent: #2997ff;
            --apple-accent-hover: #0071e3;
            --apple-danger: #ff3b30;
            --apple-success: #34c759;
            --apple-card-bg: #111111;
            --apple-input-bg: #1a1a1a;
        }

        [data-theme="light"] {
            --apple-bg: #f5f5f7;
            --apple-surface: #ffffff;
            --apple-border: #e5e5ea;
            --apple-text: #1d1d1f;
            --apple-text-muted: #515154;
            --apple-accent: #0066cc;
            --apple-accent-hover: #004499;
            --apple-danger: #ff3b30;
            --apple-success: #28cd41;
            --apple-card-bg: #ffffff;
            --apple-input-bg: #f2f2f7;
        }

        /* High Contrast Text & Component Overrides */
        .text-muted {
            color: var(--apple-text-muted) !important;
        }
        [data-theme="light"] .text-light,
        [data-theme="light"] .text-white {
            color: #1d1d1f !important;
        }
        [data-theme="light"] .text-muted {
            color: #515154 !important;
        }
        [data-theme="light"] .table-dark {
            --bs-table-bg: #ffffff !important;
            --bs-table-color: #1d1d1f !important;
            --bs-table-hover-bg: #f2f2f7 !important;
            --bs-table-hover-color: #1d1d1f !important;
            --bs-table-border-color: #e5e5ea !important;
            color: #1d1d1f !important;
            border-color: #e5e5ea !important;
        }
        [data-theme="light"] .table-dark th {
            color: #6e6e73 !important;
            border-bottom-color: #e5e5ea !important;
        }
        [data-theme="light"] .btn-outline-light {
            color: #1d1d1f !important;
            border-color: #c7c7cc !important;
            background-color: transparent !important;
        }
        [data-theme="light"] .btn-outline-light:hover {
            background-color: #e5e5ea !important;
            color: #000000 !important;
        }
        [data-theme="light"] .btn-secondary {
            background-color: #e5e5ea !important;
            color: #1d1d1f !important;
            border-color: #c7c7cc !important;
        }
        [data-theme="light"] .btn-secondary:hover {
            background-color: #d1d1d6 !important;
            color: #000000 !important;
        }
        [data-theme="light"] .bg-dark,
        [data-theme="light"] .bg-secondary {
            background-color: #f2f2f7 !important;
            color: #1d1d1f !important;
        }
        [data-theme="light"] .border-dark,
        [data-theme="light"] .border-secondary {
            border-color: #e5e5ea !important;
        }
        [data-theme="light"] .dropdown-menu {
            background-color: #ffffff !important;
            border-color: #e5e5ea !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
        }
        [data-theme="light"] .dropdown-item {
            color: #1d1d1f !important;
        }
        [data-theme="light"] .dropdown-item:hover {
            background-color: #f2f2f7 !important;
            color: #0066cc !important;
        }
        [data-theme="light"] .list-group-item {
            background-color: #ffffff !important;
            color: #1d1d1f !important;
            border-color: #e5e5ea !important;
        }
        [data-theme="light"] .modal-content {
            background-color: #ffffff !important;
            color: #1d1d1f !important;
            border-color: #e5e5ea !important;
        }
        [data-theme="light"] .datatable-table > tbody > tr:hover {
            background-color: #f2f2f7 !important;
        }
        [data-theme="light"] .datatable-input,
        [data-theme="light"] .datatable-selector {
            background-color: #f2f2f7 !important;
            color: #1d1d1f !important;
            border-color: #e5e5ea !important;
        }
        [data-theme="light"] .btn-check:checked + .btn-outline-light {
            background: rgba(0, 102, 204, 0.1) !important;
            border-color: #0066cc !important;
            color: #0066cc !important;
        }

        /* Global SweetAlert2 Theme Styling */
        .swal2-popup {
            background-color: var(--apple-surface) !important;
            color: var(--apple-text) !important;
            border: 1px solid var(--apple-border) !important;
            border-radius: 20px !important;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
        }
        .swal2-toast {
            border-radius: 14px !important;
            padding: 12px 18px !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.35) !important;
            border: 1px solid var(--apple-border) !important;
        }
        .swal2-title {
            color: var(--apple-text) !important;
            font-family: 'Inter', -apple-system, sans-serif !important;
            font-weight: 600 !important;
        }
        .swal2-html-container, .swal2-content {
            color: var(--apple-text-muted) !important;
            font-family: 'Inter', -apple-system, sans-serif !important;
        }
        .swal2-confirm {
            border-radius: 980px !important;
            padding: 10px 26px !important;
            font-weight: 500 !important;
            font-family: 'Inter', -apple-system, sans-serif !important;
            box-shadow: none !important;
        }
        .swal2-cancel {
            border-radius: 980px !important;
            padding: 10px 26px !important;
            font-weight: 500 !important;
            font-family: 'Inter', -apple-system, sans-serif !important;
            box-shadow: none !important;
        }
        .swal2-icon {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            margin: 1.75rem auto 1rem auto !important;
            box-sizing: content-box !important;
        }
        .swal2-icon .swal2-icon-content {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            line-height: 1 !important;
            width: 100% !important;
            height: 100% !important;
            font-size: 3.75em !important;
        }
        .swal2-icon.swal2-warning .swal2-icon-content,
        .swal2-icon.swal2-question .swal2-icon-content,
        .swal2-icon.swal2-info .swal2-icon-content {
            margin-top: -0.04em !important;
        }

        [data-theme="light"] .swal2-popup {
            background-color: #ffffff !important;
            color: #1d1d1f !important;
            border: 1px solid #e5e5ea !important;
            box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important;
        }
        [data-theme="light"] .swal2-toast {
            background-color: #ffffff !important;
            border: 1px solid #e5e5ea !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
        }
        [data-theme="light"] .swal2-title {
            color: #1d1d1f !important;
        }
        [data-theme="light"] .swal2-html-container {
            color: #6e6e73 !important;
        }
        
        body { 
            background-color: var(--apple-bg);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: var(--apple-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        /* Apple-style floating blurry navbar */
        .navbar { 
            background: var(--apple-surface) !important;
            backdrop-filter: saturate(180%) blur(20px);
            -webkit-backdrop-filter: saturate(180%) blur(20px);
            border-bottom: 1px solid var(--apple-border);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .navbar-brand { 
            font-weight: 600; 
            font-size: 1.25rem; 
            color: var(--apple-text) !important; 
            letter-spacing: -0.01em;
        }
        .nav-link { 
            font-weight: 400; 
            font-size: 0.85rem;
            color: var(--apple-text-muted) !important; 
            transition: color 0.3s ease; 
        }
        .nav-link:hover { color: var(--apple-text) !important; }

        /* Card & Inputs Light/Dark Overrides */
        .card {
            background-color: var(--apple-card-bg) !important;
            border-color: var(--apple-border) !important;
            color: var(--apple-text) !important;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .card-header {
            background-color: var(--apple-card-bg) !important;
            border-bottom-color: var(--apple-border) !important;
            color: var(--apple-text) !important;
        }
        .form-control, .form-select {
            background-color: var(--apple-input-bg) !important;
            color: var(--apple-text) !important;
            border-color: var(--apple-border) !important;
        }
        .form-control:focus, .form-select:focus {
            background-color: var(--apple-input-bg) !important;
            color: var(--apple-text) !important;
            border-color: var(--apple-accent) !important;
            box-shadow: 0 0 0 0.25rem rgba(41, 151, 255, 0.25) !important;
        }

        /* Global Table Contrast & Background Overrides */
        .table {
            --bs-table-bg: transparent !important;
            --bs-table-color: var(--apple-text) !important;
            background-color: var(--apple-surface) !important;
            color: var(--apple-text) !important;
        }
        .table th, .table td {
            color: var(--apple-text) !important;
            border-color: var(--apple-border) !important;
        }
        .table-hover > tbody > tr:hover > * {
            --bs-table-accent-bg: var(--apple-input-bg) !important;
            color: var(--apple-text) !important;
        }
        [data-theme="light"] .table {
            background-color: #ffffff !important;
            color: #1d1d1f !important;
        }
        [data-theme="light"] .table th,
        [data-theme="light"] .table td {
            color: #1d1d1f !important;
            border-color: #e5e5ea !important;
        }

        /* ==========================================================================
           UNIVERSAL ICON ALIGNMENT, CENTERING & SPACING (System-wide)
           ========================================================================== */
        
        /* 1. Base Bootstrap Icon Alignment */
        i.bi, .bi {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            vertical-align: -0.125em;
            line-height: 1 !important;
            flex-shrink: 0;
        }

        /* 2. Neutralize baseline shift in flex/inline-flex containers for perfect vertical centering */
        .d-flex i.bi,
        .d-inline-flex i.bi,
        .btn i, .btn .bi,
        .badge i, .badge .bi,
        .card-header i, .card-header .bi,
        .alert i, .alert .bi,
        .nav-link i, .nav-link .bi,
        .dropdown-item i, .dropdown-item .bi,
        .input-group-text i, .input-group-text .bi,
        .modal-title i, .modal-title .bi {
            vertical-align: 0 !important;
        }

        /* 3. Global Icon Spacing: Guarantee icons NEVER stick to adjacent text or icons */
        /* Space after icon when followed by text or elements */
        .btn > i:first-child:not(:only-child),
        .btn > .bi:first-child:not(:only-child),
        .badge > i:first-child:not(:only-child),
        .badge > .bi:first-child:not(:only-child),
        .nav-link > i:first-child,
        .nav-link > .bi:first-child,
        .card-header i,
        .card-header .bi,
        .dropdown-item i,
        .dropdown-item .bi,
        .alert i,
        .alert .bi,
        .modal-title i,
        .modal-title .bi,
        .form-label i,
        .form-label .bi,
        .table td > i:first-child:not(:only-child),
        .table td > .bi:first-child:not(:only-child),
        .table th > i:first-child:not(:only-child),
        .table th > .bi:first-child:not(:only-child) {
            margin-right: 0.5rem !important;
        }

        /* Space before icon when trailing text (e.g. Star badges next to user names) */
        a > i.bi:last-child:not(:only-child),
        a > .bi:last-child:not(:only-child),
        span > i.bi:last-child:not(:only-child),
        span > .bi:last-child:not(:only-child),
        .btn > i:last-child:not(:only-child),
        .btn > .bi:last-child:not(:only-child) {
            margin-left: 0.4rem !important;
        }

        /* Spacing between adjacent icons (e.g. consecutive stars, dual status icons) */
        i.bi + i.bi,
        .bi + .bi,
        i + i,
        svg + svg {
            margin-left: 0.3rem !important;
        }

        /* Spacing between adjacent buttons & forms in toolbars or action groups */
        .btn + .btn,
        .btn + form,
        form + .btn,
        form + form,
        .btn-icon + .btn-icon,
        .btn-icon-sm + .btn-icon-sm,
        .btn-group > .btn + .btn,
        .btn-group > form {
            margin-left: 0.6rem !important;
        }

        .badge {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.4rem !important;
            vertical-align: middle !important;
        }

        /* Action Tag High-Contrast Styling */
        .action-tag {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 6px;
            background-color: rgba(41, 151, 255, 0.18) !important;
            color: #64d2ff !important;
            border: 1px solid rgba(41, 151, 255, 0.35) !important;
            white-space: nowrap;
        }
        .action-tag i, .action-tag .bi {
            margin-right: 0.35rem !important;
        }
        [data-theme="light"] .action-tag {
            background-color: rgba(0, 102, 204, 0.12) !important;
            color: #0066cc !important;
            border: 1px solid rgba(0, 102, 204, 0.3) !important;
        }

        /* Nav Tabs High Contrast Styling */
        .nav-tabs {
            border-bottom: 2px solid var(--apple-border) !important;
        }
        .nav-tabs .nav-link {
            color: var(--apple-text-muted) !important;
            border: 1px solid transparent !important;
            border-radius: 8px 8px 0 0 !important;
            font-weight: 500 !important;
            padding: 8px 16px !important;
            transition: all 0.2s ease !important;
        }
        .nav-tabs .nav-link:hover {
            color: var(--apple-text) !important;
            background-color: rgba(125, 125, 125, 0.15) !important;
        }
        .nav-tabs .nav-link.active {
            color: var(--apple-accent) !important;
            background-color: var(--apple-input-bg) !important;
            border-color: var(--apple-border) var(--apple-border) transparent !important;
            border-bottom: 2px solid var(--apple-accent) !important;
            font-weight: 600 !important;
        }
        
        /* 4. Global Button Overrides for System-wide Uniformity */
        .btn {
            border-radius: 980px !important;
            font-weight: 500 !important;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            padding: 8px 20px !important;
            line-height: 1.4 !important;
            white-space: nowrap !important;
            transition: all 0.2s ease !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.55rem !important;
            font-size: 0.875rem !important;
            box-shadow: none !important;
        }
        .btn-sm {
            padding: 6px 16px !important;
            font-size: 0.8rem !important;
            border-radius: 980px !important;
            white-space: nowrap !important;
            gap: 0.45rem !important;
        }
        .btn-lg {
            padding: 12px 28px !important;
            font-size: 1rem !important;
            border-radius: 980px !important;
            white-space: nowrap !important;
            gap: 0.65rem !important;
        }
        .btn:hover {
            transform: translateY(-1px) !important;
            opacity: 0.92 !important;
        }
        .btn:active {
            transform: translateY(0) !important;
        }

        /* 5. Icon-Only Buttons Centering & Square/Circular Sizing (Zero margin so icon is dead center) */
        .btn-icon,
        .btn-icon-sm,
        .btn:has(> i:only-child:not(:has(~ *))),
        .btn:has(> .bi:only-child:not(:has(~ *))) {
            padding: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            flex-shrink: 0 !important;
            border-radius: 50% !important;
        }
        .btn-icon i,
        .btn-icon .bi,
        .btn-icon-sm i,
        .btn-icon-sm .bi,
        .btn:has(> i:only-child:not(:has(~ *))) i,
        .btn:has(> .bi:only-child:not(:has(~ *))) .bi {
            margin: 0 !important;
            padding: 0 !important;
        }
        .btn-icon.btn-sm,
        .btn-icon-sm,
        .btn-sm:has(> i:only-child:not(:has(~ *))),
        .btn-sm:has(> .bi:only-child:not(:has(~ *))) {
            width: 32px !important;
            height: 32px !important;
            min-width: 32px !important;
            min-height: 32px !important;
        }
        .btn:not(.btn-sm):not(.btn-lg):has(> i:only-child:not(:has(~ *))),
        .btn:not(.btn-sm):not(.btn-lg):has(> .bi:only-child:not(:has(~ *))) {
            width: 38px !important;
            height: 38px !important;
            min-width: 38px !important;
            min-height: 38px !important;
        }
        .btn-icon.btn-lg,
        .btn-lg:has(> i:only-child:not(:has(~ *))),
        .btn-lg:has(> .bi:only-child:not(:has(~ *))) {
            width: 44px !important;
            height: 44px !important;
            min-width: 44px !important;
            min-height: 44px !important;
        }

        /* Input group exception for buttons with icons */
        .input-group .btn {
            border-radius: 0 980px 980px 0 !important;
            padding-left: 1.25rem !important;
            padding-right: 1.25rem !important;
            width: auto !important;
            min-width: 44px !important;
            height: auto !important;
        }

        /* 6. Card Headers, Alerts, Dropdowns & Form Labels Icon Alignment */
        .card-header {
            display: flex !important;
            align-items: center !important;
        }
        .dropdown-item {
            display: flex !important;
            align-items: center !important;
        }
        .dropdown-item i, .dropdown-item .bi {
            width: 1.25rem !important;
            text-align: center !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
        }
        .table td i, .table th i,
        .table td .bi, .table th .bi {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            vertical-align: -0.125em !important;
        }

        /* Custom Button Colors */
        .btn-ns-primary {
            background-color: var(--apple-text);
            color: var(--apple-bg);
            border: none; 
        }
        .btn-ns-primary:hover { 
            background-color: #d1d1d6; 
            color: var(--apple-bg);
        }

        .btn-ns-accent {
            background-color: var(--apple-accent);
            color: white;
            border: none; 
        }
        .btn-ns-accent:hover { 
            background-color: var(--apple-accent-hover); 
            color: white;
        }
        
        /* Forms & Cards */
        .card { 
            background: var(--apple-surface);
            border: 1px solid var(--apple-border); 
            border-radius: 18px !important; 
            color: var(--apple-text);
            overflow: hidden !important;
        }
        .card-header { 
            background: transparent; 
            border-bottom: 1px solid var(--apple-border);
            border-radius: 18px 18px 0 0 !important; 
            font-weight: 600; 
            padding: 1.25rem 1.5rem;
        }
        .table-responsive {
            overflow-x: auto;
            overflow-y: hidden;
            border-radius: 0 0 18px 18px;
        }
        .table tbody tr:last-child,
        .table tbody tr:last-child td,
        .table tbody tr:last-child th {
            border-bottom: 0 !important;
        }
        /* Badges */
        .badge { 
            font-weight: 500 !important; 
            padding: 0.45rem 1rem !important; 
            border-radius: 980px !important; 
            font-size: 0.82rem !important; 
            display: inline-flex !important; 
            align-items: center !important; 
            justify-content: center !important; 
            line-height: 1.2 !important;
            vertical-align: middle !important;
        }
        .btn-sm {
            padding: 0.45rem 1rem !important;
            font-size: 0.82rem !important;
            border-radius: 980px !important;
            line-height: 1.2 !important;
            vertical-align: middle !important;
        }
        .badge-donor { background: rgba(41, 151, 255, 0.15) !important; color: #2997ff !important; border: 1px solid rgba(41, 151, 255, 0.3) !important; }
        .badge-ngo { background: rgba(52, 199, 89, 0.15) !important; color: #34c759 !important; border: 1px solid rgba(52, 199, 89, 0.3) !important; }
        .badge-admin { background: rgba(255, 59, 48, 0.15) !important; color: #ff3b30 !important; border: 1px solid rgba(255, 59, 48, 0.3) !important; }
        .badge-success { background: rgba(52, 199, 89, 0.15) !important; color: #34c759 !important; border: 1px solid rgba(52, 199, 89, 0.3) !important; }
        .badge-warning { background: rgba(255, 159, 10, 0.15) !important; color: #ff9f0a !important; border: 1px solid rgba(255, 159, 10, 0.3) !important; }
        .badge-info { background: rgba(10, 132, 255, 0.15) !important; color: #64d2ff !important; border: 1px solid rgba(10, 132, 255, 0.3) !important; }
        .badge-secondary { background: rgba(142, 142, 147, 0.15) !important; color: #8e8e93 !important; border: 1px solid rgba(142, 142, 147, 0.3) !important; }
        .badge-moderator { background: rgba(191, 90, 242, 0.15) !important; color: #bf5af2 !important; border: 1px solid rgba(191, 90, 242, 0.3) !important; }
        .badge-danger { background: rgba(255, 59, 48, 0.15) !important; color: #ff3b30 !important; border: 1px solid rgba(255, 59, 48, 0.3) !important; }

        /* Navbar Notification Badge */
        .nav-badge-count {
            position: absolute !important;
            top: -4px !important;
            right: -4px !important;
            background-color: #ff3b30 !important;
            color: #ffffff !important;
            font-size: 0.65rem !important;
            font-weight: 700 !important;
            border-radius: 980px !important;
            min-width: 18px !important;
            height: 18px !important;
            padding: 0 4px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border: 2px solid var(--apple-surface) !important;
            line-height: 1 !important;
            z-index: 10 !important;
        }

        /* Light Mode Badges - Enhanced Contrast */
        [data-theme="light"] .badge-success { background: rgba(40, 167, 69, 0.15) !important; color: #1e7e34 !important; border: 1px solid rgba(40, 167, 69, 0.35) !important; }
        [data-theme="light"] .badge-warning { background: rgba(217, 119, 6, 0.15) !important; color: #b45309 !important; border: 1px solid rgba(217, 119, 6, 0.35) !important; }
        [data-theme="light"] .badge-info { background: rgba(0, 102, 204, 0.15) !important; color: #0055b3 !important; border: 1px solid rgba(0, 102, 204, 0.35) !important; }
        [data-theme="light"] .badge-secondary { background: rgba(142, 142, 147, 0.15) !important; color: #48484a !important; border: 1px solid rgba(142, 142, 147, 0.35) !important; }
        [data-theme="light"] .badge-donor { background: rgba(0, 102, 204, 0.15) !important; color: #0055b3 !important; border: 1px solid rgba(0, 102, 204, 0.35) !important; }
        [data-theme="light"] .badge-ngo { background: rgba(40, 167, 69, 0.15) !important; color: #1e7e34 !important; border: 1px solid rgba(40, 167, 69, 0.35) !important; }
        [data-theme="light"] .badge-admin { background: rgba(220, 53, 69, 0.15) !important; color: #bd2130 !important; border: 1px solid rgba(220, 53, 69, 0.35) !important; }
        [data-theme="light"] .badge-danger { background: rgba(220, 53, 69, 0.15) !important; color: #bd2130 !important; border: 1px solid rgba(220, 53, 69, 0.35) !important; }
        [data-theme="light"] .badge-moderator { background: rgba(175, 82, 222, 0.15) !important; color: #6b268a !important; border: 1px solid rgba(175, 82, 222, 0.35) !important; }
        
        /* Text Utilities */
        .text-apple-accent { color: var(--apple-accent) !important; }
        .text-apple-success { color: var(--apple-success) !important; }
        .text-apple-warning { color: #ff9f0a !important; }
        .text-apple-danger { color: var(--apple-danger) !important; }
        
        /* Form Validation Aesthetics */
        .invalid-feedback { color: var(--apple-danger); font-weight: 400; font-size: 0.8rem; margin-top: 0.5rem; }
        .alert {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 12px;
            font-size: 0.9rem;
        }
        .alert-danger {
            background: rgba(255, 59, 48, 0.1);
            border: 1px solid rgba(255, 59, 48, 0.2);
            color: #ff453a;
        }
        .alert-success {
            background: rgba(52, 199, 89, 0.1);
            border: 1px solid rgba(52, 199, 89, 0.2);
            color: #32d74b;
        }
        .btn-close { filter: invert(1) grayscale(100%) brightness(200%); opacity: 0.5; }
        .btn-close:hover { opacity: 1; }

        /* Input specific overrides */
        ::placeholder {
            color: var(--apple-text-muted) !important;
            opacity: 0.8 !important;
        }
        select option {
            background-color: var(--apple-surface);
            color: var(--apple-text);
        }
        input[type="file"]::file-selector-button {
            background-color: var(--apple-text);
            color: var(--apple-bg);
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            margin-right: 1rem;
            font-weight: 500;
            font-family: inherit;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        input[type="file"]::file-selector-button:hover {
            background-color: #d1d1d6;
        }
        
        .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--apple-text);
        }

        .form-text { color: #a1a1aa !important; }

        /* Animations */
        .animate-slide-up {
            animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        @keyframes slideUpFade {
            to { transform: translateY(0); opacity: 1; }
        }

        main { flex-grow: 1; display: flex; flex-direction: column; }
        footer { background: transparent; color: var(--apple-text-muted); border-top: 1px solid var(--apple-border); font-size: 0.8rem; }
        
        a { color: var(--apple-accent); text-decoration: none; transition: opacity 0.2s; }
        a:hover { color: var(--apple-accent); opacity: 0.8; }

        /* Pagination Styling & Alignment */
        .pagination { margin-bottom: 0; display: inline-flex; flex-wrap: wrap; gap: 4px; }
        .page-link {
            background-color: var(--apple-surface) !important;
            color: var(--apple-text) !important;
            border-color: var(--apple-border) !important;
            padding: 0.45rem 0.85rem !important;
            border-radius: 980px !important;
            margin: 0 !important;
            font-size: 0.85rem !important;
            font-weight: 500;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .page-link:hover {
            background-color: var(--apple-input-bg) !important;
            color: var(--apple-accent) !important;
        }
        .page-item.active .page-link {
            background-color: var(--apple-accent) !important;
            border-color: var(--apple-accent) !important;
            color: #ffffff !important;
        }
        .page-item.disabled .page-link {
            background-color: transparent !important;
            color: var(--apple-text-muted) !important;
            opacity: 0.5;
        }
        .pagination svg, svg.w-5.h-5 {
            width: 1rem !important;
            height: 1rem !important;
            max-width: 1rem !important;
            max-height: 1rem !important;
        }

        /* View Switcher Active Buttons (Light & Dark Mode) */
        .btn-group .btn-outline-light {
            color: var(--apple-text) !important;
            border-color: var(--apple-border) !important;
            background-color: var(--apple-surface) !important;
            font-weight: 500 !important;
        }
        .btn-group .btn-outline-light:hover {
            background-color: var(--apple-input-bg) !important;
            color: var(--apple-accent) !important;
        }
        .btn-group .btn-outline-light.active {
            background-color: var(--apple-accent) !important;
            border-color: var(--apple-accent) !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 8px rgba(41, 151, 255, 0.3) !important;
        }
        [data-theme="light"] .btn-group .btn-outline-light {
            color: #1d1d1f !important;
            border-color: #c7c7cc !important;
            background-color: #ffffff !important;
        }
        [data-theme="light"] .btn-group .btn-outline-light:hover {
            background-color: #e5e5ea !important;
            color: #0066cc !important;
        }
        [data-theme="light"] .btn-group .btn-outline-light.active {
            background-color: #0066cc !important;
            border-color: #0066cc !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 8px rgba(0, 102, 204, 0.3) !important;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3/dist/style.css" rel="stylesheet">
    <style>
        /* Custom Simple-DataTables Dark & Light Theme Override */
        .datatable-wrapper { background: transparent !important; color: var(--apple-text) !important; }
        .datatable-container { background: transparent !important; border: none !important; }
        .datatable-table { background: transparent !important; color: var(--apple-text) !important; border-collapse: separate; border-spacing: 0; }
        .datatable-table > thead > tr > th { background: var(--apple-surface) !important; border-bottom: 1px solid var(--apple-border) !important; color: var(--apple-text) !important; font-weight: 600; }
        .datatable-table > tbody > tr > td { background: transparent !important; border-bottom: 1px solid var(--apple-border) !important; color: var(--apple-text) !important; vertical-align: middle; }
        .datatable-table > tbody > tr:hover { background-color: var(--apple-input-bg) !important; }
        .datatable-input { background: var(--apple-input-bg) !important; border: 1px solid var(--apple-border) !important; color: var(--apple-text) !important; border-radius: 8px; padding: 0.375rem 0.75rem; }
        .datatable-selector { background: var(--apple-input-bg) !important; border: 1px solid var(--apple-border) !important; color: var(--apple-text) !important; border-radius: 8px; padding: 0.375rem 1.75rem 0.375rem 0.75rem; }
        .datatable-pagination a { color: var(--apple-accent) !important; background: transparent !important; border: 1px solid transparent !important; border-radius: 8px; }
        .datatable-pagination a:hover { background: var(--apple-surface) !important; border-color: var(--apple-border) !important; }
        .datatable-pagination .active a { background: var(--apple-accent) !important; color: #ffffff !important; }
        .datatable-sorter::before, .datatable-sorter::after { opacity: 0.4; }

        [data-theme="light"] .datatable-table > thead > tr > th { background: #f2f2f7 !important; color: #1d1d1f !important; }
        [data-theme="light"] .datatable-table > tbody > tr > td { color: #1d1d1f !important; }
        [data-theme="light"] .datatable-input,
        [data-theme="light"] .datatable-selector { background: #ffffff !important; color: #1d1d1f !important; border-color: #e5e5ea !important; }

        /* Global Demo Mode Visibility Controls */
        [data-demo-mode="off"] .demo-mode-only {
            display: none !important;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Leaflet.js Maps CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nXC0jkQKBWGqcRR-ntRlCFZ0bZDybRe63mzfaWuaCF4=" crossorigin=""></script>
    @stack('styles')
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg py-3" style="position: relative; z-index: 1040;">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="{{ route('home') }}">🌾 NutriShare</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('donations.index') }}"><i class="bi bi-basket"></i> Donations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('claims.index') }}"><i class="bi bi-hand-thumbs-up"></i> Claims</a>
                    </li>

                    @if(Auth::user()->isNgo() || Auth::user()->isAdmin() || Auth::user()->isModerator())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('inventory.index') }}"><i class="bi bi-box-seam"></i> Inventory</a>
                    </li>
                    @endif

                    @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('verification.index') }}"><i class="bi bi-patch-check"></i> NGO Verifications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reports.index') }}"><i class="bi bi-graph-up"></i> Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logs.index') }}"><i class="bi bi-journal-text"></i> System Logs</a>
                    </li>
                    @endif
                @endauth
            </ul>
            <ul class="navbar-nav align-items-center gap-2">
                <!-- Master Demo Mode ON/OFF Toggle Button -->
                <li class="nav-item me-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 d-inline-flex align-items-center gap-2" id="masterDemoToggleBtn" title="Toggle Master Demo Mode ON/OFF" onclick="toggleMasterDemoMode()" style="height: 36px; border: 1px solid var(--apple-border); font-size: 0.78rem;">
                        <i id="demoToggleIcon" class="bi bi-display text-warning"></i>
                        <span id="demoToggleText" class="fw-semibold">Demo: ON</span>
                    </button>
                </li>
                <li class="nav-item me-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle d-inline-flex align-items-center justify-content-center p-0" id="themeToggleBtn" title="Toggle Light / Dark Mode" onclick="toggleTheme()" style="width: 36px; height: 36px; border: 1px solid var(--apple-border);">
                        <i id="themeIcon" class="bi bi-moon-stars-fill text-info" style="font-size: 1.05rem; line-height: 1;"></i>
                    </button>
                </li>
                @auth
                    <!-- Notifications Bell Dropdown -->
                    <li class="nav-item dropdown me-2">
                        <a class="btn btn-sm btn-outline-secondary rounded-circle d-inline-flex align-items-center justify-content-center p-0 position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="System Notifications" style="width: 36px; height: 36px; border: 1px solid var(--apple-border);">
                            <i class="bi bi-bell text-apple-accent d-flex align-items-center justify-content-center" style="font-size: 1.05rem; line-height: 1; margin: 0; width: 100%; height: 100%;"></i>
                            @if(Auth::user()->unreadNotificationsCount() > 0)
                                <span class="nav-badge-count">
                                    {{ Auth::user()->unreadNotificationsCount() }}
                                </span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow-lg border-dark p-0" style="width: 320px; background-color: var(--apple-surface); border-radius: 12px; overflow: hidden; z-index: 1060 !important; margin-top: 8px !important;">
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="border-color: var(--apple-border) !important;">
                                <span class="fw-bold small" style="color: var(--apple-text);"><i class="bi bi-bell text-apple-accent me-1"></i> Notifications</span>
                                @if(Auth::user()->unreadNotificationsCount() > 0)
                                <form method="POST" action="{{ route('notifications.read-all') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 text-decoration-none small text-apple-accent" style="font-size: 0.75rem;">Mark all read</button>
                                </form>
                                @endif
                            </div>
                            <div style="max-height: 280px; overflow-y: auto;">
                                @forelse(Auth::user()->systemNotifications()->take(5)->get() as $n)
                                    <div class="p-3 border-bottom {{ !$n->is_read ? 'fw-bold' : '' }}" style="border-color: var(--apple-border) !important; {{ !$n->is_read ? 'background: rgba(41, 151, 255, 0.05);' : '' }}">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <span class="small" style="color: var(--apple-text);">{{ $n->title }}</span>
                                            <small class="text-muted" style="font-size: 0.7rem;">{{ $n->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="small text-muted mb-0" style="font-size: 0.8rem; line-height: 1.3;">{{ Str::limit($n->message, 80) }}</p>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-muted small">
                                        <i class="bi bi-bell-slash fs-4 d-block mb-1 opacity-50"></i>
                                        No notifications yet.
                                    </div>
                                @endforelse
                            </div>
                            <div class="p-2 border-top text-center" style="border-color: var(--apple-border) !important; background: var(--apple-input-bg);">
                                <a href="{{ route('notifications.index') }}" class="small text-decoration-none text-apple-accent fw-medium">View All Notifications</a>
                            </div>
                        </div>
                    </li>
                @endauth
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="btn btn-ns-primary ms-2" href="{{ route('register') }}">Register</a></li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 py-1 px-2 rounded-pill" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--apple-text); background-color: var(--apple-input-bg); border: 1px solid var(--apple-border); transition: all 0.2s ease;">
                            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="rounded-circle" style="width: 26px; height: 26px; object-fit: cover;">
                            <span class="fw-medium small" style="color: var(--apple-text);">{{ Auth::user()->name }}</span>
                            @if(Auth::user()->isDonor())
                                <span class="badge badge-donor">Donor</span>
                            @elseif(Auth::user()->isNgo())
                                <span class="badge badge-ngo">NGO</span>
                            @elseif(Auth::user()->isAdmin())
                                <span class="badge badge-admin">Admin</span>
                            @elseif(Auth::user()->isModerator())
                                <span class="badge badge-moderator">Moderator</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-dark shadow" style="background-color: var(--apple-surface);">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}" style="color: var(--apple-text);"><i class="bi bi-speedometer2 text-muted me-2"></i> Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}" style="color: var(--apple-text);"><i class="bi bi-person-gear text-muted me-2"></i> Profile Settings</a></li>
                            <li><a class="dropdown-item" href="{{ route('reviews.show', Auth::user()) }}" style="color: var(--apple-text);"><i class="bi bi-star-fill text-warning me-2"></i> My Trust & Reviews</a></li>
                            <li class="demo-mode-only"><hr class="dropdown-divider border-dark"></li>
                            <li class="dropdown-header text-uppercase px-3 demo-mode-only" style="font-size: 0.65rem; font-weight: 700; color: var(--apple-text-muted);">🎭 Demo Switcher</li>
                            <li class="demo-mode-only"><a class="dropdown-item small" href="{{ route('demo.login', 'admin') }}" style="color: var(--apple-text);"><i class="bi bi-shield-lock text-apple-danger me-2"></i> Admin (System Admin)</a></li>
                            <li class="demo-mode-only"><a class="dropdown-item small" href="{{ route('demo.login', 'moderator') }}" style="color: var(--apple-text);"><i class="bi bi-shield-check text-info me-2"></i> Moderator (Compliance)</a></li>
                            <li class="demo-mode-only"><a class="dropdown-item small" href="{{ route('demo.login', 'ngo') }}" style="color: var(--apple-text);"><i class="bi bi-box2-heart text-apple-success me-2"></i> NGO (Food Rescue)</a></li>
                            <li class="demo-mode-only"><a class="dropdown-item small" href="{{ route('demo.login', 'donor') }}" style="color: var(--apple-text);"><i class="bi bi-shop text-apple-accent me-2"></i> Donor (Sunway Grocer)</a></li>
                            <li class="demo-mode-only"><hr class="dropdown-divider border-dark"></li>
                            <li>
                                {{-- SECURITY (Module 3): CSRF token on logout form --}}
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
<div class="container-fluid px-4 mt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    {{-- SECURITY (Module 1): XSS Prevention — {{ }} auto-escapes error messages --}}
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

<!-- Main Content -->
<main class="container-fluid px-4 py-4">
    @yield('content')
</main>

<!-- Footer -->
<footer class="py-4 mt-5">
    <div class="container text-center">
        <p class="mb-1 text-muted" style="font-size: 0.95rem;">🌾 <strong>NutriShare</strong> — Surplus Food Redistribution Platform</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Theme Switcher Logic
function toggleTheme() {
    const current = document.documentElement.getAttribute('data-theme') || 'dark';
    const next = current === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    updateThemeIcon(next);
}

function updateThemeIcon(theme) {
    const icon = document.getElementById('themeIcon');
    if (icon) {
        if (theme === 'light') {
            icon.className = 'bi bi-sun-fill text-warning fs-6';
        } else {
            icon.className = 'bi bi-moon-stars-fill text-info fs-6';
        }
    }
}

// Master Demo Mode Toggle Controller
function toggleMasterDemoMode() {
    const current = localStorage.getItem('demo_mode') || 'on';
    const next = current === 'on' ? 'off' : 'on';
    localStorage.setItem('demo_mode', next);
    document.documentElement.setAttribute('data-demo-mode', next);
    updateDemoToggleUI(next);
}

function updateDemoToggleUI(mode) {
    const bar = document.getElementById('demoSwitcherBar');
    const text = document.getElementById('demoToggleText');
    const icon = document.getElementById('demoToggleIcon');
    const btn = document.getElementById('masterDemoToggleBtn');
    
    if (bar) {
        bar.style.display = mode === 'off' ? 'none' : 'block';
    }
    if (text) {
        text.innerText = mode === 'off' ? 'Demo: OFF' : 'Demo: ON';
    }
    if (icon) {
        icon.className = mode === 'off' ? 'bi bi-display-slash text-muted' : 'bi bi-display text-warning';
    }
    if (btn) {
        if (mode === 'off') {
            btn.classList.replace('btn-outline-secondary', 'btn-outline-danger');
        } else {
            btn.classList.replace('btn-outline-danger', 'btn-outline-secondary');
        }
    }
}

// Update theme & master demo mode on page load
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    updateThemeIcon(savedTheme);

    const savedDemoMode = localStorage.getItem('demo_mode') || 'on';
    document.documentElement.setAttribute('data-demo-mode', savedDemoMode);
    updateDemoToggleUI(savedDemoMode);
});

// Password field toggle
document.addEventListener('click', function(e) {
    if (e.target.closest('.toggle-password')) {
        const btn = e.target.closest('.toggle-password');
        const input = btn.previousElementSibling;
        const icon = btn.querySelector('i');
        if (input && input.tagName === 'INPUT') {
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    }
});

// GLOBAL SWEETALERT2 POPUP & CONFIRMATION INTERCEPTOR
// Eliminates all native browser "127.0.0.1:8000 says" dialogs
window.alert = function(message) {
    const isDark = (document.documentElement.getAttribute('data-theme') || 'dark') === 'dark';
    Swal.fire({
        title: 'Notice',
        text: message,
        icon: 'info',
        confirmButtonColor: '#2997ff',
        confirmButtonText: 'OK',
        background: isDark ? '#1c1c1e' : '#ffffff',
        color: isDark ? '#ffffff' : '#1d1d1f',
        customClass: {
            popup: 'rounded-4 shadow-lg border border-secondary'
        }
    });
};

function showSweetConfirm(options, callback) {
    const isDark = (document.documentElement.getAttribute('data-theme') || 'dark') === 'dark';
    Swal.fire({
        title: options.title || 'Confirmation',
        text: options.text || 'Are you sure you want to proceed?',
        icon: options.icon || 'warning',
        showCancelButton: true,
        confirmButtonColor: options.confirmColor || '#ff3b30',
        cancelButtonColor: '#8e8e93',
        confirmButtonText: options.confirmText || 'Yes, proceed',
        cancelButtonText: options.cancelText || 'Cancel',
        background: isDark ? '#1c1c1e' : '#ffffff',
        color: isDark ? '#ffffff' : '#1d1d1f',
        customClass: {
            popup: 'rounded-4 shadow-lg border border-secondary'
        }
    }).then((result) => {
        if (result.isConfirmed && typeof callback === 'function') {
            callback();
        }
    });
}

// 1. Intercept buttons / anchors with data-confirm or onclick*="confirm"
document.addEventListener('click', function(e) {
    const btn = e.target.closest('[data-confirm], [onclick*="confirm"]');
    if (!btn) return;

    const form = btn.closest('form');
    // If the button is a submit button inside a form that also has confirmation, let the submit handler manage it
    if (btn.type === 'submit' && form && (form.hasAttribute('data-confirm') || form.getAttribute('onsubmit')?.includes('confirm('))) {
        return;
    }

    e.preventDefault();
    e.stopPropagation();

    let text = btn.getAttribute('data-confirm');
    if (!text && btn.getAttribute('onclick')) {
        const match = btn.getAttribute('onclick').match(/confirm\(['"](.+?)['"]\)/);
        text = match ? match[1] : 'Are you sure you want to proceed?';
    }

    const title = btn.getAttribute('data-confirm-title') || 'Confirmation';
    const confirmText = btn.getAttribute('data-confirm-btn') || 'Yes, proceed';
    const confirmColor = btn.getAttribute('data-confirm-color') || (text && text.toLowerCase().includes('delete') ? '#ff3b30' : '#2997ff');
    const icon = text && text.toLowerCase().includes('delete') ? 'warning' : 'question';

    showSweetConfirm({ title, text, confirmText, confirmColor, icon }, function() {
        btn.removeAttribute('onclick');
        btn.removeAttribute('data-confirm');
        if (form) {
            form.submit();
        } else if (btn.tagName === 'A' && btn.href) {
            window.location.href = btn.href;
        }
    });
}, true);

// 2. Intercept any form submit with data-confirm or onsubmit*="confirm"
document.addEventListener('submit', function(e) {
    const form = e.target;
    const hasDataConfirm = form.hasAttribute('data-confirm');
    const onsubmitVal = form.getAttribute('onsubmit');
    const hasOnsubmitConfirm = onsubmitVal && onsubmitVal.includes('confirm(');

    if (!hasDataConfirm && !hasOnsubmitConfirm) return;

    e.preventDefault();
    e.stopPropagation();

    let text = form.getAttribute('data-confirm');
    if (!text && onsubmitVal) {
        const match = onsubmitVal.match(/confirm\(['"](.+?)['"]\)/);
        text = match ? match[1] : 'Are you sure you want to proceed?';
    }

    const title = form.getAttribute('data-confirm-title') || 'Confirmation';
    const confirmText = form.getAttribute('data-confirm-btn') || 'Yes, proceed';
    const confirmColor = form.getAttribute('data-confirm-color') || (text && text.toLowerCase().includes('delete') ? '#ff3b30' : '#2997ff');
    const icon = text && text.toLowerCase().includes('delete') ? 'warning' : 'question';

    showSweetConfirm({ title, text, confirmText, confirmColor, icon }, function() {
        form.removeAttribute('onsubmit');
        form.removeAttribute('data-confirm');
        form.submit();
    });
}, true);

// 3. Render modern SweetAlert2 Toasts for session flash messages on page load
document.addEventListener('DOMContentLoaded', function() {
    const isDark = (document.documentElement.getAttribute('data-theme') || 'dark') === 'dark';
    
    @if(session('success'))
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: @json(session('success')),
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        background: isDark ? '#1c1c1e' : '#ffffff',
        color: isDark ? '#ffffff' : '#1d1d1f',
    });
    @endif

    @if(session('error'))
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: @json(session('error')),
        showConfirmButton: false,
        timer: 4500,
        timerProgressBar: true,
        background: isDark ? '#1c1c1e' : '#ffffff',
        color: isDark ? '#ffffff' : '#1d1d1f',
    });
    @endif

    @if(session('status'))
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'info',
        title: @json(session('status')),
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        background: isDark ? '#1c1c1e' : '#ffffff',
        color: isDark ? '#ffffff' : '#1d1d1f',
    });
    @endif
});
</script>
@stack('scripts')
</body>
</html>
