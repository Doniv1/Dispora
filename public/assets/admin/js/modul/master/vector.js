let vectorTable;
let currentFilterStatus = '';

document.addEventListener('DOMContentLoaded', function () {
    vectorTable = initGlobalDatatable('#table_vector', function () {
        return {
            filter_status: currentFilterStatus
        };
    });

    // Trigger reload on each filter
    document.querySelectorAll('.table-filter').forEach(el => {
        el.addEventListener('change', function () {
            if (vectorTable) vectorTable.ajax.reload();
        });
    });
});



// Trigger reload saat filter diubah
function filter_status(element) {
    currentFilterStatus = element.value;
    if (vectorTable) {
        vectorTable.ajax.reload();
    }
}

var title = $('#title_modal').data('title').split('|');

function ubah_data(element, id) {
    var form = document.getElementById('form_vector');
    $('#title_modal').text(title[0]);
    form.setAttribute('action', BASE_URL + '/master/vector/update');
    $.ajax({
        url: BASE_URL + '/single/categories/id_vector',
        method: form.method,
        data: { 
            _token : csrf_token,
            id: id 
        },
        dataType: 'json',
        success: function (data) {
            $('input[name="id_vector"]').val(data.id_vector);
            $('input[name="name"]').val(data.name);
        }
    })
}

function tambah_data() {
    var form = document.getElementById('form_vector');
    form.setAttribute('action', BASE_URL + '/master/vector/insert');
    $('#title_modal').text(title[1]);
    $('#form_vector input[type="text"]').val('');
    $('#form_vector input[type="email"]').val('');
    $('#form_vector label.password').addClass('required');
    $('#form_vector textarea').val('');
}


