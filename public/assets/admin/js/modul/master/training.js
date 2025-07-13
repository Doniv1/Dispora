ClassicEditor.create(document.querySelector("#description"), {
    toolbar: {
        items: CKEditor_tool,
    },
    alignment: {
        options: ["left", "center", "right", "justify"],
    },
    table: {
        contentToolbar: ["tableColumn", "tableRow", "mergeTableCells"],
    },
    link: {
        addTargetToExternalLinks: true, // Add 'target="_blank"' for external links
        decorators: {
            openInNewTab: {
                mode: "manual",
                label: "Open in a new tab",
                attributes: {
                    target: "_blank",
                    rel: "noopener noreferrer",
                },
            },
        },
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
    language: "en",
    licenseKey: "",
})
    .then((editor) => {
        mydescription = editor;
    })
    .catch((error) => {
        console.error(error);
    });

$(function () {
    $("#kt_modal_training").on("shown.bs.modal", function () {
        $("#select_id_category").select2({
            dropdownParent: $("#form_training"),
        });
    });
});

var title = $("#title_modal").data("title").split("|");
var image = document.getElementById("display_image");
function ubah_data(element, id) {
    var foto = $(element).data("image");
    var form = document.getElementById("form_training");
    $("#title_modal").text(title[0]);
    form.setAttribute("action", BASE_URL + "/master/training/update");
    $.ajax({
        url: BASE_URL + "/single_training",
        method: form.method,
        data: {
            _token: csrf_token,
            id: id,
        },
        dataType: "json",
        success: function (data) {
            $(".checkbox-setup").prop("checked", false);
            image.style.backgroundImage = "url('" + foto + "')";
            $('input[name="id_training"]').val(data.result.id_training);
            $('input[name="title"]').val(data.result.title);
            $('#form_training select[name="id_category"]').val(
                data.result.id_category
            );
            $('#form_training select[name="id_category"]').trigger("change");
            $('input[name="name_image"]').val(data.result.image);
            $('textarea[name="sort_description"]').val(
                data.result.sort_description
            );
            mydescription.setData(data.result.description);
            if (data.vector) {
                for (let i = 0; i < data.vector.length; i++) {
                    $("#vector-" + data.vector[i]).prop("checked", true);
                }
            }
        },
    });
}

function detail_register(element, id) {
    var title = $(element).data("title");
    $.ajax({
        url: BASE_URL + "/modal_register",
        method: "POST",
        data: {
            _token: csrf_token,
            id: id,
        },
        cache: false,
        success: function (msg) {
            $("#title_modal_register").text(title);
            $("#display_modal_register").html(msg);

            // Set link untuk unduh
            $("#btn-download-register").attr(
                "href",
                BASE_URL + "/download_register?id=" + id
            );
        },
    });
}

function hapus_register(id) {
    Swal.fire({
        html: "Are you sure to delete this data?",
        icon: "question",
        showCancelButton: true,
        buttonsStyling: !1,
        confirmButtonText: "Lanjutkan",
        cancelButtonText: "Batal",
        customClass: {
            confirmButton: css_btn_confirm,
            cancelButton: css_btn_cancel,
        },
        reverseButtons: true,
    }).then(function (t) {
        if (t.isConfirmed) {
            $.ajax({
                url: BASE_URL + "/delete_regis",
                method: "POST",
                data: {
                    _token: csrf_token,
                    id: id,
                },
                dataType: "json",
                success: function (data) {
                    if (data.status == true) {
                        var icon = "success";
                        var table = $("#table_training").DataTable();
                        table.ajax.reload(null, false);
                        $("#pane-regis-" + id).remove();
                        if (data.count <= 0) {
                            $("#notfound_vector").removeClass("d-none");
                        }
                    } else {
                        var icon = "warning";
                    }
                    show_alert(icon, data.message);
                },
            });
        }
    });
}

function tambah_data() {
    var form = document.getElementById("form_training");
    form.setAttribute("action", BASE_URL + "/master/training/insert");
    image.style.backgroundImage = "url('" + base_foto + "')";
    $("#title_modal").text(title[1]);
    $('#form_training input[type="text"]').val("");
    $('#form_training input[type="email"]').val("");
    $("#form_training textarea").val("");
    $("#form_training select").val("");
    $("#form_training select").trigger("change");
    $(".checkbox-setup").prop("checked", false);
    mydescription.setData("");
}

let trainingTable;
let currentFilterStatus = "";
let currentFilterCategory = "";

document.addEventListener("DOMContentLoaded", function () {
    trainingTable = initGlobalDatatable("#table_training", function () {
        return {
            filter_status: currentFilterStatus,
            filter_category: currentFilterCategory,
        };
    });

    // Trigger reload on each filter
    document.querySelectorAll(".table-filter").forEach((el) => {
        el.addEventListener("change", function () {
            if (trainingTable) trainingTable.ajax.reload();
        });
    });
});

// Trigger reload saat filter diubah
function filter_status(element) {
    currentFilterStatus = element.value;
    if (trainingTable) {
        trainingTable.ajax.reload();
    }
}

function filter_category(element) {
    currentFilterCategory = element.value;
    if (trainingTable) {
        trainingTable.ajax.reload();
    }
}

function detail_form(element, id) {
    $(".btn-detail").prop("disabled", false);
    $(element).prop("disabled", true);
    $(".detail-daftar.show .card-body").html("");
    $(".detail-daftar.show div").hide();
    $(".detail-daftar.show").removeClass("show");
    $("#detail-daftar-" + id).addClass("show");
    $.ajax({
        url: BASE_URL + "/modal_detail_register",
        method: "POST",
        data: {
            _token: csrf_token,
            id: id,
        },
        cache: false,
        beforeSend() {
            $("#detail-daftar-" + id + " .card-body").html(div_loading);
            $("#detail-daftar-" + id + " div").fadeIn(200);
        },
        success: function (msg) {
            $("#detail-daftar-" + id + " .card-body").html(msg);
            $("#detail-daftar-" + id + " div").fadeIn(200);
        },
    });
}

function close_form(id) {
    $("#detail-daftar-" + id).removeClass("show");
    $("#btn-detail-" + id).prop("disabled", false);
    $("#detail-daftar-" + id + " .card-body").html("");
    $("#detail-daftar-" + id + " div").hide();
}
