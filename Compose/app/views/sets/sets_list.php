<?php require_once '../app/views/layout/header.php'; ?>

<!-- ZÁHLAVÍ STRÁNKY -->
<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-10 mt-4">
    <div>
        <h1 class="text-4xl font-display font-bold text-gray-900 tracking-tight">Sady</h1>
        <p class="mt-1 text-sm text-gray-500 font-medium">Tvoje sady vybavení</p>
    </div>
</div>

<?php if (empty($sets)): ?>
    <div class="text-center py-20 bg-white border border-gray-100 rounded-[2rem] shadow-sm">
        <p class="text-sm text-gray-400 font-bold tracking-wider uppercase mb-3">Zatím prázdno</p>
        <p class="text-base text-gray-500 font-medium">Zatím nemáte vytvořenou žádnou sadu.</p>
        <a href="<?= BASE_URL ?>/index.php?url=set/create"
           class="mt-4 inline-block text-sm text-gray-900 font-bold hover:text-brand transition-colors">Vytvořit první sadu →</a>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($sets as $set): ?>
            <!-- KARTA SADY (FOLDER STYLE) -->
            <div class="group border border-gray-200 shadow-md rounded-[2rem] overflow-hidden hover:shadow-xl transition-all duration-300 relative flex flex-col min-h-[320px] bg-white">
                
                <button type="button" onclick="openSetModal(<?= $set['id'] ?>)" class="absolute inset-0 z-30 w-full h-full cursor-pointer focus:outline-none"><span class="sr-only">Otevřít sadu</span></button>

                <!-- Pozadí fotka -->
                <div class="h-[220px] relative w-full shrink-0 bg-gray-100">
                    <?php if (!empty($set['photo_path'])): ?>
                        <img src="<?= BASE_URL ?>/uploads/photos/<?= htmlspecialchars($set['photo_path']) ?>" alt="<?= htmlspecialchars($set['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php else: ?>
                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300 text-gray-400 transform group-hover:scale-105 transition-transform duration-500 pb-4">
                            <svg class="w-14 h-14 opacity-50" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Spodní FOLDER vrstva -->
                <div class="flex-grow flex flex-col relative z-20 -mt-6 drop-shadow-[0_-6px_12px_rgba(0,0,0,0.15)]">
                    <div class="w-[40%] h-6 bg-white relative rounded-tl-2xl px-5 flex items-center">
                        <span class="text-[0.65rem] font-bold text-gray-500 uppercase tracking-widest truncate block w-full relative z-10 translate-y-[6px]">
                            SADA
                        </span>
                        
                        <!-- Pomalý, hladký sklon SVG s pravým rádiusem (R=16, 45deg) -->
                        <svg class="absolute top-0 w-[58px] h-6 text-white" style="left: calc(100% - 10px);" fill="currentColor" viewBox="0 0 58 24">
                            <path d="M 0 24 L 0 0 L 10 0 A 16 16 0 0 1 21.31 4.69 L 35.94 19.31 A 16 16 0 0 0 47.26 24 L 58 24 Z" />
                        </svg>
                    </div>
                    
                    <div class="bg-white w-full flex-grow p-5 pt-4 flex flex-col justify-between rounded-b-2xl">
                        <div>
                            <h2 class="text-base font-semibold text-gray-900 leading-snug"><?= htmlspecialchars($set['name']) ?></h2>
                            <?php if ($set['description']): ?>
                                <p class="mt-1 text-[0.65rem] text-gray-500 font-medium line-clamp-1 uppercase tracking-wider"><?= htmlspecialchars($set['description']) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-[0.65rem] font-bold text-gray-500 uppercase tracking-widest">Kusů</span>
                            <span class="text-sm font-bold text-gray-900"><?= (int)$set['item_count'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- MODAL S CHECKLISTEM -->
<div id="set-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Pozadí modalu -->
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeSetModal()"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <!-- Samotný modal -->
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                
                <div class="px-6 pt-6 pb-4">
                    <div class="flex items-start justify-between">
                        <div class="mt-1">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Sada</p>
                            <h3 id="modal-set-name" class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight">...</h3>
                            <p id="modal-set-desc" class="text-sm text-gray-500 font-medium mt-2">...</p>
                        </div>
                        <button type="button" onclick="closeSetModal()" class="rounded-full bg-gray-100 p-2 text-gray-500 hover:bg-gray-200 focus:outline-none transition-colors">
                            <span class="sr-only">Zavřít</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="mt-6">
                        <div class="flex justify-between items-end border-b border-gray-100 pb-2 mb-3">
                            <h4 class="text-[0.65rem] font-bold text-gray-400 uppercase tracking-widest">Checklist — Balení</h4>
                        </div>
                        
                        <!-- Zde se dynamicky načtou položky -->
                        <div id="modal-items-container" class="space-y-2 max-h-[50vh] overflow-y-auto pr-2">
                            <div class="text-center py-4">
                                <span class="text-xs text-gray-400">Načítám...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-end gap-3 shrink-0 rounded-b-2xl">
                    <a id="modal-edit-link" href="#" class="px-5 py-2.5 bg-gray-900 text-sm font-bold text-white rounded-xl hover:bg-gray-800 hover:scale-105 hover:shadow-sm transition-all uppercase tracking-wider">
                        Upravit
                    </a>
                    <a id="modal-delete-link" href="#" onclick="return confirm('Opravdu chcete tuto sadu smazat?')" class="px-5 py-2.5 bg-white border border-red-200 text-red-500 hover:bg-red-50 hover:scale-105 transition-all text-sm font-bold rounded-xl uppercase tracking-wider">
                        Smazat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const baseUrl = '<?= BASE_URL ?>';
    let totalItems = 0;

    function openSetModal(setId) {
        document.getElementById('set-modal').classList.remove('hidden');
        document.getElementById('modal-items-container').innerHTML = '<div class="text-center py-4"><span class="text-xs text-gray-400">Načítám...</span></div>';
        document.getElementById('modal-edit-link').href = baseUrl + '/index.php?url=set/edit/' + setId;
        document.getElementById('modal-delete-link').href = baseUrl + '/index.php?url=set/delete/' + setId;

        // Fetch data
        fetch(baseUrl + '/index.php?url=set/apiDetail/' + setId)
            .then(response => response.json())
            .then(data => {
                if(data.error) {
                    alert(data.error);
                    closeSetModal();
                    return;
                }
                
                document.getElementById('modal-set-name').textContent = data.set.name;
                document.getElementById('modal-set-desc').textContent = data.items.length + ' položek' + (data.set.description ? ' - ' + data.set.description : '');
                
                totalItems = data.items.length;
                let html = '';
                
                if (totalItems === 0) {
                    html = '<div class="text-center py-8"><p class="text-xs text-gray-400">Sada je prázdná.</p></div>';
                } else {
                    data.items.forEach((item, index) => {
                        let cat = item.category_name ? item.category_name : 'JINÉ';
                        let sub = cat;
                        if(item.serial_number) sub += ' // ' + item.serial_number;
                        
                        html += `
                            <label class="modal-checklist-item flex items-center gap-4 p-3 bg-gray-50/50 border border-gray-100 rounded-[1.25rem] cursor-pointer hover:border-gray-300 hover:bg-gray-50 transition-all select-none">
                                <input type="checkbox" class="modal-gear-checkbox w-4 h-4 rounded border-gray-300 text-gray-900 cursor-pointer focus:ring-gray-900" onchange="updateModalProgress()">
                                <div class="flex-1 min-w-0">
                                    <div class="item-name text-sm font-bold text-gray-900">${item.name}</div>
                                    <div class="text-[0.65rem] text-gray-500 font-medium tracking-wide uppercase">${sub}</div>
                                </div>
                            </label>
                        `;
                    });
                }
                
                document.getElementById('modal-items-container').innerHTML = html;
                updateModalProgress();
            })
            .catch(err => {
                console.error(err);
                document.getElementById('modal-items-container').innerHTML = '<div class="text-center py-4 text-red-500">Chyba načítání.</div>';
            });
    }

    function closeSetModal() {
        document.getElementById('set-modal').classList.add('hidden');
    }

    function updateModalProgress() {
        const checkboxes = document.querySelectorAll('.modal-gear-checkbox');
        const checked = document.querySelectorAll('.modal-gear-checkbox:checked').length;

        document.querySelectorAll('.modal-checklist-item').forEach(label => {
            const cb = label.querySelector('.modal-gear-checkbox');
            const nameEl = label.querySelector('.item-name');
            if (cb.checked) {
                label.classList.add('opacity-50');
                nameEl.classList.add('text-gray-400');
                nameEl.classList.remove('text-gray-900');
            } else {
                label.classList.remove('opacity-50');
                nameEl.classList.remove('text-gray-400');
                nameEl.classList.add('text-gray-900');
            }
        });
    }
</script>

<?php require_once '../app/views/layout/footer.php'; ?>
