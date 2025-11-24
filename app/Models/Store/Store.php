<?php

namespace Vanguard\Models\Store;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vanguard\Models\Meta;
use Vanguard\Models\PartnerApp;
use Vanguard\User;

class Store extends Model
{
  use HasFactory;

  const TYPES = [

  ];

  const SORTABLE = ['created_at'];

  protected $fillable = [
    'user_id', 'name', 'type', 'status', 'timezone','partner_id','sup_store_id', 'order_code'
  ];

  /**
   * Get the seller user associated with the store.
   */
  public function user()
  {
    return $this->belongsTo(User::class,'user_id','id');
  }
  public function appPartner()
  {
    return $this->belongsTo(PartnerApp::class,'id','partner_id');
  }
  public function meta()
  {
    return $this->hasMany(Meta::class,'store_id','id');
  }
}
