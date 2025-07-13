@extends('layouts.admin.main')

@push('script')
<script src="{{ asset('assets/admin/js/modul/dashboard/approval.js') }}"></script>
@endpush


@section('content')

<!--begin::Container-->
<div class="container-xxl" id="kt_content_container">
    <!--begin::approvals-->
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
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_approval" data-url="{{ route('table.approval') }}">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-10px pe-2" data-orderable="false" data-searchable="false">No</th>
                        <th class="min-w-150px">Tanggal</th>
                        <th class="min-w-200px">User</th>
                        <th class="min-w-200px">Pelatihan</th>
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
    <!--end::approvals-->
</div>
<!--end::Container-->


<!-- Modal Tambah approval -->
<div class="modal fade" id="kt_modal_approval" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="title_modal">Approval</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form method="POST" action="{{ route('set.approval') }}" class="modal-body mx-5 mx-xl-15 my-7" id="display_detail_data">
                
            </form>
            <div class="modal-footer">
                <div class="w-100 d-flex justify-content-center align-items-center">
                    <button type="button" onclick="set_approval('Y')" class="btn btn-sm btn-success me-1">Terima</button>
                    <button type="button" onclick="set_approval('N')" class="btn btn-sm btn-danger ms-1">Tolak</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection