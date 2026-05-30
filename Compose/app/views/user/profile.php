<?php require_once '../app/views/layout/header.php'; ?>

<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-8 mt-4 flex justify-between items-start">
        <div>
            <h1 class="text-4xl font-display font-bold text-gray-900 tracking-tight">Můj profil</h1>
            <a href="<?= BASE_URL ?>/index.php" class="inline-block mt-2 text-[0.65rem] font-bold text-gray-400 hover:text-gray-900 transition-colors uppercase tracking-widest">← Zpět na přehled</a>
        </div>
        <a href="<?= BASE_URL ?>/index.php?url=auth/logout" class="px-5 py-2.5 bg-white border border-red-200 text-red-500 hover:bg-red-50 hover:scale-105 transition-all text-sm font-bold rounded-xl uppercase tracking-wider mt-2">
            Odhlásit se
        </a>
    </div>

    <!-- Informace o účtu -->
    <div class="bg-white border border-gray-200 rounded-[2rem] p-8 shadow-md mb-6">
        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4">Informace o účtu</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">E-mail</label>
                <input type="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled
                       class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Registrován</label>
                <input type="text" value="<?= htmlspecialchars($user['created_at'] ?? '') ?>" disabled
                       class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
            </div>
            <?php if (!empty($user['is_admin'])): ?>
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Role</label>
                <div class="px-3 py-2 border border-gray-300 rounded-xl text-sm bg-gray-50 flex items-center h-[38px]">
                    <span class="inline-block px-3 py-1 bg-gray-900 text-white text-[0.65rem] rounded-full font-bold uppercase tracking-widest shadow-sm leading-none">Administrátor</span>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Formulář pro úpravu -->
    <form action="<?= BASE_URL ?>/index.php?url=user/updateProfile" method="POST"
          class="bg-white border border-gray-200 rounded-[2rem] p-8 space-y-6 shadow-md">

        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-2">Změna údajů</h2>

        <div>
            <label for="username" class="block text-sm font-medium text-gray-900 mb-1">Uživatelské jméno <span class="text-red-500">*</span></label>
            <input type="text" id="username" name="username" required
                   value="<?= htmlspecialchars($user['username'] ?? '') ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900">
        </div>

        <div class="pt-2">
            <p class="text-sm font-medium text-gray-900 mb-3">Změna hesla</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-900 mb-1">Nové heslo</label>
                    <input type="password" id="new_password" name="new_password" autocomplete="new-password"
                           class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900">
                </div>
                <div>
                    <label for="password_confirm" class="block text-sm font-medium text-gray-900 mb-1">Potvrdit heslo</label>
                    <input type="password" id="password_confirm" name="password_confirm" autocomplete="new-password"
                           class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900">
                </div>
            </div>
        </div>

        <!-- Tlačítka -->
        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
            <button type="submit"
                    class="px-8 py-3 bg-gray-900 text-white text-sm font-bold rounded-xl hover:scale-105 hover:shadow-lg transition-all duration-200 uppercase tracking-wider">
                Uložit
            </button>
            <a href="<?= BASE_URL ?>/index.php"
               class="px-8 py-3 bg-white border border-gray-300 text-sm font-medium text-gray-900 rounded-xl hover:bg-gray-50 hover:scale-105 hover:shadow-sm transition-all duration-200 uppercase tracking-wider">
                Zrušit
            </a>
        </div>
    </form>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>
