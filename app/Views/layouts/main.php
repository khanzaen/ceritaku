<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'CeritaKu - Platform Novel Indonesia' ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet"/>
    
    <!-- Material Symbols Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- Tailwind Config -->
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#2d333a",
                        "accent": "#7C3BD9",
                        "surface": "#ffffff",
                        "background": "#fdfdfd",
                        "border": "#e5e7eb",
                    },
                    fontFamily: {
                        "sans": ["Inter", "sans-serif"],
                        "serif": ["Lora", "serif"]
                    },
                },
            },
        }
    </script>

    <!-- Global Styles -->
    <style type="text/tailwindcss">
        @layer base {
            body {
                @apply bg-background text-slate-800 antialiased;
            }
            h1, h2, h3, h4 {
                @apply font-serif;
            }
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>

    <!-- Additional Styles -->
    <?php if (isset($additionalStyles)): ?>
        <?= $additionalStyles ?>
    <?php endif; ?>
</head>
<body class="font-sans bg-background">
    <!-- Header -->
    <?= view('layouts/user/header') ?>

    <!-- Mobile Sidebar -->
    <?= view('layouts/user/sidebar') ?>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <?= view('layouts/user/footer') ?>

    <!-- Auth Modals -->
    <?= view('layouts/modals/auth_modal') ?>

    <!-- Additional Scripts -->
    <?= $this->renderSection('additionalScripts') ?>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!-- GLOBAL TOAST NOTIFICATION SYSTEM                           -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <style>
        #globalToastContainer {
            position: fixed;
            top: 80px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 12px;
            pointer-events: none;
        }
        .g-toast {
            pointer-events: all;
            transform: translateX(120%);
            opacity: 0;
            transition: transform 0.35s cubic-bezier(.4,0,.2,1), opacity 0.35s ease;
            min-width: 300px;
            max-width: 420px;
        }
        .g-toast.show {
            transform: translateX(0);
            opacity: 1;
        }
        .g-toast.hide {
            transform: translateX(120%);
            opacity: 0;
        }
    </style>

    <div id="globalToastContainer"></div>

    <script>
    (function () {
        const CFG = {
            success: { bg:'bg-green-50',  border:'border-green-300',  text:'text-green-800',  subtext:'text-green-600',  icon:'check_circle',    iconColor:'text-green-500'  },
            error:   { bg:'bg-red-50',    border:'border-red-300',    text:'text-red-800',    subtext:'text-red-600',    icon:'error',           iconColor:'text-red-500'    },
            warning: { bg:'bg-amber-50',  border:'border-amber-300',  text:'text-amber-800',  subtext:'text-amber-600',  icon:'warning',         iconColor:'text-amber-500'  },
            info:    { bg:'bg-blue-50',   border:'border-blue-300',   text:'text-blue-800',   subtext:'text-blue-600',   icon:'info',            iconColor:'text-blue-500'   },
            review:  { bg:'bg-violet-50', border:'border-violet-300', text:'text-violet-800', subtext:'text-violet-600', icon:'send',            iconColor:'text-violet-500' },
            update:  { bg:'bg-indigo-50', border:'border-indigo-300', text:'text-indigo-800', subtext:'text-indigo-600', icon:'update',          iconColor:'text-indigo-500' },
        };

        window.showToast = function(message, type = 'success', detail = '', duration = 5000) {
            if (!message) return;
            const c = CFG[type] ?? CFG.success;
            const container = document.getElementById('globalToastContainer');

            const toast = document.createElement('div');
            toast.className = `g-toast ${c.bg} ${c.border} border rounded-2xl shadow-xl px-5 py-4 flex items-start gap-3`;
            toast.innerHTML = `
                <span class="material-symbols-outlined ${c.iconColor} text-2xl flex-shrink-0 mt-0.5" style="font-variation-settings:'FILL' 1">${c.icon}</span>
                <div class="flex-1 min-w-0">
                    <p class="${c.text} text-sm font-bold leading-snug">${message}</p>
                    ${detail ? `<p class="${c.subtext} text-xs mt-1 leading-relaxed">${detail}</p>` : ''}
                </div>
                <button onclick="dismissGlobalToast(this.parentElement)"
                    class="${c.text} opacity-40 hover:opacity-100 transition-opacity text-xl leading-none flex-shrink-0 mt-0.5 ml-1">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>
            `;

            container.appendChild(toast);
            // Trigger animation
            requestAnimationFrame(() => requestAnimationFrame(() => toast.classList.add('show')));

            const timer = setTimeout(() => dismissGlobalToast(toast), duration);
            toast._timer = timer;
        };

        window.dismissGlobalToast = function(el) {
            if (!el || el.classList.contains('hide')) return;
            clearTimeout(el._timer);
            el.classList.replace('show', 'hide');
            setTimeout(() => el.remove(), 380);
        };

        // Auto-fire flash messages dari PHP session
        document.addEventListener('DOMContentLoaded', function () {
            <?php if (session()->getFlashdata('success')): ?>
                showToast(<?= json_encode(session()->getFlashdata('success')) ?>, 'success');
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                showToast(<?= json_encode(session()->getFlashdata('error')) ?>, 'error');
            <?php endif; ?>
            <?php if (session()->getFlashdata('warning')): ?>
                showToast(<?= json_encode(session()->getFlashdata('warning')) ?>, 'warning');
            <?php endif; ?>
            <?php if (session()->getFlashdata('info')): ?>
                showToast(<?= json_encode(session()->getFlashdata('info')) ?>, 'info');
            <?php endif; ?>
            <?php if (session()->getFlashdata('review')): ?>
                showToast(<?= json_encode(session()->getFlashdata('review')) ?>, 'review', 'Admin will check and publish it soon.');
            <?php endif; ?>
        });
    })();
    </script>
    <!-- ═══════════════════════════════════════════════════════════ -->

</body>
</html>