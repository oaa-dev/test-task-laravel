<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';
    const UPDATED_AT = null;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
