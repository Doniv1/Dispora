@if($result && $result->isNotEmpty())
@foreach($result AS $row)
<!--begin::User-->
<div class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed" id="pane-regis-{{ $row->id_regis_training }}">
    <!--begin::Details-->
    <div class="d-flex align-items-center">
        <!--begin::Avatar-->
        <div class="background-partisi" style="background-image : url({{ image_check($row->user->image,'user','user') }});border-radius : 100%; width : 40px; height : 40px;"></div>
        <!--end::Avatar-->
        <!--begin::Details-->
        <div class="ms-5">
            <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">{{ $row->user->name }}</a>
            <div class="fw-semibold text-muted">{{ $row->user->email }}</div>
        </div>
        <!--end::Details-->
    </div>
    <!--end::Details-->
    <!--begin::Access menu-->
    
    <div class="ms-2 w-100px">
        <button class="btn btn-icon btn-sm btn-info me-1 btn-detail" id="btn-detail-{{ $row->id_regis_training }}" type="button" onclick="detail_form(this,{{ $row->id_regis_training }})">
            <i class="fa-solid fa-circle-info fs-3"></i>
        </button>
        <button onclick="hapus_register({{ $row->id_regis_training }})" class="btn btn-icon btn-sm btn-danger" stype="button">
            <i class="fa-solid fa-trash fs-3"></i>
        </button>
    </div>
    <!--end::Access menu-->
</div>
<!--end::User-->
<div class="w-100 card detail-daftar" id="detail-daftar-{{ $row->id_regis_training }}">
    <div style="display : none">
        <div class="w-100 d-flex justify-content-end align-items-center pt-2 px-5">
            <i onclick="close_form({{ $row->id_regis_training }})" class="fa-solid fa-xmark fs-2 cursor-pointer"></i>
        </div>
        <div class="card-body pt-2">

        </div>
    </div>
</div>
@endforeach
@endif


<div class="w-100 d-flex justify-content-center align-items-center flex-column {{ ($result && $result->isNotEmpty()) ? 'd-none' : ''; }}" id="notfound_vector">
    <img src="<?= image_check('empty.svg','default') ?>" alt="" style="max-width : 250px">
    <h3 class="text-primary fs-30 mt-2">Tidak ada member terdaftar</h3>
    <p class="text-muted fs-15 mt-1 text-center" style="max-width : 400px">Belum ada data member ditambahkan! Silahkan hubungi admin jika terjdi kesalahan</p>
</div>