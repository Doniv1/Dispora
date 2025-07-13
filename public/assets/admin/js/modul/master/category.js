let categoryTable;
let currentFilterStatus = '';

document.addEventListener('DOMContentLoaded', function () {
    categoryTable = initGlobalDatatable('#table_category', function () {
        return {
            filter_status: currentFilterStatus
        };
    });

    // Trigger reload on each filter
    document.querySelectorAll('.table-filter').forEach(el => {
        el.addEventListener('change', function () {
            if (categoryTable) categoryTable.ajax.reload();
        });
    });
});



// Trigger reload saat filter diubah
function filter_status(element) {
    currentFilterStatus = element.value;
    if (categoryTable) {
        categoryTable.ajax.reload();
    }
}

var title = $('#title_modal').data('title').split('|');

function ubah_data(element, id) {
    var form = document.getElementById('form_category');
    $('#title_modal').text(title[0]);
    form.setAttribute('action', BASE_URL + '/master/category/update');
    $.ajax({
        url: BASE_URL + '/single/categories/id_category',
        method: form.method,
        data: { 
            _token : csrf_token,
            id: id 
        },
        dataType: 'json',
        success: function (data) {
            $('input[name="id_category"]').val(data.id_category);
            $('input[name="name"]').val(data.name);
        }
    })
}

function tambah_data() {
    var form = document.getElementById('form_category');
    form.setAttribute('action', BASE_URL + '/master/category/insert');
    $('#title_modal').text(title[1]);
    $('#form_category input[type="text"]').val('');
    $('#form_category input[type="email"]').val('');
    $('#form_category label.password').addClass('required');
    $('#form_category textarea').val('');
}


