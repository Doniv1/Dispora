@extends('layouts.admin.main')

@push('script')
    <script src="{{ asset('assets/admin/js/modul/master/training.js') }}"></script>
@endpush


@section('content')

    <!--begin::Container-->
    <div class="container-xxl" id="kt_content_container">
        <!--begin::trainings-->
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
                        <input type="text" class="form-control form-control-solid w-250px ps-12 search-datatable"
                            placeholder="Search" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <div class="w-100 mw-150px">
                        <!--begin::Select2-->
                        <select onchange="filter_category(this)" class="form-select form-select-solid table-filter me-2"
                            data-control="select2">
                            <option value="all">All</option>
                            @if ($category && $category->isNotEmpty())
                                @foreach ($category as $row)
                                    <option value="{{ $row->id_category }}">{{ $row->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <!--end::Select2-->
                    </div>
                    <div class="w-100 mw-150px">
                        <!--begin::Select2-->
                        <select onchange="filter_status(this)" class="form-select form-select-solid table-filter"
                            data-control="select2">
                            <option value="all">All</option>
                            <option value="Y">Account Active</option>
                            <option value="N">Account Inactive</option>
                        </select>
                        <!--end::Select2-->
                    </div>
                    <!--begin::Add training-->
                    <a role="button" onclick="tambah_data()" data-bs-toggle="modal" data-bs-target="#kt_modal_training"
                        class="btn btn-primary">Tambah Data Pelatihan</a>
                    <!--end::Add training-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_training"
                    data-url="{{ route('table.training') }}">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-10px pe-2" data-orderable="false" data-searchable="false">No</th>
                            <th class="min-w-200px" data-orderable="false" data-searchable="false">Media</th>
                            <th class="min-w-200px">Judul</th>
                            <th class="min-w-100px">Kategori</th>
                            <th class="min-w-100px">Jumlah Pendaftar</th>
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
        <!--end::trainings-->
    </div>
    <!--end::Container-->



    <!-- Modal Tambah training -->
    <div class="modal fade" id="kt_modal_training" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="title_modal" data-title="Edit Pelatihan|Tambah Pelatihan"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body mx-5 mx-xl-15 my-7">
                    <!--begin::Form-->
                    <form id="form_training" class="form" action="{{ route('insert.training') }}" method="POST"
                        enctype="multipart/form-data">
                        <!--begin::Scroll-->
                        <div class="d-flex flex-column me-n7 pe-7" id="#">

                            <!--begin::Input group-->
                            <div class="fv-row mb-7 d-flex justify-content-center align-items-center flex-column">
                                <!--begin::Label-->
                                <label class="d-block fw-semibold fs-6 mb-5">Image</label>
                                <!--end::Label-->
                                <!--begin::Image input-->
                                <div class="image-input background-partisi" data-kt-image-input="true"
                                    style="background-image: url('<?= image_check('default.jpg', 'default') ?>')">
                                    <!--begin::Image preview wrapper-->
                                    <div id="display_image" class="image-input-wrapper w-200px h-125px background-partisi"
                                        style="background-image: url('<?= image_check('default.jpg', 'default') ?>')"></div>
                                    <!--end::Image preview wrapper-->

                                    <!--begin::Edit button-->
                                    <label
                                        class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="click"
                                        title="Edit">
                                        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span
                                                class="path2"></span></i>

                                        <!--begin::Inputs-->
                                        <input type="file" name="image" accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" name="avatar_remove" />
                                        <input type="hidden" name="name_image" />
                                        <!--end::Inputs-->
                                    </label>
                                    <!--end::Edit button-->

                                    <!--begin::Cancel button-->
                                    <span
                                        class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow hps_image"
                                        data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                        data-bs-dismiss="click" title="Cancel">
                                        <i class="ki-outline ki-cross fs-3"></i>
                                    </span>
                                    <!--end::Cancel button-->

                                    <!--begin::Remove button-->
                                    <span
                                        class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow hps_image"
                                        data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                        data-bs-dismiss="click" title="Delete">
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
                            <input type="hidden" name="id_training">
                            <!--begin::Input group-->
                            <div class="fv-row mb-7" id="req_title">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">Judul</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="title" class="form-control form-control-solid mb-3 mb-lg-0"
                                    placeholder="Enter Title" autocomplete="off" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-7" id="req_id_category">
                                <!--begin::Label-->
                                <label id="label_id_category"
                                    class="id_category required fw-semibold fs-6 mb-2">Kategori</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select id="select_id_category" name="id_category" class="form-select form-select-solid"
                                    data-control="select2" data-placeholder="Pilih Kategori">
                                    <option value="">Pilih Kategori</option>
                                    <?php if($category) : ?>
                                    <?php foreach($category as $row) : ?>
                                    <option value="<?= $row->id_category ?>" <?= $row->status == 'N' ? 'disabled' : '' ?>>
                                        <?= $row->name ?></option>
                                    <?php endforeach;?>
                                    <?php endif;?>
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-7" id="req_sort_description">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">Sort Deskripsi</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <textarea name="sort_description" id="sort_description" cols="30" rows="5"
                                    class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Enter Sort Description"></textarea>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-7" id="req_description">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">Deskripsi</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <textarea name="description" id="description" cols="30" rows="10"
                                    class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Enter Description"></textarea>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            
                        </div>
                        <!--end::Scroll-->
                        <!--begin::Actions-->
                        <div class="text-center pt-15">
                            <button type="button" id="submit_training" data-editor="description"
                                onclick="submit_form(this,'#form_training')" class="btn btn-primary">
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

    <!--begin::Modal - Invite Friends-->
    <div class="modal fade" id="kt_modal_register" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--begin::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
                    <!--begin::Heading-->
                    <div class="text-center mb-10">
                        <h1 class="mb-3 fs-2x fw-bold text-dark">📋 Peserta Terdaftar</h1>
                        <div class="text-gray-600 fs-6">
                            Berikut adalah daftar user yang telah mendaftar pada pelatihan: <br>
                            <b class="text-primary" id="title_modal_register"></b>
                        </div>

                        <!--begin::Tools-->
                        <div class="text-center mt-5">
                            <a href="#" id="btn-download-register"
                                class="btn btn-light-primary btn-sm px-5 py-2 fw-bold">
                                <i class="ki-duotone ki-download fs-3 me-2"></i>Unduh Daftar Peserta
                            </a>
                        </div>
                        <!--end::Tools-->
                    </div>
                    <!--end::Heading-->

                    <!--begin::Users-->
                    <div class="mb-10">
                        <div class="mh-300px scroll-y me-n7 pe-7" id="display_modal_register">
                        </div>
                    </div>
                    <!--end::Users-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>
    <!--end::Modal - Invite Friend-->
@endsection
