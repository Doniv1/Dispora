<!-- /.content-wrapper -->
<footer class="bg-navy text-inverse">
    <div class="container pt-12 pt-lg-6 pb-13 pb-md-15">
        <hr class="mt-11 mb-12" />
        <div class="row gy-6 gy-lg-0">
            <div class="col-md-4 col-lg-3">
                <div class="widget">
                    @if ($setting->logo_white && file_exists(public_path('data/setting/' . $setting->logo_white)))
                        <div class="background-partisi-contain"
                            style="width : 200px;height : 80px;background-image : url('{{ image_check($setting->logo_white, 'setting') }}');">
                        </div>
                    @endif
                    @php
                        $status = false;
                        if (isset($sosmed) && $sosmed->isNotEmpty()) {
                            foreach ($sosmed as $row) {
                                if ($row->url != '') {
                                    $status = true;
                                }
                            }
                        }
                    @endphp


                    @if ($status == true)
                        <nav class="nav social social-white">
                            @foreach ($sosmed as $row)
                                @if ($row->url != '')
                                    <a href="{{ $row->url }}" title="{{ $row->name_sosmed }}"><i
                                            class="{{ $row->icon }}"></i></a>
                                @endif
                            @endforeach
                        </nav>
                    @endif

                    <!-- /.social -->
                </div>
                <!-- /.widget -->
            </div>
            <!-- /column -->
            @if ($setting->meta_address || $web_phone->isNotEmpty() || $web_email->isNotEmpty())
                <div class="col-md-4 col-lg-3">
                    <div class="widget">
                        <h4 class="widget-title text-white mb-3">Kontak</h4>
                        <address class="pe-xl-15 pe-xxl-17">{{ $setting->meta_address }}</address>
                        @if ($web_email && $web_email->isNotEmpty())
                            @foreach ($web_email as $row)
                                <a href="mailto:{{ $row->email }}">{{ $row->email }}</a><br />
                            @endforeach
                        @endif
                        @if ($web_phone && $web_phone->isNotEmpty())
                            @foreach ($web_phone as $row)
                                {{ $row->name ? $row->name . ' | ' . phone_format('0' . $row->phone) : phone_format('0' . $row->phone) }}
                                <br />
                            @endforeach
                        @endif

                    </div>
                    <!-- /.widget -->
                </div>
            @endif
            <!-- /column -->
            <div class="col-md-4 col-lg-3">
                <div class="widget">
                    <h4 class="widget-title text-white mb-3">Menu</h4>
                    <ul class="list-unstyled  mb-0">
                        <li><a class="scrollto" href="{{ route('home') }}#home">Home</a></li>
                        <li><a class="scrollto" href="{{ route('home') }}#about">Tentang </a></li>
                        <li><a class="scrollto" href="{{ route('home') }}#contact">Hubungi kami</a></li>
                    </ul>
                </div>
                <!-- /.widget -->
            </div>
            <!-- /column -->
           
            <!-- /column -->
        </div>
        <!--/.row -->
        <p class="mb-4">©{{ $setting->meta_title }}.</p>
    </div>
    <!-- /.container -->
</footer>
<div class="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
    </svg>
</div>


