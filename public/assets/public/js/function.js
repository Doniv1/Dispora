$(function () {
   
    $('.hps_foto').on('click', function () {
        // console.log('hapus');
        $('input[name=nama_foto]').val("");
    });

    $('.edt_foto').on('click', function () {
        $('input[name=nama_foto]').val("");
    })

    $('.hps_foto_dinamis').on('click', function () {
        // console.log('hapus');
        var name = $(this).data('name');
        $('input[name='+name+']').val("");
    });

    $('.edt_foto_dinamis').on('click', function () {
        var name = $(this).data('name');
        $('input[name='+name+']').val("");
    })

    $('#cetak_excel').on('click', function () {
        $('#cetak_excel').data('click');
        var href = $(this).attr('href');
        console.log(href);
    })


});


function refreshPage() {
    location.reload(); // Melakukan refresh halaman
}
function hideye(element,id) {
    var value = $(element).val();
    // console.log(value);
    if (value == '') {
        $(id).addClass('d-none');
    }else{
        $(id).removeClass('d-none');
    }
}
function formposition(id) {
    const forms = document.querySelectorAll('form');
    let position = -1;
    var id = id.replace(/#/g, '');
    forms.forEach((form, index) => {
        
        if (form.id === id) {
            position = index; // Posisi form (1-based index)
        }
    });
    if (position == -1) {
        console.error('FORM DOESN`T EXISTS');
    }
    return position;
}
function submit_form(element, id_form, urlplus = '', draging = false, confirm = false,loader = '',other_url ='') {

    var num = formposition(id_form);
    if (confirm == true) {
        var message = $(element).data('message');
        if (!message) {
            message = confirm_action;
        }

        Swal.fire({
            html: message,
            icon: 'question',
            showCancelButton: true,
            buttonsStyling: !1,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: css_btn_confirm,
                cancelButton: css_btn_cancel
            },
            reverseButtons: true
        }).then((function (t) {
            if (t.isConfirmed) {
                proses_form(element, id_form, num, urlplus, draging,loader, other_url)
            }
        }));
    } else {
        proses_form(element, id_form, num, urlplus, draging,loader, other_url)
    }
}

