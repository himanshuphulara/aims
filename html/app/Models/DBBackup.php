<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DBBackup extends Model
{
    use HasFactory;
    protected $table = "dbbackups";
    protected $guarded = [];
}
