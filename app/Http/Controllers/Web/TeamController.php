<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Models\Teams;
use Illuminate\Support\Facades\Auth;
use Vanguard\Models\UserTeams;
use Vanguard\User;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $query = Teams::query();
        $user = Auth::user();
        $role = $user->role->name;
        if ($role == 'Seller') {
            $query->where('user_id', $user->id);
        }
        if ($role == 'Staff') {
            $query->where('staff_id', $user->id);
        }
        if (isset($request->staff_id) && !empty($request->staff_id)) {
            $query->where('staff_id', $request->staff_id);
        }
        if (isset($request->user_id) && !empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }


        $teams = $query->paginate(20);
        return view('teams.index', compact('teams', 'role', 'request'));
    }

    public function ajax(Request $request, $id)
    {
        $store = Teams::find($id);
        return view('teams.ajax.index', compact('store'));
    }

    public function add(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->name;
        if ($request->isMethod('post')) {
            $team = new Teams();
            $team->name = $request->name_add;
            $rs = $team->save();
            if ($rs) {
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }


        return view('teams.add-edit', ['edit' => false]);
    }
    public function store(Request $request)
    {

        $team = new Teams();
        $team->name = $request->name;
        $team->link_page = $request->link_page;
        $rs = $team->save();
        if ($rs) {
            return redirect()->route('teams.index')->with('success', 'add success');
        } else {
            return redirect()->route('teams.index')->with('error', 'add error');
        }
    }
    public function view(Request $request, $id)
    {
        $team = Teams::find($id);
        return view('teams.add-edit', [
            'team' => $team,
            'edit' => true
        ]);
    }
    public function update(Request $request, $id)
    {
        $team = Teams::find($id);
        $team->name = $request->name;
        $team->link_page = $request->link_page;

        $rs = $team->save();


        if ($rs) {
            return redirect()->route('teams.index')->with('success', 'edit success');
        } else {
            return redirect()->route('teams.index')->with('error', 'edit error');
        }

    }
    public function chooseUser(Request $request, $id)
    {
        $users = User::select(['id', 'username'])->whereIn('role_id', [3, 5])
            ->whereIn('status',['Active'])
            ->get();
        return view('teams.ajax.chooseuser', compact('users', 'id'));
    }
    public function acceptUser(Request $request, $id)
    {
        $userTeam = User::where('id', $request->user_id)
            ->where('team_id', $id)
            ->first();

        // If the record exists, delete it; otherwise, create a new one
        if ($userTeam) {
            $userTeam->team_id= null;
            $userTeam->save();
            return response()->json(["message" => "deleted", "data" => true], 200);
        }

        $rs = User::find( $request->user_id);
        $rs->team_id = $id;
        $rs->save();
        return response()->json([
            "message" => $rs ? "added" : "error",
            "data" => $rs ? true : []
        ], $rs ? 200 : 404);

    }
}
