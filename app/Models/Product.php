<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'products';

    protected $fillable = [
        'catid',
        'subcatid',
        'prdctname',
        'prdctdesc',
        'prdctsku',
        'prdctprice',
        'prdctstock',
        'pstatus',
        'prdctimage',
        'prdctslug',
        'prdctvariation',
        'prdcttag',
        'prdctpercentageoff'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->prdctslug  = Str::slug($product->prdctname);
        });
    }
}
