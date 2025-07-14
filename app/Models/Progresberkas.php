<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progresberkas extends Model
{
    use HasFactory;
    protected $table = "Progres_berkas";
    protected $guarded = ['id'];
}
