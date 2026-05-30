<?php require_once '../app/views/layout/header.php'; ?>

<div class="max-w-4xl mx-auto px-4 py-4">
    <div class="mb-4 mt-2">
        <h1 class="text-4xl font-display font-bold text-gray-900 tracking-tight">Nová sada</h1>
        <a href="<?= BASE_URL ?>/index.php?url=set/index"
           class="inline-block mt-2 text-[0.65rem] font-bold text-gray-400 hover:text-gray-900 transition-colors uppercase tracking-widest">← Zpět na sady</a>
    </div>

    <form action="<?= BASE_URL ?>/index.php?url=set/store" method="POST" enctype="multipart/form-data"
          class="bg-white border border-gray-200 rounded-[2rem] p-6 space-y-4 shadow-md">

        <div>
            <label for="name" class="block text-sm font-medium text-gray-900 mb-1">Název sady <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" required placeholder="např. Cestování"
                   class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900">
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-900 mb-1">Popis</label>
            <textarea id="description" name="description" rows="3" placeholder="Volitelný popis sady..."
                      class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm resize-y focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900"></textarea>
        </div>

        <!-- Vlastní fotka sady -->
        <div>
            <label for="photo" class="block text-sm font-medium text-gray-900 mb-1">Fotka sady (Volitelné)</label>
            <div class="p-4 bg-gray-50 border border-gray-300 rounded-xl hover:border-gray-900 hover:ring-1 hover:ring-gray-900 focus-within:border-gray-900 focus-within:ring-1 focus-within:ring-gray-900 transition-all">
                <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png,.webp"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-[0.65rem] file:font-bold file:uppercase file:tracking-widest file:bg-gray-900 file:text-white hover:file:bg-black cursor-pointer focus:outline-none">
                <p class="mt-3 text-[0.65rem] text-gray-400 font-medium uppercase tracking-wider">Doporučeno nahrávat fotky na šířku. Povolené formáty: JPG, PNG, WEBP.</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-900 mb-3">Obsah sady</label>
            <?php if (empty($availableGear)): ?>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                    <p class="text-xs text-gray-500">Zatím nemáte žádné vybavení. Nejprve si nějaké přidejte v sekci Vybavení.</p>
                </div>
            <?php else: ?>
                <div class="space-y-2 max-h-96 overflow-y-auto pr-2">
                    <?php foreach ($availableGear as $gear): ?>
                        <label class="flex items-center gap-4 p-3 bg-white border border-gray-200 rounded-lg cursor-pointer hover:border-gray-900 hover:shadow-sm transition-all select-none">
                            <input type="checkbox" name="equipment_ids[]" value="<?= $gear['id'] ?>"
                                   class="w-4 h-4 rounded border-gray-300 text-gray-900 cursor-pointer focus:ring-gray-900">
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($gear['name']) ?></div>
                                <div class="text-xs text-gray-500 font-medium tracking-wide">
                                    <?= $gear['category_name'] ? htmlspecialchars($gear['category_name']) : 'JINÉ' ?>
                                    <?php if ($gear['serial_number']): ?> // <?= htmlspecialchars($gear['serial_number']) ?><?php endif; ?>
                                </div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
            <button type="submit"
                    class="px-8 py-2.5 bg-gray-900 text-white text-sm font-bold rounded-xl hover:scale-105 hover:shadow-lg transition-all duration-200 uppercase tracking-wider">
                Uložit
            </button>
            <a href="<?= BASE_URL ?>/index.php?url=set/index"
               class="px-8 py-2.5 bg-white border border-gray-300 text-sm font-medium text-gray-900 rounded-xl hover:bg-gray-50 hover:scale-105 hover:shadow-sm transition-all duration-200 uppercase tracking-wider">
                Zrušit
            </a>
        </div>
    </form>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>
