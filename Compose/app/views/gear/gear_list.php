<?php require_once '../app/views/layout/header.php'; ?>

<!-- ZÁHLAVÍ STRÁNKY -->
<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-8 mt-4">
    <div>
        <h1 class="text-4xl font-display font-bold text-gray-900 tracking-tight">Vybavení</h1>
        <p class="mt-1 text-sm text-gray-500 font-medium">Tvůj kompletní přehled vybavení</p>
    </div>
</div>

<!-- FILTRY KATEGORIÍ -->
<?php if (!empty($categories)): ?>
<div class="flex flex-wrap gap-2 mb-10" id="category-filters">
    <button onclick="filterCategory('all')" data-cat="all"
            class="filter-btn px-4 py-2 rounded-full text-xs font-bold tracking-widest uppercase transition-all duration-200 bg-gray-900 text-white border border-gray-900 shadow-md">
        ALL
    </button>
    <?php foreach ($categories as $cat): ?>
        <button onclick="filterCategory(<?= $cat['id'] ?>)" data-cat="<?= $cat['id'] ?>"
                class="filter-btn px-4 py-2 rounded-full text-xs font-bold tracking-widest uppercase transition-all duration-200 bg-white text-gray-500 hover:text-gray-900 border border-gray-300 hover:shadow-md">
            <?= htmlspecialchars($cat['name']) ?>
        </button>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- SEZNAM VYBAVENÍ -->
