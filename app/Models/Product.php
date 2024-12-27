<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    const UPDATED_AT = null;

    protected $fillable = ['name', 'price', 'description'];
    protected $table = 'products';

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
