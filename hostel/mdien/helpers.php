<?php
declare(strict_types=1);

function sanitize(string $value): string {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function redirect(string $url, string $message = '', string $type = 'success'): void {
    if ($message) {
        $_SESSION['flash'] = ['msg' => $message, 'type' => $type];
    }
    header("Location: $url");
    exit;
}

function flashMessage(): string {
    if (!isset($_SESSION['flash'])) return '';
    ['msg' => $msg, 'type' => $type] = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $color = $type === 'error' ? 'red' : 'green';
    return "<div class='mb-4 p-3 rounded-lg bg-{$color}-100 text-{$color}-800 text-sm'>{$msg}</div>";
}

function badge(string $status): string {
    $map = [
        'New'      => 'bg-blue-100 text-blue-700',
        'Pending'  => 'bg-yellow-100 text-yellow-700',
        'Resolved' => 'bg-green-100 text-green-700',
    ];
    $class = $map[$status] ?? 'bg-gray-100 text-gray-700';
    return "<span class='px-2 py-1 rounded-full text-xs font-medium {$class}'>{$status}</span>";
}