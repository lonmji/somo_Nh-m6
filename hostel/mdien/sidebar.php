<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$navItems = [
    ['href' => 'registration.php',     'icon' => 'fa-user-plus',    'label' => 'Register Student'],
    ['href' => 'manage-students.php',  'icon' => 'fa-users',        'label' => 'Manage Students'],
    ['href' => 'manage-rooms.php',     'icon' => 'fa-door-open',    'label' => 'Manage Rooms'],
    ['href' => 'manage-courses.php',   'icon' => 'fa-book',         'label' => 'Manage Courses'],
    ['href' => 'new-complaints.php',   'icon' => 'fa-comment-dots', 'label' => 'Complaints'],
];
?>
<aside class="w-64 bg-indigo-900 text-white flex flex-col min-h-screen">
    <div class="p-6 border-b border-indigo-700">
        <h1 class="text-xl font-bold tracking-wide">
            <i class="fa-solid fa-building-columns mr-2"></i>Hostel Admin
        </h1>
    </div>
    <nav class="flex-1 p-4 space-y-1">
        <?php foreach ($navItems as $item): ?>
            <?php $active = ($currentPage === $item['href']) ? 'bg-indigo-700' : 'hover:bg-indigo-800'; ?>
            <a href="<?= $item['href'] ?>"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition <?= $active ?>">
                <i class="fa-solid <?= $item['icon'] ?> w-4 text-center"></i>
                <?= $item['label'] ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <div class="p-4 border-t border-indigo-700">
        <a href="logout.php"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-red-700 transition">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
    </div>
</aside>