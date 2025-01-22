<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kehadiran extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'id_user', 'tanggal', 'waktu_mulai', 'waktu_keluar', 'status'];

    public $timestamp = true;
}
