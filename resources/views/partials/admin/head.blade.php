<!--begin::Head-->
<head>
    <base href="{{ url('/') }}/"/>
    <title>{{ $setting->meta_title }}{{ isset($title) ? ' | '.$title : '' }}</title>
    <meta charset="utf-8" />		
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- UNTUK SEO -->
    <link rel="icon" href="{{ asset(image_check($setting->icon, 'setting')) }}?v={{ time() }}" type="image/x-icon">
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->

    <!-- UNTUK SEO -->
    <link rel="icon" href="{{ image_check($setting->icon,'setting') }}?v={{ time() }}" type="image/x-icon">

    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{ asset('assets/public/plugins/custom/datatables/datatables.bundle.css'); }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/public/plugins/custom/datatables/rowReorder.datatables.min.css'); }}" rel="stylesheet" type="text/css" />
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link rel="stylesheet" href="{{ asset('assets/base_color/color.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="{{ asset('assets/public/plugins/global/plugins.bundle.css'); }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/public/plugins/custom/vis-timeline/vis-timeline.bundle.css'); }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/public/css/style.bundle.css'); }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/public/css/custom_pribadi.css'); }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/public/css/loading_custom.css'); }}" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="{{ asset('assets/public/plugins/ckeditor5/ckeditor.js'); }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-xxx" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        var CKEditor_tool = ["heading", "alignment","|",'fontSize','fontColor', 'fontBackgroundColor',"|", "bold", "italic", "link", "bulletedList", "numberedList", "|", "outdent", "indent", "|", "blockQuote", "insertTable", "mediaEmbed", "undo", "redo"];
         var font_color =  [
            {
                color: 'hsl(0, 0%, 0%)',
                label: 'Black'
            },
            {
                color : 'hsl(0, 0%, 100%)',
                label : 'White'
            },
            {
                color: 'hsl(0, 75%, 60%)',
                label: 'Red'
            },
            {
                color: 'hsl(120, 75%, 60%)',
                label: 'Green'
            },
            {
                color: 'hsl(240, 75%, 60%)',
                label: 'Blue'
            },
            {
                color: 'hsl(60, 75%, 60%)',
                label: 'Yellow'
            },
            {
                color: 'hsl(235, 85%, 35%)',
                label : 'Primary'
            }
        ];
    </script>
    @stack('styles')
    <style>
    .cursor-pointer{
        cursor: pointer !important;
    }
    .cursor-disabled{
        cursor: not-allowed !important;
    }
    .cursor-scroll{
        cursor: all-scroll;
    }
    /* .form-control,
    .form-select{
        border : 1px solid var(--bs-gray-300) !important;
    } */
    .order-table{
        cursor : move !important;
    }

    .menu-accordion.active{
        color : #FF286B !important;
    }
    .swal2-textarea{
        color : #FFFFFF !important;
    }

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
    .swal2-textarea {
        color : black !important;
    }

    .preview-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(0, 0, 0, 0.8);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }

    .preview-overlay img {
      max-width: 90%;
      max-height: 90%;
      border: 5px solid #fff;
      border-radius: 10px;
      box-shadow: 0 0 20px #000;
    }

    .preview-overlay:hover {
      cursor: pointer;
    }
</style>
</head>
<!--end::Head-->