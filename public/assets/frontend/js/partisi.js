function detail_training(element, id) {
    var image = document.getElementById('display_image_training');
    var foto = $(element).data('image');
    $.ajax({
        url: BASE_URL + '/get_detail_training',
        method: 'POST',
        data: { 
            _token : csrf_token,
            id: id 
        },
        cache : false,
        dataType: 'json',
        success: function (data) {
            image.style.backgroundImage = "url('" + foto + "')";
            $('#pendaftaran_id_training').val(data.id_training);
            $('#display_category_training').text(data.category);
            $('#display_title_training').text(data.title);
            $('#display_description_training').html(data.description);
        }
    })
}
