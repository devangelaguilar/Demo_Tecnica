<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;


class Producto extends Model
{
    protected $connection = 'mongodb';
}
