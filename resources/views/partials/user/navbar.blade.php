@php
    $segment1 = request()->segment(1);
    $segment2 = request()->segment(2);
@endphp
<header class="wrapper bg-light">
    <nav class="navbar navbar-expand-lg classic transparent navbar-light">
        <div class="container flex-lg-row flex-nowrap align-items-center">
            <div class="navbar-brand w-100">
                <a href="{{ route('home') }}">
                    @if($setting->logo  && file_exists(public_path('data/setting/' . $setting->logo )))
                    <div class="background-partisi-contain" style="width : 200px;height : 80px;background-image : url('{{ image_check($setting->logo,'setting') }}');"></div>
                    @endif
                </a>
            </div>
            <div class="navbar-collapse offcanvas offcanvas-nav offcanvas-start">
                <div class="offcanvas-header d-lg-none">
                    <h3 class="text-white fs-30 mb-0">{{ $setting->meta_title }}</h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body ms-lg-auto d-flex flex-column h-100">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link scrollto" href="{{ route('home') }}#home">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ ($segment1 == 'training') ? 'active' : '' }}" href="{{ route('training') }}">Pelatihan</a>
                        </li>
                        @if(session(config('session.prefix') . '_id_user'))
                        <li class="nav-item">
                            <a class="nav-link {{ ($segment1 == 'mytraining') ? 'active' : '' }}" href="{{ route('mytraining') }}">Pelatihan Saya</a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link scrollto" href="{{ route('home') }}#contact">Hubungi Kami</a>
                        </li>
                        <li class="nav-item khusus-android">
                            <a role="button" data-bs-target="#modalLogin" data-bs-toggle="modal" class="nav-link">Login</a>
                        </li>
                    </ul>
                    <!-- /.navbar-nav -->
                    <div class="offcanvas-footer d-lg-none">
                        <div>
                            @if($web_email && $web_email->isNotEmpty())
                            @foreach($web_email AS $row)
                                <a href="mailto:{{ $row->email }}" class="link-inverse"><span>{{ $row->email }}</span></a><br />
                            @endforeach
                            @endif 
                            @if($web_phone && $web_phone->isNotEmpty())
                            @foreach($web_phone AS $row)
                                {{ ($row->name) ? $row->name.' | '.phone_format('0'.$row->phone) : phone_format('0'.$row->phone) }} <br />
                            @endforeach
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


                            @if($status == true)
                            <nav class="nav social social-white mt-4">
                            @foreach($sosmed AS $row)
                                @if($row->url != '')
                                <a href="{{ $row->url }}" title="{{ $row->name_sosmed }}"><i class="{{ $row->icon }}"></i></a>
                                @endif
                            @endforeach
                            </nav>
                            @endif
                        </div>
                    </div>
                    <!-- /.offcanvas-footer -->
                </div>
                <!-- /.offcanvas-body -->
            </div>
            <!-- /.navbar-collapse -->
            <div class="navbar-other ms-lg-4">
                <ul class="navbar-nav flex-row align-items-center ms-auto">
                    <!-- <li class="nav-item"><a class="nav-link" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-search"><i class="uil uil-search"></i></a></li> -->
                    @if(!session(config('session.prefix') . '_id_user'))
                    <li class="nav-item d-none d-md-block">
                        <button data-bs-target="#modalLogin" data-bs-toggle="modal" class="btn btn-sm btn-primary rounded-pill">Login</button>
                    </li>
                    @else
                    <li class="nav-item d-none d-md-block">
                        <a type="button" href="{{ route('logout') }}" onclick="confirm_alert(this, event, 'Are you sure you want to leave the system?')" class="btn btn-sm btn-danger rounded-pill">Log Out</a>
                    </li>
                    @endif
                    
                    <li class="nav-item d-lg-none">
                        <button class="hamburger offcanvas-nav-btn"><span></span></button>
                    </li>
                </ul>
                <!-- /.navbar-nav -->
            </div>
            <!-- /.navbar-other -->
        </div>
        <!-- /.container -->
    </nav>
    <!-- /.navbar -->
</header>
<!-- /header -->

<!-- <div class="offcanvas offcanvas-top bg-light" id="offcanvas-search" data-bs-scroll="true"> -->
<!-- <div class="container d-flex flex-row py-6">
    <form method="GET" action="{{ route('training') }}" class="search-form w-100">
    <input id="search-form" name="search" type="text" class="form-control" placeholder="Masukkan kata kunci">
    </form>
    <!-- /.search-form -->
    <!-- <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div> -->
<!-- /.container -->
</div>