function proses_form(element, id_form, num, urlplus = '', draging = false,loader = '',other_url ='') {
    var typeloader = $(element).data('loader'); 
    if (draging == true) {
        var drag = document.getElementById('sistem_drag');
        var filter = document.getElementById('sistem_filter');
    }
    // console.log('ok');
    var text_button = document.getElementById(element.id).innerHTML;
    if (other_url != '') {
        var url = other_url;
    }else{
        var url = $(id_form).attr('action') + urlplus;
    }
    
    var method = $(id_form).attr('method');
    // console.log(url);
    var form = $('form')[num];
    var form_data = new FormData(form);
    var editor = $(element).data('editor');
    var arr_status = $(element).data('arr_status');
    var editor_array = $(element).data('editor_array');
    if (editor) {
        let array = editor.split(",");
        for (var a = 0; a < array.length; a++) {
            form_data.append(array[a], window['my'+array[a]].getData($("#"+array[a]).val()));
            // console.log(array[a],window['my'+array[a]].getData($("#"+array[a]).val()));
        }
       
    }

    if (editor_array) {
        let name_arru = arr_status.split(",");
        let arru = editor_array.split(",");
        for (var a = 0; a < arru.length; a++) {
            var final_arru = arru[a].split('|');
            for (var b = 0; b < final_arru.length; b++) {
                form_data.append(name_arru[a]+'[]', window['my'+final_arru[b]].getData($("#"+final_arru[b]).val()));
            }
        }
       
    }
    form_data.append('_token',csrf_token);

    console.log(url, method, form, form_data);
    $.ajax({
        url: url,
        method: method,
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function () {
            if (loader == '') {
                loader = 'Loading...';
            }
            $('#' + element.id).prop('disabled', true);
            // console.log(typeloader);
            // console.log(!isEmpty(typeloader));
            if (!isEmpty(typeloader)) {
                if (typeloader == 'small' || !typeloader) {
                    $('#' + element.id).html('<div class="spinner-border text-light" role="status">\
  <span class="visually-hidden">Loading...</span>\
</div>');
                }else{
                    showLoading(loader)
                }
            }else{
                $('#' + element.id).html(loader);
            }
            


        },
        success: function (data) {
            $('.fadedin').remove();
            if (data.load != null) {
                if (data.load.loading_page == true) {
                    // KTApp.showPageLoading();
                    // console.log('loading');
                    for (var a = 0; a < data.load.length; a++) {
                        $(data.load[a].parent).load(data.load[a].reload);
                       
                    }
                }else{
                    for (var a = 0; a < data.load.length; a++) {
                        $(data.load[a].parent).load(data.load[a].reload);
                    }
                }
                
                
                // document.body.style.overflowY = 'scroll'
            }

            if (data.datatable) {
                var table = $('#'+data.datatable).DataTable();
                table.ajax.reload(null, false);
            }

            if (data.select_manipulator != null) {
                for (var i = 0; i < data.select_manipulator.length; i++) {
                    if (data.select_manipulator[i].action == 'remove') {
                        $(data.select_manipulator[i].id).find('option[value="'+data.select_manipulator[i].value+'"]').remove();
                        $(data.select_manipulator[i].id).trigger('change'); // Update Select2
                    }else if(data.select_manipulator[i].action == 'edit'){
                        console.log('edit');
                        $(data.select_manipulator[i].id).find('option[value="'+data.select_manipulator[i].value+'"]').text(data.select_manipulator[i].text);
                        $(data.select_manipulator[i].id).trigger('change'); // Update Select2
                    }else if(data.select_manipulator[i].action == 'add'){
                        console.log('add');
                        $(data.select_manipulator[i].id).append('<option value="'+data.select_manipulator[i].value+'">'+data.select_manipulator[i].text+'</option>');
                        $(data.select_manipulator[i].id).trigger('change'); // Update Select2
                    }

                    if (data.select_manipulator[i].choosed != null && data.select_manipulator[i].choosed == true) {
                        console.log('bisa sayang');
                        $(data.select_manipulator[i].id).val(data.select_manipulator[i].value);
                        $(data.select_manipulator[i].id).trigger('change');
                    }
                }
            }
            if (data.status == 200 || data.status == true) {
                if (draging == true) {
                    drag.classList.add('d-none');
                    filter.classList.remove('d-none');
                }
                var icon = 'success';
            } else {
                var icon = 'warning';
            }

            $('#' + element.id).prop('disabled', false);
            if (!isEmpty(typeloader)) {
                if (typeloader == 'big') {
                    if (data.reload) {
                        if (data.alert) {
                            var wdwd = data.alert.width;
                            if (!wdwd) {
                                wdwd = null;
                            }
                            sessionStorage.setItem('isReload', 'true');
                            sessionStorage.setItem('alert_icon', icon);
                            sessionStorage.setItem('alert_message', data.alert.message);
                            sessionStorage.setItem('alert_width', wdwd);
                        }
                    
                        custom_reload();
                    }else{
                        hideLoading();
                        if (data.alert) {
                            var wdwd = data.alert.width;
                            if (!wdwd) {
                                wdwd = null;
                            }
                            Swal.fire({
                                html: data.alert.message,
                                icon: icon,
                                buttonsStyling: !1,
                                confirmButtonText: 'Lanjutkan',
                                customClass: {
                                    confirmButton: css_btn_confirm
                                },
                                width : wdwd
                            })
                        }
                    
                    }
                }else{
                    $('#' + element.id).html(text_button);
                }
            }else{
                $('#' + element.id).html(text_button);
            }

            

            if (data.page_to) {
                var page_active = false;
                var menu = false;
                if (data.page_to.active == true) {
                    page_active = data.page_to.active;
                    menu = true;
                }
                page_red_to(data.page_to.page,page_active,menu);
            }

            if (data.add_class) {
                const ruw = data.add_class.length;
                for (var i = 0; i < ruw; i++) {
                    $(data.add_class[i].base).addClass(data.add_class[i].class);
                }
            }

            if (data.remove_class) {
                const riw = data.remove_class.length;
                for (var i = 0; i < riw; i++) {
                    $(data.remove_class[i].base).removeClass(data.remove_class[i].class);
                }
            }
            if (data.input) {
                    if (data.input.id) {
                         if (data.input.password) {
                            $(data.input.id).find("input[type=password]").val("");
                        }
                        if (data.input.text) {
                            $(data.input.id).find("input[type=text]").val("");
                        }
                        if (data.input.number) {
                            $(data.input.id).find("input[type=number]").val("");
                        }
                        if (data.input.textarea) {
                            $(data.input.id).find("textarea").val("");
                        }

                        if (data.input.all) {
                            $(data.input.id + ' input[type=text]').val("");
                            $(data.input.id + ' input[type=password]').val("");
                            $(data.input.id + ' input[type=number]').val("");
                            $(data.input.id + ' select').val("");
                            $(data.input.id + ' select').trigger("change");
                            $(data.input.id + ' textarea').val("");
                        }
                    }else{
                         if (data.input.password) {
                            $(id_form).find("input[type=password]").val("");
                        }
                        if (data.input.text) {
                            $(id_form).find("input[type=text]").val("");
                        }
                        if (data.input.number) {
                            $(id_form).find("input[type=number]").val("");
                        }
                        if (data.input.textarea) {
                            $(id_form).find("textarea").val("");
                        }
                        if (data.input.file) {
                            $(id_form).find("input[type=file]").value = null;
                        }

                        if (data.input.all) {
                            $(id_form + ' input[type=text]').val("");
                            $(id_form + ' input[type=email]').val("");
                            $(id_form + ' input[type=password]').val("");
                            $(id_form + ' input[type=number]').val("");
                            $(id_form + ' input[type=file]').value = null;
                            $(id_form + ' select').val("");
                            $(id_form + ' select').trigger("change");
                            $(id_form + ' textarea').val("");
                        }
                    }
                   

                }

            if (data.element != null) {
                const row = data.element.length;
                for (var i = 0; i < row; i++) {
                    $(data.element[i].row).html(data.element[i].value);
                }
            }
            if (data.modal != null) {
                $(data.modal.id).modal(data.modal.action);
            }

            if (data.trigger != null) {
                $(data.trigger.id).trigger(data.trigger.action);
            }
            if (data.canvas != null) {
                for (var a = 0; a < data.canvas.length; a++) {
                    $(data.canvas[a].id).offcanvas(data.canvas[a].action);
                }
            }
            var cek_ketersediaan = true;
            if (!isEmpty(typeloader)) {
                if (typeloader == 'small' || !typeloader) {
                    cek_ketersediaan = true;
                }else{
                    cek_ketersediaan = false;
                }
            }
            if (data.alert && cek_ketersediaan == true) {
                var wdwd = data.alert.width;
                            if (!wdwd) {
                                wdwd = null;
                            }
                Swal.fire({
                    html: data.alert.message,
                    icon: icon,
                    buttonsStyling: !1,
                    confirmButtonText: 'Lanjutkan',
                    customClass: {
                        confirmButton: css_btn_confirm
                    },
                    width : wdwd
                }).then(function () {
                    if (data.redirect) {
                        location.href = data.redirect;
                    }
                    if (data.reload == true && cek_ketersediaan == true) {
                        location.reload();
                    }
                    

                    // if (data.page_to != '') {
                    //     user_page_to(data.page_to);
                    // }
                    if (data.element != null) {
                        const row = data.element.length;
                        for (var i = 0; i < row; i++) {
                            $(data.element[i].row).html(data.element[i].value);
                        }
                    }
                    if (data.remove != null) {
                        const rowk = data.remove.length;
                        for (var i = 0; i < rowk; i++) {
                            $(data.remove[i]).remove();
                        }                    }
                    if (data.tumpuk_bawah != null) {
                        const roww = data.tumpuk_bawah.length;
                        for (var i = 0; i < roww; i++) {
                            $(data.tumpuk_bawah[i].parent).append(data.tumpuk_bawah[i].value);
                        }
                    }
                    if (data.tumpuk_atas != null) {
                        const rowwu = data.tumpuk_atas.length;
                        for (var i = 0; i < rowwu; i++) {
                            $(data.tumpuk_atas[i].parent).append(data.tumpuk_atas[i].value);
                        }
                    }
                    
                });
            } else {
                if (data.required) {
                    // console.log(data.required);
                    const array = data.required.length;
                    for (var i = 0; i < array; i++) {
                        $('#' + data.required[i][0]).append('<span class="text-danger size-12 fadedin">' + data.required[i][1] + '</span>');
                        // console.log(data.required[i][0]);
                    }

                }

                if (data.redirect && cek_ketersediaan == true) {
                    location.href = data.redirect;
                }
                if (data.modal != null) {
                    $(data.modal.id).modal(data.modal.action);
                }

                if (data.reload == true && cek_ketersediaan == true) {
                    location.reload();
                }
            }
        }
    });
}

