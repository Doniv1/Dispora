@extends('layouts.user.main')

@section('content')
<!-- /header -->
<section class="wrapper bg-gray">
    <div class="container pt-10 pb-14 pb-md-16">
        <div class="text-center row grid-view gx-md-8 gx-xl-10 gy-8 gy-lg-0">
            <div class="text-yellow-800 text-sm font-medium p-4">
                    ⚠️ Jika Anda <strong>DITERIMA</strong> maka history pelatihan akan tetap ada.<br>
                    Jika <strong>TIDAK DITERIMA</strong>, maka history pelatihan akan langsung <strong>terhapus otomatis</strong>.
                </div>
                
            {{-- ✅ NOTIFIKASI DITERIMA --}}
            @if(isset($notifikasi) && count($notifikasi) > 0)
            <div class="alert alert-success">
            @foreach($notifikasi as $n)
            🎉 Anda telah <strong>DITERIMA</strong> dalam pelatihan <strong>{{ $n->training->title }}</strong>!<br>
            @endforeach
            </div>
            @endif

            @if(isset($result) && $result->isNotEmpty())
                @foreach($result as $row)
                    <div class="col-md-6 col-lg-4 mx-auto mb-3" id="training-{{ $row->training->id_training }}">
                        <a role="button" 
                           data-image="{{ image_check($row->training->image,'training') }}" 
                           class="card cursor-pointer">
                            <div class="card-body">
                                <div class="rounded background-partisi w-100 mb-4" 
                                     style="height: 150px; background-image: url({{ image_check($row->training->image,'training') }})">
                                </div>
                                <h4 class="mb-1">{{ short_text($row->training->title, 50) }}</h4>
                                <h5 class="mb-1 fs-15">Tanggal : {{ date('d-M-Y',strtotime($row->created_at)) }}</h5>
                    @php
    $statusLabel = 'Diproses';
    $statusClass = 'bg-danger text-while';

    if ($row->approved === 'Y') {
        $statusLabel = 'Diterima';
        $statusClass = 'bg-primary text-white';
    }
@endphp

<span class="m-auto badge {{ $statusClass }}">{{ $statusLabel }}</span>

                                <div class="meta mb-2 text-dark">{{ $row->training->category->name ?? '-' }}</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @else
                <div class="pane-not-found text-center">
                    <img src="{{ image_check('empty.svg','default') }}" alt="Empty" style="max-width : 250px">
                    <h3>Tidak ada riwayat pelatihan</h3>
                    <p>Data riwayat pelatihan belum ada! Hubungi admin jika terjadi kesalahan</p>
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
                        <a class="page-link" href="{{ url('mytraining') }}?offset={{ $offset - 1 }}&search={{ $search }}" aria-label="Previous">
                            <span aria-hidden="true"><i class="fa-solid fa-arrow-left"></i></span>
                        </a>
                    </li>
                @endif

                {{-- Page Numbers --}}
                @for ($i = 1; $i <= $total; $i++)
                    <li class="page-item {{ $i == $offset ? 'active' : '' }}">
                        <a class="page-link" href="{{ url('mytraining') }}?offset={{ $i }}&search={{ $search }}">{{ $i }}</a>
                    </li>
                @endfor

                {{-- Next Page --}}
                @if ($offset < $total)
                    <li class="page-item">
                        <a class="page-link" href="{{ url('mytraining') }}?offset={{ $offset + 1 }}&search={{ $search }}" aria-label="Next">
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
