@component('mail::message')
# Halo, {{ $name }}

Kami menerima permintaan untuk mereset password akun Anda.

Klik tombol di bawah ini untuk mengganti password Anda:

@php
   $link = 'https://dispora.viewapp.online/reset-password/' . $token . '?email=' . urlencode($email);
@endphp

@component('mail::button', ['url' => $link])
Reset Password
@endcomponent

Link ini hanya berlaku selama 15 menit.

Jika Anda tidak meminta reset password, abaikan email ini.

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
