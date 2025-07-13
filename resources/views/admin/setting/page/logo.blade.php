<form method="POST" action="{{ route('setting.logo') }}" id="form_ubah_setting">
    <div class="container px-4">

        <!-- ICONS -->
        <div class="row mb-6">
            <label class="col-lg-4 col-form-label fw-semibold fs-6">Icon</label>

            <!-- ICON -->
            <div class="col-lg-4">
                <div class="image-input image-input-outline background-partisi-contain" data-kt-image-input="true" style="background-image: url('{{ image_check('default.jpg','default') }}')">
                    <div class="image-input-wrapper w-100px h-100px background-partisi-contain" style="background-image: url('{{ image_check($result->icon, 'setting') }}')"></div>

                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Ubah data">
                        <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
                        <input type="file" name="icon" accept=".png, .jpg, .ico" />
                        <input type="hidden" name="icon_remove" />
                    </label>

                    <span class="hps_icon btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Batal">
                        <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                    </span>

                    <span class="hps_icon btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Hapus data">
                        <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                    </span>
                </div>
                <div class="form-text text-danger">Icon</div>
                <div class="form-text">Tipe yang didukung: png, jpg, ico</div>
                <input type="hidden" name="name_icon" value="{{ $result->icon }}">
            </div>

            <!-- ICON WHITE -->
            <div class="col-lg-4">
                <div class="image-input image-input-outline background-partisi-contain" data-kt-image-input="true" style="background-color: var(--bs-primary); background-image: url('{{ image_check('default.jpg','default') }}')">
                    <div class="image-input-wrapper w-100px h-100px background-partisi-contain" style="background-image: url('{{ image_check($result->icon_white, 'setting') }}')"></div>

                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Ubah data">
                        <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
                        <input type="file" name="icon_white" accept=".png, .jpg, .ico" />
                        <input type="hidden" name="icon_white_remove" />
                    </label>

                    <span class="hps_icon_white btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Batal">
                        <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                    </span>

                    <span class="hps_icon_white btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Hapus data">
                        <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                    </span>
                </div>
                <div class="form-text text-danger">Icon Putih</div>
                <div class="form-text">Tipe yang didukung: png, jpg, ico</div>
                <input type="hidden" name="name_icon_white" value="{{ $result->icon_white }}">
            </div>
        </div>

        <!-- LOGOS -->
        <div class="row mb-6">
            <label class="col-lg-4 col-form-label fw-semibold fs-6">Logo</label>

            <!-- LOGO -->
            <div class="col-lg-4">
                <div class="image-input image-input-outline background-partisi-contain" data-kt-image-input="true" style="background-image: url('{{ image_check('default.jpg','default') }}')">
                    <div class="image-input-wrapper w-200px h-125px background-partisi-contain" style="background-size: contain; background-image: url('{{ image_check($result->logo, 'setting') }}')"></div>

                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Ubah data">
                        <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
                        <input type="file" name="logo" accept=".png, .jpg" />
                        <input type="hidden" name="logo_remove" />
                    </label>

                    <span class="hps_logo btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Batal">
                        <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                    </span>

                    <span class="hps_logo btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Hapus data">
                        <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                    </span>
                </div>
                <div class="form-text text-danger">Logo</div>
                <div class="form-text">Tipe yang didukung: png, jpg</div>
                <input type="hidden" name="name_logo" value="{{ $result->logo }}">
            </div>

            <!-- LOGO WHITE -->
            <div class="col-lg-4">
                <div class="image-input image-input-outline background-partisi-contain" data-kt-image-input="true" style="background-color: var(--bs-primary); background-image: url('{{ image_check('default.jpg','default') }}')">
                    <div class="image-input-wrapper w-200px h-125px background-partisi-contain" style="background-size: contain; background-image: url('{{ image_check($result->logo_white, 'setting') }}')"></div>

                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Ubah data">
                        <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
                        <input type="file" name="logo_white" accept=".png, .jpg" />
                        <input type="hidden" name="logo_white_remove" />
                    </label>

                    <span class="hps_logo_white btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Batal">
                        <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                    </span>

                    <span class="hps_logo_white btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Hapus data">
                        <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                    </span>
                </div>
                <div class="form-text text-danger">Logo Putih</div>
                <div class="form-text">Tipe yang didukung: png, jpg</div>
                <input type="hidden" name="name_logo_white" value="{{ $result->logo_white }}">
            </div>
        </div>

        <div class="row w-100">
            <div class="col-12 w-100 d-flex justify-content-center">
                <button type="button" id="btn_save_logo" data-loader="big" onclick="submit_form(this,'#form_ubah_setting')" class="btn btn-primary">Simpan</button>
            </div>
        </div>

    </div>
</form>
