<?php

namespace Vanguard\Http\Requests\Team;

use Vanguard\Http\Requests\Request;

class CreateTeamRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|regex:/^[a-zA-Z0-9\-_\.]+$/|unique:teams,name',
            'link_page' => 'required|string'
        ];
    }
}
