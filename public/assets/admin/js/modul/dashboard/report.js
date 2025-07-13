let reportTable;

document.addEventListener('DOMContentLoaded', function () {
    reportTable = initGlobalDatatable('#table_report');

    // Trigger reload on each filter
    document.querySelectorAll('.table-filter').forEach(el => {
        el.addEventListener('change', function () {
            if (reportTable) reportTable.ajax.reload();
        });
    });
});

function detail_data(id) {
    $.ajax({
        url: BASE_URL + '/detail/pengaduan',
        method: 'POST',
        data: { 
            _token : csrf_token,
            id: id 
        },
        cache : false,
        success: function (msg) {
            $('#display_detail').html(msg);
        }
    })
}



