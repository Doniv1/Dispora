@extends('layouts.user.main')
@section('content')


    @if ($banner && $banner->isNotEmpty())
        <section class="wrapper bg-dark">
            <div class="swiper-container swiper-hero dots-over" data-margin="0" data-autoplay="true" data-autoplaytime="7000"
                data-nav="true" data-dots="true" data-items="1">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        @foreach ($banner as $row)
                            <div class="swiper-slide bg-overlay bg-overlay-400 bg-dark bg-image"
                                data-image-src="{{ image_check($row->image, 'banner') }}">
                                <div class="container h-100">
                                    <div class="row h-100">
                                        <div
                                            class="col-md-10 offset-md-1 col-lg-7 offset-lg-0 col-xl-6 col-xxl-5 text-center text-lg-start justify-content-center align-self-center align-items-start">
                                            <h2
                                                class="display-1 fs-56 mb-4 text-white animate__animated animate__slideInDown animate__delay-1s">
                                                {{ $row->title }}</h2>
                                            <p
                                                class="lead fs-23 lh-sm mb-7 text-white animate__animated animate__slideInRight animate__delay-2s">
                                                {{ $row->description }}</p>
                                        </div>
                                        <!--/column -->
                                    </div>
                                    <!--/.row -->
                                </div>
                                <!--/.container -->
                            </div>
                        @endforeach
                    </div>
                    <!--/.swiper-wrapper -->
                </div>
        </section>
    @endif

    @if (session(config('session.prefix') . '_id_user'))
        <section class="wrapper bg-light" id="home">
            <div class="overflow-hidden">
                <div class="container py-14 py-md-16">
                    <div class="row">
                        <div class="col-xl-7 col-xxl-6 mx-auto text-center">
                            <h2 class="display-5 text-center mt-2 mb-10">Rekomendasi Pelatihan</h2>
                        </div>
                        <!--/column -->
                    </div>
                    @if ($recommended && !empty($recommended))
                        <!--/.row -->
                        <div class="swiper-container nav-bottom nav-color mb-14 swiper-container-3" data-margin="30"
                            data-dots="false" data-nav="true" data-items-lg="3" data-items-md="2" data-items-xs="1">
                            <div
                                class="swiper overflow-visible pb-2 swiper-initialized swiper-horizontal swiper-backface-hidden">
                                <div class="swiper-wrapper" id="swiper-wrapper-8af71f4d98f5d8e0" aria-live="off"
                                    style="cursor: grab; transform: translate3d(-1140px, 0px, 0px); transition-duration: 0ms; transition-delay: 0ms;">
                                    @foreach ($recommended as $row)
                                        <div class="swiper-slide" role="group" style="width: 350px; margin-right: 30px;">
                                            <article>
                                                <div role="button"
                                                    data-image="{{ image_check($row['training']->image, 'training') }}"
                                                    onclick="detail_training(this,{{ $row['training']->id_training }})"
                                                    data-bs-target="#modalDetailTraining" data-bs-toggle="modal"
                                                    class="card shadow-lg">
                                                    <figure class="card-img-top overlay overlay-1" style="height : 220px">
                                                        <a role="button">
                                                            <img src="{{ image_check($row['training']->image, 'training') }}"
                                                                srcset="{{ image_check($row['training']->image, 'training') }} 2x"
                                                                alt="">
                                                            <span class="bg"></span>
                                                        </a>
                                                        <figcaption>
                                                            <h5 class="from-top mb-0">Read More</h5>
                                                        </figcaption>
                                                    </figure>
                                                    <div class="card-body p-6" style="height : 230px">
                                                        <div class="post-header">
                                                            <div class="post-category">
                                                                <a role="button" class="hover"
                                                                    rel="category">{{ $row['training']->category->name }}</a>
                                                            </div>
                                                            <!-- /.post-category -->
                                                            <h2 class="post-title h4 mt-1 mb-1"><a class="link-dark"
                                                                    role="button">{{ short_text($row['training']->title, 40) }}</a>
                                                            </h2>
                                                            <p class="h10 mt-1 mb-3">
                                                                {{ short_text($row['training']->sort_description, 60) }}</p>
                                                        </div>
                                                        <!-- /.post-header -->
                                                        <div class="post-footer">
                                                            <ul class="post-meta d-flex mb-0">
                                                                <li class="post-date"><i
                                                                        class="uil uil-calendar-alt"></i><span>{{ date_to_word($row['training']->created_at) }}</span>
                                                                </li>
                                                            </ul>
                                                            <!-- /.post-meta -->
                                                        </div>
                                                        <!-- /.post-footer -->
                                                    </div>
                                                    <!--/.card-body -->
                                                </div>
                                                <!-- /.card -->
                                            </article>
                                            <!-- /article -->
                                        </div>
                                        <!--/.swiper-slide -->
                                    @endforeach
                                </div>
                                <!--/.swiper-wrapper -->
                                <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                            </div>
                            <!-- /.swiper -->
                            <div class="swiper-controls">
                                <div class="swiper-navigation">
                                    <div class="swiper-button swiper-button-prev" tabindex="0" role="button"
                                        aria-label="Previous slide" aria-controls="swiper-wrapper-8af71f4d98f5d8e0"
                                        aria-disabled="false"></div>
                                    <div class="swiper-button swiper-button-next swiper-button-disabled" tabindex="-1"
                                        role="button" aria-label="Next slide"
                                        aria-controls="swiper-wrapper-8af71f4d98f5d8e0" aria-disabled="true"></div>
                                </div>
                            </div>
                        </div>
                        <!-- /.swiper-container -->
                </div>
                <!-- /.container -->
            @else
                <div class="w-100 d-flex justify-content-center align-items-center flex-column">
                    <img src="<?= image_check('empty.svg', 'default') ?>" alt="" style="max-width : 250px">
                    <h3 class="text-primary fs-30 mt-2">Tidak ada data rekomendasi</h3>
                    <p class="text-muted fs-15 mt-1 text-center" style="max-width : 400px">Belum ada data rekomendasi
                        pelatihan ditambahkan! Silahkan hubungi admin jika terjdi kesalahan</p>
                </div>
    @endif
    </div>
    <!-- /.overflow-hidden -->
    </section>
    @endif

    <section class="wrapper bg-soft-primary">
        <div class="overflow-hidden">
            <div class="container py-14 py-md-16">
                <div class="row">
                    <div class="col-xl-7 col-xxl-6 mx-auto text-center">
                        <h2 class="display-5 text-center mt-2 mb-10">Pelatihan terbaru</h2>
                    </div>
                    <!--/column -->
                </div>
                @if ($training && $training->isNotEmpty())
                    <!--/.row -->
                    <div class="swiper-container nav-bottom nav-color mb-14 swiper-container-3" data-margin="30"
                        data-dots="false" data-nav="true" data-items-lg="3" data-items-md="2" data-items-xs="1">
                        <div
                            class="swiper overflow-visible pb-2 swiper-initialized swiper-horizontal swiper-backface-hidden">
                            <div class="swiper-wrapper" id="swiper-wrapper-8af71f4d98f5d8e0" aria-live="off"
                                style="cursor: grab; transform: translate3d(-1140px, 0px, 0px); transition-duration: 0ms; transition-delay: 0ms;">
                                @foreach ($training as $row)
                                    <div class="swiper-slide" role="group" style="width: 350px; margin-right: 30px;">
                                        <article>
                                            <div role="button" data-image="{{ image_check($row->image, 'training') }}"
                                                onclick="detail_training(this,{{ $row->id_training }})"
                                                data-bs-target="#modalDetailTraining" data-bs-toggle="modal"
                                                class="card shadow-lg">
                                                <figure class="card-img-top overlay overlay-1" style="height : 220px">
                                                    <a role="button">
                                                        <img src="{{ image_check($row->image, 'training') }}"
                                                            srcset="{{ image_check($row->image, 'training') }} 2x"
                                                            alt="">
                                                        <span class="bg"></span>
                                                    </a>
                                                    <figcaption>
                                                        <h5 class="from-top mb-0">Read More</h5>
                                                    </figcaption>
                                                </figure>
                                                <div class="card-body p-6" style="height : 230px">
                                                    <div class="post-header">
                                                        <div class="post-category">
                                                            <a role="button" class="hover"
                                                                rel="category">{{ $row->category->name }}</a>
                                                        </div>
                                                        <!-- /.post-category -->
                                                        <h2 class="post-title h4 mt-1 mb-1"><a class="link-dark"
                                                                role="button">{{ short_text($row->title, 40) }}</a></h2>
                                                        <p class="h10 mt-1 mb-3">
                                                            {{ short_text($row->sort_description, 60) }}</p>
                                                    </div>
                                                    <!-- /.post-header -->
                                                    <div class="post-footer">
                                                        <ul class="post-meta d-flex mb-0">
                                                            <li class="post-date"><i
                                                                    class="uil uil-calendar-alt"></i><span>{{ date_to_word($row->created_at) }}</span>
                                                            </li>
                                                        </ul>
                                                        <!-- /.post-meta -->
                                                    </div>
                                                    <!-- /.post-footer -->
                                                </div>
                                                <!--/.card-body -->
                                            </div>
                                            <!-- /.card -->
                                        </article>
                                        <!-- /article -->
                                    </div>
                                    <!--/.swiper-slide -->
                                @endforeach
                            </div>
                            <!--/.swiper-wrapper -->
                            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                        </div>
                        <!-- /.swiper -->
                    </div>
                    <!-- /.swiper-container -->
            </div>
            <!-- /.container -->
        @else
            <div class="w-100 d-flex justify-content-center align-items-center flex-column">
                <img src="<?= image_check('empty.svg', 'default') ?>" alt="" style="max-width : 250px">
                <h3 class="text-primary fs-30 mt-2">Tidak ada data pelatihan</h3>
                <p class="text-muted fs-15 mt-1 text-center" style="max-width : 400px">Belum ada data pelatihan
                    ditambahkan! Silahkan hubungi admin jika terjdi kesalahan</p>
            </div>
            @endif
        </div>
        <!-- /.overflow-hidden -->
    </section>

    @if ($setting->about)
        <section class="wrapper image-wrapper bg-image bg-overlay text-white"
            data-image-src="{{ image_check('about.jpg', 'setting') }}"
            style="background-image: url({{ image_check('about.jpg', 'setting') }});">
            <div class="container py-14 py-md-17 text-center">
                <div class="row">
                    <div class="col-xl-10 col-xxl-8 mx-auto text-center text-white">
                        {!! $setting->about !!}
                    </div>
                    <!--/column -->
                </div>
                <!--/.row -->
            </div>
            <!-- /.container -->
        </section>
    @endif

    <section class="wrapper bg-light" id="contact">
        <div class="container py-14 py-md-16">
            <h2 class="display-4 mb-3 text-center">Tinggalkan Pesan</h2>
            <p class="lead text-center mb-6 px-md-16 px-lg-0">Hai tinggalkan pesan untuk kami! Agar kami dapat berkembang
                menjadi lebih baik</p>
            <div class="row">
                <div class="col-xl-10 mx-auto">
                    <div class="row gy-10 gx-lg-8 gx-xl-12 d-flex justify-content-center align-items-center">
                        @if ($setting->meta_address || $web_phone->isNotEmpty() || $web_email->isNotEmpty())
                            <div class="col-lg-4">
                                @if ($setting->meta_address)
                                    <div class="d-flex flex-row">
                                        <div>
                                            <div class="icon text-primary fs-28 me-4 mt-n1"> <i
                                                    class="fa-solid fa-building"></i> </div>
                                        </div>
                                        <div>
                                            <h5 class="mb-1">Alamat</h5>
                                            <address>{{ $setting->meta_address }}</address>
                                        </div>
                                    </div>
                                @endif
                                @if ($web_phone && $web_phone->isNotEmpty())
                                    <div class="d-flex flex-row">
                                        <div>
                                            <div class="icon text-primary fs-28 me-4 mt-n1"> <i
                                                    class="fa-solid fa-phone-volume"></i> </div>
                                        </div>
                                        <div>
                                            <h5 class="mb-1">Nomor Telepon</h5>
                                            <p>
                                                @foreach ($web_phone as $row)
                                                    {{ $row->name ? $row->name . ' | ' . phone_format($row->phone) : phone_format($row->phone) }}
                                                    <br />
                                                @endforeach
                                            </p>
                                        </div>
                                    </div>
                                @endif
                                @if ($web_email && $web_email->isNotEmpty())
                                    <div class="d-flex flex-row">
                                        <div>
                                            <div class="icon text-primary fs-28 me-4 mt-n1"> <i
                                                    class="fa-solid fa-inbox"></i> </div>
                                        </div>
                                        <div>
                                            <h5 class="mb-1">Alamat Email</h5>
                                            @foreach ($web_email as $row)
                                                <p class="mb-0"><a href="mailto:{{ $row->email }}"
                                                        class="text-body">{{ $row->email }}</a></p>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                        <div class="col-lg-8">
                            <form class="contact-form needs-validation mt-10 pt-5" id="form_contact" method="post"
                                action="{{ route('contact.insert') }}" novalidate>
                                <div class="messages"></div>
                                <div class="row gx-4">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-4" id="req_contact_first_name">
                                            <input id="form_first_name" type="text" name="first_name"
                                                class="form-control" placeholder="Masukkan nama depan" required
                                                autocomplete="off">
                                            <label for="form_first_name">Nama Depan *</label>
                                        </div>
                                    </div>
                                    <!-- /column -->
                                    <div class="col-md-6">
                                        <div class="form-floating mb-4" id="req_contact_last_name">
                                            <input id="form_last_name" type="text" name="last_name"
                                                class="form-control" placeholder="Masukkan nama belakang" required
                                                autocomplete="off">
                                            <label for="form_last_name">Nama Belakang *</label>
                                        </div>
                                    </div>
                                    <!-- /column -->
                                    <div class="col-md-12">
                                        <div class="form-floating mb-4" id="req_contact_email">
                                            <input id="form_email" type="email" name="email" class="form-control"
                                                placeholder="Masukkan Alamat Email" required autocomplete="off">
                                            <label for="form_email">Email *</label>
                                        </div>
                                    </div>
                                    <!-- /column -->
                                    <div class="col-12">
                                        <div class="form-floating mb-4" id="req_contact_message">
                                            <textarea id="form_message" name="message" class="form-control" placeholder="Masukkan Pesan" style="height: 150px"
                                                required autocomplete="off"></textarea>
                                            <label for="form_message">Pesan *</label>
                                        </div>
                                    </div>
                                    <!-- /column -->
                                    <div class="col-12 d-flex justify-content-center align-items-center">
                                        <button type="button" id="submit_form_contact"
                                            onclick="submit_form(this,'#form_contact')"
                                            class="btn btn-primary rounded-pill btn-send mb-3">Kirim Pesan</button>
                                    </div>
                                    <!-- /column -->
                                </div>
                                <!-- /.row -->
                            </form>
                            <!-- /form -->
                        </div>
                        <!--/column -->

                    </div>
                    <!--/.row -->
                </div>
                <!-- /column -->
            </div>
            <!-- /.row -->
        </div>
    </section>
    <!-- /section -->

@endsection