<div class="modal fade" id="modalLogin" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"
    aria-labelledby="modalLoginLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded">
            <div class="modal-body">
                <div class="w-100 d-flex justify-content-end align-items-center px-4">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-xmark"></i></button>
                </div>
                <form id="formLogin" method="POST" action="{{ route('auth.login') }}" class="pt-5">
                    <div class="mb-3 text-center">
                        <h2 class="form-label">Login</h2>
                    </div>


                    <div class="mb-3" id="req_login_email">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email"
                            placeholder="Masukkan email Anda" autocomplete="off">
                    </div>

                    <div class="mb-3 position-relative" id="req_login_password">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Masukkan kata sandi anda" autocomplete="off">
                            <span class="input-group-text toggle-password" data-target="password"
                                style="cursor: pointer;">
                                <i class="fa-solid fa-eye-slash"></i>
                            </span>
                        </div>
                    </div>

                    <button onclick="submit_form(this,'#formLogin')" id="button_login" type="button"
                        class="btn btn-primary w-100">Masuk</button>

                    <div class="text-center mt-3">
                        <p>Belum memiliki akun? <a role="button" onclick="toAuth('#formRegister','#formLogin')"
                                class="text-primary">Daftar sekarang</a></p>
                    </div>
                </form>


                <form id="formRegister" class="pt-5 d-none" method="POST" action="{{ route('auth.register') }}">
                    <div id="pane_register">
                        <div class="mb-3 text-center">
                            <h2 class="form-label">Daftar</h2>
                        </div>

                        <div class="mb-3" id="req_name">
                            <label for="register-name" class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" id="register-name"
                                placeholder="Masukkan nama lengkap Anda" autocomplete="off">
                        </div>
                        <div class="mb-3" id="req_gender">
                            <label for="register-gender" class="form-label">Jenis Kelamin</label>
                            <select name="gender" id="gender" class="form-select">
                                <option value="">-- Pilih salah satu --</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>

                        <div class="mb-3" id="req_born_date">
                            <label for="born_date" class="form-label">Tanggal Lahir</label>
                            <input type="date" name="born_date" class="form-control" id="register-born_date"
                                placeholder="Masukkan tanggal lahir anda" autocomplete="off">
                        </div>

                        <div class="mb-3" id="req_education_status">
                            <label for="register-education_status" class="form-label">Status Pendidikan</label>
                            <select name="education_status" id="education_status" class="form-select">
                                <option value="">-- Pilih salah satu --</option>
                                <option value="SMA">SMA</option>
                                <option value="SMK">SMK</option>
                                <option value="Mahasiswa">Mahasiswa</option>
                            </select>
                        </div>

                        <div class="mb-3" id="req_phone">
                            <label for="phone-number" class="form-label">Nomor Telpon</label>
                            <input type="phone-number" name="phone" class="form-control" id="register-email"
                                placeholder="Masukkan nomor telepon Anda" autocomplete="off">
                        </div>

                        <div class="mb-3" id="req_email">
                            <label for="register-email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="register-email"
                                placeholder="Masukkan email Anda" autocomplete="off">
                        </div>


                        <div class="mb-3 position-relative" id="req_password">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" id="regis_password"
                                    placeholder="Masukkan kata sandi anda" autocomplete="off">
                                <span class="input-group-text toggle-password" data-target="regis_password"
                                    style="cursor: pointer;">
                                    <i class="fa-solid fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-3 position-relative" id="req_repassword">
                            <label for="password" class="form-label">Konfirmasi Kata Sandi</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="repassword" id="regis_repassword"
                                    placeholder="Masukkan konfirmasi kata sandi anda" autocomplete="off">
                                <span class="input-group-text toggle-password" data-target="regis_repassword"
                                    style="cursor: pointer;">
                                    <i class="fa-solid fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>


                        <button role="button" id="button_regis_1"
                            onclick="submit_form(this,'#formRegister', '', false, false,'',`{{ route('auth.register.first') }}`)"
                            class="btn btn-primary w-100 text-white rounded-3 shadow-sm hover-shadow">Selanjutnya</button>

                    </div>

                    <div id="pane_vector" class="d-none">
                        <div class="mb-3 text-center">
                            <h2 class="text-blue-950 form-label">Isi preferensi anda</h2>
                        </div>

                        {{-- Pertanyaan 1: Minat --}}
                        @if ($vector && $vector->isNotEmpty())
                            <div class="mb-4">
                                <label class="form-label fw-bold">1. Pilih minat anda</label>
                                <div class="row w-100">
                                    <select name="id_vector" id="vector" class="form-select">
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
                            <div class="mb-4">
                                <label class="form-label fw-bold">2. Riwayat program pelatihan yang pernah
                                    diikuti</label>
                                <div class="row w-100">
                                    <select name="id_riwayat_pelatihan" id="riwayat_pelatihan" class="form-select">
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

                        {{-- Tombol --}}
                        <div class="w-100 d-flex row">
                            <!-- <div class="col-md-6 d-flex justify-content-center align-items-center mb-2">
                                <button role="button" id="cancel_register" onclick="cancel_register()"
                                    class="btn btn-secondary w-100 text-white rounded-3 shadow-sm hover-shadow">Kembali</button>
                            </div> -->
                            <div class="justify-content-center align-items-center mb-2">
                                <button role="button" id="button_register"
                                    onclick="submit_form(this,'#formRegister')"
                                    class="btn btn-primary w-100 text-white rounded-3 shadow-sm hover-shadow">Daftar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>



            <div class="text-center mt-3">
                <p>Sudah memiliki akun? <a role="button" class="text-primary"
                        onclick="toAuth('#formLogin','#formRegister')">Login di sini</a></p>
            </div>


            </form>
        </div>
    </div>
