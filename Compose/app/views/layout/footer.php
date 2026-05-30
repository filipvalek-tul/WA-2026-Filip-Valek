    </main>

    <footer class="border-t border-gray-200/80 bg-white mt-auto">
        <div class="max-w-[1400px] mx-auto px-6 sm:px-10 py-6 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <img src="<?= BASE_URL ?>/img/Compose_logo.svg" alt="Compose Logo" class="h-7 w-auto">
                <span class="text-xl font-outfit font-semibold text-gray-900 tracking-tight">Compose</span>
            </div>
            <span class="text-xs text-gray-300 font-medium">WA 2026 — Filip Válek</span>
        </div>
    </footer>

    <?php if (isset($_SESSION['user_id'])): 
        $currentUrl = $_GET['url'] ?? '';
        $isOnSets   = strpos($currentUrl, 'set') === 0;
        
        $fabHref  = BASE_URL . '/index.php?url=' . ($isOnSets ? 'set/create' : 'gear/create');
        $fabTitle = $isOnSets ? 'Přidat sadu' : 'Přidat vybavení';
    ?>
        <a href="<?= $fabHref ?>" 
           class="fixed bottom-8 right-8 z-50 flex items-center justify-center w-16 h-16 bg-gray-900 text-white rounded-full shadow-2xl hover:scale-110 transition-all duration-300 group"
           title="<?= $fabTitle ?>">
            <svg class="w-8 h-8 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
        </a>
    <?php endif; ?>

</body>
</html>
