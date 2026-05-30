<?php require_once '../app/views/layout/header.php'; ?>

<div class="max-w-4xl mx-auto px-4 py-4">
    <div class="mb-4 mt-2">
        <h1 class="text-4xl font-display font-bold text-gray-900 tracking-tight">Upravit vybavení</h1>
        <a href="<?= BASE_URL ?>/index.php?url=gear/detail/<?= $item['id'] ?>" class="inline-block mt-2 text-[0.65rem] font-bold text-gray-400 hover:text-gray-900 transition-colors uppercase tracking-widest">← Zpět na detail</a>
    </div>

    <form action="<?= BASE_URL ?>/index.php?url=gear/update/<?= $item['id'] ?>" method="POST" enctype="multipart/form-data"
          class="bg-white border border-gray-200 rounded-[2rem] p-6 space-y-4 shadow-md">

        <!-- Název a Model -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-900 mb-1">Název <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" required
                       value="<?= htmlspecialchars($item['name']) ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900">
            </div>
            <div>
                <label for="model" class="block text-sm font-medium text-gray-900 mb-1">Model / výrobce</label>
                <input type="text" id="model" name="model"
                       value="<?= htmlspecialchars($item['model'] ?? '') ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900">
            </div>
        </div>

        <!-- Řádek 1: Kategorie, Cena, Datum -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-900 mb-1">Kategorie</label>
                <select id="category_id" name="category_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 bg-white">
                    <option value="">— Vyberte kategorii —</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (int)$item['category_id'] === (int)$cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="purchase_price" class="block text-sm font-medium text-gray-900 mb-1">Pořizovací cena (Kč)</label>
                <input type="number" id="purchase_price" name="purchase_price" min="0" step="1"
                       value="<?= htmlspecialchars($item['purchase_price'] ?? '') ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900">
            </div>
            <div>
                <label for="purchase_date" class="block text-sm font-medium text-gray-900 mb-1">Datum pořízení</label>
                <input type="date" id="purchase_date" name="purchase_date"
                       value="<?= htmlspecialchars($item['purchase_date'] ?? '') ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900">
            </div>
        </div>

        <!-- Řádek 2: Množství a Stav -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-900 mb-1">Množství</label>
                <input type="number" id="quantity" name="quantity" min="1" value="<?= (int)($item['quantity'] ?? 1) ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900">
            </div>
            <div>
                <label for="set_id" class="block text-sm font-medium text-gray-900 mb-1">Přidat do sady</label>
                <select id="set_id" name="set_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 bg-white">
                    <option value="">— Nepřidávat do sady —</option>
                    <?php if (!empty($userSets)): ?>
                        <?php foreach ($userSets as $set): ?>
                            <option value="<?= $set['id'] ?>" <?= $currentSetId === (int)$set['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($set['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="flex items-end pb-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="is_used" name="is_used" value="1"
                           class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 w-4 h-4" <?= !empty($item['is_used']) ? 'checked' : '' ?>>
                    <span class="text-sm font-medium text-gray-900">Koupeno použité</span>
                </label>
            </div>
        </div>

        <!-- Sériové číslo -->
        <div>
            <label for="serial_number" class="block text-sm font-medium text-gray-900 mb-1">Sériové číslo</label>
            <input type="text" id="serial_number" name="serial_number"
                   value="<?= htmlspecialchars($item['serial_number'] ?? '') ?>"
                   class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900">
        </div>

        <!-- Poznámky -->
        <div>
            <label for="notes" class="block text-sm font-medium text-gray-900 mb-1">Poznámky</label>
            <textarea id="notes" name="notes" rows="3"
                      class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm resize-y focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900"><?= htmlspecialchars($item['notes'] ?? '') ?></textarea>
        </div>

        <!-- Fotka a Účtenka -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Fotka -->
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Vlastní fotka vybavení</label>
                <div class="p-4 bg-gray-50 border border-gray-300 rounded-xl hover:border-gray-900 hover:ring-1 hover:ring-gray-900 focus-within:border-gray-900 focus-within:ring-1 focus-within:ring-gray-900 transition-all">
                    <?php if ($item['photo_path']): ?>
                        <div class="mb-4 relative inline-block group">
                            <a href="<?= BASE_URL ?>/uploads/photos/<?= htmlspecialchars($item['photo_path']) ?>" target="_blank" class="block">
                                <img src="<?= BASE_URL ?>/uploads/photos/<?= htmlspecialchars($item['photo_path']) ?>" alt="Aktuální fotka" class="h-20 w-auto rounded-xl object-cover shadow-sm border border-gray-200">
                            </a>
                            <a href="<?= BASE_URL ?>/index.php?url=gear/deletePhoto/<?= $item['id'] ?>" 
                               class="absolute -top-2 -right-2 flex items-center justify-center w-6 h-6 rounded-full bg-white border border-gray-200 text-red-500 hover:bg-red-500 hover:text-white hover:border-red-500 transition-all shadow-sm z-10" title="Ihned smazat fotku">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png,.webp"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-[0.65rem] file:font-bold file:uppercase file:tracking-widest file:bg-gray-900 file:text-white hover:file:bg-black cursor-pointer focus:outline-none">
                    <p class="mt-3 text-[0.65rem] text-gray-400 font-medium uppercase tracking-wider">Nahradí stávající fotku.</p>
                </div>
            </div>
            
            <!-- Účtenka -->
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Doklad / Účtenka</label>
                <div class="p-4 bg-gray-50 border border-gray-300 rounded-xl hover:border-gray-900 hover:ring-1 hover:ring-gray-900 focus-within:border-gray-900 focus-within:ring-1 focus-within:ring-gray-900 transition-all">
                    <?php if ($item['receipt_path']): ?>
                        <div class="mb-4 relative inline-block group">
                            <a href="<?= BASE_URL ?>/uploads/receipts/<?= htmlspecialchars($item['receipt_path']) ?>"
                               target="_blank"
                               class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 hover:border-gray-900 hover:shadow-sm transition-all">
                                Zobrazit doklad
                            </a>
                            <a href="<?= BASE_URL ?>/index.php?url=gear/deleteReceipt/<?= $item['id'] ?>" 
                               class="absolute -top-2 -right-2 flex items-center justify-center w-6 h-6 rounded-full bg-white border border-gray-200 text-red-500 hover:bg-red-500 hover:text-white hover:border-red-500 transition-all shadow-sm z-10" title="Ihned smazat doklad">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="receipt" name="receipt" accept=".jpg,.jpeg,.png,.pdf,.webp"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-[0.65rem] file:font-bold file:uppercase file:tracking-widest file:bg-gray-900 file:text-white hover:file:bg-black cursor-pointer focus:outline-none">
                    <p class="mt-3 text-[0.65rem] text-gray-400 font-medium uppercase tracking-wider">Nahradí stávající doklad.</p>
                </div>
            </div>
        </div>

        <!-- Tlačítka -->
        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
            <button type="submit"
                    class="px-8 py-2.5 bg-gray-900 text-white text-sm font-bold rounded-xl hover:scale-105 hover:shadow-lg transition-all duration-200 uppercase tracking-wider">
                Uložit
            </button>
            <a href="<?= BASE_URL ?>/index.php?url=gear/detail/<?= $item['id'] ?>"
               class="px-8 py-2.5 bg-white border border-gray-300 text-sm font-medium text-gray-900 rounded-xl hover:bg-gray-50 hover:scale-105 hover:shadow-sm transition-all duration-200 uppercase tracking-wider">
                Zrušit
            </a>
        </div>
    </form>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>
