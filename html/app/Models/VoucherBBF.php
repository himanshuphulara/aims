<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherBBF extends Model
{
    use HasFactory;
    protected $table = "vouchers_bbf";
    protected $casts = [
        'voc_json' => 'array',
    ];
}
