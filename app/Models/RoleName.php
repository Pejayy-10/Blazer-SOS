<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleName extends Model
{
    use HasFactory;

    protected $fillable = ['name']; // Allow mass assignment for the name
}