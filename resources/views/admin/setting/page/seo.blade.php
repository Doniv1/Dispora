<!--begin::Form-->
<form id="form_seo_panel" method="POST" class="form" action="{{ route('setting.seo') }}">
    <!--begin::Input group-->
    <div class="row mb-6">
        <label for="meta_title" class="col-lg-4 col-form-label required fw-semibold fs-6">Judul Web</label>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-12 fv-row" id="req_meta_title">
                    <input id="meta_title" value="{{ $result->meta_title }}" type="text" name="meta_title" class="form-control form-control-lg form-control-solid" placeholder="Masukkan judul website" autocomplete="off" />
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="row mb-6">
        <label for="meta_author" class="col-lg-4 col-form-label required fw-semibold fs-6">Nama Author</label>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-12 fv-row" id="req_meta_author">
                    <input id="meta_author" value="{{ $result->meta_author }}" type="text" name="meta_author" class="form-control form-control-lg form-control-solid" placeholder="Masukkan nama author" autocomplete="off" />
                </div>
            </div>
        </div>
    </div> -->

    <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">Nomor Telepon</label>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-12 fv-row" id="parent_phone">
                    @if($phone && $phone->isNotEmpty())
                        @php $no = 1; @endphp
                        @foreach($phone as $row)
                            @php $num = $no++; @endphp
                            <div class="input-group mb-3" id="phone-frame-{{ $num }}">
                                <input type="text" name="name_phone[{{ $num }}]" value="{{ $row->name }}" class="form-control form-control-lg" placeholder="Nama teller (Opsional)" autocomplete="off"/>
                                <span class="input-group-text" id="phone-62-{{ $num }}">+62</span>
                                <input id="phone-{{ $num }}" type="text" name="phone[{{ $num }}]" value="{{ $row->phone }}" class="form-control form-control-lg" placeholder="Masukkan nomor telepon" autocomplete="off" aria-describedby="phone-62-{{ $num }}"/>
                                @if($num == 1)
                                    <button class="btn btn-primary" type="button" onclick="tambah_contact(this)">
                                        <i class="fa fa-plus fs-4"></i>
                                    </button>
                                @else
                                    <button class="btn btn-light-danger" type="button" onclick="hapus_contact({{ $num }})">
                                        <i class="fa fa-trash fs-4"></i>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="input-group mb-3">
                            <input type="text" name="name_phone[1]" class="form-control form-control-lg" placeholder="Masukkan nama teller" autocomplete="off"/>
                            <span class="input-group-text" id="phone-62-1">+62</span>
                            <input id="phone-1" type="text" name="phone[1]" class="form-control form-control-lg" placeholder="Masukkan nomor telepon" autocomplete="off" aria-describedby="phone-62-1"/>
                            <button class="btn btn-primary" type="button" onclick="tambah_contact(this)">
                                <i class="fa fa-plus fs-4"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">Alamat Email</label>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-12 fv-row" id="parent_email">
                    @if($email && $email->isNotEmpty())
                        @php $no = 1; @endphp
                        @foreach($email as $row)
                            @php $num = $no++; @endphp
                            <div class="input-group mb-3" id="email-frame-{{ $num }}">
                                <input id="email-{{ $num }}" type="text" name="email[{{ $num }}]" value="{{ $row->email }}" class="form-control form-control-lg" placeholder="Masukkan alamat email" autocomplete="off" aria-describedby="email-62-{{ $num }}"/>
                                @if($num == 1)
                                    <button class="btn btn-primary" type="button" onclick="tambah_email(this)">
                                        <i class="fa fa-plus fs-4"></i>
                                    </button>
                                @else
                                    <button class="btn btn-light-danger" type="button" onclick="hapus_email({{ $num }})">
                                        <i class="fa fa-trash fs-4"></i>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="input-group mb-3">
                            <input id="email-1" type="text" name="email[1]" class="form-control form-control-lg" placeholder="Masukkan alamat email" autocomplete="off"/>
                            <button class="btn btn-primary" type="button" onclick="tambah_email(this)">
                                <i class="fa fa-plus fs-4"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="row mb-6">
        <label for="meta_keyword_website" class="col-lg-4 col-form-label fw-semibold fs-6">Kata Kunci Pencarian</label>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-12 fv-row" id="req_meta_keyword">
                    <input class="form-control form-control-lg form-control-solid ps-4" value="{{ $result->meta_keyword }}" placeholder="Masukkan keyword website" name="meta_keyword" id="keyword_website"/>
                </div>
            </div>
        </div>
    </div> -->

    <div class="row mb-6">
        <label for="meta_address" class="col-lg-4 col-form-label fw-semibold fs-6">Alamat</label>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-12 fv-row" id="req_meta_address">
                    <textarea name="meta_address" id="meta_address" cols="30" rows="3" class="form-control form-control-lg form-control-solid" placeholder="Masukkan alamat">{{ $result->meta_address }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="row mb-6">
        <label for="meta_description" class="col-lg-4 col-form-label fw-semibold fs-6">Deskripsi Singkat Website</label>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-12 fv-row" id="req_meta_description">
                    <textarea name="meta_description" id="meta_description" cols="30" rows="3" class="form-control form-control-lg form-control-solid" placeholder="Masukkan deskripsi singkat website">{{ $result->meta_description }}</textarea>
                </div>
            </div>
        </div>
    </div> -->

    <div class="row w-100">
        <div class="col-12 w-100 d-flex justify-content-center">
            <button type="button" id="btn_save_seo" data-loader="big" onclick="submit_form(this,'#form_seo_panel')" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</form>
<!--end::Form-->