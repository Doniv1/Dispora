


var title = $('#title_modal').data('title').split('|');
var image = document.getElementById('display_image');
function ubah_data(element, id) {
    var foto = $(element).data('image');
    var form = document.getElementById('form_banner');
    $('#title_modal').text(title[0]);
    form.setAttribute('action', BASE_URL + '/master/banner/update');
    $.ajax({
        url: BASE_URL + '/single/banners/id_banner',
        method: form.method,
        data: { 
            _token : csrf_token,
            id: id 
        },
        dataType: 'json',
        success: function (data) {
            $('.checkbox-setup').prop('checked',false);
            image.style.backgroundImage = "url('" + foto + "')";
            $('input[name="id_banner"]').val(data.id_banner);
            $('input[name="title"]').val(data.title);
            $('textarea[name="description"]').val(data.description);
             $('input[name="name_image"]').val(data.image);
        }
    })
}

function tambah_data() {
    var form = document.getElementById('form_banner');
    form.setAttribute('action', BASE_URL + '/master/banner/insert');
    image.style.backgroundImage = "url('" + base_foto + "')";
    $('#title_modal').text(title[1]);
    $('#form_banner input[type="text"]').val('');
    $('#form_banner input[type="email"]').val('');
    $('#form_banner textarea').val('');
}


let bannerTable;
let currentFilterStatus = '';
let currentFilterCategory = '';

document.addEventListener('DOMContentLoaded', function () {
    bannerTable = initGlobalDatatable('#table_banner', function () {
        return {
            filter_status: currentFilterStatus,
            filter_category: currentFilterCategory
        };
    });

    // Trigger reload on each filter
    document.querySelectorAll('.table-filter').forEach(el => {
        el.addEventListener('change', function () {
            if (bannerTable) bannerTable.ajax.reload();
        });
    });
});



// Trigger reload saat filter diubah
function filter_status(element) {
    currentFilterStatus = element.value;
    if (bannerTable) {
        bannerTable.ajax.reload();
    }
}


function filter_category(element) {
    currentFilterCategory = element.value;
    if (bannerTable) {
        bannerTable.ajax.reload();
    }
}