function hapus_data(element, e, id, db = 'users', primary = '') {
    e.preventDefault();
    var message = 'Are you sure you want to delete this data? Deleted data cannot be recovered.';
    var reload = '';
    if ($(element).data('reload')) {
        reload = $(element).data('reload');
    }
    var datatable = '';
    if ($(element).data('datatable')) {
        datatable = $(element).data('datatable');
    }

    var permanent = true;
    if ($(element).data('permanent')) {
        permanent = $(element).data('permanent');
    }
    const icon = 'question';
    Swal.fire({
        html: message,
        icon: icon,
        showCancelButton: true,
        buttonsStyling: !1,
        confirmButtonText: 'Lanjutkan',
        cancelButtonText: 'Batal',
        customClass: {
            confirmButton: css_btn_confirm,
            cancelButton: css_btn_cancel
        },
        reverseButtons: true
    }).then((function (t) {
        if (t.isConfirmed) {
            $.ajax({
                url: BASE_URL + '/delete',
                method: 'POST',
                data: { 
                    _token : csrf_token,
                    id: id, db : db,
                    primary : primary,
                    reload:reload,
                    permanent : permanent},
                cache: false,
                dataType: 'json',
                beforeSend: function(){
                    if (reload == 'big') {
                        showLoading('Loading...');
                    }
                },
                success: function (data) {
                    // console.log(data);
                    if (data.status == 200 || data.status == true) {
                        if (data.alert) {
                            if (reload == 'big') {
                                if (data.alert) {
                                    var wdwd = data.alert.width;
                                    if (!wdwd) {
                                        wdwd = null;
                                    }
                                    sessionStorage.setItem('isReload', 'true');
                                    sessionStorage.setItem('alert_icon', 'success');
                                    sessionStorage.setItem('alert_message', data.alert.message);
                                    sessionStorage.setItem('alert_width', wdwd);
                                }
                            
                                custom_reload();
                            }else{
                                var wdwd = data.alert.width;
                                if (!wdwd) {
                                    wdwd = null;
                                }
                                Swal.fire({
                                    html: data.alert.message,
                                    icon: 'success',
                                    buttonsStyling: !1,
                                    confirmButtonText: 'Lanjutkan',
                                    customClass: { confirmButton: css_btn_confirm },
                                    width : wdwd
                                }).then((function (t) {
                                    if (t.isConfirmed) {
                                        if (datatable) {
                                            var table = $('#'+datatable).DataTable();
                                            table.ajax.reload(null, false);
                                        }

                                        if (data.select_manipulator != null) {
                                            for (var i = 0; i < data.select_manipulator.length; i++) {
                                                if (data.select_manipulator[i].action == 'remove') {
                                                    $(data.select_manipulator[i].id).find('option[value="'+data.select_manipulator[i].value+'"]').remove();
                                                    $(data.select_manipulator[i].id).trigger('change'); // Update Select2
                                                }else if(data.select_manipulator[i].action == 'edit'){
                                                    console.log('edit');
                                                    $(data.select_manipulator[i].id).find('option[value="'+data.select_manipulator[i].value+'"]').text(data.select_manipulator[i].text);
                                                    $(data.select_manipulator[i].id).trigger('change'); // Update Select2
                                                }else if(data.select_manipulator[i].action == 'add'){
                                                    console.log('add');
                                                    $(data.select_manipulator[i].id).append('<option value="'+data.select_manipulator[i].value+'">'+data.select_manipulator[i].text+'</option>');
                                                    $(data.select_manipulator[i].id).trigger('change'); // Update Select2
                                                }
                                            }
                                        }
                                    }
                                }));
                            }
                            
                        }else{
                            if (data.table_reload) {
                                var table = $('#'+data.table_reload).DataTable();
                                table.ajax.reload(null, false);
                            }

                            if (data.select_manipulator != null) {
                                for (var i = 0; i < data.select_manipulator.length; i++) {
                                    if (data.select_manipulator[i].action == 'remove') {
                                        $(data.select_manipulator[i].id).find('option[value="'+data.select_manipulator[i].value+'"]').remove();
                                        $(data.select_manipulator[i].id).trigger('change'); // Update Select2
                                    }else if(data.select_manipulator[i].action == 'edit'){
                                        console.log('edit');
                                        $(data.select_manipulator[i].id).find('option[value="'+data.select_manipulator[i].value+'"]').text(data.select_manipulator[i].text);
                                        $(data.select_manipulator[i].id).trigger('change'); // Update Select2
                                    }else if(data.select_manipulator[i].action == 'add'){
                                        console.log('add');
                                        $(data.select_manipulator[i].id).append('<option value="'+data.select_manipulator[i].value+'">'+data.select_manipulator[i].text+'</option>');
                                        $(data.select_manipulator[i].id).trigger('change'); // Update Select2
                                    }
                                }
                            }
                        }
                        
                        
                    } else {
                        var wdwd = data.alert.width;
                        if (!wdwd) {
                            wdwd = null;
                        }
                        Swal.fire({
                            html: data.alert.message,
                            icon: 'warning',
                            buttonsStyling: !1,
                            width : wdwd,
                            confirmButtonText: 'Lanjutkan',
                            customClass: { confirmButton: css_btn_confirm }
                        });
                    }
                }
            })
        }
    }))
}




