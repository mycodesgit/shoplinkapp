<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WelcomeBanner extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'welcomebanner';

    protected $fillable = [
        'welcometext',
        'welcomesubtext',
        'welcomestatus',
    ];
}
