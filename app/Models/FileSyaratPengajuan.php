<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileSyaratPengajuan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function pengajuan()
    {
        return $this->belongsTo(pengajuan_sbt::class, 'id_pengajuan_sbts', 'id');
    }
}
