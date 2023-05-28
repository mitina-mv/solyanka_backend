<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RoleFavourites;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    public function list()
    { 
        $roles = Role::all()->all();

        return response()->json($roles, Response::HTTP_OK);
    }

    public function create(Request $request)
    {        
        $user = Auth::user() ?: User::getUserAuthById(request()->user_id);
        if(!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Вы не авторизованы!'
            ], Response::HTTP_BAD_REQUEST);
        }

        $role = Role::create([
            'name' => $request->name,
            'text' => $request->text,
            'user_id' => $user->id,
            'icon' => Role::PATH_ICON . $request->icon . '.png',
        ]);

        // добавление роли в избранное
        $roleFavourite = RoleFavourites::create([
            'user_id' => Auth::user()->id,
            'role_id' => $role->id
        ]);

        return response()->json([
           'id' => $roleFavourite->id,
        ], Response::HTTP_OK);
    }

    public function read($id)
    {
        $role = Role::find($id);

        return response()->json($role, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $user = Auth::user() ?: User::getUserAuthById(request()->user_id);
        if(!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Вы не авторизованы!'
            ], Response::HTTP_BAD_REQUEST);
        }

        $role = Role::where([
            'user_id' => $user->id,
            'id' => $id
        ])->firsl();

        if(!$role)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Вы не имеете право удалять эту роль'
            ], Response::HTTP_OK);
        }

        // удаление роли из избранного
        $roleFavourite = RoleFavourites::where([
            'user_id' => $user->id,
            'role_id' => $role->id
        ]);
        $roleFavourite->delete();
        
        try {
            $role->forceDelete();
        } catch (Exception $e) {
            $role->delete();
        }

        return response()->json([
            'id' => $id,
            'message' => 'Удаление успешно'
        ], Response::HTTP_OK);
    }
}
