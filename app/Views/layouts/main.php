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
</body>
</html>