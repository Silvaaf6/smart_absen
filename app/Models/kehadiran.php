<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class kehadiran extends Model
{
    use HasFactory;
    protected $fillable   = ['id', 'id_user', 'tanggal', 'waktu_mulai', 'waktu_keluar', 'surat_dokter', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
