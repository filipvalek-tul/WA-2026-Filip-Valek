<?php require_once '../app/views/layout/header.php'; ?>

<div class="max-w-3xl mx-auto flex flex-col justify-center min-h-[65vh] w-full">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Nová registrace</h2>
        <p class="mt-2 text-sm text-gray-500">Vytvořte si účet pro správu vašeho knižního katalogu. Pole označená hvězdičkou jsou povinná.</p>
    </div>

    <?php if (isset($_SESSION['messages']) && !empty($_SESSION['messages'])): ?>
        <div class="mb-6 space-y-4">
            <?php foreach ($_SESSION['messages'] as $type => $messages): ?>
                <?php 
                    $bgClass = 'bg-gray-100 text-gray-800 border-gray-300';
                    if ($type === 'success') $bgClass = 'bg-green-50 text-green-800 border-green-200';
                    if ($type === 'error') $bgClass = 'bg-red-50 text-red-800 border-red-200';
                    if ($type === 'notice') $bgClass = 'bg-yellow-50 text-yellow-800 border-yellow-200';
                ?>
                <?php foreach ($messages as $message): ?>
                    <div class="p-4 border rounded-md <?= $bgClass ?>">
                        <p class="text-sm font-medium"><?= htmlspecialchars($message) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['messages']); ?>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-8 sm:p-10">
            <form action="<?= BASE_URL ?>/index.php?url=auth/storeUser" method="post" class="space-y-6">
                
                <div class="grid grid-cols-1 gap-y-6 gap-x-8 lg:grid-cols-2">
                    <div class="lg:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Přihlašovací údaje</h3>
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Uživatelské jméno <span class="text-red-500">*</span></label>
                        <input type="text" id="username" name="username" required 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">E-mail <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" required 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Heslo <span class="text-red-500">*</span></label>
                        <input type="password" id="password" name="password" required 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                    </div>

                    <div>
                        <label for="password_confirm" class="block text-sm font-medium text-gray-700">Potvrzení hesla <span class="text-red-500">*</span></label>
                        <input type="password" id="password_confirm" name="password_confirm" required 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                    </div>

                    <div class="lg:col-span-2 mt-4">
                        <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Osobní údaje (Volitelné)</h3>
                    </div>

                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">Křestní jméno</label>
                        <input type="text" id="first_name" name="first_name" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Příjmení</label>
                        <input type="text" id="last_name" name="last_name" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                    </div>

                    <div class="lg:col-span-2">
                        <label for="nickname" class="block text-sm font-medium text-gray-700">Zobrazovaná přezdívka</label>
                        <input type="text" id="nickname" name="nickname" placeholder="Jak vám máme v aplikaci říkat?"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 placeholder-gray-400 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                    </div>

                </div>

                <div class="pt-4 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                    <p class="text-sm text-gray-600">
                        Už máte účet? <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="font-medium text-black hover:underline transition-colors">Přihlaste se zde</a>.
                    </p>
                    <button type="submit" 
                            class="w-full sm:w-auto bg-black border border-transparent rounded-md shadow-sm py-2 px-6 inline-flex justify-center text-sm font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-colors">
                        Vytvořit účet
                    </button>
                </div>
                
            </form>
        </div>
    </div>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>
