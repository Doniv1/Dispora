<!--begin::Form-->
<form id="form_about_panel" method="POST" class="form" action="{{ route('update.about') }}">
    <!--begin::Input group-->
    <div class="row mb-6">
        <label for="about" class="col-lg-4 col-form-label fw-semibold fs-6">Text about us</label>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-12 fv-row" id="req_about">
                    <textarea name="about" id="about" class="form-control form-control-lg form-control-solid" placeholder="Masukkan about us" autocomplete="off" cols="30" rows="5">{{ $result->about }}</textarea>
                </div>
            </div>
        </div>
    </div>


    <div class="row w-100">
        <div class="col-12 w-100 d-flex justify-content-center">
            <button type="button" id="btn_save_about" data-editor="about" data-loader="big" onclick="submit_form(this,'#form_about_panel')" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</form>
<!--end::Form-->
