
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $setting->meta_description }}">
    <meta name="keywords" content="{{ $setting->meta_keyword }}">
    <meta name="author" content="{{ $setting->meta_author }}">
    <title>{{ $setting->meta_title }}{{ isset($title) ? ' | '.$title : '' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="{{ image_check($setting->icon, 'setting') }}?v={{ time() }}" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link href="{{ asset('assets/public/js/alert/sweetalert2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/style.css') }}">
     <link rel="stylesheet" href="{{ asset('assets/frontend/css/colors/base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/plugins/aos/css/aos.css') }}">
    <link rel="preload" href="{{ asset('assets/frontend/css/fonts/urbanist.css') }}" as="style" onload="this.rel='stylesheet'">
    <link href="{{ asset('assets/public/css/loading_custom.css'); }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/public/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/public/css/select2_bs.css') }}" rel="stylesheet">
    <!-- Tambahkan ini di bagian <head> -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    

    @stack('styles')

    <style>
        .swiper-controls .swiper-navigation .swiper-button.swiper-button-prev::after {
            content: "<" !important;
        }
        .swiper-controls .swiper-navigation .swiper-button.swiper-button-next::after {
            content: ">" !important;
        }
        .cursor-pointer{
        cursor: pointer !important;
    }
        .bg-overlay::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgb(142 141 146 / 50%); /* putih transparan */
            z-index: 1;
        }

        /* Loader harus muncul pertama kali */
        #before_load {
            width : 100vw;
            height: 100vh;
            position: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #FFFFFF;
            z-index : 10000;
        }

        .load {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100px;
            height: 100px;
        }

        .load hr {
            border: 0;
            margin: 0;
            width: 40%;
            height: 40%;
            position: absolute;
            border-radius: 50%;
            animation: spin 2s ease infinite
        }

        .load :first-child {
            background: #ab0d22;
            animation-delay: -1.5s
        }

        .load :nth-child(2) {
            background: #F63D3A;
            animation-delay: -1s
        }

        .load :nth-child(3) {
            background: #67141f;
            animation-delay: -0.5s
        }

        .load :last-child {
            background: #c4031d
        }

        @keyframes spin {
            0%, 100% { transform: translate(0) }
            25% { transform: translate(160%) }
            50% { transform: translate(160%, 160%) }
            75% { transform: translate(0, 160%) }
        }

        .hover-shadow:hover {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        

        .progress-wrap::after {
            content: '';
        }
        span.fadedin{
            font-size : 12px !important;
        }

        .swal2-modal{
            max-width : 500px !important;
        }
    </style>


    <style>
        .background-partisi{
            background-position : center !important;
            background-repeat : no-repeat !important;
            background-size :cover !important;
        }
        .background-partisi-contain{
            background-position : center !important;
            background-repeat : no-repeat !important;
            background-size :contain !important;
        }
    </style>

    <!-- <script>
        window.addEventListener("load", function () {
            hideLoader();
        });

        function hideLoader() {
            const afterLoad = document.getElementById("after_load");
            const beforeLoad = document.getElementById("before_load");

            beforeLoad.style.display = "none";
            afterLoad.style.display = "block";

            // Re-init AOS setelah konten muncul
            AOS.init();

            // Inisialisasi Swiper setelah konten tampil
            initSwiper();
        }

        function initSwiper() {
            new Swiper('.swiper-container', {
                slidesPerView: 1,
                spaceBetween: 30,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                // kamu bisa sesuaikan opsi berikut sesuai kebutuhanmu
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                    },
                    1200: {
                        slidesPerView: 3,
                    }
                }
            });
        }
    </script> -->


</head>