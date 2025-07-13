$(function () {
    if ($('.search_mekanik input[type="text"]')) {
        var i = document.querySelector('.search_mekanik i');
        var button = document.querySelector('.search_mekanik button');
        var input = document.querySelector('.search_mekanik input');
        $('.search_mekanik input[type="text"]').on('keydown', function () {
            var value = $(this).val();
            if (value != '') {
                i.classList.add('d-none');
                input.classList.remove('ps-13');
                button.classList.remove('d-none');
                $('.search_mekanik button').attr('disabled', false);
            } else {
                i.classList.remove('d-none');
                input.classList.add('ps-13');
                button.classList.add('d-none');
                $('.search_mekanik button').attr('disabled', true);
            }
        })
    }


     /* Tanpa Rupiah */
    
    

    


});
function matauang(element = null, rep = null) {
    let angka = $(element).val();

    // Ambil hanya digit angka
    let asli = angka.replace(/[^0-9]/g, "");

    // Hilangkan leading zero (nol di depan), kecuali kalau hasil akhirnya kosong
    asli = asli.replace(/^0+/, '') || '0';

    // Format ke rupiah
    let rupiah = format_uang(asli);

    $(element).val(rupiah);

    if (rep != null) {
        $(rep).val(asli);
    }
}

    

    function format_uang(angka)
    {
       
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split    = number_string.split(','),
            sisa     = split[0].length % 3,
            rupiah     = split[0].substr(0, sisa),
            ribuan     = split[0].substr(sisa).match(/\d{3}/gi);
            
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }

function func_search(element){
     var i = document.querySelector('.search_mekanik i');
        var button = document.querySelector('.search_mekanik button');
        var input = document.querySelector('.search_mekanik input');
            var value = $(element).val();
            if (value != '') {
                i.classList.add('d-none');
                input.classList.remove('ps-13');
                button.classList.remove('d-none');
                $('.search_mekanik button').attr('disabled', false);
            } else {
                i.classList.remove('d-none');
                input.classList.add('ps-13');
                button.classList.add('d-none');
                $('.search_mekanik button').attr('disabled', true);
            }
}
function checked_action(element, child = 'child_checkbox') {
    var drag = document.getElementById('sistem_drag');
    var filter = document.getElementById('sistem_filter');
    if ($(element).is(':checked')) {
        drag.classList.remove('d-none');
        filter.classList.add('d-none');
        $('.' + child).prop('checked', true);
    } else {
        drag.classList.add('d-none');
        filter.classList.remove('d-none');
        $('.' + child).prop('checked', false);
    }


}

function child_checked(main = '') {
    var drag = document.getElementById('sistem_drag');
    var filter = document.getElementById('sistem_filter');

    var total = $('.child_checkbox').length;
    var total_checked = $('input.child_checkbox:checked').length;
    if (main == '') {
         if (total_checked == total) {
            $('thead input[type=checkbox]').prop('checked', true);
        } else {
            $('thead input[type=checkbox]').prop('checked', false);
        }
    }else{
         if (total_checked == total) {
            $(main).prop('checked', true);
        } else {
            $(main).prop('checked', false);
        }
    }
   
    if ($(this).is(':checked')) {
        if (total_checked > 0) {
            drag.classList.remove('d-none');
            filter.classList.add('d-none');
        } else {
            drag.classList.add('d-none');
            filter.classList.remove('d-none');

        }

    } else {
        if (total_checked > 0) {
            drag.classList.remove('d-none');
            filter.classList.add('d-none');
        } else {
            drag.classList.add('d-none');
            filter.classList.remove('d-none');
        }
    }
}
function search(excel = true) {
    var src = $('.search_mekanik input[name=search]').val();
    src = src.split(" ");
    var value = '';
    var no = 0;
    src.forEach(val => {
        if (no++ > 0) {
            value += '--';
        }
        value += val
    });
    var get = url_get();
    if (excel == true) {
         var l = $('#cetak_excel').attr('href');
        const li = l.split("?");
        var link_cetak = li[0];
    }
   
    var params = '';
    if (get.length > 0) {
        if (get[0].split('=')[0] != 'search') {
            for (var i = 0; i < get.length; i++) {

                if (get[i].split('=')[0] != 'search') {
                    if (i == 0) {
                        params += '?' + get[i];
                    } else {
                        params += '&' + get[i];
                    }
                }

            }
            params += '&search=' + value;
        } else {

            params += '?search=' + value;
            for (var i = 0; i < get.length; i++) {

                if (get[i].split('=')[0] != 'search') {

                    params += '&' + get[i];
                }
            }
        }
    } else {

        params = '?search=' + value;
    }

    var uri = BASE_URL + page + params;
    uri = encodeURI(uri);
    if (excel == true) {
         $('#cetak_excel').attr('href', link_cetak + params);
    }
   
    window.history.pushState('', '', uri);
    $('#base_table').load(uri + ' #reload_table');

}