</div>
</div>



<div class="modal fade" id="modalDetailTraining" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-hidden="true" aria-labelledby="modalDetailTrainingLabel" tabindex="-1">
    <div
        class="modal-dialog modal-dialog-centered {{ session(config('session.prefix') . '_id_user') ? 'modal-xl' : 'modal-lg' }}">
        <div class="modal-content rounded">
            <div class="modal-body">
                <div class="w-100 d-flex justify-content-end align-items-center px-4">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="row">
                    <div
                        class="{{ session(config('session.prefix') . '_id_user') ? 'col-lg-7 col-md-12' : 'col-12' }}">
                        @if (!session(config('session.prefix') . '_id_user'))
                            <div class="w-100 alert alert-primary">
                                Login terlebih dahulu untuk melakukan pendaftaran
                            </div>
                        @endif
                        <div class="d-flex justify-content-start align-items-start flex-column">
                            <div class="rounded background-partisi w-100 mb-4 d-flex justify-content-start align-items-start"
                                id="display_image_training"
                                style="height : 300px;background-image : url({{ image_check('default', 'training') }})">
                                <span class="bg-primary px-3 text-white fs-15 rounded mx-3 my-4"
                                    id="display_category_training"></span>
                            </div>
                            <h2 class="text-primary mt-2" id="display_title_training"></h2>
                            <div id="display_description_training"></div>
                        </div>
                    </div>
                    @if (session(config('session.prefix') . '_id_user'))
                        @if ($form && $form->isNotEmpty())
                            <div class="col-lg-5 col-md-12">
                                <form class="contact-form needs-validation" id="form_pendaftaran_pelatihan"
                                    method="post" action="{{ route('register.training') }}" novalidate>
                                    <h3 class="text-primary">FORM PENDAFTARAN</h3>
                                    <input type="hidden" name="id_training" id="pendaftaran_id_training">
                                    <div class="row gx-4">
                                        @foreach ($form as $row)
                                            @if (!in_array($row->type, [3, 4]))
                                                <div class="col-md-12">
                                                    <div class="form-floating mb-4"
                                                        id="req_regis_training_field_{{ $row->id_form }}">
                                                        <input id="form_pendaftaran_{{ $row->id_form }}"
                                                            type="{{ set_form_type($row->type) }}"
                                                            name="field_{{ $row->id_form }}" class="form-control"
                                                            placeholder="Enter {{ $row->field }}" required
                                                            autocomplete="off">
                                                        <label
                                                            for="form_pendaftaran_{{ $row->id_form }}">{{ $row->field }}
                                                            *</label>
                                                    </div>
                                                </div>
                                            @elseif($row->type == 4)
                                                <div class="col-md-12">
                                                    <label style="color : #969eab;"
                                                        for="form_pendaftaran_{{ $row->id_form }}">{{ $row->field }}
                                                        *</label>
                                                    <div class="form-floating mb-4"
                                                        id="req_regis_training_field_{{ $row->id_form }}">
                                                        <input id="form_pendaftaran_{{ $row->id_form }}"
                                                            type="{{ set_form_type($row->type) }}"
                                                            name="field_{{ $row->id_form }}" class="form-control"
                                                            placeholder="Enter {{ $row->field }}" required
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-12">
                                                    <div class="form-floating mb-4"
                                                        id="req_regis_training_field_{{ $row->id_form }}">
                                                        <textarea name="field_{{ $row->id_form }}" id="form_pendaftaran_{{ $row->id_form }}" cols="30" rows="10"
                                                            class="form-control" placeholder="Enter {{ $row->field }}"></textarea>
                                                        <label
                                                            for="form_pendaftaran_{{ $row->id_form }}">{{ $row->field }}
                                                            *</label>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                        <!-- /column -->
                                        <div class="col-12 d-flex justify-content-center align-items-center">
                                            <button type="button" id="submit_form_pendaftaran_pelatihan"
                                                onclick="submit_form(this,'#form_pendaftaran_pelatihan')"
                                                class="btn btn-primary rounded-pill btn-send mb-3">Kirim</button>
                                        </div>
                                        <!-- /column -->
                                    </div>
                                    <!-- /.row -->
                                </form>
                                <!-- /form -->
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