function hapus_data_noreload(element, id, url = '',parent_remove = '',pengganti = '') {
    // e.preventDefault();
    var text = $(element).data('text');
    if (text) {
        var message = text;
    }else{
        var message = 'Are you sure you want to delete this data? Deleted data cannot be recovered.';
    }
    
    const icon = 'question';
    Swal.fire({
        html: message,
        icon: icon,
        showCancelButton: true,
        buttonsStyling: !1,
        confirmButtonText: 'Lanjutkan',
        cancelButtonText: 'Batal',
        customClass: {
            confirmButton: css_btn_confirm,
            cancelButton: css_btn_cancel
        },
        reverseButtons: true
    }).then((function (t) {
        if (t.isConfirmed) {
            $.ajax({
                url: BASE_URL + url,
                method: 'POST',
                data: { id: id },
                cache: false,
                dataType: 'json',
                success: function (data) {
                    // console.log(data);
                    if (data.status == 200 || data.status == true) {
                        if (parent_remove != '') {
                           $(parent_remove).remove(); 
                        }else{
                            $(element).remove();
                        }
                        alert_noreload('success','Data berhasil dihapus!');

                        if (data.checkbox) {
                            var get = $(data.checkbox.pane).attr('data-except');
                            var arr = [];
                            if (get) {
                                var cek  = get.indexOf('|');
                                if (cek) {
                                    var arr2 = get.split('|');
                                    for (let i = 0; i < arr2.length; i++) {
                                        arr.push(arr2[i]);
                                    }
                                }
                            }
                            
                            arr.push(data.checkbox.id);
                            var final = arr.join('|')
                            $(data.checkbox.pane).attr('data-except',final);
                        }

                    } else {
                        var wdwd = data.alert.width;
                        if (!wdwd) {
                            wdwd = null;
                        }
                        Swal.fire({
                            html: data.alert.message,
                            icon: 'warning',
                            buttonsStyling: !1,
                            width : wdwd,
                            confirmButtonText: 'Lanjutkan',
                            customClass: { confirmButton: css_btn_confirm }
                        });
                    }
                }
            })
        }
    }))
}


