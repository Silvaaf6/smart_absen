<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class pengajuan_cuti extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'id_user', 'tgl_pengajuan', 'kategori_cuti', 'tgl_mulai', 'tgl_selesai', 'alasan', 'status'];

    public $timestamp = true;
    
    public function User() {
        return $this->belongsTo(User::class, "id_user");
    }
}
