<?php require_once '../app/views/layout/header.php'; ?>

<div class="max-w-xl">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>/index.php?url=gear/detail/<?= $comment['equipment_id'] ?>"
           class="text-sm text-gray-400 hover:text-gray-600 transition-colors">← Zpět na detail vybavení</a>
        <h1 class="mt-3 text-xl font-semibold text-gray-900">Upravit záznam</h1>
    </div>

    <form action="<?= BASE_URL ?>/index.php?url=comment/update/<?= $comment['id'] ?>" method="POST"
          class="bg-white border border-gray-200 rounded-lg p-6 space-y-5">

        <div>
            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Obsah záznamu</label>
            <textarea id="content" name="content" rows="5" required
                      class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm resize-y focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900"><?= htmlspecialchars($comment['content']) ?></textarea>
        </div>

        <div class="flex gap-3 pt-2 border-t border-gray-100">
            <button type="submit"
                    class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                Uložit záznam
            </button>
            <a href="<?= BASE_URL ?>/index.php?url=gear/detail/<?= $comment['equipment_id'] ?>"
               class="px-4 py-2 border border-gray-300 text-sm text-gray-600 rounded-md hover:bg-gray-50 transition-colors">
                Zrušit
            </a>
        </div>
    </form>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>
