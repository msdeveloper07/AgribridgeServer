<?php

namespace App\Modules\Security\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    use HasFactory;
     protected $fillable = ['usr_id','rol_id'];   
}