function filter(name = [],excel = true,hid = true) {
    var get = url_get();
    if (excel == true) {
          var l = $('#cetak_excel').attr('href');
    const li = l.split("?");
    var link_cetak = li[0];
    }
  
    var params = '';
    if (name.length > 0) {

        if (get.length > 0) {
            params += '?';
            // fruits.includes("Mango");
            for (var i = 0; i < get.length; i++) {
                if (!name.includes(get[i].split('=')[0])) {
                    params += get[i] + '&';
                }
            }
            for (var i = 0; i < name.length; i++) {
                params += name[i] + '=' + $('.filter_mekanik .filter-input[name=' + name[i] + ']').val().split(" ").join("") + '&';
            }
        } else {
            for (var i = 0; i < name.length; i++) {
                if (i == 0) {
                    params += '?' + name[i] + '=' + $('.filter_mekanik .filter-input[name=' + name[i] + ']').val().split(" ").join("");
                } else {
                    params += '&' + name[i] + '=' + $('.filter_mekanik .filter-input[name=' + name[i] + ']').val().split(" ").join("");
                }

            }
        }
        var uri = BASE_URL + page + params;
        uri = encodeURI(uri);
        if (excel == true) {
             $('#cetak_excel').attr('href', link_cetak + params);
        }
       
        window.history.pushState('', '', uri);
        if (hid == true) {
            $('.filter_mekanik').hide();
        }
        
        $('#base_table').load(uri + ' #reload_table');
    }
}
function url_get() {
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    var data = [];
    for (var i = 0; i < sURLVariables.length; i++) {
        if (sURLVariables[i] != '') {
            data[i] = sURLVariables[i]
        }

    }
    // console.log(data);
    return data;
}

function url_get_1() {
    var url = window.location.search.substring(1);
    var request = {};
    var arr = [];
    var pairs = url.substring(url.indexOf('?') + 1).split('&');
    for (var i = 0; i < pairs.length; i++) {
      var pair = pairs[i].split('=');

      //check we have an array here - add array numeric indexes so the key elem[] is not identical.
      if(endsWith(decodeURIComponent(pair[0]), '[]') ) {
          var arrName = decodeURIComponent(pair[0]).substring(0, decodeURIComponent(pair[0]).length - 2);
          if(!(arrName in arr)) {
              arr.push(arrName);
              arr[arrName] = [];
          }

          arr[arrName].push(decodeURIComponent(pair[1]));
          request[arrName] = arr[arrName];
      } else {
        request[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
      }
    }
    // console.log(request);
    return Object.keys(request);
}
function endsWith(str, suffix) {
    return str.indexOf(suffix, str.length - suffix.length) !== -1;
}

function pagination(element, e,reload = 0) {
    var drag = document.getElementById('sistem_drag');
    var filter = document.getElementById('sistem_filter');
    var uri = window.location.search;
    e.preventDefault();
    var child = $(element).children("a");
    var href = child.attr("href");
    if (uri) {
        var url = href + uri;
    } else {
        var url = href;
    }
    // console.log(url);
    if (reload == 0) {
         $('#base_table').load(url + ' #reload_table');
        window.history.pushState('', '', url);
        drag.classList.add('d-none');
        filter.classList.remove('d-none');
    }else{
        window.location.href = url;
    }
   
}

function setup_value(place,value) {
    $(place).val(value);
}


// Function to show the loading modal
function showLoading(text = '',brand = false) {
    if (brand == false) {
        document.getElementById('loadingModal').style.display = 'flex';
        if (text == '') {
            var text = 'Loading...';
        }
        $('#text_loading').html(ucwords(text));
    }else{
         document.getElementById('loadingModalBrands').style.display = 'flex';
        if (text == '') {
            var text = 'Loading...';
        }
        $('#text_loading_brands').html(ucwords(text));

        const logo_brands_1 = document.getElementById("logo_brands_1");
        const logo_brands_2 = document.getElementById("logo_brands_2");
        // Ganti logo dengan delay 500ms
        setTimeout(() => {
            logo_brands_1.classList.add("hidden");
            logo_brands_2.classList.remove("hidden");
        }, 500);

    }
   
    
}


// Function to hide the loading modal
function hideLoading(brands = false) {
    if (brands == false) {
        document.getElementById('loadingModal').style.display = 'none';
    }else{
        document.getElementById('loadingModalBrands').style.display = 'none';
    }
    
}

function custom_reload(limit = 1000,link = '',text = '') {
    if (link == '') {
        var link = window.location.href;
    }
    if (text == '') {
        var text = 'In the process...';
    }
    showLoading(text);
    // Use a timeout to ensure the loading screen is visible
    setTimeout(() => {
        // Use the Fetch API to perform a background reload
        fetch(link, { cache: 'reload' })
            .then(response => {
                console.log(link,response)
                if (response.ok) {
                    // Reload the page after the response is received
                    location.reload();
                } else {
                    console.error('Failed to fetch the page');
                    hideLoading()
                }
            })
            .catch(error => {
                console.error('Error:', error);
                hideLoading()
            });
    }, limit); // Short delay to ensure loading screen is visible
}


function ucwords(str) {
    if (typeof str !== 'string') {
        return str; // Return the input if it's not a string
    }
    return str
        .split(' ') // Split the string into words based on spaces
        .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()) // Capitalize the first letter of each word
        .join(' '); // Join the words back into a single string
}

function ucfirst(str) {
    if (typeof str !== 'string' || str.length === 0) {
        return str; // Return the input if it's not a string or is empty
    }
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function isEmpty(value) {
    if (typeof value === 'undefined') {
        return true;
    } else {
       return typeof value === 'string' && value.trim() === '';
    }
    
}

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    
    if (parts.length === 2) {
        return parts.pop().split(';').shift();
    }
    return null; // Return null if the cookie is not found
}
