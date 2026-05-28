<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalAllotment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id', 
        'subcategory_id',
        'fund_type',
        'allotment_amount',
        'allotment_date',
        'description'
    ];

    protected $casts = [
        'allotment_date' => 'date',
        'allotment_amount' => 'decimal:2'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }
}
