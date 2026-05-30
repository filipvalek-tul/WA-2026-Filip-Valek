<?php require_once '../app/views/layout/header.php'; ?>

<div class="flex flex-col justify-center h-full my-auto pt-8 lg:pt-16 pb-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
        <div class="max-w-2xl">
            <h1 class="text-5xl sm:text-6xl font-display font-bold text-gray-900 tracking-tight mb-6">Měj své vybavení pod kontrolou.</h1>
            <p class="text-lg sm:text-xl text-gray-500 font-medium mb-8">
                Compose je tvůj osobní nástroj pro evidenci fototechniky. Spravuj objektivy, těla, blesky, chystej si sady na focení a měj přehled o servisu na jednom místě.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-start gap-4">
                <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="px-8 py-3.5 bg-gray-900 text-white text-sm font-bold rounded-xl hover:scale-105 hover:shadow-lg transition-all uppercase tracking-wider w-full sm:w-auto text-center">
                    Přihlásit se
                </a>
                <a href="<?= BASE_URL ?>/index.php?url=auth/register" class="px-8 py-3.5 bg-white border border-gray-300 text-gray-900 text-sm font-bold rounded-xl hover:bg-gray-50 hover:scale-105 hover:shadow-sm transition-all uppercase tracking-wider w-full sm:w-auto text-center">
                    Vytvořit účet
                </a>
            </div>
        </div>
        
        <!-- Obrázek vpravo -->
        <div class="hidden lg:flex justify-end items-center relative w-full h-full max-h-[300px] mt-4 lg:mt-0">
            <div class="relative w-full max-w-xl">
                <img src="<?= BASE_URL ?>/img/hero_wide.png" alt="Photography Gear" class="relative z-10 w-full aspect-video object-cover rounded-3xl shadow-2xl">
                <div class="absolute -inset-2 bg-gradient-to-tr from-gray-200 to-gray-50 rounded-3xl -z-10 blur-xl opacity-50"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full mb-auto">
        <div class="bg-white p-8 rounded-[2rem] border border-gray-200 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-left">
            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-6 text-gray-900">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2">Evidence techniky</h3>
            <p class="text-sm text-gray-500">Měj přehled o každém kusu vybavení, počtu kusů, datu nákupu i pořizovací ceně a záruce.</p>
        </div>
        <div class="bg-white p-8 rounded-[2rem] border border-gray-200 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-left">
            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-6 text-gray-900">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2">Servisní deník</h3>
            <p class="text-sm text-gray-500">U každého předmětu můžeš zaznamenat historii servisu, oprav a jakékoliv důležité poznámky.</p>
        </div>
        <div class="bg-white p-8 rounded-[2rem] border border-gray-200 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-left">
            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-6 text-gray-900">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2">Sady na focení</h3>
            <p class="text-sm text-gray-500">Vytvářej si virtuální batohy (sady) pro konkrétní focení a přiřazuj k nim adekvátní techniku.</p>
        </div>
    </div>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>
