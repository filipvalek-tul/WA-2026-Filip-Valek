<?php require_once '../app/views/layout/header.php'; ?>

<div class="sm:flex sm:items-center sm:justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Dostupné knihy</h2>
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

<?php if (empty($books)): ?>
    <div class="text-center py-12 bg-white rounded-lg border border-gray-200">
        <p class="text-gray-500">V databázi se zatím nenachází žádné knihy.</p>
    </div>
<?php else: ?>
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Název knihy</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Autor</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rok</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cena</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($books as $book): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($book['id']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($book['title']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($book['author']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($book['year']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($book['price']) ?> Kč</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <a href="<?= BASE_URL ?>/index.php?url=book/show/<?= $book['id'] ?>" class="text-gray-600 hover:text-black">Detail</a>
                                
                                <?php if (isset($_SESSION['user_id']) && ((int)$_SESSION['user_id'] === (int)$book['created_by'] || !empty($_SESSION['is_admin']))): ?>
                                    <a href="<?= BASE_URL ?>/index.php?url=book/edit/<?= $book['id'] ?>" class="text-gray-600 hover:text-black">Upravit</a>
                                    <a href="<?= BASE_URL ?>/index.php?url=book/delete/<?= $book['id'] ?>" onclick="return confirm('Opravdu chcete tuto knihu smazat?')" class="text-red-600 hover:text-red-900">Smazat</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require_once '../app/views/layout/footer.php'; ?>