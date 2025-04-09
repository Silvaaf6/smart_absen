<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jabatan extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'nama_jabatan'];
    protected $table = 'jabatans';
    public $timestamp = true;

    public function users()
    {
        return $this->hasMany(User::class, 'id_jabatan');
    }
}

