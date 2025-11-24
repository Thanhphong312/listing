<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vanguard\User;

class Teams extends Model
{
    use HasFactory;

    protected $fillable = [
      'id','name', 'link_page', 'revuenue'
    ];
    public function number_menber(){
        return $this->user_team->count();
    }
    public function user_team(){
        return $this->hasMany(User::class,'team_id');
    }
}
