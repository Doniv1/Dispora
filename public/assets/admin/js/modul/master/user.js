let userTable;
let currentFilterStatus = "";

document.addEventListener("DOMContentLoaded", function () {
    userTable = initGlobalDatatable("#table_user", function () {
        return {
            filter_status: currentFilterStatus,
        };
    });

    // Trigger reload on each filter
    document.querySelectorAll(".table-filter").forEach((el) => {
        el.addEventListener("change", function () {
            if (userTable) userTable.ajax.reload();
        });
    });
});

// Trigger reload saat filter diubah
function filter_status(element) {
    currentFilterStatus = element.value;
    if (userTable) {
        userTable.ajax.reload();
    }
}

var image = document.getElementById("display_image");
var title = $("#title_modal").data("title").split("|");
$(function () {
    $(".hps_image").on("click", function () {
        // console.log('hapus');
        $("input[name=name_image]").val("");
    });
});

function ubah_data(element, id) {
    var foto = $(element).data("image");
    var image = document.getElementById("display_image");
    var form = document.getElementById("form_user");
    $("#title_modal").text("Edit User");
    form.setAttribute("action", BASE_URL + "/master/user/update");

    $.ajax({
        url: BASE_URL + "/single/users/id_user",
        method: form.method,
        data: {
            _token: csrf_token,
            id: id,
        },
        dataType: "json",
        success: function (data) {
            image.style.backgroundImage = "url('" + foto + "')";
            $('input[name="id_user"]').val(data.id_user);
            $('input[name="role"]').val(data.role);
            $('input[name="name"]').val(data.name);
            $('input[name="phone"]').val(data.phone);
            $('input[name="email"]').val(data.email);
            $('input[name="name_image"]').val(data.image);

            // Field tambahan
            $('select[name="gender"]').val(data.gender);
            $('input[name="born_date"]').val(data.born_date);
            $('select[name="education_status"]').val(data.education_status);
            $('select[name="id_vector"]').val(data.id_vector);
            $('select[name="id_riwayat_pelatihan"]').val(
                data.id_riwayat_pelatihan
            );

            $("#form_user label.password").removeClass("required");

            $("#kt_modal_user").modal("show");
        },
    });
}

function tambah_data() {
    var form = document.getElementById("form_user");
    form.setAttribute("action", BASE_URL + "/master/user/insert");
    $("#title_modal").text(title[1]);
    image.style.backgroundImage = "url('" + user_base_foto + "')";
    $('#form_user input[type="text"]').val("");
    $('#form_user input[type="email"]').val("");
    $("#form_user label.password").addClass("required");
    $("#form_user textarea").val("");
}

function history_booking(id_user) {
    var tableId = "#display_history";
    var modalId = "#kt_modal_history";
    // Hapus DataTable kalau sebelumnya udah pernah dibuat
    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().clear().destroy();
    }

    // Tampilkan modal dulu
    $(modalId).modal("show");

    // Setelah modal tampil baru init DataTable
    $(modalId).on("shown.bs.modal", function () {
        if ($.fn.DataTable.isDataTable(tableId)) {
            $(tableId).DataTable().clear().destroy();
            console.log("destroy");
        }
        $(tableId + " tbody").html("");
        var table = $(tableId).DataTable({
            processing: true,
            serverSide: true,
            deferLoading: 0,
            ajax: {
                url: BASE_URL + "/table/history",
                type: "POST",
                data: {
                    _token: csrf_token,
                    id_user: id_user,
                },
                cache: false,
            },
            lengthMenu: [
                [5, 10, 25, 50, 100],
                [5, 10, 25, 50, 100],
            ],
            paging: true,
            searching: true,
            ordering: true,
        });

        // Debounce pencarian
        function debounce(func, wait, immediate) {
            let timeout;
            return function () {
                const context = this,
                    args = arguments;
                const later = function () {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                const callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        }
        // Trigger reload data manual setelah setup
        table.ajax.reload();

        $("#searchTable")
            .off("keyup")
            .on(
                "keyup",
                debounce(function () {
                    table.search(this.value).draw();
                }, 500)
            );
    });
}
