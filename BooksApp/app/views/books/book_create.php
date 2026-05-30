<?php require_once '../app/views/layout/header.php'; ?>

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Přidat novou knihu</h2>
    <p class="mt-2 text-sm text-gray-500">Vyplňte údaje a uložte knihu do databáze. Pole označená hvězdičkou jsou povinná.</p>
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
        <form action="<?= BASE_URL ?>/index.php?url=book/store" method="post" enctype="multipart/form-data" class="space-y-6">
            <div class="grid grid-cols-1 gap-y-6 gap-x-8 lg:grid-cols-2">
                <div class="lg:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700">Název knihy <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                </div>

                <div>
                    <label for="author" class="block text-sm font-medium text-gray-700">Autor <span class="text-red-500">*</span></label>
                    <input type="text" id="author" name="author" placeholder="Příjmení Jméno" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                </div>

                <div>
                    <label for="isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
                    <input type="text" id="isbn" name="isbn" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Kategorie <span class="text-red-500">*</span></label>
                    <select id="category" name="category" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-white focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                        <option value="">-- Vyberte kategorii --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['id']) ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="subcategory" class="block text-sm font-medium text-gray-700">Podkategorie</label>
                    <select id="subcategory" name="subcategory" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-white focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                        <option value="">-- Vyberte podkategorii --</option>
                        <?php foreach ($subcategories as $subcat): ?>
                            <option value="<?= htmlspecialchars($subcat['id']) ?>">
                                <?= htmlspecialchars($subcat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700">Rok vydání <span class="text-red-500">*</span></label>
                    <input type="number" id="year" name="year" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Cena (Kč)</label>
                    <input type="number" id="price" name="price" step="0.5" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                </div>

                <div class="lg:col-span-2">
                    <label for="link" class="block text-sm font-medium text-gray-700">Odkaz</label>
                    <input type="text" id="link" name="link" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                </div>

                <div class="lg:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Popis knihy</label>
                    <textarea id="description" name="description" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-black focus:border-black sm:text-sm"></textarea>
                </div>    

                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Obrázky knihy</label>
                    <div class="w-full">
                        <label for="images" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-md cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-black transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span id="file-title" class="text-sm text-gray-900 font-medium">Klikni pro výběr souborů</span>
                                <span id="file-info" class="text-xs text-gray-500 mt-1 text-center px-4">Žádné soubory nebyly vybrány (JPG, PNG, WebP)</span>
                            </div>
                            <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200 flex justify-end">
                <button type="submit" class="bg-black border border-transparent rounded-md shadow-sm py-2 px-6 inline-flex justify-center text-sm font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-colors">
                    Uložit knihu do DB
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const fileInput = document.getElementById('images');
    const fileTitle = document.getElementById('file-title');
    const fileInfo = document.getElementById('file-info');

    fileInput.addEventListener('change', function(event) {
        const files = event.target.files;
        
        if (files.length === 0) {
            fileTitle.textContent = 'Klikni pro výběr souborů';
            fileTitle.className = 'text-sm text-gray-900 font-medium';
            fileInfo.textContent = 'Žádné soubory nebyly vybrány (JPG, PNG, WebP)';
        } else if (files.length === 1) {
            fileTitle.textContent = 'Soubor připraven';
            fileTitle.className = 'text-sm text-black font-bold';
            fileInfo.textContent = files[0].name;
        } else {
            fileTitle.textContent = 'Soubory připraveny';
            fileTitle.className = 'text-sm text-black font-bold';
            fileInfo.textContent = 'Vybráno celkem: ' + files.length + ' souborů';
        }
    });
</script>

<?php require_once '../app/views/layout/footer.php'; ?>