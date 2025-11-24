<?php

namespace Vanguard\Services\Design;

use Illuminate\Support\Facades\Auth;
use Vanguard\Models\Designs;
use Vanguard\Services\ModelService;

class DesignService extends ModelService
{
    public function __construct(private readonly DesignMetaService $designMetaService)
    {
        $this->model = resolve(Designs::class);
    }

    public function panigate( $filter)
    {
        $designs =  $this->model->with('user');
        $user = Auth::user();
        $role = $user->role->name;
       
        if($role=='Seller'||$role=='Staff'){
            $designs->where('user_id', $user->id);
        }
        if (isset($filter['id']) && !empty($filter['id'])) {
            $designs->where('id', $filter['id']);
        }
         if (isset($filter['user_code']) && !empty($filter['user_code'])) {
            $designs->where('user_code', "LIKE", $filter['user_code']);
        }
        if(isset($filter['staff_id'])&&!empty($filter['staff_id'])){
            $designs->where('user_id',$filter['staff_id']);
        }
        if(isset($filter['seller_id'])&&!empty($filter['seller_id'])){
            $designs->where('user_id',$filter['seller_id']);
        }
        if (isset($filter['created_at']) && !empty($filter['created_at'])) {
            $date = $filter['created_at'] . '%';
            $designs->where('created_at', "LIKE", $date);
        }
        if (isset($filter['tag']) && !empty($filter['tag'])) {
            $designs->where('tag', "LIKE", "%" . $filter['tag'] . "%");
        }
    
        $designs = $designs->orderBy('id','DESC')->paginate(20);
        return $designs;
    }
}
