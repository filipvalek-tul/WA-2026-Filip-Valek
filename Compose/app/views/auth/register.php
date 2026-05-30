<?php require_once '../app/views/layout/header.php'; ?>

<div class="flex justify-center pt-8 pb-16">
    <div class="w-full max-w-md px-4">
        <div class="mb-6 text-left">
            <h1 class="text-4xl font-display font-bold text-gray-900 tracking-tight">Registrace</h1>
            <p class="mt-2 text-sm text-gray-500 font-medium">Vytvoř si nový účet pro Compose.</p>
            <a href="<?= BASE_URL ?>/index.php" class="inline-block mt-6 text-[0.65rem] font-bold text-gray-400 hover:text-gray-900 transition-colors uppercase tracking-widest">← Zpět na úvod</a>
        </div>

        <div class="bg-white p-8 sm:p-10 rounded-[2rem] border border-gray-200 shadow-md">
            <form action="<?= BASE_URL ?>/index.php?url=auth/storeUser" method="POST" class="space-y-5">
            <div>
                <label for="username" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Uživatelské jméno <span class="text-red-500">*</span></label>
                <input type="text" id="username" name="username" required autocomplete="username"
                       <?= !empty($old_username) ? 'value="' . htmlspecialchars($old_username) . '"' : '' ?>
                       class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">E-mail <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" required autocomplete="email"
                       <?= !empty($old_email) ? 'value="' . htmlspecialchars($old_email) . '"' : '' ?>
                       class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Heslo <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="password" name="password" required autocomplete="new-password"
                           class="w-full px-4 py-3 pr-12 bg-gray-50 border border-gray-300 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
                    <button type="button" onclick="const p = document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password';" class="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-gray-900 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </button>
                </div>
                <p class="mt-2 text-xs text-gray-400 font-medium">Minimálně 8 znaků, alespoň jedno číslo</p>
            </div>

            <div>
                <label for="password_confirm" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Potvrdit heslo <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="password_confirm" name="password_confirm" required autocomplete="new-password"
                           class="w-full px-4 py-3 pr-12 bg-gray-50 border border-gray-300 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
                    <button type="button" onclick="const p = document.getElementById('password_confirm'); p.type = p.type === 'password' ? 'text' : 'password';" class="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-gray-900 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </button>
                </div>
            </div>

            <button type="submit"
                    class="w-full py-3.5 mt-4 bg-gray-900 text-white text-sm font-bold rounded-xl hover:bg-gray-800 hover:scale-105 hover:shadow-lg transition-all uppercase tracking-wider">
                Vytvořit účet
            </button>
        </form>

        <p class="mt-8 text-sm text-center text-gray-500 font-medium">
            Již máš účet?
            <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="text-gray-900 font-bold hover:underline">Přihlas se</a>
        </p>
        </div>
    </div>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>
