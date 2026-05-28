<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRV extends Model
{
    use HasFactory;
    protected $table = "crvs";
    protected $guarded = [];
}
