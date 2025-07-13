@extends('layouts.admin.main')

@push('script')
<script src="{{ asset('assets/admin/js/modul/master/banner.js') }}"></script>
@endpush


@section('content')

<!--begin::Container-->
<div class="container-xxl" id="kt_content_container">
    <!--begin::banners-->
    <div class="card card-flush">
        <!--begin::Card header-->
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" class="form-control form-control-solid w-250px ps-12 search-datatable" placeholder="Search"  />
                </div>
                <!--end::Search-->
            </div>
            <!--end::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                <div class="w-100 mw-150px">
                    <!--begin::Select2-->
                    <select onchange="filter_status(this)" class="form-select form-select-solid table-filter" data-control="select2">
                        <option value="all">All</option>
                        <option value="Y">Account Active</option>
                        <option value="N">Account Inactive</option>
                    </select>
                    <!--end::Select2-->
                </div>
                <!--begin::Add banner-->
                <a role="button"  onclick="tambah_data()" data-bs-toggle="modal" data-bs-target="#kt_modal_banner"  class="btn btn-primary">Tambah Data Banner</a>
                <!--end::Add banner-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_banner" data-url="{{ route('table.banner') }}">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-10px pe-2" data-orderable="false" data-searchable="false">No</th>
                        <th class="min-w-200px" data-orderable="false" data-searchable="false">Media</th>
                        <th class="min-w-200px">Judul</th>
                        <th class="min-w-200px">Deskripsi</th>
                        <th class="text-center min-w-100px" data-searchable="false">Status</th>
                        <th class="text-end min-w-70px" data-orderable="false" data-searchable="false">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                </tbody>
            </table>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::banners-->
</div>
<!--end::Container-->



<!-- Modal Tambah banner -->
<div class="modal fade" id="kt_modal_banner" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="title_modal" data-title="Edit Data Banner|Tambah Data Banner"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body mx-5 mx-xl-15 my-7">
                <!--begin::Form-->
                <form id="form_banner" class="form" action="{{ route('insert.banner') }}"  method="POST" enctype="multipart/form-data">
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column me-n7 pe-7" id="#">

                        <!--begin::Input group-->
                        <div class="fv-row mb-7 d-flex justify-content-center align-items-center flex-column">
                            <!--begin::Label-->
                            <label class="d-block fw-semibold fs-6 mb-5">Image</label>
                            <!--end::Label-->
                            <!--begin::Image input-->
                            <div class="image-input background-partisi" data-kt-image-input="true" style="background-image: url('<?= image_check('default.jpg','default') ?>')">
                                <!--begin::Image preview wrapper-->
                                <div id="display_image" class="image-input-wrapper w-200px h-125px background-partisi" style="background-image: url('<?= image_check('default.jpg','default') ?>')"></div>
                                <!--end::Image preview wrapper-->

                                <!--begin::Edit button-->
                                <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Edit">
                                    <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                                    <!--begin::Inputs-->
                                    <input type="file" name="image" accept=".png, .jpg, .jpeg" />
                                    <input type="hidden" name="avatar_remove" />
                                    <input type="hidden" name="name_image" />
                                    <!--end::Inputs-->
                                </label>
                                <!--end::Edit button-->

                                <!--begin::Cancel button-->
                                <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow hps_image" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Cancel">
                                    <i class="ki-outline ki-cross fs-3"></i>
                                </span>
                                <!--end::Cancel button-->

                                <!--begin::Remove button-->
                                <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow hps_image" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Delete">
                                    <i class="ki-outline ki-cross fs-3"></i>
                                </span>
                                <!--end::Remove button-->
                            </div>
                            <!--end::Image input-->
                            <!--begin::Hint-->
                            <div class="form-text">Tipe: png, jpg, jpeg.</div>
                            <!--end::Hint-->
                        </div>
                        <!--end::Input group-->
                        <input type="hidden" name="id_banner">
                        <!--begin::Input group-->
                        <div class="fv-row mb-7" id="req_title">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">Judul</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="title" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Enter Title" autocomplete="off" />
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="fv-row mb-7" id="req_description">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">Deskripsi</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <textarea name="description" id="description" cols="30" rows="10"  class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Enter Description"></textarea>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->

                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="button" id="submit_banner" onclick="submit_form(this,'#form_banner')" class="btn btn-primary">
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