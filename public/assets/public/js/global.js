document.addEventListener('DOMContentLoaded', () => {
    // Use session storage to determine if a reload occurred
    if (sessionStorage.getItem('isReload')) {
        // Show SweetAlert2 if the page was reloaded
        var alert_icon = sessionStorage.getItem('alert_icon');
        var alert_message = sessionStorage.getItem('alert_message');

        var targetId = sessionStorage.getItem('scroll_bot');
        
        if (targetId) {
            
            // Scroll ke elemen dengan ID yang ditentukan
            var targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: 'smooth' });
            }
            // Hapus data setelah digunakan
            localStorage.removeItem('scroll_bot');
        }
        Swal.fire({
            html: alert_message,
            icon: alert_icon,
            buttonsStyling: !1,
            confirmButtonText: ucwords('ok'),
            customClass: {
                confirmButton: css_btn_confirm
            }
        });
        

        // Remove the flag after showing the alert
        sessionStorage.removeItem('isReload');
        sessionStorage.removeItem('alert_icon');
        sessionStorage.removeItem('alert_message');
        
        
    }
});


function tab_to(element, to) {
    $('.parent_tab_display li a.active').removeClass('active');
    $(element).addClass('active');

    $('.row_tab_display.showin').addClass('hidin');
    $('.row_tab_display.showin').removeClass('showin');

    $(to).removeClass('hidin');
    $(to).addClass('showin');
}



function activate_checkbox(element,to,kelas = 'active') {
    if ($(element).is(':checked')) {
        $(to).addClass(kelas);
    } else {
        $(to).removeClass(kelas);
    }
}