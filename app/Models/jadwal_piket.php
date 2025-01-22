<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jadwal_piket extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'id_user', 'judul', 'tanggal'];

    public $timestamp = true;
}
