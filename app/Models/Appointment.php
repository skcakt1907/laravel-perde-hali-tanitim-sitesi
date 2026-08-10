<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Kostenloses Aufmaß & Beratung talebi */
class Appointment extends Model
{
    // 'status' bilerek dışarıda: yalnızca admin forceFill ile günceller.
    protected $fillable = [
        'name', 'phone', 'email', 'subject', 'zip', 'city', 'address', 'date', 'time', 'note', 'locale',
    ];

    protected $casts = ['date' => 'date'];

    /** Admin panelindeki durum akışı */
    public const DURUMLAR = [
        'yeni'      => 'Yeni talep',
        'arandi'    => 'Arandı',
        'planlandi' => 'Ölçü randevusu planlandı',
        'olculdu'   => 'Ölçü alındı / teklif verildi',
        'kazanildi' => 'İş alındı',
        'iptal'     => 'İptal',
    ];
}
