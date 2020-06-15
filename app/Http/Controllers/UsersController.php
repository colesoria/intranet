<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{
    private $roles = ['Administrador', 'Super administrador'];
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function me(Request $request)
    {
        $data['me'] = $request->user();
        return view('users.me', $data);
    }

    public function all(Request $request)
    {
        if (!$request->user()->hasAnyRole($this->roles)) {
            return redirect('/');
        }
        $data['admin'] = false;
        $data['superadmin'] = false;
        $data['active'] = 'users';
        $users = [];
        if ($request->user()->hasRole('Super administrador')) {
            $data['superadmin'] = true;
            $users = User::with('roles')->with('departments')->get();
        }
        if ($request->user()->hasRole('Administrador')) {
            $data['admin'] = true;
            $users = User::with(['roles' => function ($query) {
                $query->where('name', '!=', 'Super administrador');
            }])->get();
        }
        foreach ($users as $i => $user) {
            $data['users'][$i] = $user;
            $roles = json_decode($user['roles']);
            $departments = json_decode($user['departments']);
            if ($roles != []) {
                $data['users'][$i]['roles'] = $roles[0]->name;
            } else {
                unset($data['users'][$i]);
            }
            if ($departments != []) {
                $data['users'][$i]['departments'] = $departments[0]->name;
            } else {
                unset($data['users'][$i]);
            }
        }
        return view('users.all', $data);
    }

    public function delete(Request $request, $id)
    {
        if (!$request->user()->hasAnyRole($this->roles)) {
            return redirect('/');
        }
        $user = User::find($id);
        $this->destroy($user);
        return redirect(route('allusers'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(null, 204);
    }
}
