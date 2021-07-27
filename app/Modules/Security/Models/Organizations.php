<?php

namespace App\Modules\Security\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizations extends Model
{
    use HasFactory;

     protected $fillable = ['org_name','app_id'];   
}
