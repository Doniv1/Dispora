<!--begin::Form-->
<form id="form_sosmed_panel" method="POST" class="form" action="{{ route('setting.sosmed') }}">
    @csrf
    <div class="row w-100">
        <div class="col-12 w-100 d-flex justify-content-end mb-5">
            <button class="btn btn-primary" onclick="tambah_sosmed()" type="button" data-bs-toggle="modal" data-bs-target="#kt_modal_sosmed">Tambah Sosmed</button>
        </div>
    </div>

    @if($sosmed)
        @foreach($sosmed as $row)
        <!--begin::Input group-->
        <div class="row mb-6">
            <!--begin::Label-->
            <label for="input_{{ $row->id_sosmed }}" class="col-lg-4 col-form-label required fw-semibold fs-6">{{ ucwords($row->name) }}</label>
            <!--end::Label-->
            <!--begin::Col-->
            <div class="col-lg-8">
                <!--begin::Row-->
                <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-9 col-sm-9 fv-row">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="icon-{{ $row->id_sosmed }}">
                                <i class="{{ $row->icon }}"></i>
                            </span>
                            <input type="text" name="name_sosmed[{{ $row->id_sosmed }}]" value="{{ $row->name_sosmed }}" id="input_name_{{ $row->id_sosmed }}" class="form-control" placeholder="Masukkan username ({{ $row->name }})" aria-label="Masukkan username ({{ $row->name }})" autocomplete="off">
                            <input type="text" name="sosmed[{{ $row->id_sosmed }}]" value="{{ $row->url }}" id="input_{{ $row->id_sosmed }}" class="form-control" placeholder="Masukkan url ({{ $row->name }})" aria-label="Masukkan url ({{ $row->name }})" aria-describedby="icon-{{ $row->id_sosmed }}" autocomplete="off">
                        </div>
                    </div>
                    <!--end::Col-->
                    <!--begin::Col untuk tombol-->
                    <div class="col-lg-3 col-sm-3">
                        <button type="button" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm btn-lg me-1" title="Ubah Data" onclick="ubah_sosmed(this, {{ $row->id_sosmed }})" data-bs-toggle="modal" data-bs-target="#kt_modal_sosmed">
                            <i class="ki-outline ki-pencil fs-2"></i>
                        </button>
                        <button type="button" onclick="hapus_data(this, event, {{ $row->id_sosmed }}, 'sosmeds', 'id_sosmed')" data-reload="big" title="Hapus Data" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm btn-lg">
                            <i class="ki-outline ki-trash fs-2"></i>
                        </button>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
            </div>
            <!--end::Col-->
        </div>
        <!--end::Input group-->
        @endforeach
    @else
        <div class="text-center mb-6">TIDAK ADA DATA SOSMED</div>
    @endif

    <div class="row w-100">
        <div class="col-12 w-100 d-flex justify-content-center">
            <button type="button" id="btn_save_sosmed" data-loader="big" onclick="submit_form(this,'#form_sosmed_panel')" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</form>
<!--end::Form-->
