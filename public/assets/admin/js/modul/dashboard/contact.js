let contactTable;
let currentFilterStatus = '';

document.addEventListener('DOMContentLoaded', function () {
    contactTable = initGlobalDatatable('#table_contact');

    // Trigger reload on each filter
    document.querySelectorAll('.table-filter').forEach(el => {
        el.addEventListener('change', function () {
            if (contactTable) contactTable.ajax.reload();
        });
    });
});



