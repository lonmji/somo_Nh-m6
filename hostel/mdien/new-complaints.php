<?php
$pageTitle = 'Complaints';
require_once '../includes/layout/header.php';

$db = getDB();
$complaints = $db->query("SELECT * FROM complaints ORDER BY registrationDate DESC");
?>

<?php require_once '../includes/layout/sidebar.php'; ?>

<main class="flex-1 p-8">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Complaints</h2>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="data-table w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Complaint No.</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $i = 1; while ($c = $complaints->fetch_object()): ?>
                    <?php $status = empty($c->complaintStatus) ? 'New' : $c->complaintStatus; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-400"><?= $i++ ?></td>
                        <td class="px-4 py-3 font-medium"><?= sanitize($c->ComplainNumber) ?></td>
                        <td class="px-4 py-3"><?= sanitize($c->complaintType) ?></td>
                        <td class="px-4 py-3"><?= badge($status) ?></td>
                        <td class="px-4 py-3"><?= sanitize($c->registrationDate) ?></td>
                        <td class="px-4 py-3">
                            <a href="complaint-detail.php?id=<?= $c->id ?>"
                               class="text-indigo-600 hover:underline text-xs font-medium">
                                View
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once '../includes/layout/footer.php'; ?>