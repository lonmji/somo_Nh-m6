<?php
$pageTitle = 'Manage Students';
require_once '../includes/layout/header.php';

$db = getDB();

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $db->prepare("DELETE FROM registration WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    redirect('manage-students.php', 'Student removed successfully.');
}

$students = $db->query("SELECT id, firstName, middleName, lastName, regno,
                               contactno, roomno, seater, stayfrom
                        FROM registration ORDER BY id DESC");
?>

<?php require_once '../includes/layout/sidebar.php'; ?>

<main class="flex-1 p-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Registered Students</h2>
            <a href="registration.php"
               class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                <i class="fa-solid fa-plus mr-2"></i>Add Student
            </a>
        </div>
        <?= flashMessage() ?>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="data-table w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Student Name</th>
                        <th class="px-4 py-3">Reg No.</th>
                        <th class="px-4 py-3">Contact</th>
                        <th class="px-4 py-3">Room</th>
                        <th class="px-4 py-3">Seater</th>
                        <th class="px-4 py-3">Stay From</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $i = 1; while ($s = $students->fetch_object()): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-400"><?= $i++ ?></td>
                        <td class="px-4 py-3 font-medium text-gray-800">
                            <?= sanitize("{$s->firstName} {$s->middleName} {$s->lastName}") ?>
                        </td>
                        <td class="px-4 py-3"><?= sanitize($s->regno) ?></td>
                        <td class="px-4 py-3"><?= sanitize($s->contactno) ?></td>
                        <td class="px-4 py-3"><?= sanitize($s->roomno) ?></td>
                        <td class="px-4 py-3"><?= sanitize($s->seater) ?></td>
                        <td class="px-4 py-3"><?= sanitize($s->stayfrom) ?></td>
                        <td class="px-4 py-3 flex gap-2">
                            <a href="student-details.php?id=<?= $s->id ?>"
                               class="text-indigo-600 hover:underline text-xs font-medium">
                                <i class="fa-solid fa-eye"></i> View
                            </a>
                            <a href="?delete=<?= $s->id ?>"
                               onclick="return confirm('Delete this student?')"
                               class="text-red-500 hover:underline text-xs font-medium">
                                <i class="fa-solid fa-trash"></i> Delete
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