<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiPelatihan extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $judul;
    public $status;

    public function __construct($nama, $judul, $status)
    {
        $this->nama = $nama;
        $this->judul = $judul;
        $this->status = $status;
    }

    public function build()
    {
        return $this->subject('Status Pendaftaran Pelatihan Anda')
                    ->markdown('emails.notifikasi_pelatihan');
    }
}

