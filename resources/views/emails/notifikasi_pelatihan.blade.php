@component('mail::message')
# Halo, {{ $nama }}

@switch($status)
    @case('Y')
**Selamat!**  
Anda telah diterima pada program pelatihan berikut:

**Judul Pelatihan: {{ $judul }}**

@component('mail::button', ['url' => route('home') . '#home'])
Lihat Detail
@endcomponent

@break

@case('N')
**Mohon maaf.**  
Anda tidak diterima pada program pelatihan berikut:

**Judul Pelatihan: {{ $judul }}**

Kami mengucapkan terima kasih atas partisipasi Anda. Jangan ragu untuk mencoba kembali di pelatihan berikutnya.

@break
@endswitch

Terima kasih,  
{{ config('app.name') }}
@endcomponent
