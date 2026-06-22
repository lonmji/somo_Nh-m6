document.addEventListener('DOMContentLoaded', () => {
    // Init DataTables on any table with class .data-table
    if (typeof $.fn.DataTable !== 'undefined') {
        $('.data-table').DataTable({
            pageLength: 10,
            language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries' }
        });
    }

    // Auto-hide flash messages after 3s
    const flash = document.getElementById('flash-message');
    if (flash) setTimeout(() => flash.remove(), 3000);
});