function clearFileInput(ctrl) {
  try {
    ctrl.value = null;
  } catch(ex) { }
  if (ctrl.value) {
    ctrl.parentNode.replaceChild(ctrl.cloneNode(true), ctrl);
  }
}

function preview_image(element) {
    let imageUrl = "";

    // Jika element adalah img langsung ambil src
    if (element.tagName === "IMG") {
      imageUrl = element.src;
    } else {
      // Jika div, ambil dari background-image
      const bg = window.getComputedStyle(element).backgroundImage;
      imageUrl = bg.slice(5, -2); // hapus url("...") jadi ambil tengahnya
    }

    // Buat overlay
    const overlay = document.createElement('div');
    overlay.className = 'preview-overlay';
    overlay.onclick = () => overlay.remove(); // Klik overlay untuk menutup

    const img = document.createElement('img');
    img.src = imageUrl;

    overlay.appendChild(img);
    document.body.appendChild(overlay);
  }

function preview_embed(element,  url) {
    var modal = document.getElementById("modal_preview_all");
    var modalEmbed = document.getElementById("modal_preview_embed");
    var modalImg = document.getElementById("modal_preview_image");
    var captionText = document.getElementById("modal_preview_caption");
    
    modalImg.classList.add('hidin');
    modalImg.classList.remove('showin');
    modalEmbed.classList.remove('hidin');
    modalEmbed.classList.add('showin');
    
    var capt = $(element).data('caption');
    modal.style.display = "block";
    if (url == '') {
        modalEmbed.src = element.src;
    }else{
        modalEmbed.src = url;
    }
    

    if (capt) {
        captionText.innerHTML = capt;
    }

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("modal_preview_close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() { 
        modal.style.display = "none";
    }
}

