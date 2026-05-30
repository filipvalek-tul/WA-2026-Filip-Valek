<?php require_once '../app/views/layout/header.php'; ?>

<div class="max-w-md mx-auto flex flex-col justify-center min-h-[65vh] w-full">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Přihlášení</h2>
        <p class="mt-2 text-sm text-gray-500">Vítejte zpět v naší Knihovně.</p>
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
            <form action="<?= BASE_URL ?>/index.php?url=auth/authenticate" method="post" class="space-y-6">
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                    <input type="email" id="email" name="email" required autofocus
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Heslo</label>
                    <input type="password" id="password" name="password" required 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full bg-black border border-transparent rounded-md shadow-sm py-2 px-4 flex justify-center text-sm font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-colors">
                        Přihlásit se
                    </button>
                </div>
                
                <p class="text-center text-sm text-gray-600 border-t border-gray-200 pt-6 mt-6">
                    Nemáte ještě účet? <a href="<?= BASE_URL ?>/index.php?url=auth/register" class="font-medium text-black hover:underline transition-colors">Zaregistrujte se</a>.
                </p>
                
            </form>
        </div>
    </div>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>
