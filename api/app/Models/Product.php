<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    //
    protected $connection = 'mongodb';
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'brand',
    ];
}
