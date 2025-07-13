@extends('layouts.admin.main')

@push('script')
    <script src="{{ asset('assets/admin/js/modul/master/user.js') }}"></script>
@endpush


@section('content')
    <!--begin::Container-->
    <div class="container-xxl" id="kt_content_container">
        <!--begin::users-->
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
                        <select onchange="filter_status(this)" class="form-select form-select-solid table-filter"
                            data-control="select2">
                            <option value="all">All</option>
                            <option value="Y">Account Active</option>
                            <option value="N">Account Inactive</option>
                        </select>
                        <!--end::Select2-->
                    </div>
                    <!--begin::Add user-->
                    <a role="button" onclick="tambah_data()" data-bs-toggle="modal" data-bs-target="#kt_modal_user"
                        class="btn btn-primary">Tambah User</a>
                    <!--end::Add user-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_user"
                    data-url="{{ route('table.user') }}">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th>No</th>
                            <th>User</th>
                            <th>Kontak</th>
                            <th>Gender</th>
                            <th>Tgl Lahir</th>
                            <th>Pendidikan</th>
                            <th>Minat</th>
                            <th>Riwayat</th>
                            <th>Kategori</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                    </tbody>
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::users-->
    </div>
    <!--end::Container-->



    <!-- Modal Tambah user -->
    <div class="modal fade" id="kt_modal_user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="title_modal" data-title="Edit User|Add User"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body mx-5 mx-xl-15 my-7">
                    <!--begin::Form-->
                    <form id="form_user" class="form" action="{{ route('insert.user') }}" method="POST"
                        enctype="multipart/form-data">
                        <!--begin::Scroll-->
                        <div class="d-flex flex-column me-n7 pe-7" id="#">

                            <!--begin::Input group-->
                            <div class="fv-row mb-7 d-flex justify-content-center align-items-center flex-column">
                                <!--begin::Label-->
                                <label class="d-block fw-semibold fs-6 mb-5">Image</label>
                                <!--end::Label-->
                                <!--begin::Image input-->
                                <div class="image-input image-input-circle background-partisi" data-kt-image-input="true"
                                    style="background-image: url('<?= image_check('user.jpg', 'default') ?>')">
                                    <!--begin::Image preview wrapper-->
                                    <div id="display_image" class="image-input-wrapper w-125px h-125px background-partisi"
                                        style="background-image: url('<?= image_check('user.jpg', 'default') ?>')"></div>
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
                                        data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-bs-dismiss="click"
                                        title="Cancel">
                                        <i class="ki-outline ki-cross fs-3"></i>
                                    </span>
                                    <!--end::Cancel button-->

                                    <!--begin::Remove button-->
                                    <span
                                        class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow hps_image"
                                        data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-bs-dismiss="click"
                                        title="Delete">
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

                            <input type="hidden" name="id_user">
                            <input type="hidden" name="role" value="2">
                            <!--begin::Input group-->
                            <div class="fv-row mb-7" id="req_name">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">Nama Lengkap</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0"
                                    placeholder="Enter Full Name" autocomplete="off" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-7" id="req_phone">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">Nomor Telepon</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="phone" class="form-control form-control-solid mb-3 mb-lg-0"
                                    placeholder="Enter Phone Number" autocomplete="off" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <div class="mb-3" id="req_gender">
                                <label for="register-gender" class="form-label">Jenis Kelamin</label>
                                <select name="gender" id="gender" class="form-control form-control-solid mb-3 mb-lg-0">
                                    <option value="">-- Pilih salah satu --</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>

                            <div class="mb-3" id="req_born_date">
                                <label for="born_date" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="born_date" class="form-control form-control-solid mb-3 mb-lg-0" id="register-born_date"
                                    placeholder="Masukkan tanggal lahir anda" autocomplete="off">
                            </div>

                            <div class="mb-3" id="req_education_status">
                                <label for="register-education_status" class="form-label">Status Pendidikan</label>
                                <select name="education_status" id="education_status" class="form-control form-control-solid mb-3 mb-lg-0">
                                    <option value="">-- Pilih salah satu --</option>
                                    <option value="SMA">SMA</option>
                                    <option value="SMK">SMK</option>
                                    <option value="Mahasiswa">Mahasiswa</option>
                                </select>
                            </div>

                            @if ($vector && $vector->isNotEmpty())
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Minat</label>
                                    <div class="">
                                        <select name="id_vector" id="vector" class="form-control form-control-solid mb-3 mb-lg-0">
                                            <option value="">-- Pilih salah satu --</option>
                                            @foreach ($vector as $row)
                                                <option value="{{ $row->id_vector }}">{{ $row->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            {{-- Pertanyaan 2: Riwayat Program Pelatihan --}}
                            @if ($training && $training->isNotEmpty())
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Riwayat program pelatihan yang pernah diikuti</label>
                                    <div class="">
                                        <select name="id_riwayat_pelatihan" id="riwayat_pelatihan" class="form-control form-control-solid mb-3 mb-lg-0">
                                            <option value="">-- Pilih salah satu --</option>
                                            <option value="">Belum Pernah Mengikuti Pelatihan</option>
                                            @foreach ($training as $row)
                                                <option value="{{ $row->id_training }}">{{ $row->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <!--begin::Input group-->
                            <div class="fv-row mb-7" id="req_email">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">Alamat Email</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="email" name="email" class="form-control form-control-solid mb-3 mb-lg-0"
                                    placeholder="Enter Email Address" autocomplete="off" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-7" id="req_password">
                                <!--begin::Label-->
                                <label class="required password fw-semibold fs-6 mb-2">Password</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="password" name="password"
                                    class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Enter Password"
                                    autocomplete="off" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-7" id="req_repassword">
                                <!--begin::Label-->
                                <label class="required password fw-semibold fs-6 mb-2">Konfirmasi password</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="password" name="repassword"
                                    class="form-control form-control-solid mb-3 mb-lg-0"
                                    placeholder="Enter Password Confirmation" autocomplete="off" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                        </div>
                        <!--end::Scroll-->
                        <!--begin::Actions-->
                        <div class="text-center pt-15">
                            <button type="button" id="submit_user" onclick="submit_form(this,'#form_user')"
                                class="btn btn-primary">
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
