<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'category';

    protected $fillable = [
        'catname',
        'subcatname',
        'caticon',
        'posted',
        'pcstatus',
    ];
}
