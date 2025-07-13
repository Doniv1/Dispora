// .config-datatable.js
$.extend(true, $.fn.dataTable.defaults, {
    language: {
        "sProcessing": "Sedang memproses...",
        "sLengthMenu": "Tampilkan _MENU_ data per halaman",
        "sZeroRecords": "Tidak ditemukan data yang sesuai",
        "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
        "sInfoFiltered": "(disaring dari _MAX_ data keseluruhan)",
        "sSearch": "Cari:",
        "oPaginate": {
            "sFirst": "Pertama",
            "sLast": "Terakhir",
            "sNext": "Berikutnya",
            "sPrevious": "Sebelumnya"
        }
    }
});
