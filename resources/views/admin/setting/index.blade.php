@extends('layouts.admin.main')

@push('script')
<script src="{{ asset('assets/admin/js/modul/setting/logo.js') }}"></script>';
<script src="{{ asset('assets/admin/js/modul/setting/umum.js') }}"></script>';
@endpush


@section('content')
<!--begin::Container-->
<div class="container-xxl" id="kt_content_container">
    <!--begin::Card-->
    <div class="card card-flush">
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin:::Tabs-->
            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-4 fw-semibold mb-15">
                <!--begin:::Tab item-->
                <li class="nav-item">
                    <a onclick="set_url_params('umum')" class="nav-link text-active-primary d-flex align-items-center pb-5 @if(!$page || $page == 'umum') active @endif" data-bs-toggle="tab" href="#general_pane">
                        <i class="ki-duotone ki-home fs-2 me-2"></i>Umum
                    </a>
                </li>
                <!--end:::Tab item-->

                <!--begin:::Tab item-->
                <li class="nav-item">
                    <a onclick="set_url_params('seo')" class="nav-link text-active-primary d-flex align-items-center pb-5 @if($page == 'seo') active @endif" data-bs-toggle="tab" href="#seo_pane">
                        <i class="fa-brands fa-searchengin fs-2 me-2"></i>SEO
                    </a>
                </li>
                <!--end:::Tab item-->

                <!--begin:::Tab item-->
                <li class="nav-item">
                    <a onclick="set_url_params('sosmed')" class="nav-link text-active-primary d-flex align-items-center pb-5 @if($page == 'sosmed') active @endif" data-bs-toggle="tab" href="#sosmed_pane">
                        <i class="fa-solid fa-hashtag fs-2 me-2"></i>Sosial Media
                    </a>
                </li>
                <!--end:::Tab item-->

                <!--begin:::Tab item-->
                <li class="nav-item">
                    <a onclick="set_url_params('about')" class="nav-link text-active-primary d-flex align-items-center pb-5 @if($page == 'about') active @endif" data-bs-toggle="tab" href="#about_pane">
                        <i class="fa-solid fa-text-width fs-2 me-2"></i>About
                    </a>
                </li>
                <!--end:::Tab item-->

                <!--begin:::Tab item-->
                <li class="nav-item">
                    <a onclick="set_url_params('form')" class="nav-link text-active-primary d-flex align-items-center pb-5 @if($page == 'form') active @endif" data-bs-toggle="tab" href="#form_pane">
                        <i class="fa-solid fa-list  fs-2 me-2"></i>Formulir
                    </a>
                </li>
                <!--end:::Tab item-->
            </ul>
            <!--end:::Tabs-->

            <!--begin:::Tab content-->
            <div class="tab-content" id="tab_pane">
                <!--begin:::Tab pane-->
                <div class="tab-pane fade @if(!$page || $page == 'umum') show active @endif" id="general_pane" role="tabpanel">
                    @include('admin.setting.page.logo')
                </div>
                <!--end:::Tab pane-->

                <!--begin:::Tab pane-->
                <div class="tab-pane fade @if($page == 'seo') show active @endif" id="seo_pane" role="tabpanel">
                    @include('admin.setting.page.seo')
                </div>
                <!--end:::Tab pane-->

                <!--begin:::Tab pane-->
                <div class="tab-pane fade @if($page == 'sosmed') show active @endif" id="sosmed_pane" role="tabpanel">
                    @include('admin.setting.page.sosmed')
                </div>
                <!--end:::Tab pane-->

                <!--begin:::Tab pane-->
                <div class="tab-pane fade @if($page == 'about') show active @endif" id="about_pane" role="tabpanel">
                    @include('admin.setting.page.about')
                </div>
                <!--end:::Tab pane-->

                <!--begin:::Tab pane-->
                <div class="tab-pane fade @if($page == 'form') show active @endif" id="form_pane" role="tabpanel">
                    @include('admin.setting.page.form')
                </div>
                <!--end:::Tab pane-->
            </div>
            <!--end:::Tab content-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>
<!--end::Container-->


<!--begin::Modal-->
<div class="modal fade" id="kt_modal_sosmed" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="title_modal_sosmed" data-title="Tambah Sosial Media|Edit Sosmed"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body mx-5 mx-xl-15 my-7">
                <!--begin::Form-->
                <form id="form_sosmed" class="form" action="{{ route('insert.sosmed') }}" method="POST" enctype="multipart/form-data">
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column me-n7 pe-7">
                        <!--begin::Input group-->
                        <div class="fv-row mb-7" id="req_sosmed_icon">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">Icon</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="icon" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Masukkan Kode Icon" autocomplete="off" />
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->

                        <input type="hidden" name="id_sosmed">

                        <!--begin::Input group-->
                        <div class="fv-row mb-7" id="req_sosmed_name">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">Nama</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Masukkan Nama" autocomplete="off" />
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Scroll-->

                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="button" id="submit_sosmed" data-loader="big" onclick="submit_form(this,'#form_sosmed')" class="btn btn-primary">
                            <span class="indicator-label">Simpan</span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->


<div class="modal fade" id="kt_modal_form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="title_modal_form" data-title="Edit Form|Add Form"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body mx-5 mx-xl-15 my-7">
                <!--begin::Form-->
                <form id="form_form" class="form" action="{{ route('insert.form') }}"  method="POST" enctype="multipart/form-data">
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column me-n7 pe-7" id="#">
                        <input type="hidden" name="id_form">
                        <!--begin::Input group-->
                        <div class="fv-row mb-7" id="req_field">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">Field</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="field" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Enter field" autocomplete="off" />
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="fv-row mb-7" id="req_type">
                            <!--begin::Label-->
                            <label id="label_type" class="type required fw-semibold fs-6 mb-2">Type</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select id="select_type" name="type" class="form-select form-select-solid" data-control="select2" data-placeholder="Choose Type">
                                <option value="">Choose Type</option>
                                <option value="1">Text</option>
                                <option value="2">Textarea</option>
                                <option value="3">Number</option>
                                <option value="4">File</option>
                            </select>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->

                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="button" id="btn_submit_form" onclick="submit_form(this,'#form_form')" class="btn btn-primary">
                            <span class="indicator-label">Submit</span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>
</div>
@endsection