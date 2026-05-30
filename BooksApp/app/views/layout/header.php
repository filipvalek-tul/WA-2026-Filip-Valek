<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knihovna - Výuková aplikace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] }, } } }
    </script>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased overflow-y-scroll flex flex-col min-h-screen">
    
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="<?= BASE_URL ?>/index.php" class="hover:opacity-80 transition-opacity">
                    <h1 class="text-xl font-bold tracking-tight text-black">Knihovna</h1>
                </a>
                <nav>
                    <ul class="flex items-center space-x-6">

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li>
                                <a href="<?= BASE_URL ?>/index.php?url=book/create" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-black hover:bg-gray-800 transition-colors shadow-sm">
                                    + Přidat knihu
                                </a>
                            </li>
                            <li class="flex items-center" title="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>">
                                <div class="w-8 h-8 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </div>
                            </li>
                            <li>
                                <a href="<?= BASE_URL ?>/index.php?url=auth/logout" class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors">
                                    Odhlásit
                                </a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors shadow-sm">
                                    Přihlásit
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-grow w-full">