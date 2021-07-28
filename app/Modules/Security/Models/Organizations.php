<?php

namespace App\Modules\Security\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organizations extends Model
{
 use HasFactory, SoftDeletes;
 protected $fillable = ['org_name','app_id'];    
 protected $guarded = [];

}
