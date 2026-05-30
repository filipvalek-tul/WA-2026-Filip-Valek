<?php require_once '../app/views/layout/header.php'; ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-semibold text-gray-900">Správa uživatelů</h1>
        <p class="mt-0.5 text-sm text-gray-500">Celkem: <?= count($users) ?> uživatelů</p>
    </div>
</div>

<?php if (empty($users)): ?>
    <div class="text-center py-12 border border-gray-200 rounded-lg bg-white">
        <p class="text-sm text-gray-400">Žádní uživatelé nenalezeni.</p>
    </div>
<?php else: ?>
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">ID</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Uživatel</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">E-mail</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Role</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Registrace</th>
                    <th class="px-5 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Akce</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($users as $u): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-sm text-gray-400"><?= $u['id'] ?></td>
                        <td class="px-5 py-3 text-sm font-medium text-gray-900"><?= htmlspecialchars($u['username']) ?></td>
                        <td class="px-5 py-3 text-sm text-gray-500"><?= htmlspecialchars($u['email']) ?></td>
                        <td class="px-5 py-3 text-sm">
                            <?php if ($u['is_admin']): ?>
                                <span class="px-2 py-0.5 bg-amber-50 text-amber-700 text-xs rounded-full">Admin</span>
                            <?php else: ?>
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-xs rounded-full">Uživatel</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-400"><?= htmlspecialchars($u['created_at']) ?></td>
                        <td class="px-5 py-3 text-right">
                            <?php if ((int)$u['id'] !== (int)$_SESSION['user_id']): ?>
                                <a href="<?= BASE_URL ?>/index.php?url=user/delete/<?= $u['id'] ?>"
                                   onclick="return confirm('Opravdu smazat uživatele <?= htmlspecialchars($u['username']) ?>? Tato akce je nevratná.')"
                                   class="text-sm text-red-500 hover:text-red-700 transition-colors">
                                    Smazat
                                </a>
                            <?php else: ?>
                                <span class="text-xs text-gray-300">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once '../app/views/layout/footer.php'; ?>