function switching(element, e, id) {
    console.log($(element).data('url'));
    var url = $(element).data('url');
    var primary = $(element).data('primary');
    e.preventDefault();
    const icon = 'question';
    if ($(element).is(':checked')) {
        var value = 'Y';
        var type = false;
        var message = ucfirst('Are you sure you want to open access to this data? Then the data will be accessible to the system.');
    } else {
        var value = 'N';
         var type = "textarea";
        var message = ucfirst('Are you sure you want to close access to this data? the data will not be able to access the system');
    }
    Swal.fire({
        text: message,
        icon: icon,
        input: type,
        inputAttributes: {
            autocapitalize: 'off',
            name: 'reason'
        },
        inputPlaceholder: 'Include a reason for turning off data',
        showCancelButton: true,
        buttonsStyling: !1,
        confirmButtonText: 'Lanjutkan',
        cancelButtonText: 'Batal',
        customClass: {
            confirmButton: css_btn_confirm,
            cancelButton: css_btn_cancel
        },
        reverseButtons: true
    }).then((function (t) {
        if (t.isConfirmed) {
            var reason = $('textarea[name=reason]').val();
            $.ajax({
                url: url,
                method: 'POST',
                data: { 
                    _token : csrf_token,
                    id: id, 
                    action: value, 
                    reason: reason ,
                    primary : primary
                },
                cache: false,
                dataType: 'json',
                success: function (data) {
                    // console.log(data);
                    if (data.status == 200 || data.status == true) {

                        if (value == 'Y') {
                            $('#switch-' + id).prop('checked', true);
                        } else {
                            $('#switch-' + id).prop('checked', false);
                        }

                        if (data.alert) {
                            var wdwd = data.alert.width;
                            if (!wdwd) {
                                wdwd = null;
                            }
                            Swal.fire({
                                html: data.alert.message,
                                icon: data.alert.icon,
                                width : wdwd,
                                buttonsStyling: !1,
                                confirmButtonText: 'Lanjutkan',
                                customClass: { confirmButton: css_btn_confirm }
                            });
                        }
                    } else {

                        if (value == 'Y') {
                            $('#switch-' + id).prop('checked', true);
                        } else {
                            $('#switch-' + id).prop('checked', false);
                        }
                        var wdwd = data.alert.width;
                        if (!wdwd) {
                            wdwd = null;
                        }
                        Swal.fire({
                            html: data.alert.message,
                            icon: data.alert.icon,
                            buttonsStyling: !1,
                            width : wdwd,
                            confirmButtonText: 'Lanjutkan',
                            customClass: { confirmButton: css_btn_confirm }
                        });
                    }
                }
            })
        } else {

            if (value == 'Y') {
                $('#switch-' + id).prop('checked', false);
            } else {
                $('#switch-' + id).prop('checked', true);
            }

        }
    }));

}

