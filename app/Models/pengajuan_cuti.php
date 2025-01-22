<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pengajuan_cuti extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'id_user', 'tgl_pengajuan', 'kategori_cuti', 'tgl_mulai', 'tgl_selesai', 'alasan', 'status'];

    public $timestamp = true;
}
