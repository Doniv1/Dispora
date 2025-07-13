

// The DOM elements you wish to replace with Tagify
var input1 = document.querySelector("#keyword_website");

// Initialize Tagify components on the above inputs
new Tagify(input1);

$(function () {

    $('#kt_modal_form').on('shown.bs.modal', function () {
        $('#select_type').select2({ dropdownParent: $('#form_form') });
        
    });

});


function tambah_contact(element) {
    const div = document.getElementById('parent_phone');
    const child = div.childElementCount;
    var newCount = (child + 1);

    var html ='';
    html += '<div class="input-group mb-3" id="phone-frame-'+newCount+'">';
    html += '<input type="text" name="name_phone['+newCount+']" class="form-control form-control-lg" placeholder="Nama teller (Opsional)" autocomplete="off"/>';
    html += '<span class="input-group-text" id="phone-62-'+newCount+'">+62</span>';
    html += '<input id="phone" type="text" name="phone['+newCount+']" class="form-control form-control-lg" placeholder="Masukkan nomor telepon" autocomplete="off" aria-describedby="phone-62-'+newCount+'"/>';
    html += '<button class="btn btn-light-danger" type="button" onclick="hapus_contact('+newCount+')">';
    html += ' <i class="fa fa-trash fs-4"></i>';
    html += '</button></div>';

    div.insertAdjacentHTML('beforeend',html);
}


function hapus_contact(num) {
    $('#phone-frame-'+num).remove();
}



function tambah_email(element) {
    const div = document.getElementById('parent_email');
    const child = div.childElementCount;
    var newCount = (child + 1);

    var html ='';
    html += '<div class="input-group mb-3" id="email-frame-'+newCount+'">';
    html += '<input id="email" type="text" name="email['+newCount+']" class="form-control form-control-lg" placeholder="Masukkan alamat email" autocomplete="off"/>';
    html += '<button class="btn btn-light-danger" type="button" onclick="hapus_email('+newCount+')">';
    html += ' <i class="fa fa-trash fs-4"></i>';
    html += '</button></div>';

    div.insertAdjacentHTML('beforeend',html);
}


function hapus_email(num) {
    $('#email-frame-'+num).remove();
}


var title = $('#title_modal_sosmed').data('title').split('|');
var title_form = $('#title_modal_form').data('title').split('|');
function ubah_sosmed(element, id) {
    var form = document.getElementById('form_sosmed');
    $('#title_modal_sosmed').text(title[1]);
    form.setAttribute('action', BASE_URL + '/setting/update/sosmed');
    $.ajax({
        url: BASE_URL + '/single/sosmeds/id_sosmed',
        method: 'POST',
        data: { 
            _token : csrf_token,
            id: id
         },
        dataType: 'json',
        success: function (data) {
            $('#form_sosmed input[name="id_sosmed"]').val(data.id_sosmed);
            $('#form_sosmed input[name="name"]').val(data.name);
            $('#form_sosmed input[name="icon"]').val(data.icon);
        }
    })
}

function tambah_sosmed() {
    var form = document.getElementById('form_sosmed');
    form.setAttribute('action', BASE_URL + '/setting/insert/sosmed');
    $('#title_modal_sosmed').text(title[0]);
    $('#form_sosmed input').val('');
}

function ubah_form(element, id) {
    var form = document.getElementById('form_form');
    $('#title_modal_form').text(title[1]);
    form.setAttribute('action', BASE_URL + '/setting/update/form');
    $.ajax({
        url: BASE_URL + '/single/forms/id_form',
        method: 'POST',
        data: { 
            _token : csrf_token,
            id: id
         },
        dataType: 'json',
        success: function (data) {
            $('#form_form input[name="id_form"]').val(data.id_form);
            $('#form_form input[name="field"]').val(data.field);
            $('#form_form select[name="type"]').val(data.type);
            $('#form_form select[name="type"]').trigger('change');
        }
    })
}

function tambah_form() {
    var form = document.getElementById('form_form');
    form.setAttribute('action', BASE_URL + '/setting/insert/form');
    $('#title_modal_form').text(title[0]);
    $('#form_form input').val('');
    $('#form_form select').val('');
    $('#form_form select').trigger('change');
}


function set_url_params(pageValue) {
  const url = new URL(window.location.href);
  url.searchParams.set('page', pageValue);
  window.history.pushState({}, '', url);
}


ClassicEditor.create(document.querySelector('#about'), {
    toolbar: {
        items: CKEditor_tool,
    },
    alignment: {
        options: ['left', 'center', 'right', 'justify'],
    },
    table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'],
    },
    link: {
        addTargetToExternalLinks: true, // Add 'target="_blank"' for external links
        decorators: {
            openInNewTab: {
                mode: 'manual',
                label: 'Open in a new tab',
                attributes: {
                    target: '_blank',
                    rel: 'noopener noreferrer'
                }
            }
        }
    },
    fontColor: {
        colors: font_color,
        columns: 5,
        documentColors: 10,
        colorPicker: true,
    },
    fontBackgroundColor: {
        colors: font_color,
    },
    language: 'en',
    licenseKey: '',
}).then((editor) => {
    myabout = editor;
})
.catch((error) => {
    console.error(error);
});

let formTable;

document.addEventListener('DOMContentLoaded', function () {

    formTable = initGlobalDatatable('#table_form',null, {
        // enableRowReorder: true,
        // rowReorderDataSrc: 0,
    });

    // Trigger reload on each filter
    document.querySelectorAll('.table-filter').forEach(el => {
        el.addEventListener('change', function () {
            if (formTable) formTable.ajax.reload();
        });
    });

    $('#table_form').on('row-reorder.dt', function (e, diff, edit) {
        const hasil = [];

        document.querySelectorAll('#table_form tbody tr.order-table').forEach((tr, index) => {
            const id = tr.getAttribute('data-id');
            hasil.push({
                id: id,
                urutan: index
            });
        });

        // Simpan ke server
        $.post(BASE_URL + '/table/form/order', {
            _token: csrf_token,
            result: hasil
        });
    });

});