function switch_modal(id, id2) {
    // var scrollBarWidth = window.innerWidth - document.body.offsetWidth;
    // $('body').css({
    //     marginRight: scrollBarWidth,
    //     overflow: 'hidden'
    // });

    $('#' + id).modal('hide');
    $('#' + id2).modal('show');

    document.getElementById("main_body").style.paddingRight = "0px";
}


function confirm_alert(element, e, message = '', url = null, method = 'POST', data, checkbox = false) {
    var data_param = $(element).data();
    // console.log(data_param.id);
    var href = $(element).attr('href');
    e.preventDefault();
    const icon = 'question';
    Swal.fire({
        text: message,
        icon: icon,
        showCancelButton: true,
        buttonsStyling: !1,
        confirmButtonText: 'Lanjutkan',
        cancelButtonText: 'Batal',
        customClass: {
            confirmButton: css_btn_confirm,
            cancelButton: css_btn_cancel
        },
        reverseButtons: true
    }).then((function (t) {
        if (t.isConfirmed) {
            if (url != null) {
                $.ajax({
                    url: url,
                    method: method,
                    data: data,
                    cache: false,
                    dataType: 'json',
                    success: function (data) {
                        // console.log(data);
                        if (data.status == 200 || data.status == true) {
                            if (checkbox == true) {
                                $(this).prop('checked', true);
                            }
                            if (data.alert) {
                                var wdwd = data.alert.width;
                                if (!wdwd) {
                                    wdwd = null;
                                }
                                Swal.fire({
                                    html: data.alert.message,
                                    icon: data.alert.icon,
                                    width : wdwd,
                                    buttonsStyling: !1,
                                    confirmButtonText: 'Lanjutkan',
                                    customClass: { confirmButton: css_btn_confirm }
                                });
                            }
                            if (data.reload) {
                                location.reload();
                            }
                            if (data.redirect) {
                                location.href = data.redirect;
                            }
                        } else {
                            var wdwd = data.alert.width;
                            if (!wdwd) {
                                wdwd = null;
                            }
                            Swal.fire({
                                html: data.alert.message,
                                icon: 'warning',
                                width: wdwd,
                                buttonsStyling: !1,
                                confirmButtonText: 'Lanjutkan',
                            });
                        }
                    }
                })
            } else {
                var param = '';
                if (data_param) {
                    var i = 0;
                    Object.keys(data_param).forEach(key => {
                        i++;
                        if (i == 1) {
                            param += '?' + key + '=' + data_param[key];
                        } else {
                            param += '&' + key + '=' + data_param[key];
                        }
                    });
                }
                document.location.href = href + param;
            }
        }
    }))
}

function show_alert(icon,message) {
    Swal.fire({
        text: message,
        icon: icon,
        confirmButtonText: 'Ok',
        customClass: {
            confirmButton: css_btn_confirm,
        },
    })
}
function redirect(halaman) {
    location.href = BASE_URL + halaman;
}

function potong_string(text, limit) {
  if (text.length > limit) {
    return text.slice(0, limit); // Potong hingga charLimit dan tambahkan '...'
  }
  return text; // Jika panjang string <= charLimit, kembalikan teks asli
}

function set_max_long(element,batas = 0) {
    if (batas > 0) {
        var value = $(element).val();
        if (value.length > batas) {
            var hasil = potong_string(value,batas);
            // console.log(hasil);
            $(element).val(hasil);
            alert_noreload('warning','Text terlalu panjang, anda hanya di izinkan memasukan '+batas+' karakter')
        }
    }
    
    
}


