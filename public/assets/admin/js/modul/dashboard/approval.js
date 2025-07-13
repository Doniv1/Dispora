let approvalTable;

document.addEventListener('DOMContentLoaded', function () {
    approvalTable = initGlobalDatatable('#table_approval');

    // Trigger reload on each filter
    document.querySelectorAll('.table-filter').forEach(el => {
        el.addEventListener('change', function () {
            if (approvalTable) approvalTable.ajax.reload();
        });
    });
});



function detail_training(id) {
    $.ajax({
        url: BASE_URL + '/modal_detail_register',
        method: 'POST',
        data: { 
            _token : csrf_token,
            id: id 
        },
        cache: false,
        beforeSend(){
            $('#display_detail_data').html(div_loading);
        },
        success: function (msg) {
            $('#display_detail_data').html(msg);
        }
    })
    
    
}

function set_approval(status) {
    var id = $('#set_id_regis').val();
    $.ajax({
        url: BASE_URL + '/set_approval',
        method: 'POST',
        data: { 
            _token : csrf_token,
            id: id,
            status : status
        },
        dataType: 'json',
        success: function (data) {
            if (data.status == true) {
                var icon = 'success';
            }else{
                var icon = 'warning';
            }
            show_alert(icon,data.message);
            $('#kt_modal_approval').modal('hide');
            var table = $('#table_approval').DataTable();
            table.ajax.reload(null, false);
        }
    })
    
    
}