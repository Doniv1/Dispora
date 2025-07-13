let adminTable;
let currentFilterStatus = '';

document.addEventListener('DOMContentLoaded', function () {
    adminTable = initGlobalDatatable('#table_admin', function () {
        return {
            filter_status: currentFilterStatus,
        };
    });

    // Trigger reload on each filter
    document.querySelectorAll('.table-filter').forEach(el => {
        el.addEventListener('change', function () {
            if (adminTable) adminTable.ajax.reload();
        });
    });
});



// Trigger reload saat filter diubah
function filter_status(element) {
    currentFilterStatus = element.value;
    if (adminTable) {
        adminTable.ajax.reload();
    }
}



var image = document.getElementById('display_image');
var title = $('#title_modal').data('title').split('|');
$(function () {

    $('.hps_image').on('click', function () {
        // console.log('hapus');
        $('input[name=name_image]').val("");
    });

    

});

function ubah_data(element, id) {
    var foto = $(element).data('image');
    var form = document.getElementById('form_admin');
    $('#title_modal').text(title[0]);
    form.setAttribute('action', BASE_URL + '/master/user/update');
    $.ajax({
        url: BASE_URL + '/single/users/id_user',
        method: form.method,
        data: { 
            _token : csrf_token,
            id: id 
        },
        dataType: 'json',
        success: function (data) {
            image.style.backgroundImage = "url('" + foto + "')";
            $('input[name="id_user"]').val(data.id_user);
            $('input[name="role"]').val(data.role);
            $('input[name="name"]').val(data.name);
            $('input[name="phone"]').val(data.phone);
            $('input[name="email"]').val(data.email);
            $('input[name="name_image"]').val(data.image);
            $('#form_admin label.password').removeClass('required');
        }
    })
}

function tambah_data() {
    var form = document.getElementById('form_admin');
    form.setAttribute('action', BASE_URL + '/master/user/insert');
    $('#title_modal').text(title[1]);
    image.style.backgroundImage = "url('" + user_base_foto + "')";
    $('#form_admin input[type="text"]').val('');
    $('#form_admin input[type="email"]').val('');
    $('#form_admin label.password').addClass('required');
    $('#form_admin textarea').val('');
}