function display_image(element,target,id_showin = '') {

    var id = $(element).prop('id');
    var file = document.getElementById(id).files[0];
    var reader  = new FileReader();
    reader.onload = function(e)  {
        $(target).prop('src',e.target.result);
     }
     // you have to declare the file loading
     reader.readAsDataURL(file);

     if (id_showin != '') {
        $(id_showin).removeClass('hidin');
        $(id_showin).addClass('showin');
     }
 }



function alert_show(element, icon, message){
    Swal.fire({
        html: message,
        icon: icon,
        buttonsStyling: !1,
        confirmButtonText: 'Lanjutkan',
        customClass: {
            confirmButton: css_btn_confirm
        }
    })
}

let alertCount = 0;

function alert_noreload(type = 'success',message) {
    const alertContainer = document.getElementById('alert-container-noreload');
    const alertSound = document.getElementById('alert-sound-noreload');

    // Play notification sound
    alertSound.currentTime = 0; // Reset sound to start
    alertSound.play();

    // Create a new alert element
    const alertBox = document.createElement('div');
    alertBox.classList.add('alert-noreload');
    alertBox.classList.add(type);
    alertBox.innerHTML = `
    <span>${message}</span>
    <button class="close-btn-noreload">&times;</button>
    `;

    // Add close functionality
    const closeBtn = alertBox.querySelector('.close-btn-noreload');
    closeBtn.addEventListener('click', () => {
        alertContainer.removeChild(alertBox);
    });

    // Append the alert to the container
    alertContainer.prepend(alertBox);

    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (alertContainer.contains(alertBox)) {
            alertContainer.removeChild(alertBox);
        }
    }, 3000);
}


function compare_value(element, place) {
    var value = $(element).val();
    $(place).val(value);
}

function selisih_hari(tgl1,tgl2) {
    var tanggal1 = new Date(tgl1); // new Date() saja akan menghasilkan tanggal sekarang
    var tanggal2 = new Date(tgl2); // format tanggal YYYY-MM-DD, tahun-bulan-hari
    
    // set jam menjadi jam 12 malam, atau 00
    tanggal1.setHours(0, 0, 0, 0);
    tanggal2.setHours(0, 0, 0, 0);
    
    var selisih = Math.abs(tanggal1 - tanggal2);
    // Selisih akan dalam millisecond atau mili detik
    
    var hariDalamMillisecond = 1000 * 60 * 60 * 24; // 1000 * 1 menit * 1 jam * 1 hari
    
    var selisihTanggal = Math.round(selisih / hariDalamMillisecond);

    return selisihTanggal;
}


function get_tab(property, filter_tab,vector="",filter = "data-tab",id_prefix = false) {
    const base = document.querySelector(".base_tab");
    if (id_prefix != false) {
        target_div = document.querySelectorAll("#display_tab_"+id_prefix+" .zoom_filter");
    }else{
        target_div = document.querySelectorAll("#display_tab .zoom_filter");
    }
    
    base.querySelector(".active").classList.remove("active");
    $(property).addClass("active");

    target_div.forEach((div) => {
        let display_value = div.getAttribute(filter);
        if ((display_value == 'tab_'+filter_tab) || (filter_tab == "all")) {
            div.classList.remove("hidin");
            div.classList.add("showin");
        } else {
            div.classList.add("hidin");
            div.classList.remove("showin");
        }
    });
    if (vector != "") {
        const vector_bantuan = document.querySelector("#vector_bantuan");
        const tampil = document.querySelectorAll(".showin");
        if (tampil.length == 0) {
            vector_bantuan.classList.remove("hiding");
            vector_bantuan.classList.add("showin");
        } else {
            vector_bantuan.classList.add("hiding");
            vector_bantuan.classList.remove("showin");
        }
    }
    
}


function updateDisplay(base, value) {
    // Display the CKEditor value in a separate div
    document.querySelector(base).innerText = value;
}

function embed_youtube(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);

    if(match && match[2].length === 11){
        return "https://www.youtube.com/embed/"+ match[2];
    }else{
        return null;
    }
}



