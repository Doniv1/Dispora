@extends('layouts.admin.main')

@push('script')
<script src="{{ asset('assets/admin/js/modul/setting/umum.js') }}"></script>
@endpush


@section('content')
<!--begin::forms-->
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
            <!--begin::Add form-->
            <a role="button"  onclick="tambah_form()" data-bs-toggle="modal" data-bs-target="#kt_modal_form"  class="btn btn-primary">Tambah Form</a>
            <!--end::Add form-->
        </div>
        <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body pt-0 table-responsive">
        <!--begin::Table-->
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_form" data-url="{{ route('table.form') }}">
            <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-10px pe-2">Urutan</th>
                    <th class="min-w-200px">Field</th>
                    <th class="min-w-200px">Type</th>
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
<!--end::forms-->

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