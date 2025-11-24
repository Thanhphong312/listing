<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vanguard\User;

class UserTeams extends Model
{
    use HasFactory;
    protected $fillable = [
        'team_id', 'user_id'
    ];
    public function users(){
        return $this->hasMany(User::class);
    }
    public function teams(){
        return $this->hasMany(Teams::class);
    }
}
