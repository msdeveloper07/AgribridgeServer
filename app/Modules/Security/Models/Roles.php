<?php

namespace App\Modules\Security\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;
    
    protected $fillable = ['org_id','role_name'];   
}
