<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RoleFavourites;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    public function list()
    { 
        $roles = Role::all()->all();

        return response()->json([
            $roles,
        ], Response::HTTP_OK);
    }

    public function create(Request $request)
    {        
        $role = Role::create([
            'name' => $request->name,
            'text' => $request->text,
            'user_id' => Auth::user()->id,
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

        return response()->json([
            $role,
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        // удаление роли из избранного
        $roleFavourite = RoleFavourites::where([
            'user_id' => Auth::user()->id,
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
