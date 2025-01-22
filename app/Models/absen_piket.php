<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class absen_piket extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'id_jadwal_piket', 'id_user', 'tanggal', 'jam_mulai', 'jam_berakhir', 'deskripsi', 'status'];

    public $timestamp = true;
}
