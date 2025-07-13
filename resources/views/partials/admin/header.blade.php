<!--begin::Header tablet and mobile-->
<div class="header-mobile py-3">
    <!--begin::Container-->
    <div class="container d-flex flex-stack">
        
        @if($setting->logo)
            <!--begin::Mobile logo-->
            <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                <a href="{{ route('dashboard'); }}">
                    <img alt="Logo" src="{{ asset(image_check($setting->logo, 'setting')) }}" class="h-35px" />
                </a>
            </div>
        @endif
        <!--begin::Aside toggle-->
        <button class="btn btn-icon btn-active-color-primary me-n4" id="kt_aside_toggle">
            <i class="ki-duotone ki-abstract-14 fs-2x">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </button>
        <!--end::Aside toggle-->
    </div>
    <!--end::Container-->
</div>
<!--end::Header tablet and mobile-->
<!--begin::Header-->
<div id="kt_header" class="header py-6 py-lg-0" data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{lg: '300px'}">
    <!--begin::Container-->
    <div class="header-container container-xxl">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap me-lg-20 py-3 py-lg-0 me-3">
            @if(isset($title) || isset($subtitle))
            <!--begin::Heading-->
            <h1 class="d-flex flex-column text-gray-900 fw-bold my-1">
                <span class="text-white fs-1">{{ucwords($title)}}</span>
                @if(isset($subtitle))
                <small class="text-gray-400 fs-6 fw-normal pt-2">{{ $subtitle; }}</small>
                @endif
            </h1>
            <!--end::Heading-->
            @endif
        </div>
        <!--end::Page title=-->
        
        <!-- begin::Wrapper-->
        <!-- <div class="d-flex align-items-center flex-wrap">
            <!--begin::Action-->
            <!-- <div class="d-flex align-items-center py-3 py-lg-0">
                    <a href="{{ route('admin.profile'); }}" style="width: 55px !important; height: 55px !important; 
                        background-image: url('{{ image_check(session(config('session.prefix') . '_image'), 'user','user') }}'); 
                        background-position: center; 
                        background-size: cover; 
                        background-repeat: no-repeat;
                        border-radius : 100%;
                        ">
                    </a>
            </div> -->
            <!--end::Action-->
        <!-- </div> --> -->
        <!--end::Wrapper -->
    </div>
    <!--end::Container-->
    <div class="header-offset"></div>
</div>
<!--end::Header-->