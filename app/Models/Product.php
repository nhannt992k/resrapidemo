<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'price', 'seller_id','category_id'];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_id');
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
