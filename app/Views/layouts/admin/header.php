<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Panel') ?> – CeritaKu Admin</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#6c5ce7', dark: '#5a4bd1' },
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ── Sidebar base ── */
        #admin-sidebar {
            background: #ffffff;
            border-right: 1px solid #ede9fe;
            transition: transform 0.35s cubic-bezier(.4, 0, .2, 1);
            box-shadow: 4px 0 24px rgba(108, 92, 231, 0.06);
        }

        /* Subtle top accent bar */
        #admin-sidebar .brand-section {
            position: relative;
        }

        #admin-sidebar .brand-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 16px;
            right: 16px;
            height: 1px;
            background: linear-gradient(90deg, transparent, #ede9fe, transparent);
        }

        /* Nav links */
        .nav-link {
            border-left: 2px solid transparent;
            transition: all 0.18s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link:hover {
            border-left-color: rgba(108, 92, 231, 0.4);
            background: #f5f3ff;
            color: #5b21b6;
        }

        .nav-link:hover .nav-icon {
            color: #7c3aed;
        }

        .nav-link.active {
            border-left-color: #6c5ce7;
            background: linear-gradient(90deg, #ede9fe, #f5f3ff);
            color: #5b21b6;
        }

        .nav-link.active .nav-icon {
            color: #6c5ce7;
        }

        /* Section label */
        .nav-section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.12em;
            color: #c4b5fd;
            text-transform: uppercase;
            padding: 0 12px;
            margin: 20px 0 6px;
        }

        /* Scrollbar sidebar */
        #admin-sidebar::-webkit-scrollbar {
            width: 3px;
        }

        #admin-sidebar::-webkit-scrollbar-thumb {
            background: #ddd6fe;
            border-radius: 4px;
        }

        /* Mobile sidebar */
        @media (max-width: 1023px) {
            #admin-sidebar {
                transform: translateX(-100%);
                position: fixed;
            }

            #admin-sidebar.open {
                transform: translateX(0);
            }
        }

        /* Topbar */
        #topbar {
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.92);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }

        /* Flash messages */
        .flash-success {
            background: linear-gradient(90deg, #f0fdf4, #dcfce7);
            border-color: #86efac;
        }

        .flash-error {
            background: linear-gradient(90deg, #fef2f2, #fee2e2);
            border-color: #fca5a5;
        }

        /* Global scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 99px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
</head>