<?php if (empty($equipmentList)): ?>
    <div class="text-center py-20 bg-white border border-gray-100 rounded-[2rem] shadow-sm">
        <p class="text-sm text-gray-400 font-bold tracking-wider uppercase mb-3">Zatím prázdno</p>
        <p class="text-base text-gray-500 font-medium">Tvůj inventář neobsahuje žádné vybavení.</p>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?= BASE_URL ?>/index.php?url=gear/create" class="mt-4 inline-block text-sm text-gray-900 font-bold hover:text-brand transition-colors">Přidat první kousek →</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="gear-grid">
        <?php foreach ($equipmentList as $item): ?>
            <!-- KARTA VYBAVENÍ -->
            <div class="gear-card group border border-gray-200 shadow-md rounded-[2rem] overflow-hidden hover:shadow-xl transition-all duration-300 relative flex flex-col min-h-[320px] bg-white"
                 data-category="<?= $item['category_id'] ?? 'none' ?>">
                
                <button type="button" onclick="openGearModal(<?= $item['id'] ?>)" class="absolute inset-0 z-30 w-full h-full text-left focus:outline-none"><span class="sr-only">Zobrazit detail</span></button>

                <!-- Pozadí fotka -->
                <div class="h-[220px] relative w-full shrink-0 bg-gray-100">
                    <?php if (!empty($item['photo_path'])): ?>
                        <img src="<?= BASE_URL ?>/uploads/photos/<?= htmlspecialchars($item['photo_path']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php else: ?>
                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300 text-gray-400 transform group-hover:scale-105 transition-transform duration-500 pb-4">
                            <?php if (stripos($item['category_name'] ?? '', 'lens') !== false || stripos($item['category_name'] ?? '', 'objektiv') !== false): ?>
                                <svg class="w-16 h-16 opacity-50" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5m7.5-13.5L12 3.225l-3.75 2.025M15.75 18.75L12 20.775l-3.75-2.025" />
                                </svg>
                            <?php else: ?>
                                <svg class="w-16 h-16 opacity-50" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                                </svg>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Spodní FOLDER vrstva (vytažená nahoru přes margin) -->
                <div class="flex-grow flex flex-col relative z-20 -mt-6 drop-shadow-[0_-6px_12px_rgba(0,0,0,0.15)]">
                    <!-- Tab (Záložka složky, výška 24px) -->
                    <div class="w-[40%] h-6 bg-white relative rounded-tl-2xl px-5 flex items-center">
                        <span class="text-[0.65rem] font-bold text-gray-500 uppercase tracking-widest truncate block w-full relative z-10 translate-y-[6px]">
                            <?= $item['category_name'] ? htmlspecialchars($item['category_name']) : 'GEAR' ?>
                        </span>
                        
                        <!-- Pomalý, hladký sklon SVG s pravým rádiusem (R=16, 45deg) -->
                        <svg class="absolute top-0 w-[58px] h-6 text-white" style="left: calc(100% - 10px);" fill="currentColor" viewBox="0 0 58 24">
                            <path d="M 0 24 L 0 0 L 10 0 A 16 16 0 0 1 21.31 4.69 L 35.94 19.31 A 16 16 0 0 0 47.26 24 L 58 24 Z" />
                        </svg>
                    </div>
                    
                    <!-- Hlavní tělo složky (přesahuje přesně dolů) -->
                    <div class="bg-white w-full flex-grow p-5 pt-4 flex flex-col justify-between rounded-b-2xl">
                        <div>
                            <h2 class="text-base font-semibold text-gray-900 leading-snug"><?= htmlspecialchars($item['name']) ?></h2>
                            <?php if ($item['serial_number']): ?>
                                <p class="mt-1 text-[0.65rem] text-gray-500 font-medium uppercase tracking-wider">
                                    SN: <?= htmlspecialchars($item['serial_number']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
function filterCategory(catId) {
    const activeClass = 'filter-btn px-4 py-2 rounded-full text-xs font-bold tracking-widest uppercase transition-all duration-200 bg-gray-900 text-white border border-gray-900 shadow-md';
    const inactiveClass = 'filter-btn px-4 py-2 rounded-full text-xs font-bold tracking-widest uppercase transition-all duration-200 bg-white text-gray-500 hover:text-gray-900 border border-gray-300 hover:shadow-md';

    document.querySelectorAll('.filter-btn').forEach(btn => {
        const isActive = String(btn.dataset.cat) === String(catId);
        btn.className = isActive ? activeClass : inactiveClass;
    });

    document.querySelectorAll('.gear-card').forEach(card => {
        if (catId === 'all' || String(card.dataset.category) === String(catId)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>

<!-- MODAL PRO DETAIL VYBAVENÍ -->
<div id="gear-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeGearModal()"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl w-full flex flex-col max-h-[90vh]">
                
                <!-- Záhlaví modalu -->
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between shrink-0 bg-white z-20">
                    <div class="mt-1">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1" id="modal-gear-category">Kategorie</p>
                        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight" id="modal-gear-name">Název vybavení</h3>
                    </div>
                    <button type="button" onclick="closeGearModal()" class="rounded-full bg-gray-100 p-2 text-gray-500 hover:bg-gray-200 focus:outline-none transition-colors">
                        <span class="sr-only">Zavřít</span>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Tělo modalu (scrollovatelné) -->
                <div class="overflow-y-auto flex-1 p-6 bg-gray-50/50">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                        
                        <!-- Levý sloupec (2/5): Fotka + Specifikace -->
                        <div class="md:col-span-2 flex flex-col gap-6">
                            <!-- Fotka -->
                            <div id="modal-gear-photo-container" class="rounded-2xl overflow-hidden shadow-sm border border-gray-200 hidden">
                                <img id="modal-gear-photo" src="" alt="Vybavení" class="w-full h-auto max-h-64 object-cover">
                            </div>

                            <!-- Mřížka s informacemi (List) -->
                            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50">
                                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-widest">Specifikace</h4>
                                </div>
                                <div id="modal-gear-info" class="divide-y divide-gray-100">
                                    <div class="text-center py-4"><span class="text-xs text-gray-400">Načítám detaily...</span></div>
                                </div>
                            </div>
                            
                            <!-- Účtenka -->
                            <div id="modal-gear-receipt-container" class="hidden"></div>
                        </div>

                        <!-- Pravý sloupec (3/5): Poznámky + Logbook -->
                        <div class="md:col-span-3 flex flex-col gap-6">
                            
                            <!-- Poznámky -->
                            <div id="modal-gear-notes-container" class="hidden"></div>

                            <!-- Logbook / Komentáře -->
                            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4 border-b border-gray-100 pb-3">Logbook / Servis</h4>
                                
                                <div id="modal-gear-comments" class="space-y-4 mb-4">
                                    <!-- Zde se vykreslí komentáře -->
                                </div>

                                <!-- Formulář pro nový komentář -->
                                <form id="modal-comment-form" action="" method="POST" class="mt-4 pt-4 border-t border-gray-100">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Nový záznam</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="content" required placeholder="Napiš poznámku nebo záznam o servisu..." 
                                               class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-gray-900">
                                        <button type="submit" class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 hover:scale-105 transition-all">
                                            Uložit
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <!-- Patička modalu s akcemi -->
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-end gap-3 shrink-0 rounded-b-2xl">
                    <a id="modal-gear-edit" href="#" class="px-5 py-2.5 bg-gray-900 text-sm font-bold text-white rounded-xl hover:bg-gray-800 hover:scale-105 hover:shadow-sm transition-all uppercase tracking-wider">
                        Upravit
                    </a>
                    <a id="modal-gear-delete" href="#" onclick="return confirm('Opravdu smazat toto vybavení?')" class="px-5 py-2.5 bg-white border border-red-200 text-red-500 hover:bg-red-50 hover:scale-105 transition-all text-sm font-bold rounded-xl uppercase tracking-wider">
                        Smazat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const baseUrl = '<?= BASE_URL ?>';

    function openGearModal(id) {
        const modal = document.getElementById('gear-modal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Nastavit správný action na formulář
        document.getElementById('modal-comment-form').action = baseUrl + '/index.php?url=comment/store/' + id;
        
        // Odkazy edit a delete
        document.getElementById('modal-gear-edit').href = baseUrl + '/index.php?url=gear/edit/' + id;
        document.getElementById('modal-gear-delete').href = baseUrl + '/index.php?url=gear/delete/' + id;

        // Reset
        document.getElementById('modal-gear-info').innerHTML = '<div class="text-center py-4 col-span-full"><span class="text-xs text-gray-400">Načítám...</span></div>';
        document.getElementById('modal-gear-comments').innerHTML = '';
        document.getElementById('modal-gear-photo-container').classList.add('hidden');

        fetch(baseUrl + '/index.php?url=gear/apiDetail/' + id)
            .then(res => res.json())
            .then(data => {
                if(data.error) {
                    alert(data.error);
                    closeGearModal();
                    return;
                }

                const item = data.item;
                const comments = data.comments;
                
                document.getElementById('modal-gear-name').textContent = item.name;
                document.getElementById('modal-gear-category').textContent = item.category_name || 'GEAR';

                if (item.photo_path) {
                    const img = document.getElementById('modal-gear-photo');
                    img.src = baseUrl + '/uploads/photos/' + item.photo_path;
                    document.getElementById('modal-gear-photo-container').classList.remove('hidden');
                }

                // Generování informačních karet
                let infoHtml = '';
                const createRow = (label, value) => {
                    if (!value) return '';
                    return `
                    <div class="px-4 py-3 flex justify-between items-center bg-white hover:bg-gray-50 transition-colors">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">${label}</span>
                        <span class="text-sm font-medium text-gray-900 text-right max-w-[60%] truncate" title="${value}">${value}</span>
                    </div>`;
                };

                infoHtml += createRow('Model', item.model);
                infoHtml += createRow('Sériové číslo', item.serial_number);
                infoHtml += createRow('Množství', item.quantity + ' ks');
                infoHtml += createRow('Stav', item.is_used == 1 ? 'Použité (Bazar)' : 'Nové');
                
                if (item.purchase_price) {
                    infoHtml += createRow('Pořizovací cena', parseFloat(item.purchase_price).toLocaleString('cs-CZ') + ' Kč');
                }
                
                if (item.purchase_date) {
                    const d = new Date(item.purchase_date);
                    infoHtml += createRow('Datum nákupu', d.toLocaleDateString('cs-CZ'));
                }

                if (item.notes) {
                    const notesContainer = document.getElementById('modal-gear-notes-container');
                    notesContainer.innerHTML = `
                    <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm">
                        <h4 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4 border-b border-gray-100 pb-3">Poznámky</h4>
                        <div class="text-sm text-gray-800 whitespace-pre-line">${item.notes}</div>
                    </div>`;
                    notesContainer.classList.remove('hidden');
                } else {
                    document.getElementById('modal-gear-notes-container').classList.add('hidden');
                    document.getElementById('modal-gear-notes-container').innerHTML = '';
                }

                if (item.receipt_path) {
                    const receiptContainer = document.getElementById('modal-gear-receipt-container');
                    receiptContainer.innerHTML = `
                    <a href="${baseUrl}/uploads/receipts/${item.receipt_path}" target="_blank" class="flex items-center justify-center gap-2 w-full py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-900 hover:border-gray-900 shadow-sm transition-all uppercase tracking-wider">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                        Zobrazit doklad / účtenku
                    </a>`;
                    receiptContainer.classList.remove('hidden');
                } else {
                    document.getElementById('modal-gear-receipt-container').classList.add('hidden');
                    document.getElementById('modal-gear-receipt-container').innerHTML = '';
                }

                document.getElementById('modal-gear-info').innerHTML = infoHtml || '<div class="text-xs text-gray-500 p-4">Žádné podrobné informace k zobrazení.</div>';

                // Generování komentářů
                let commentsHtml = '';
                if (comments.length === 0) {
                    commentsHtml = '<p class="text-xs text-gray-400 italic py-2">Zatím žádné záznamy v logbooku.</p>';
                } else {
                    comments.forEach(c => {
                        const isOwnerOrAdmin = (parseInt(c.user_id) === parseInt(data.current_user_id)) || data.is_admin;
                        let actions = '';
                        if (isOwnerOrAdmin) {
                            actions = `
                                <div class="flex gap-3 ml-4">
                                    <a href="${baseUrl}/index.php?url=comment/delete/${c.id}" onclick="return confirm('Opravdu smazat záznam?')" class="text-[0.6rem] text-red-400 hover:text-red-600 font-bold uppercase tracking-wider transition-colors">Smazat</a>
                                </div>
                            `;
                        }

                        const d = new Date(c.created_at);
                        commentsHtml += `
                        <div class="py-3 border-b border-gray-50 last:border-0">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-bold text-gray-900">${c.author_name}</span>
                                        <span class="text-[0.6rem] text-gray-400 tracking-wider uppercase">${d.toLocaleDateString('cs-CZ')} ${d.toLocaleTimeString('cs-CZ', {hour: '2-digit', minute:'2-digit'})}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-0.5">${c.content}</p>
                                </div>
                                ${actions}
                            </div>
                        </div>`;
                    });
                }
                document.getElementById('modal-gear-comments').innerHTML = commentsHtml;

                // Skrýt form pro nepřihlášené/ty co nemají přístup (v app máme většinou přístup k přidání komentu pokud ho vidíme, protože sdílení je jen pro usery)
                // Pro zjednodušení vždy zobrazujeme form
            })
            .catch(err => {
                console.error(err);
                document.getElementById('modal-gear-info').innerHTML = '<div class="text-xs text-red-500">Chyba při načítání dat.</div>';
            });
    }

    function closeGearModal() {
        document.getElementById('gear-modal').classList.add('hidden');
        document.body.style.overflow = '';
        // Odebrat open_gear_modal param z URL bez refreshe
        const url = new URL(window.location);
        if(url.searchParams.has('open_gear_modal')) {
            url.searchParams.delete('open_gear_modal');
            window.history.replaceState({}, '', url);
        }
    }

    // Auto-open modal pokud jsme byli přesměrováni (např. po uložení/komentáři)
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const modalId = urlParams.get('open_gear_modal');
        if (modalId) {
            openGearModal(modalId);
        }
    });
</script>

<?php require_once '../app/views/layout/footer.php'; ?>
