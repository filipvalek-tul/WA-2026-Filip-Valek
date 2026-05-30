<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compose</title>
    <meta name="description" content="Správa fotografického vybavení — Compose">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 
                        sans: ['"Inter"', 'sans-serif'],
                        display: ['"Inter"', 'sans-serif'],
                        outfit: ['"Outfit"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    fontSize: {
                        '2xs': ['0.65rem', { lineHeight: '1rem' }],
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #f3f4f6; }
    </style>
</head>
<body class="text-gray-900 font-sans antialiased min-h-screen flex flex-col">

    <?php
        $currentUrl = $_GET['url'] ?? '';
        $isOnSets   = strpos($currentUrl, 'set') === 0;
        $isOnGear   = !$isOnSets;
    ?>

    <!-- NAVIGAČNÍ LIŠTA -->
    <header class="bg-[#f3f4f6] border-b-0 sticky top-0 z-20 pt-6 pb-4">
        <div class="max-w-[1400px] mx-auto px-6 sm:px-10">
            <div class="flex items-center justify-between">

                <!-- LOGO -->
                <a href="<?= BASE_URL ?>/index.php" class="flex-shrink-0 flex items-center gap-2">
                    <img src="<?= BASE_URL ?>/img/Compose_logo.svg" alt="Compose Logo" class="h-11 sm:h-12 w-auto">
                    <span class="hidden md:block text-3xl font-outfit font-semibold text-gray-900 tracking-tight">Compose</span>
                </a>

                <!-- PILL SWITCHER -->
                <div class="flex-1 flex justify-center">
                    <div class="inline-flex items-center bg-gray-50 rounded-full p-1.5 shadow-sm border border-gray-200">
                        <a href="<?= BASE_URL ?>/index.php"
                           class="px-6 py-2 rounded-full text-xs font-bold uppercase tracking-widest transition-all <?= $isOnGear ? 'bg-gray-900 text-white shadow-md' : 'text-gray-400 hover:text-gray-900' ?>">
                            VYBAVENÍ
                        </a>
                        <a href="<?= BASE_URL ?>/index.php?url=set/index"
                           class="px-6 py-2 rounded-full text-xs font-bold uppercase tracking-widest transition-all <?= $isOnSets ? 'bg-gray-900 text-white shadow-md' : 'text-gray-400 hover:text-gray-900' ?>"
                           onclick="<?php if (!isset($_SESSION['user_id'])) echo "event.preventDefault(); alert('Pro zobrazení sad se musíte přihlásit.'); window.location.href='" . BASE_URL . "/index.php?url=auth/login';"; ?>">
                            SADY
                        </a>
                    </div>
                </div>

                <!-- PRAVÁ STRANA — Avatar -->
                <div class="flex items-center justify-end flex-shrink-0">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="flex items-center gap-3">
                            <?php if (!empty($_SESSION['is_admin'])): ?>
                                <a href="<?= BASE_URL ?>/index.php?url=user/index"
                                   class="text-xs font-bold text-gray-400 hover:text-gray-900 transition-colors uppercase tracking-wider">
                                    Admin
                                </a>
                            <?php endif; ?>
                            
                            <a href="<?= BASE_URL ?>/index.php?url=user/profile" class="group flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white font-bold text-sm hover:scale-105 transition-all duration-200 shadow-sm" title="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>">
                                <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 2)) ?>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center gap-4">
                            <a href="<?= BASE_URL ?>/index.php?url=auth/login"
                               class="px-5 py-2.5 bg-gray-900 text-white text-xs font-bold rounded-xl hover:scale-105 hover:shadow-md transition-all uppercase tracking-wider">
                                Přihlásit
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </header>

    <main class="flex-grow max-w-[1400px] w-full mx-auto px-6 sm:px-10 py-12">

        <!-- FLASH ZPRÁVY -->
        <?php if (!empty($_SESSION['messages'])): ?>
            <div class="mb-8 space-y-2">
                <?php foreach ($_SESSION['messages'] as $type => $messages): ?>
                    <?php
                        $cls = 'bg-white text-gray-700 border-gray-200';
                        if ($type === 'success') $cls = 'bg-green-50 text-green-800 border-green-200';
                        if ($type === 'error')   $cls = 'bg-red-50 text-red-700 border-red-200';
                        if ($type === 'notice')  $cls = 'bg-amber-50 text-amber-800 border-amber-200';
                    ?>
                    <?php foreach ($messages as $msg): ?>
                        <div class="px-4 py-3 border rounded-xl text-sm font-medium <?= $cls ?> flex justify-between items-center relative pr-10 shadow-sm transition-all">
                            <span><?= htmlspecialchars($msg) ?></span>
                            <button type="button" onclick="this.parentElement.style.display='none'" class="absolute right-3 top-1/2 -translate-y-1/2 p-1 rounded-md opacity-50 hover:opacity-100 hover:bg-black/5 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['messages']); ?>
        <?php endif; ?>
