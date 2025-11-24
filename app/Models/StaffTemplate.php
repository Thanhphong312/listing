<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffTemplate extends Model
{
    use HasFactory;
    protected $table = "stafftemplates";
    protected $fillable = [
        'user_id', 'template_id'
    ];
}
