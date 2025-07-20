<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Pendaftaran Pelatihan</title>
    <style>
        body {
            font-family: Times, "Times New Roman", serif;
            font-size: 12px;
            margin: 40px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .header-left {
            width: 60%;
        }
        .header-left h1 {
            font-size: 18px;
            margin: 0;
        }
        .header-left p {
            margin: 2px 0;
        }
        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 20px;
            text-decoration: underline;
        }
        table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }
        td {
            padding: 6px;
        }
        .ttd {
            margin-top: 60px;
            text-align: right;
        }
        .ttd p {
            margin: 2px 0;
        }

        .head{
            font-weight: bold
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-left">
            <img src="{{ public_path('assets/logo/dispora.png') }}" height="60" alt="Logo Dispora">
            <h1 style="margin: 5px 0 0 0;">Dinas Pemuda dan Olahraga</h1>
            <h1 style="margin: 2px 0;">Kota Palembang</h1>
        </div>
    </div>

    <div class="title">BUKTI PENERIMAAN PELATIHAN</div>

    <p>Dengan ini menyatakan bahwa peserta berikut :</p>

    <table>
        <tr>
            <td class="head" style="width: 30%;">Nama Peserta</td>
            <td>: {{ $regis->user->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="head">Tanggal Lahir</td>
            <td>: {{ $regis->user->born_date
            ? \Carbon\Carbon::parse($regis->user->born_date)->translatedFormat('d F Y')
            : '-' }}</td>
        </tr>
        <tr>
            <td class="head">Phone</td>
            <td>: {{ $regis->user->phone ?? '-' }}</td>
        </tr>
        <tr>
            <td class="head">Email</td>
            <td>: {{ $regis->user->email ?? '-' }}</td>
        </tr>
        <tr>
            <td class="head">Program Pelatihan</td>
            <td>: {{ $regis->training->title ?? '-' }}</td>
        </tr>
        <tr>
            <td class="head">Kategori</td>
            <td>: {{ $regis->training->category->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="head">Status</td>
            <td>: <strong style="color: green;">DITERIMA</strong></td>
        </tr>
        <tr>
            <td class="head">Tanggal Pendaftaran</td>
            <td>: {{ \Carbon\Carbon::parse($regis->created_at)->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <p style="margin-top: 20px; text-align:justify;">
        Surat ini dicetak secara otomatis oleh sistem sebagai bukti bahwa yang bersangkutan telah dinyatakan diterima sebagai peserta pelatihan yang diselenggarakan oleh Dinas Pemuda dan Olahraga Kota Palembang.
    </p>

    <div class="ttd">
        <p>Palembang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p style="margin-top: 60px;">Admin DISPORA</p>
    </div>

</body>
</html>
