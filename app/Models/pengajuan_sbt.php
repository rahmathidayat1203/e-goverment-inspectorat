<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pengajuan_sbt extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function fileSyaratPengajuan()
    {
        return $this->hasOne(FileSyaratPengajuan::class, 'id_pengajuan_sbts', 'id');
    }

    public function user(){
        return $this->hasOne(User::class,'id','id_user');
    }

    public function progress()
    {
        return $this->hasMany(Progresberkas::class, 'id_psbts', 'id');
    }
}
