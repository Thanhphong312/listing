<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $fillable = [
        'order_id',
        'first_name',
        'last_name',
        'phone',
        'address_1',
        'address_2',
        'city',
        'state',
        'postcode',
        'country',
    ];
}
