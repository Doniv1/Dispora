@extends('layouts.user.main')

@section('content')
<!-- /header -->
<section class="wrapper bg-gray">
    <div class="container pt-10 pb-14 pb-md-16">

{{-- Rekomendasi Pelatihan --}}
        @if(isset($recommended) && count($recommended) > 0)
            <h2 class="text-center text-primary fw-bold mb-4">Rekomendasi Pelatihan Untuk Anda</h2>
            <div class="row grid-view gx-md-8 gx-xl-10 gy-8 mb-10">
                @foreach ($recommended as $row)
                    <div class="col-md-6 col-lg-4 mx-auto mb-3" id="recommended-training-{{ $row['training']->id_training }}">
                        <a role="button" 
                           data-image="{{ image_check($row['training']->image,'training') }}" 
                           onclick="detail_training(this,{{ $row['training']->id_training }})" 
                           data-bs-target="#modalDetailTraining" 
                           data-bs-toggle="modal" 
                           class="card cursor-pointer">
                            <div class="card-body">
                                <div class="rounded background-partisi w-100 mb-4" 
                                     style="height: 150px; background-image: url({{ image_check($row['training']->image,'training') }}); background-size: cover;">
                                </div>
                                <h4 class="mb-1">{{ short_text($row['training']->title, 50) }}</h4>
                                <p class="h10 mt-1 mb-3">
                                {{ short_text($row['training']->sort_description, 200) }}</p>
                                <div class="meta mb-2">{{ $row['training']->category->name ?? '-' }}</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif


        <h2 class="text-center text-primary fw-bold mb-4">
            Daftar Pelatihan
        </h2>
        <div class="row grid-view gx-md-8 gx-xl-10 gy-8 gy-lg-0">
            @if(isset($result) && $result->isNotEmpty())
                @foreach($result as $row)
                    <div class="col-md-6 col-lg-4 mx-auto mb-3" id="training-{{ $row->id_training }}">
                        <a role="button" 
                           data-image="{{ image_check($row->image,'training') }}" 
                           onclick="detail_training(this,{{ $row->id_training }})" 
                           data-bs-target="#modalDetailTraining" 
                           data-bs-toggle="modal" 
                           class="card cursor-pointer">
                            <div class="card-body">
                                <div class="rounded background-partisi w-100 mb-4" 
                                     style="height: 150px; background-image: url({{ image_check($row->image,'training') }})">
                                </div>
                                <h4 class="mb-1">{{ short_text($row->title, 50) }}</h4>
                                <p class="h10 mt-1 mb-3">
                                {{ short_text($row->sort_description, 200) }}</p>
                                <div class="meta mb-2">{{ $row->category->name ?? '-' }}</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @else
                <div class="pane-not-found text-center">
                    <img src="{{ image_check('empty.svg','default') }}" alt="Empty" style="max-width : 250px">
                    <h3>Tidak ada data pelatihan</h3>
                    <p>Data pelatihan belum ada! Hubungi admin jika terjadi kesalahan</p>
                </div>
            @endif
        </div>
        <!--/.row -->

        @if($total > 0)
        <nav class="d-flex mt-5" aria-label="pagination">
            <ul class="pagination pagination-alt">

                {{-- Previous Page --}}
                @if ($offset > 1)
                    <li class="page-item">
                        <a class="page-link" href="{{ url('training') }}?offset={{ $offset - 1 }}&search={{ $search }}" aria-label="Previous">
                            <span aria-hidden="true"><i class="fa-solid fa-arrow-left"></i></span>
                        </a>
                    </li>
                @endif

                {{-- Page Numbers --}}
                @for ($i = 1; $i <= $total; $i++)
                    <li class="page-item {{ $i == $offset ? 'active' : '' }}">
                        <a class="page-link" href="{{ url('training') }}?offset={{ $i }}&search={{ $search }}">{{ $i }}</a>
                    </li>
                @endfor

                {{-- Next Page --}}
                @if ($offset < $total)
                    <li class="page-item">
                        <a class="page-link" href="{{ url('training') }}?offset={{ $offset + 1 }}&search={{ $search }}" aria-label="Next">
                            <span aria-hidden="true"><i class="fa-solid fa-arrow-right"></i></span>
                        </a>
                    </li>
                @endif

            </ul>
        </nav>
        @endif
    </div>
</section>
@endsection
