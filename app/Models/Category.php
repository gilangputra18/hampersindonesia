<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'title', 'display_order'];

    public function products()
    {
        return $this->hasMany(Product::class)->orderBy('display_order', 'asc')->orderBy('id', 'desc');
    }
}
